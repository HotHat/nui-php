<?php

namespace App;

use App\Http\Controller\DashboardController;
use App\Http\Controller\LoginController;
use App\Http\Controller\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\SessionStart;
use Niu\Kernel;
use Niu\Router;
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

    protected function registerExceptionHandler(): void
    {
        parent::registerExceptionHandler();

        set_exception_handler(function (Throwable $exp) {
            file_put_contents(
                __DIR__ . '/../Storage/error.log',
                sprintf("%s: %s\n", date('Y-m-d H:i:s'), $exp->__toString()),
                FILE_APPEND
            );
        });
    }

    public function routeProvider(): void
    {
        $router = new Router();
        $router->post('/admin/login', [LoginController::class, 'login']);

        $router->group([
            'middleware' => ['admin'],
            'prefix' => '/admin'
        ], function ($router) {
            $router->get('/dashboard', [DashboardController::class, 'dashboard']);
            $router->get('/user', [UserController::class, 'index']);
            $router->post('/logout', [LoginController::class, 'logout']);
        });
        $this->routes = $router->getRoutes();
    }

}