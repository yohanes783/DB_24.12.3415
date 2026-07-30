<?php

// Pastikan folder storage temporary di Vercel sudah ada
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/bootstrap/cache', 0755, true);
}

// Forward ke file entry point resmi Laravel
require __DIR__ . '/../public/index.php';
