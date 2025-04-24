<?php

namespace App\Providers;

use App\Models\Generic;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class GenericsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $generics = Generic::pluck('value',  'key')->toArray();
        View::share('generics', $generics);
    }
}
