<?php declare(strict_types=1);

use App\Controller\DashboardController;
use App\Controller\UserController;
use App\Controller\LoginController;
use App\Controller\SettingController;
use App\Redirect;
use App\Request;
use App\Response;

require "helpers.php";
initSite();

function sessionMiddleware($req, $next) {
    session_start();
    return $next($req);
}

function authMiddleware($req, $next) {
    if (!in_array($req->getUri(), [ '/login', '/login/submit'])) {
        $user = auth_user();
        if (!$user) {
            return (new Response())->redirect('/login');
        }
    }

    return $next($req);
}

function routeDispatch($router) {
    return function ($req) use ($router) : Response {
        $uri = $req->getUri();

        ob_start();
        if (isset($router[$uri])) {
            $action = $router[$uri];

            $resp = $action($req);

        } else {
            $resp = new Response('404');
        }
        $echo = ob_get_contents();
        ob_end_clean();
        if (is_string($resp)) {
            return new Response($echo . $resp);
        } else if ($resp instanceof Response) {
           return new Response($echo . $resp->rawData);
        }

        return new Response($echo);
    };
}

function pipe($router, $middlewares) {
    return array_reduce($middlewares, function($carry, $item) {
        return function($req) use ($carry, $item) {
            return $item($req, $carry);
        };
    }, routeDispatch($router));
}


//
$request = new Request();


$router = [];

$router['/login'] = action([LoginController::class, 'login']);

$router['/logout'] = action([LoginController::class, 'logout']);

$router['/login/submit'] = action([LoginController::class, 'submit']);

$router['/admin/'] = action([DashboardController::class, 'dashboard']);

$router['/admin/user'] = action([UserController::class, 'index']);
$router['/admin/user/list'] = action([UserController::class, 'list']);
$router['/admin/user/add'] = action([UserController::class, 'add']);
$router['/admin/user/update'] = action([UserController::class, 'update']);
$router['/admin/user/del'] = action([UserController::class, 'del']);

$router['/admin/setting'] = action([SettingController::class, 'index']);
$router['/admin/setting/all'] = action([SettingController::class, 'all']);
$router['/admin/setting/update'] = action([SettingController::class, 'update']);
$router['/admin/setting/updateUser'] = action([SettingController::class, 'updateUser']);

$router['/server/status'] = action([DashboardController::class, 'status']);


// 模板环境变量
TemplateRender::add('request_uri', $request->getUri());

$dispatch = pipe($router, ['authMiddleware', 'sessionMiddleware']);


$resp = $dispatch($request);

// 渲染response内容
// TODO: http头, http 内容
if ($resp->redirect) {
    \redirect($resp->redirect);
} else {
    echo $resp->rawData;
}