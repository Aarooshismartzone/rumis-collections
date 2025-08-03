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
        return view('frontend.customer.customer-login');
    }

    public function register(Request $request)
    {
        // Validate form input
        $request->validate([
            'fname' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'lname' => 'nullable|string|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|email|unique:customers,email',
            'pnum' => 'required|numeric|digits_between:7,15|unique:customers,pnum',
            'password' => 'required|string|min:6|confirmed',
        ]);
        // Handle first and last name
        $fname = $request->input('fname'); // First Name
        $lname = $request->input('lname'); // Last Name (can be null)

        // Generate unique username
        $baseUsername = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $fname), 0, 4));
        do {
            $username = $baseUsername . rand(1000, 9999);
        } while (Customer::where('username', $username)->exists());

        // Create new customer
        $customer = Customer::create([
            'fname' => $fname,
            'lname' => $lname,
            'username' => $username,
            'email' => $request->email,
            'pnum' => $request->pnum,
            'password' => Hash::make($request->password),
            'company_name' => null,
            'profile_image' => null,
            'email_verified_at' => null,
            'is_active' => true,
        ]);

        // Auto-login (optional)
        auth('customer')->login($customer);

        // Redirect to customer dashboard or wherever
        return redirect()->route('customer.dashboard')->with('success', 'Registration successful. Welcome!');
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
            Session::put('customer_fname', $customer->fname);
            Session::put('customer_lname', $customer->lname);
            Session::put('customer_name', $customer->full_name);
            Session::put('customer_email', $customer->email);

            return redirect()->back();
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        Session::forget(['customer_id', 'customer_name', 'customer_email']);
        return redirect()->route('customer.login')->with('success', 'Logged out successfully.');
    }
}
