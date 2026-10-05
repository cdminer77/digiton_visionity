<?php
$root = __DIR__;
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = $root . $uri;

// 1. If it is an existing PHP file, execute it directly
if (file_exists($file) && is_file($file)) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        chdir(dirname($file));
        require $file;
        return true;
    }
    // Static asset (css, js, png, jpg, etc.): let built-in server serve it
    return false;
}

// 2. If it is an existing directory with index.php
if (is_dir($file) && file_exists($file . '/index.php')) {
    chdir($file);
    require $file . '/index.php';
    return true;
}

// 3. Fallback to WordPress main router
chdir($root);
require $root . '/index.php';
