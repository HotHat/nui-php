<?php
namespace App\Database;

class DB
{
    private static $instance;
    static public function instance() {
        if (!self::$instance) {
            $cfg = config('database');

            self::$instance = new PdoDB(
                $cfg['db_driver'],
                $cfg['db_database'],
                $cfg['db_host'] . ':' . $cfg['db_port'],
                $cfg['db_username'],
                $cfg['db_password'],
            );
        }

        return self::$instance;
    }
}