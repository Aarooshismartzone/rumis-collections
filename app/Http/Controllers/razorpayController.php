<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Note;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;

class razorpayController extends Controller
{
    public function viewPaymentPage()
    {
        if (!session()->has('checkout_summary')) {
            return redirect()->route('checkout')->with('error', 'No order summary found.');
        }

        $summary = session('checkout_summary');

        // Check if customer is logged in
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;

        return view('frontend.razorpay', compact('summary', 'customer'));
    }

    public function proceedToPayment(Request $request)
    {
        if (!$request->has('razorpay_payment_id')) {
            return redirect()->route('checkout')->with('error', 'Payment failed or cancelled.');
        }

        $paymentId = $request->input('razorpay_payment_id');
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $checkoutData = Session::get('checkout_summary');
        $checkoutData['payment_id'] = $paymentId; // Add payment ID to checkout data in session
        $checkoutData['payment_method'] = 'Razorpay';

        $note = new Note;
        if (Session::has('customer_id')) {
            $note->customer_id = Session::get('customer_id');
        } else {
            $note->guest_token = Session::get('guest_token');
        }

        $note->order_id = $checkoutData['order_id'];
        $note->related_to = 'Payment Status';
        $note->is_manual = false;

        try {
            $payment = $api->payment->fetch($paymentId);

            if ($payment->status == 'authorized') {
                $payment->capture(['amount' => $payment->amount]); // capture full amount

                // Save to 'payments' table
                Payment::create([
                    'order_id'       => $checkoutData['order_id'],
                    'payment_method' => 'Razorpay',
                    'currency_name'  => 'INR',
                    'amount'         => $payment->amount / 100, // Razorpay sends amount in paise
                    'payment_id'     => $paymentId,
                ]);


                $note->note = 'Payment captured successfully via Razorpay on ' . Carbon::now()->format('d-m-Y, h:i A') . '.';
                $note->save();

                return redirect()->route('payment.success')->with('success', 'Payment Successful!');
            } else {

                $note->note = 'Payment attempted but was not authorized';
                $note->save();

                return redirect()->route('checkout')->with('error', 'Payment was not authorized.');
            }
        } catch (\Exception $e) {

            $note->note = 'Payment could not be made. ' . $e->getmessage();
            $note->save();

            return redirect()->route('checkout')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }
}
