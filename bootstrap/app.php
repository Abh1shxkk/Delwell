<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'admin.guest' => \App\Http\Middleware\AdminRedirect::class,
            'admin.auth'  => \App\Http\Middleware\AdminAuthenticate::class,
            'manager.guest'   => \App\Http\Middleware\ManagerRedirect::class,
            'manager.auth'    => \App\Http\Middleware\ManagerAuthenticate::class,
            'user.guest'   => \App\Http\Middleware\UserRedirect::class,
            'user.auth'    => \App\Http\Middleware\UserAuthenticate::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'profile.complete' => \App\Http\Middleware\EnsureProfileComplete::class,
        ]);

        // Use this for default guard redirect only
        $middleware->redirectTo(
            guests: '/login',
            users: '/user/dashboard'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
