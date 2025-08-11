<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Customeraddress;
use App\Models\Order;
use App\Models\Orderitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class customerDashboardController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('customer_id', Session::get('customer_id'))->orderBy('id', 'desc')->limit(5)->get();
        return view('frontend.customer.dashboard', compact('orders'));
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

    public function viewAddresses()
    {
        $addresses = Customeraddress::where('customer_id', Session::get('customer_id'))->get();
        return view('frontend.customer.addresses', compact('addresses'));
    }

    public function addAddress(Request $request)
    {
        $customer_id = Session::get('customer_id');
//dd($request->city); //DD works for this part
        $request->validate([
            'address_line_1' => [
                'required',
                'not_regex:/[<>$#]/'
            ],
            'address_line_2' => [
                'nullable',
                'not_regex:/[<>$#]/'
            ],
            'pincode' => [
                'required',
                'digits:6'
            ],
            'city' => [
                'required',
                'not_regex:/[<>$#]/'
            ],
            'state' => [
                'required',
                'not_regex:/[<>$#]/'
            ],
            'country' => [
                'required',
                'not_regex:/[<>$#]/'
            ],
        ], [
            'regex' => 'The :attribute contains invalid characters.',
        ]);
       // dd('stops here'); //does not reach till here
        // Save the address
        $address = new Customeraddress();
        $address->customer_id = $customer_id;
        $address->address_line_1 = $request['address_line_1'];
        $address->address_line_2 = $request['address_line_2'] ?? '';
        $address->city = $request['city'];
        $address->state = $request['state'];
        $address->country = $request['country'];
        $address->pin_code = $request['pincode'];
        $address->is_primary_address = false; // Default false
        $address->save();

        return redirect()->back()->with('success', 'Address added successfully!');
    }

    public function viewProfile()
    {
        $customer = Customer::find(Session::get('customer_id'));
        return view('frontend.customer.profile', compact('customer'));
    }
}
