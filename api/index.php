<?php

// 1. Prepare writable directories in /tmp for Vercel Serverless environment
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Set environment paths to /tmp so Laravel doesn't write to read-only root
putenv('APP_STORAGE=/tmp/storage');
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_SERVER['APP_STORAGE'] = '/tmp/storage';

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');

// 3. Handle SQLite Database in /tmp if external DB is not provided
$dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? 'sqlite');

if ($dbConnection === 'sqlite') {
    $bundledDb = __DIR__ . '/../database/database.sqlite';
    $tmpDb = '/tmp/database.sqlite';

    if (!file_exists($tmpDb) || filesize($tmpDb) === 0) {
        if (file_exists($bundledDb) && filesize($bundledDb) > 0) {
            copy($bundledDb, $tmpDb);
        } else {
            touch($tmpDb);
        }
    }

    if (file_exists($tmpDb)) {
        chmod($tmpDb, 0666);
        putenv("DB_DATABASE={$tmpDb}");
        $_ENV['DB_DATABASE'] = $tmpDb;
        $_SERVER['DB_DATABASE'] = $tmpDb;
    }
}

// 4. Forward execution to public/index.php
require __DIR__ . '/../public/index.php';
