<?php declare(strict_types=1);

namespace Niu;

class Config
{
    private static array $config = [];

    public static function load($file) {
        $base = Application::getInstance()->container()->get('path.config');
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

    public static function get($cfg) {
        $sp = explode('.', $cfg);
        $file = array_shift($sp);
        $next = Config::load($file);
        foreach ($sp as $it) {
            $next = $next[$it] ?? null;
        }
        return $next;
    }

}
