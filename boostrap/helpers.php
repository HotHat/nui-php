<?php declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;
use Niu\Config;

function appPath() {
   return \Niu\Application::getInstance()->container()->get('path.app');
}


#[NoReturn]
function dd($var): void
{
    var_dump($var);
    die();
}

function dump($var): void {
    var_dump($var);
}

function config($cfg) {
    return Config::load($cfg);
}

#[NoReturn]
function redirect($url): void {
    header("Location: " . $url);
    die();
}


function respJson($data): bool|string
{
    header('Content-Type: application/json');
    return json_encode($data);
}

function respSuccess($data=null, $isArray=false): bool|string
{
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


function hashMake($password): string
{
    return password_hash($password, PASSWORD_BCRYPT, [
        'cost' => 12
    ]);
}

function hashVerify($password, $hash): bool {
    return password_verify($password, $hash);
}
