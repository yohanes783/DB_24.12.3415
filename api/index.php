<?php

// 1. Buat folder temporary di Vercel Serverless
$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

if (!is_dir($storagePath)) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
}

if (!is_dir($cachePath)) {
    mkdir($cachePath, 0755, true);
}

// 2. Set variabel environment untuk path cache Laravel
$_ENV['APP_STORAGE_PATH'] = $storagePath;
$_ENV['APP_CONFIG_CACHE'] = $cachePath . '/config.php';
$_ENV['APP_SERVICES_CACHE'] = $cachePath . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $cachePath . '/packages.php';
$_ENV['APP_ROUTES_CACHE'] = $cachePath . '/routes.php';

// 3. Panggil file index Laravel utama
require __DIR__ . '/../public/index.php';
