<?php

// 1. Buat folder temporary di /tmp untuk storage dan log
$storagePath = '/tmp/storage';
$viewsPath   = '/tmp/storage/framework/views';
$logsPath    = '/tmp/storage/logs';
$cachePath   = '/tmp/bootstrap/cache';

if (!is_dir($viewsPath)) {
    mkdir($viewsPath, 0755, true);
}
if (!is_dir($logsPath)) {
    mkdir($logsPath, 0755, true);
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

// 2. Buat file database SQLite kosong di /tmp jika tidak ada
$sqlitePath = '/tmp/database.sqlite';
if (!file_exists($sqlitePath)) {
    touch($sqlitePath);
}

// 3. Set Environment variables ke path /tmp
putenv("VIEW_COMPILED_PATH={$viewsPath}");
putenv("DB_DATABASE={$sqlitePath}");

$_ENV['VIEW_COMPILED_PATH'] = $viewsPath;
$_ENV['DB_DATABASE']        = $sqlitePath;
$_ENV['APP_STORAGE_PATH']   = $storagePath;
$_ENV['APP_CONFIG_CACHE']   = $cachePath . '/config.php';
$_ENV['APP_SERVICES_CACHE'] = $cachePath . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $cachePath . '/packages.php';
$_ENV['APP_ROUTES_CACHE']   = $cachePath . '/routes.php';

// 4. Panggil Laravel Application
require __DIR__ . '/../public/index.php';
