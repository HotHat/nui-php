<?php

namespace Niu\Facade;

class Route extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'router';
    }

}