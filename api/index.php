<?php

// 1. Create writable temporary directories in /tmp for Vercel Serverless environment
$storageDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Set serverless environment variables
$envVars = [
    'APP_STORAGE' => '/tmp/storage',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_SERVICES_CACHE' => '/tmp/bootstrap/cache/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/bootstrap/cache/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/bootstrap/cache/config.php',
    'APP_ROUTES_CACHE' => '/tmp/bootstrap/cache/routes.php',
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
    'APP_KEY' => getenv('APP_KEY') ?: 'base64:4kwYPMKbCbip6ZhtmROiOaUNVpzJK+AvcQuKzShPIC8=',
];

foreach ($envVars as $key => $val) {
    if (!getenv($key)) {
        putenv("{$key}={$val}");
        $_ENV[$key] = $val;
        $_SERVER[$key] = $val;
    }
}

// 3. Run application with exception handler to capture serverless runtime errors
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    error_log((string)$e);
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
    }
    echo "<h1>500 Internal Server Error</h1>";
    echo "<p>An error occurred while processing your request on Vercel Serverless.</p>";
    echo "<pre>" . htmlspecialchars((string)$e) . "</pre>";
}
