<?php

// 1. Buat folder temporary yang bisa ditulis di /tmp milik Vercel
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
}

// 2. Set environment path storage
$_ENV['APP_STORAGE_PATH'] = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';

// 3. Panggil entrypoint standar Laravel
require __DIR__ . '/../public/index.php';
