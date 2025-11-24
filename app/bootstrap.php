<?php
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');

spl_autoload_register(function ($class) {
    $paths = [APP_PATH . '/controllers/', APP_PATH . '/models/'];
    foreach ($paths as $p) {
        $file = $p . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});