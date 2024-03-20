<?php

// require 'helpers.php';

$autoload = [
    'classmap' => [
        "App\\" => "app/",
        "Firebase\\JWT\\" => 'third_party/JWT/'
    ],
    'files' => [
        'boostrap/helpers.php'
    ]
];

foreach ($autoload['files'] as $file) {
    require __DIR__ . '/../' . $file;
}

spl_autoload_register(function ($class) use ($autoload) {
    // echo '***************', PHP_EOL;
    // dump($class);
    foreach ($autoload['classmap'] as $key => $value) {
        if (str_starts_with($class, $key)) {
            // echo '--------------------', PHP_EOL;
            // dump($class);
            // dump($key);
            // dump($value);
            $class = str_replace($key, $value, $class);
            $class = str_replace('\\', '/', $class);
            $path = __DIR__ . '/../' . $class . '.php';
            if (file_exists($path)) {
                require $path;
            } else {
                die('can\'t find php file:' . $path);
            }
        }
    }
});