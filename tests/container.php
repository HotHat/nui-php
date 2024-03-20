<?php declare(strict_types=1);

use App\HttpKernel;
use Niu\Container;

require '../boostrap/autoload.php';

$container = new Container();

$pimple['service'] = $container->factory(function () {
    return new \Niu\Request();
});

$pimple['same_service'] = new \Niu\Request();

// print_r($instance);
$s1 = $pimple['service'];
$s2 = $pimple['service'];

var_dump($s2 === $s1);

$p1 = $pimple['same_service'];
$p2 = $pimple['same_service'];

var_dump($p2 === $p1);


