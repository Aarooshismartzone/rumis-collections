<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    /**
     * Show the dashboard page (initial load).
     * Default initial range: Last 7 days.
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect('/internal/login');
        }

        $end = Carbon::now();
        $start = Carbon::today()->subDays(6); // last 7 days

        // Totals
        $salesToday = Order::whereDate('created_at', Carbon::today())->sum('grand_total');
        $salesLast7Days = Order::whereBetween('created_at', [$start, $end])->sum('grand_total');
        $salesThisMonth = Order::whereBetween('created_at', [Carbon::now()->startOfMonth(), $end])->sum('grand_total');

        // Growth calculations (compared with previous comparable periods)
        $salesYesterday = Order::whereDate('created_at', Carbon::yesterday())->sum('grand_total');

        $prev7Start = $start->copy()->subDays(7);
        $prev7End = $start->copy()->subDay();
        $salesPrev7Days = Order::whereBetween('created_at', [$prev7Start, $prev7End])->sum('grand_total');

        $prevMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $prevMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $salesPrevMonth = Order::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->sum('grand_total');

        $growthToday = $this->calculateGrowth($salesYesterday, $salesToday);
        $growthLast7Days = $this->calculateGrowth($salesPrev7Days, $salesLast7Days);
        $growthMonth = $this->calculateGrowth($salesPrevMonth, $salesThisMonth);

        // Sales trend for last 7 days
        $salesTrend = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Top 5 products (by qty) in last 7 days
        $topProducts = Orderitem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty'),
            // multiply by discounted_price (assumes price in products)
            DB::raw('SUM(quantity * products.discounted_price) as total_sales')
        )
            ->join('orders', 'orderitems.order_id', '=', 'orders.id')
            ->join('products', 'orderitems.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('product_id', 'products.product_name', 'products.discounted_price')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get()
            ->map(function ($row) {
                // attach product_name cleanly for blade/js
                $row->product_name = $row->product_name ?? ($row->product ? $row->product->product_name : null);
                return $row;
            });

        // Top buyers in last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $topBuyers = Order::select(
            DB::raw("IFNULL(customer_id, guest_token) as buyer"),
            DB::raw("CASE WHEN customer_id IS NOT NULL THEN 'customer' ELSE 'guest' END as type"),
            DB::raw('COUNT(*) as orders_count'),
            DB::raw('SUM(grand_total) as total_spent')
        )
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('buyer', 'type')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // Resolve customer names where applicable
        $topBuyers->transform(function ($b) {
            if ($b->type === 'customer') {
                $cust = Customer::find($b->buyer);
                $b->name = $cust ? ($cust->fname . ' ' . $cust->lname) : 'Unknown Customer';
            } else {
                $b->name = 'Guest (' . $b->buyer . ')';
            }
            return $b;
        });

        return view('backend.dashboard', [
            'user' => Auth::user(),
            'salesToday' => $salesToday,
            'salesLast7Days' => $salesLast7Days,
            'salesThisMonth' => $salesThisMonth,
            'growthToday' => $growthToday,
            'growthLast7Days' => $growthLast7Days,
            'growthMonth' => $growthMonth,
            'salesTrend' => $salesTrend,
            'topProducts' => $topProducts,
            'topBuyers' => $topBuyers,
        ]);
    }

    /**
     * AJAX endpoint: return dashboard data for a given date range
     * Accepts POST with start_date and end_date (YYYY-MM-DD)
     */
    public function fetchDashboardData(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::today()->subDays(6);
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now();

        // Total sales in range
        $salesTotal = Order::whereBetween('created_at', [$start, $end])->sum('grand_total');

        // Previous comparable range for growth
        $days = max(1, $start->diffInDays($end) + 1);
        $prevEnd = $start->copy()->subDay()->endOfDay();
        $prevStart = $start->copy()->subDays($days)->startOfDay();
        $salesPrev = Order::whereBetween('created_at', [$prevStart, $prevEnd])->sum('grand_total');

        $growth = $this->calculateGrowth($salesPrev, $salesTotal);

        // Sales trend grouped by date
        $salesTrend = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Top products (by qty) in range
        $topProducts = Orderitem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(quantity * products.discounted_price) as total_sales'),
            'products.product_name'
        )
            ->join('orders', 'orderitems.order_id', '=', 'orders.id')
            ->join('products', 'orderitems.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('product_id', 'products.product_name', 'products.discounted_price')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Top buyers in range
        $topBuyers = Order::select(
            DB::raw("IFNULL(customer_id, guest_token) as buyer"),
            DB::raw("CASE WHEN customer_id IS NOT NULL THEN 'customer' ELSE 'guest' END as type"),
            DB::raw('COUNT(*) as orders_count'),
            DB::raw('SUM(grand_total) as total_spent')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('buyer', 'type')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // Resolve names for customers
        $topBuyers->transform(function ($b) {
            if ($b->type === 'customer') {
                $cust = Customer::find($b->buyer);
                $b->name = $cust ? ($cust->fname . ' ' . $cust->lname) : 'Unknown Customer';
            } else {
                $b->name = 'Guest (' . $b->buyer . ')';
            }
            return $b;
        });

        return response()->json([
            'salesTotal' => $salesTotal,
            'growth' => $growth,
            'salesTrend' => $salesTrend,
            'topProducts' => $topProducts,
            'topBuyers' => $topBuyers,
        ]);
    }

    /**
     * Calculate percentage growth between previous and current
     */
    private function calculateGrowth($previous, $current)
    {
        $previous = floatval($previous);
        $current = floatval($current);

        if ($previous == 0 && $current > 0) {
            return 100.00;
        } elseif ($previous == 0 && $current == 0) {
            return 0.00;
        }

        return round((($current - $previous) / max(1, $previous)) * 100, 2);
    }
}
