<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Notifications\CustomerResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class customerPasswordResetController extends Controller
{
    // Show the form to request a password reset link
    public function showLinkRequestForm()
    {
        return view('frontend.customer.forgot-password');
    }

    // Send the reset link to customer's email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Set the custom notification callback BEFORE calling sendResetLink
        Password::broker('customers')->sendResetLink(
            $request->only('email'),
            function ($customer, $token) {
                $customer->notify(new CustomerResetPassword($token, $customer->email));
            }
        );

        return back()->with('status', 'We have emailed your password reset link!');
    }

    // Show the reset password form
    public function showResetForm(Request $request, $token)
    {
        return view('frontend.customer.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Handle the password reset
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($customer, $password) {
                $customer->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $customer->save();

                event(new PasswordReset($customer));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
