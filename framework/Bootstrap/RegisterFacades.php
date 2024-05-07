<?php

namespace Nui\Bootstrap;

use Nui\Facade\App;
use Nui\Facade\DB;
use Nui\Facade\Facade;
use Nui\Facade\Route;

class RegisterFacades
{
    public function bootstrap($container): void
    {
        Facade::setFacadeContainer($container);

        $coreFacades = [
            'App' => App::class,
            'DB' => DB::class,
            'Route' => Route::class,
        ];

        foreach ($coreFacades as $alias => $facade) {
            class_alias($facade, $alias, true);
        }
    }
}