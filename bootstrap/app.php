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
        // 1. Mengecualikan route webhook Midtrans dari blokir CSRF
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback', 
        ]);

        // 2. Mendaftarkan alias middleware 'admin' agar bisa dibaca oleh routes/web.php
        // CATATAN: Pastikan Anda sudah membuat file Admin middleware (biasanya bernama IsAdmin, Admin, atau sejenisnya)
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class, // <-- Sesuaikan nama class middleware Admin Anda di sini
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
