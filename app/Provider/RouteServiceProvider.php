<?php
namespace App\Provider;

use App\Http\Controller\DashboardController;
use App\Http\Controller\HttpProxyController;
use App\Http\Controller\LoginController;
use App\Http\Controller\PermissionController;
use App\Http\Controller\RoleController;
use App\Http\Controller\UserController;
use Nui\Container;
use Nui\Router;

class RouteServiceProvider
{
    public function register(Container $container): void
    {
        $router = $container->make('router');

        $router->post('/admin/login', [LoginController::class, 'login']);
        $router->get('/admin/auth/captcha', [LoginController::class, 'captcha']);

        $router->group([
            'middleware' => ['admin'],
            'prefix' => '/admin'
        ], function ($router) {
            $router->get('/user/detail', [UserController::class, 'detail']);
            $router->get('/role/permissions', [UserController::class, 'permissions']);

            $router->get('/dashboard', [DashboardController::class, 'dashboard']);

            $router->get('/user/index', [UserController::class, 'index']);
            $router->post('/user/add', [UserController::class, 'add']);
            $router->patch('/user/update', [UserController::class, 'update']);
            $router->delete('/user/:id', [UserController::class, 'delete']);

            $router->get('/role/index', [RoleController::class, 'index']);
            $router->post('/role/add', [RoleController::class, 'add']);
            $router->patch('/role/user/add/:id', [RoleController::class, 'addUser']);
            $router->patch('/role/:id', [RoleController::class, 'update']);
            $router->delete('/role/:id', [RoleController::class, 'delete']);

            $router->get('/permission/menu', [PermissionController::class, 'index']);
            $router->post('/permission/add', [PermissionController::class, 'add']);
            $router->patch('/permission/update', [PermissionController::class, 'update']);
            $router->delete('/permission/delete', [PermissionController::class, 'delete']);

            $router->post('/logout', [LoginController::class, 'logout']);
        });

        $router->get('/curl/test', [LoginController::class, 'test']);
        $router->post('/curl/test2', [LoginController::class, 'test2']);
        $router->get('/ps/:abc', [LoginController::class, 'ps']);

    }

}