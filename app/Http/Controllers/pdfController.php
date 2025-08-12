<?php

namespace App\Http\Controllers;

use App\Models\Generic;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class pdfController extends Controller
{
    public function downloadReceipt($order_id)
    {
        $order = Order::with('customer')->findOrFail($order_id);
        if (
            $order->customer_id == Session::get('customer_id')
            || $order->guest_token == Session::get('guest_token')
            || Auth::check()
        ) {
            $orderItems = Orderitem::with('product')->where('order_id', $order_id)->get();
            $payment = Payment::where('order_id', $order_id)->first();

            // If no payment is found, redirect back with a warning message
            if (!$payment) {
                return redirect()->back()->with('warning', 'Payment has not been made yet for this order.');
            }

            // Get generic website information
            $generics = Generic::whereIn('key', ['title', 'tagline', 'logo_2'])->pluck('value', 'key');

            $data = [
                'order' => $order,
                'orderItems' => $orderItems,
                'payment' => $payment,
                'generics' => $generics,
                'date' => $order->created_at->format('d M Y, h:i A'),
                'customer_type' => $order->customer_id ? 'Registered' : 'Guest',
                'payment_status' => "Paid",
            ];

            $pdf = Pdf::loadView('pdfs.sales-receipt', $data);
            return $pdf->download('receipt_order_' . $order->id . '.pdf');
        }
        return back()->with('warning', 'You are not authorized to view this order.');
    }
}
