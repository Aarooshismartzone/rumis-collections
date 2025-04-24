<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class customerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('customer.auth.customer-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            if (!$customer->is_active) {
                return back()->with('error', 'Your account is not active.');
            }

            // Store customer session
            Session::put('customer_id', $customer->id);
            Session::put('customer_name', $customer->getFullNameAttribute());
            Session::put('customer_email', $customer->email);

            return redirect()->route('frontend.dashboard');
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function dashboard()
    {
        if (!Session::has('customer_id')) {
            return redirect()->route('customer.login')->with('error', 'Please login first.');
        }

        return view('frontend.dashboard');
    }

    public function logout()
    {
        Session::forget(['customer_id', 'customer_name', 'customer_email']);
        return redirect()->route('customer.login')->with('success', 'Logged out successfully.');
    }
}
