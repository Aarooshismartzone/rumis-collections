<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Orderitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class customerDashboardController extends Controller
{
    public function dashboard()
    {
        return view('frontend.customer.dashboard');
    }

    public function viewOrders()
    {
        $orders = Order::where('customer_id', Session::get('customer_id'))->get();
        return view('frontend.customer.orders', compact('orders'));
    }

    public function viewOrderItems($id)
    {
        $customer_id = Session::get('customer_id');

        // Fetch the order and validate ownership
        $order = Order::where('id', $id)
            ->where('customer_id', $customer_id)
            ->firstOrFail();

        // Load order items with related product info
        $orderItems = Orderitem::where('order_id', $id)
            ->with('product')
            ->get();

        return view('frontend.customer.orderitems', compact('order', 'orderItems'));
    }

    public function viewProfile(){
        $customer = Customer::find(Session::get('customer_id'));
        return view('frontend.customer.profile', compact('customer'));
    }
}
