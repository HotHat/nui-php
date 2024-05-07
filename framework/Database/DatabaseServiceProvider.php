<?php

namespace Nui\Database;

use Nui\Database\Query\Builder;
use Nui\Database\Query\MySqlGrammar;
use PDO;

class DatabaseServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register($container): void
    {
        $container['db.connection'] = function () {
            $cfg = config('database');
            $pdo = new PDO(
                sprintf('%s:dbname=%s;host=%s',
                    $cfg['driver'],
                    $cfg['database'],
                    $cfg['host'] . ':' . $cfg['port']),
                $cfg['username'],
                $cfg['password'],
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            return new MySqlConnection(
                $pdo,
                $cfg['database'],
                $cfg['prefix'],
                []
            );
        };
    }
}
