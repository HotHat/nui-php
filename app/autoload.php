<?php

require 'helpers.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = str_replace('App', 'app', $class);

    $path = __DIR__ . '/../' . $class . '.php';
    if (file_exists($path)) {
        include $path;
    } else {
        die('can\'t find php file:' . $path);
    }
});