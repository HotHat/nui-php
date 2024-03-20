<?php declare(strict_types=1);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require '../boostrap/autoload.php';


$cfg = config('jwt');
$payload = [
    'iat' => strtotime('2024-03-20 01:30:00'),
    'exp' => strtotime('2024-03-20 10:30:00'),
    'id' => 1,
    'name' => 'hello'
];
// dump(time());
// dd($payload);
$jwt = JWT::encode($payload, $cfg['jwt_key'], 'HS256');
// sleep(5);
$decode = JWT::decode($jwt, new Key($cfg['jwt_key'], 'HS256'));
dd(['encode' => $jwt, 'decode' => $decode] );
