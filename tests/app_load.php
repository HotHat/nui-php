<?php declare(strict_types=1);
require  __DIR__ . "/../boostrap/autoload.php";
$app = require  __DIR__ . "/../boostrap/app.php";

$app->make('http.kernel')->bootstrap();

return $app;
