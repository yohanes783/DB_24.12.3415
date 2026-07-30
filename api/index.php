<?php

// 1. Buat direktori sementara di /tmp
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

// 2. Set environment variabel storage dan view
$_ENV['APP_STORAGE_PATH']   = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = $viewsPath;

// 3. Load aplikasi Laravel
require __DIR__ . '/../public/index.php';
