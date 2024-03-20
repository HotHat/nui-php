<?php declare(strict_types=1);

use Niu\Request;

require  __DIR__ . "/../boostrap/autoload.php";

$app = require_once __DIR__ . "/../boostrap/app.php";


$kernel = $app->make('http.kernel');

$response = $kernel->handle(new Request());

echo $response;
