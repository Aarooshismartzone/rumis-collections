<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class customerAuthController extends Controller
{
    /**
     * Generate a guest token.
     */
    public function generateGuestToken(Request $request)
    {
        // Generate a random token
        $token = Str::random(60);

        // You can store this token temporarily (in cache for 24 hours)
        Cache::put('guest_token_' . $token, true, now()->addHours(24));

        return response()->json([
            'status' => true,
            'token' => $token,
            'message' => 'Guest token generated successfully.',
        ]);
    }
}
