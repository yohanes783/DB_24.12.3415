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
    ->withMiddleware(function (Middleware $middleware) {
        // PERBAIKAN NGROK: Mempercayai semua proxy agar HTTPS & Session dari Ngrok terbaca
        $middleware->trustProxies(at: '*');

        // 1. Mengecualikan route webhook Midtrans & Pendaftaran Partner dari blokir CSRF
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
            'partner/register',
            'partner/register/*',
        ]);

        // 2. Mendaftarkan alias middleware aplikasi
        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminMiddleware::class,
            'superadmin' => \App\Http\Middleware\EnsureUserIsSuperAdmin::class,
            'partner'    => \App\Http\Middleware\EnsureIsPartner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
