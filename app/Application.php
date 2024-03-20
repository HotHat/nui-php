<?php

namespace App;

use App\Http\Controller\DashboardController;
use App\Http\Controller\LoginController;
use App\Http\Controller\UserController;

class Application extends Kernel
{
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

        /*
        $this->routes = array_reduce([
            //
            $this->routeGroup([], [
                '/login' => [LoginController::class, 'submit']
            ]),
            //
            $this->routeGroup(['admin'], [
                '/admin/dashboard' => [DashboardController::class, 'dashboard'],
                '/admin/user' => [UserController::class, 'index'],
                // '/admin/user/list' => [UserController::class, 'list'],
                // '/admin/user/add' => [UserController::class, 'add'],
                // '/admin/user/update' => [UserController::class, 'update'],
                // '/admin/user/del' => [UserController::class, 'del'],
                // '/admin/setting' => [SettingController::class, 'index'],
                '/logout' => [LoginController::class, 'logout'],
            ]),
            //
        ], function ($carry, $item) {
            return array_merge($carry, $item);
        }, []);
        */
    }

}