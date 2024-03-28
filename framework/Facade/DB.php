<?php

namespace Niu\Facade;

/**
 * @method static string table(string $table)
 */
class DB extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'db.connection';
    }

}