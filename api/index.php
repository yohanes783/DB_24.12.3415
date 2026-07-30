<?php

// 1. Buat direktori sementara yang bisa ditulis di /tmp
$storagePath = '/tmp/storage';
$viewsPath   = '/tmp/storage/framework/views';
$cachePath   = '/tmp/bootstrap/cache';

if (!is_dir($viewsPath)) {
    mkdir($viewsPath, 0755, true);
}
if (!is_dir($storagePath . '/framework/sessions')) {
    mkdir($storagePath . '/framework/sessions', 0755, true);
}
if (!is_dir($storagePath . '/framework/cache')) {
    mkdir($storagePath . '/framework/cache', 0755, true);
}
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0755, true);
}

// 2. Buat database sqlite kosong di /tmp jika tidak ada
$sqlitePath = '/tmp/database.sqlite';
if (!file_exists($sqlitePath)) {
    touch($sqlitePath);
}

// 3. Force override environment variables
putenv("VIEW_COMPILED_PATH={$viewsPath}");
putenv("SESSION_DRIVER=array");
putenv("CACHE_STORE=array");
putenv("CACHE_DRIVER=array");
putenv("LOG_CHANNEL=stderr");
putenv("DB_DATABASE={$sqlitePath}");

$_ENV['VIEW_COMPILED_PATH'] = $viewsPath;
$_ENV['SESSION_DRIVER']      = 'array';
$_ENV['CACHE_STORE']          = 'array';
$_ENV['CACHE_DRIVER']         = 'array';
$_ENV['LOG_CHANNEL']          = 'stderr';
$_ENV['DB_DATABASE']          = $sqlitePath;
$_ENV['APP_STORAGE_PATH']     = $storagePath;

// 4. Panggil bootstrap Laravel
require __DIR__ . '/../public/index.php';
