<?php

namespace Niu\Bootstrap;

use Niu\Facade\App;
use Niu\Facade\DB;
use Niu\Facade\Facade;
use Niu\Facade\Route;

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