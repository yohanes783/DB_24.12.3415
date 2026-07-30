<?php

// Paksa php menampilkan error jika ada yang bermasalah
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Buat direktori sementara di /tmp
$storagePath = '/tmp/storage';
$viewsPath   = '/tmp/storage/framework/views';

if (!file_exists($viewsPath)) {
    @mkdir($viewsPath, 0755, true);
}
if (!file_exists($storagePath . '/framework/sessions')) {
    @mkdir($storagePath . '/framework/sessions', 0755, true);
}
if (!file_exists($storagePath . '/framework/cache')) {
    @mkdir($storagePath . '/framework/cache', 0755, true);
}

$_ENV['APP_STORAGE_PATH']   = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = $viewsPath;

require __DIR__ . '/../public/index.php';
