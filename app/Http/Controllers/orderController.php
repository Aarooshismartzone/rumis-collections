<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class orderController extends Controller
{
    public function addOrder(Request $request)
    {
        $request->validate([
            // Delivery
            'delivery.fname' => ['required', 'regex:/^[A-Za-z\s,()\/-]+$/', 'not_regex:/[<>$#]/'],
            'delivery.lname' => ['nullable', 'regex:/^[A-Za-z\s,()\/-]+$/', 'not_regex:/[<>$#]/'],
            'delivery.company_name' => ['nullable', 'not_regex:/[<>$#]/'],
            'delivery.address_line_1' => ['required', 'not_regex:/[<>$#]/'],
            'delivery.address_line_2' => ['nullable', 'not_regex:/[<>$#]/'],
            'delivery.city' => ['required', 'not_regex:/[<>$#]/'],
            'delivery.state' => ['required', 'not_regex:/[<>$#]/'],
            'delivery.country' => ['required', 'not_regex:/[<>$#]/'],
            'delivery.pin_code' => ['required', 'not_regex:/[<>$#]/'],
            'delivery.pnum' => ['required', 'regex:/^[0-9\+]+$/', 'max:13'],

            // Billing
            'billing.fname' => ['required', 'regex:/^[A-Za-z\s,()\/-]+$/', 'not_regex:/[<>$#]/'],
            'billing.lname' => ['nullable', 'regex:/^[A-Za-z\s,()\/-]+$/', 'not_regex:/[<>$#]/'],
            'billing.company_name' => ['nullable', 'not_regex:/[<>$#]/'],
            'billing.address_line_1' => ['required', 'not_regex:/[<>$#]/'],
            'billing.address_line_2' => ['nullable', 'not_regex:/[<>$#]/'],
            'billing.city' => ['required', 'not_regex:/[<>$#]/'],
            'billing.state' => ['required', 'not_regex:/[<>$#]/'],
            'billing.country' => ['required', 'not_regex:/[<>$#]/'],
            'billing.pin_code' => ['required', 'not_regex:/[<>$#]/'],
            'billing.pnum' => ['required', 'regex:/^[0-9\+]+$/', 'max:13'],
        ]);
        $checkoutData = Session::get('checkout_summary');
        $order = new Order;
        if (Session::has('customer_id')) {
            $order->customer_id = Session::get('customer_id');
        } elseif (Session::has('guest_token')) {
            $order->guest_token = Session::get('guest_token');
        }
        $order->total_amount = $checkoutData['total_price'];
        $order->delivery_charge = $checkoutData['delivery_charge'];
        $order->gst_amount = $checkoutData['gst_amount'];
        $order->grand_total = $checkoutData['grand_total'];
        $order->billing_same_as_delivery = $request->billing_same_as_delivery ? true : false;
        $order->d_fname = $request->input('delivery.fname');
        $order->d_lname = $request->input('delivery.lname');
        $order->d_company = $request->input('delivery.company_name');
        $order->d_address_line_1 = $request->input('delivery.address_line_1');
        $order->d_address_line_2 = $request->input('delivery.address_line_2');
        $order->d_city = $request->input('delivery.city');
        $order->d_state = $request->input('delivery.state');
        $order->d_country = $request->input('delivery.country');
        $order->d_pin_code = $request->input('delivery.pin_code');
        $order->d_pnum = $request->input('delivery.pnum');

        if ($order->billing_same_as_delivery) {
            $order->b_fname = $order->d_fname;
            $order->b_lname = $order->d_lname;
            $order->b_company = $order->d_company;
            $order->b_address_line_1 = $order->d_address_line_1;
            $order->b_address_line_2 = $order->d_address_line_2;
            $order->b_city = $order->d_city;
            $order->b_state = $order->d_state;
            $order->b_country = $order->d_country;
            $order->b_pin_code = $order->d_pin_code;
            $order->b_pnum = $order->d_pnum;
        } else {
            $order->b_fname = $request->input('billing.fname');
            $order->b_lname = $request->input('billing.lname');
            $order->b_company = $request->input('billing.company_name');
            $order->b_address_line_1 = $request->input('billing.address_line_1');
            $order->b_address_line_2 = $request->input('billing.address_line_2');
            $order->b_city = $request->input('billing.city');
            $order->b_state = $request->input('billing.state');
            $order->b_country = $request->input('billing.country');
            $order->b_pin_code = $request->input('billing.pin_code');
            $order->b_pnum = $request->input('billing.pnum');
        }
        $order->save();
        $checkoutData['order_id'] = $order->id;
        $checkoutData['delivery'] = [
            'fname' => $order->d_fname,
            'lname' => $order->d_lname,
            'company_name' => $order->d_company,
            'address_line_1' => $order->d_address_line_1,
            'address_line_2' => $order->d_address_line_2,
            'city' => $order->d_city,
            'state' => $order->d_state,
            'country' => $order->d_country,
            'pin_code' => $order->d_pin_code,
            'pnum' => $order->d_pnum,
        ];
        
        // Add Billing Info
        $checkoutData['billing'] = [
            'fname' => $order->b_fname,
            'lname' => $order->b_lname,
            'company_name' => $order->b_company,
            'address_line_1' => $order->b_address_line_1,
            'address_line_2' => $order->b_address_line_2,
            'city' => $order->b_city,
            'state' => $order->b_state,
            'country' => $order->b_country,
            'pin_code' => $order->b_pin_code,
            'pnum' => $order->b_pnum,
        ];
        Session::put('checkout_summary', $checkoutData);
        return redirect()->route('order.summary');
    }
}
