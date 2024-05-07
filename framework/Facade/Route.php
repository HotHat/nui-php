<?php

namespace Nui\Facade;

class Route extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'router';
    }

}