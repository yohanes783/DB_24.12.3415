<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Buat struktur folder /tmp yang dibutuhkan
$storagePath = '/tmp/storage';
if (!is_dir($storagePath . '/framework/views')) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
    mkdir($storagePath . '/bootstrap/cache', 0755, true);
}

// 2. Set environment variabel penting untuk Vercel
putenv("APP_STORAGE_PATH={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("APP_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
putenv("APP_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
putenv("APP_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
putenv("APP_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");

$_ENV['APP_STORAGE_PATH']   = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

// 3. Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 4. Bootstrap Laravel Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 5. Ubah Storage Path pada instance aplikasi Laravel
$app->useStoragePath($storagePath);

// 6. Jalankan Request melalui HTTP Kernel
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
