<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Note;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
        $orderId = $checkoutData['order_id'];
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
                    'order_id'       => $orderId,
                    'payment_method' => 'Razorpay',
                    'currency_name'  => 'INR',
                    'amount'         => $payment->amount / 100, // Razorpay sends amount in paise
                    'payment_id'     => $paymentId,
                ]);


                $note->note = 'Payment captured successfully via Razorpay on ' . Carbon::now()->format('d-m-Y, h:i A') . '.';
                $note->save();

                if (Session::has('customer_id')) {
                    $cartItems = Cart::where('customer_id', Session::get('customer_id'))
                        ->where('is_wishlist', 'no')
                        ->get();
                } else {
                    $cartItems = Cart::where('guest_token', Session::get('guest_token'))
                        ->where('is_wishlist', 'no')
                        ->get();
                }

                $order = Order::find($orderId);

                foreach ($cartItems as $item) {
                    $product = $item->product;

                    if ($product) {
                        $product->increment('number_of_orders', $item->quantity);
                    }

                    Orderitem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ]);
                }

                $order->order_status = 'payment-success';
                $order->save();

                $note_mail = new Note;
                if (Session::has('customer_id')) {
                    $note_mail->customer_id = Session::get('customer_id');
                } else {
                    $note_mail->guest_token = Session::get('guest_token');
                }

                $note_mail->order_id = $orderId;
                $note_mail->related_to = 'Order Email Status';

                // Email with PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = env('MAIL_HOST');
                    $mail->SMTPAuth   = true;
                    $mail->Username   = env('MAIL_USERNAME');
                    $mail->Password   = env('MAIL_PASSWORD');
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = env('MAIL_PORT');

                    // Recipients
                    $mail->setFrom(env('MAIL_USERNAME'), "Rumi's Collections");
                    $mail->addAddress('contact@aarooshi.com', 'Admin');

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Order #' . $order->id . " Payment Confirmed - Rumi's Collections";
                    $mail->Body = "
                    <p>A new order has been successfully paid.</p>
                    <p><strong>Order ID:</strong> ' . $order->id . '</p>
                    <hr>
                    <p>This is an automated message from Rumi's Collections. For any queries, contact us at <a href='mailto:contact@rumiscollections.com'>contact@rumiscollections.com</a>.</p>
                ";
                    //$mail->AltBody = 'A new order has been successfully paid. Order ID: ' . $order->id;

                    $mail->send();

                    $note_mail->note = "Email Sent successfully on " . Carbon::now()->format('d-m-Y, h:i A') . ".";
                } catch (Exception $e) {
                    $note_mail->note = "Email could not be sent. " . $mail->ErrorInfo;
                }

                $note_mail->is_manual = false;
                $note_mail->save();

                //DELETE FUNCTION IS CARRIED OUT SEPARATELY
                if (Session::has('customer_id')) {
                    $cartItems = Cart::where('customer_id', Session::get('customer_id'))
                        ->where('is_wishlist', 0)
                        ->delete();
                } else {
                    $cartItems = Cart::where('guest_token', Session::get('guest_token'))
                        ->where('is_wishlist', 0)
                        ->delete();
                }


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
