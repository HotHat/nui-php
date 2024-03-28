<?php

namespace Niu\Facade;

class App extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'app';
    }

}