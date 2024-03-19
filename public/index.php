<?php declare(strict_types=1);

require "../app/autoload.php";

use App\Application;
use App\Request;


$app = new Application();
$response = $app->handle(new Request());

echo $response;
