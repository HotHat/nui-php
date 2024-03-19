<?php declare(strict_types=1);

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

function config($cfg) {
    return \App\Config::load($cfg);
}

function redirect($url) {
    header("Location: " . $url);
    die();
}

function session($key, $value=null) {
    if (!$value) {
        return $_SESSION[$key] ?? null;
    }
    $_SESSION[$key] = $value;
}

function sessionFlush() {
    session_unset();
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

function authLogin($user) {
    session_regenerate_id();
    session('auth_user', $user);
}

function auth_user() {
    return session('auth_user');
}

function hashMake($password) {
    return password_hash($password, PASSWORD_BCRYPT, [
        'cost' => 12
    ]);
}

function hashVerify($password, $hash): bool {
    return password_verify($password, $hash);
}
