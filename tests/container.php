<?php declare(strict_types=1);

use App\HttpKernel;
use Niu\Application;
use Niu\Container;

require __DIR__ . '/../boostrap/autoload.php';
$app = require_once __DIR__ . '/../boostrap/app.php';

// $app = new Application(__DIR__ . '/..');
//
// $contain = $app->container();
//
// $contain['http.kernel'] = function() {
//     return new HttpKernel();
// };


$kernel = $app->make('http.kernel');


var_dump($kernel instanceof HttpKernel);
var_dump($kernel instanceof \Niu\Kernel);
// $resp = $kernel->handle(new \Niu\Request());

// echo $resp;
// echo '2222';



