<?php

namespace App;

use App\Http\Controller\DashboardController;
use App\Http\Controller\LoginController;
use App\Http\Controller\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\SessionStart;
use Nui\Kernel;
use Nui\Router;
use Throwable;

class HttpKernel extends Kernel
{
    protected array $middleware = [
        SessionStart::class
    ];
    protected array $middlewareGroup = [
        'admin' => [
            Authenticate::class
        ],
        'api' => [
        ]
    ];
    protected array $routeMiddleware = [

    ];

}