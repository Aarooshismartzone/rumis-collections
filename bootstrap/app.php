<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CustomerAuth;
use App\Http\Middleware\GuestOrAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
      //  $middleware->append(CustomerAuth::class); // Register the CustomerAuth middleware
      $middleware->alias([
            //'auth' => CustomerAuth::class,
            'guestOrAuth' => GuestOrAuth::class, // custom alias here
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
