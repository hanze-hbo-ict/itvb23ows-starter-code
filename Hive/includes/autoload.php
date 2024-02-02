<?php

spl_autoload_register(function ($class) {
    $classFile = __DIR__ . '/../src/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

function register_autoload($dir)
{
    foreach (scandir($dir) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $path = $dir . '/' . $file;

        if (is_dir($path)) {
            register_autoload($path);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            require_once $path;
        }
    }
}

register_autoload(__DIR__ . '/../src');
