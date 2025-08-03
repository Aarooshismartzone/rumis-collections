<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class customerDashboardController extends Controller
{
    public function dashboard()
    {
        return view('frontend.customer.dashboard');
    }

    public function viewOrders(){
        $orders = Order::where('customer_id', Session::get('customer_id'))->get();
        return view('frontend.customer.orders', compact('orders'));
    }
}
