<?php declare(strict_types=1);

use Niu\Config;

function assetUrl() {
    return '/assets/';
}
function baseUrl() {
    return '/';
}

function storagePath() {
   return __DIR__ . '/../Storage/';
}

function dd($var) {
    var_dump($var);
    die();
}

function dump($var) {
    var_dump($var);
}

function config($cfg) {
    return Config::load($cfg);
}

function redirect($url) {
    header("Location: " . $url);
    die();
}


function respJson($data) {
    header('Content-Type: application/json');
    return json_encode($data);
}

function respSuccess($data=null, $isArray=false) {
   return respJson([
       'code' => 200,
       'message' => 'success',
       'data' => $data ?: ($isArray ? [] : new \stdClass())
   ]);
}
function respFail($message, $data=[], $code=0) {
    return respJson([
        'code' => $code,
        'message' => $message,
        'data' => $data ?: new \stdClass()
    ]);
}


function hashMake($password) {
    return password_hash($password, PASSWORD_BCRYPT, [
        'cost' => 12
    ]);
}

function hashVerify($password, $hash): bool {
    return password_verify($password, $hash);
}
