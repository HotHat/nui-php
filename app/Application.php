<?php

namespace App;

use App\Controller\DashboardController;
use App\Controller\LoginController;
use App\Controller\SettingController;
use App\Controller\UserController;
use App\Middleware\SessionStart;

class Application extends Kernel
{
    public function routeProvider(): void
    {
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
    }

}