<?php

// 1. Buat folder temporary wajib di Vercel Serverless
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
    mkdir($storagePath . '/logs', 0755, true);
}

// 2. Set storage path bawaan ke /tmp
$_ENV['APP_STORAGE_PATH'] = $storagePath;

// 3. Panggil Autoload Composer & Aplikasi Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Daftarkan path compiled view secara langsung ke Laravel Container
$app->afterResolving('view', function ($view) {
    $view->getEngineResolver()->resolve('blade')->getCompiler()->setPath('/tmp/storage/framework/views');
});

// 5. Jalankan Aplikasi
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);
