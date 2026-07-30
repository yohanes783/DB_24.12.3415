<?php

// 1. Siapkan direktori sementara
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/bootstrap/cache', 0755, true);
}

// 2. Set Environment path
putenv("APP_STORAGE_PATH={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

// 3. Jalankan entrypoint Laravel
require __DIR__ . '/../public/index.php';
