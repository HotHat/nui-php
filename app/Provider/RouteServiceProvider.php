<?php
namespace App\Provider;

use App\Http\Controller\DashboardController;
use App\Http\Controller\HttpProxyController;
use App\Http\Controller\LoginController;
use App\Http\Controller\UserController;
use Niu\Router;

class RouteServiceProvider
{
    public function register($container): void
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

        $container['app.route'] = $router;
    }

}