<?php declare(strict_types=1);

namespace Niu;

class Config
{
    private static array $config = [];
    private static Application $appliacton;
    public static function setApplication($app): void
    {
        self::$appliacton = $app;
    }

    public static function load($file) {
        $base = self::$appliacton->container()->get('path.config');
        $path = $base . '/' . $file . '.php';

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
