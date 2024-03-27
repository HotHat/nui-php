<?php

namespace Niu\Database;

use Niu\Database\Query\Builder;
use Niu\Database\Query\MySqlGrammar;
use PDO;

class DatabaseServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register($container)
    {
        $container['db.connection'] = function ($app) {
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

        $container['db.builder'] = function () use ($container) {
            $connection = $container['db.connection'];
            $grammar = new MySqlGrammar();
            return new Builder(
                $connection,
                $grammar
            );
        };
    }
}
