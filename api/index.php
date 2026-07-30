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

// 2. OTOMATIS BUAT FILE SQLITE JIKA BELUM ADA
$sqlitePath = '/tmp/database.sqlite';
if (!file_exists($sqlitePath)) {
    touch($sqlitePath);
}

// 3. Set environment variabel penting untuk Vercel
putenv("APP_STORAGE_PATH={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("DB_CONNECTION=sqlite");
putenv("DB_DATABASE={$sqlitePath}");

$_ENV['APP_STORAGE_PATH']   = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";
$_ENV['DB_CONNECTION']      = 'sqlite';
$_ENV['DB_DATABASE']        = $sqlitePath;

// 4. Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Laravel Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 6. Ubah Storage Path pada instance aplikasi
$app->useStoragePath($storagePath);

// 7. Jalankan Request melalui HTTP Kernel
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
