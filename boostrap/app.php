<?php declare(strict_types=1);


use App\Exception\Handler;
use App\HttpKernel;
use Niu\Application;

$app = new Application(realpath(__DIR__ . '/..'));

$contain = $app->container();

$contain['http.kernel'] = function() use ($app) {
    return new HttpKernel($app);
};

$contain['http.exception'] = function() {
    return new Handler();
};


return $app;