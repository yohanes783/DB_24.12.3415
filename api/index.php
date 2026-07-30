<?php

// 1. Matikan atau alihkan error output agar tidak memotong HTTP header
ini_set('display_errors', '0');

// 2. Siapkan semua struktur folder /tmp yang dibutuhkan Laravel
$tmp = '/tmp';
$storage = "$tmp/storage";
$cache = "$tmp/bootstrap/cache";

$dirs = [
    "$storage/app/public",
    "$storage/framework/cache/data",
    "$storage/framework/sessions",
    "$storage/framework/testing",
    "$storage/framework/views",
    "$storage/logs",
    $cache,
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 3. Timpa variabel lingkungan kritis
putenv("APP_STORAGE_PATH=$storage");
putenv("VIEW_COMPILED_PATH=$storage/framework/views");
putenv("SESSION_DRIVER=cookie");
putenv("CACHE_STORE=array");
putenv("CACHE_DRIVER=array");
putenv("LOG_CHANNEL=stderr");

$_ENV['APP_STORAGE_PATH']   = $storage;
$_ENV['VIEW_COMPILED_PATH'] = "$storage/framework/views";
$_ENV['SESSION_DRIVER']      = 'cookie';
$_ENV['CACHE_STORE']          = 'array';
$_ENV['CACHE_DRIVER']         = 'array';
$_ENV['LOG_CHANNEL']          = 'stderr';

// 4. Inisialisasi Aplikasi Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

// Pastikan path storage pada instance aplikasi menggunakan /tmp
$app->useStoragePath($storage);

// 5. Jalankan Kernel HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
