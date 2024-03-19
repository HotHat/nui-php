<?php declare(strict_types=1);

require '../app/Router.php';


$router = new Router();

$router->group(['middleware' => ['web', 'web2'], 'prefix' => 'v1'], function ($router) {
    $router->get('/hello', 'A@act1');
    $router->post('/world', 'A@act2');

    $router->group([
        'middleware' => ['api1', 'api2'],
        'prefix' => 'v2'
    ], function ($router) {
        $router->get('/hello', 'A2@act1');
        $router->post('/world', 'A2@act2');
    });

    $router->get('/hello2', 'A@act21');
    $router->post('/world2', 'A@act22')->name('test.add.name');
});

$router->get('/v3/abc', 'A3@act5');

$al = $router->getRoutes();
print_r($al);