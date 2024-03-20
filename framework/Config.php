<?php declare(strict_types=1);

namespace Niu;

class Config
{
    private static array $config = [];
    public static function load($file) {
        $path = __DIR__ . '/../config/' . $file . '.php';
        if (!file_exists($path)) {
            throw new \Exception($path . ' not found');
        }
        if (array_key_exists($path, self::$config)) {
            return self::$config[$path];
        } else {
            $config = include $path;
            self::$config[$path] = $config;
            return $config;
        }
    }

}
