<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class googleController extends Controller
{
    public function redirect()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();


            // Split first and last name
            $names = explode(' ', $googleUser->getName(), 2);
            $fname = $names[0] ?? '';
            $lname = $names[1] ?? '';

            // Check if customer exists
            $customer = Customer::where('email', $googleUser->getEmail())->first();

            if (!$customer) {
                // Generate unique username (like in register())
                $baseUsername = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $fname), 0, 4));
                do {
                    $username = $baseUsername . rand(1000, 9999);
                } while (Customer::where('username', $username)->exists());

                // Create new customer
                $customer = Customer::create([
                    'fname'           => $fname,
                    'lname'           => $lname,
                    'username'        => $username,
                    'email'           => $googleUser->getEmail(),
                    'pnum'            => null,
                    'password'        => bcrypt(Str::random(16)), // dummy password
                    'company_name'    => null,
                    'profile_image'   => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'is_active'       => true,
                ]);
            }

            if (!$customer->is_active) {
                return redirect()->route('customer.login')->with('error', 'Your account is not active.');
            }

            // Store customer session (same as customerAuthController)
            Session::put('customer_id', $customer->id);
            Session::put('customer_fname', $customer->fname);
            Session::put('customer_lname', $customer->lname);
            Session::put('customer_name', $customer->full_name);
            Session::put('customer_email', $customer->email);

            return redirect()->route('frontend.home');
        } catch (\Exception $e) {
            return redirect()->route('customer.login')->with('error', 'Google login failed.');
        }
    }
}
