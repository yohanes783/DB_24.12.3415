<?php

// 1. Buat direktori sementara yang bisa ditulis di Vercel
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

// 2. Set Environment Variables agar Laravel menyimpan View & Cache di /tmp
putenv("VIEW_COMPILED_PATH={$viewsPath}");
$_ENV['VIEW_COMPILED_PATH'] = $viewsPath;

$_ENV['APP_STORAGE_PATH'] = $storagePath;
$_ENV['APP_CONFIG_CACHE'] = $cachePath . '/config.php';
$_ENV['APP_SERVICES_CACHE'] = $cachePath . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $cachePath . '/packages.php';
$_ENV['APP_ROUTES_CACHE'] = $cachePath . '/routes.php';

// 3. Panggil Laravel Application
require __DIR__ . '/../public/index.php';
