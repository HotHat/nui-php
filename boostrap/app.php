<?php declare(strict_types=1);


use Niu\Application;
use App\HttpKernel;

$app = new Application(__DIR__ . '/..');

$contain = $app->container();

$contain['http.kernel'] = function() {
    return new HttpKernel();
};




return $app;