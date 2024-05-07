<?php declare(strict_types=1);


use App\Exception\Handler;
use App\HttpKernel;
use Nui\Application;

$app = new Application(realpath(__DIR__ . '/..'));

$app->singleton('http.kernel', function() use ($app) {
    return new HttpKernel($app);
});

$app->singleton('http.exception', function() {
    return new Handler();
});

return $app;