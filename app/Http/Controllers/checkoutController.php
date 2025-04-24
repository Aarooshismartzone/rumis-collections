<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\Generic;

class checkoutController extends Controller
{
    public function index()
    {
        // Determine if user is logged in or a guest
        if (Session::has('customer_id')) {
            $customer_id = Session::get('customer_id');
            $guest_token = null;
        } else {
            // If guest, check if guest_token exists, otherwise generate one
            if (!Session::has('guest_token')) {
                Session::put('guest_token', Str::random(32));
            }
            $customer_id = null;
            $guest_token = Session::get('guest_token');
        }

        // Fetch cart items based on customer_id or guest_token
        $carts = Cart::with('product')
            ->when($customer_id, function ($query) use ($customer_id) {
                return $query->where('customer_id', $customer_id);
            })
            ->when($guest_token, function ($query) use ($guest_token) {
                return $query->where('guest_token', $guest_token);
            })
            ->get();

        $generics = Generic::pluck('value', 'key')->toArray();

        // Convert 'is_gst' string to boolean
        $gstEnabled = filter_var($generics['is_gst'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $gstRate = $gstEnabled ? (float) $generics['gst_rate_percent'] : 0;

        // Calculate totals
        $totalPrice = collect($carts)->sum(fn($item) => $item->price * $item->quantity);
        $deliveryCharge = isset($generics['delivery_charges']) ? (float) $generics['delivery_charges'] : 0;
        $gstAmount = ($gstRate / 100) * $totalPrice;
        $grandTotal = $totalPrice + $deliveryCharge + $gstAmount;

        return view('frontend.checkout', compact(
            'carts',
            'totalPrice',
            'deliveryCharge',
            'gstAmount',
            'grandTotal',
            'generics'
        ));
    }
}
