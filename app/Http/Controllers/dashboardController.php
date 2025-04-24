<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class dashboardController extends Controller
{
    public function dashboard(){
        if (Auth::check()) {
            return view('backend.dashboard', ['user' => Auth::user()]);
        }
        return redirect('/internal/login');
    }
}
