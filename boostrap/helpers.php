<?php declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;
use Nui\Config;

function appPath() {
   return \Nui\Application::getInstance()->container()->get('path.app');
}


if (!function_exists('dd'))
{
    #[NoReturn]
    function dd($var): void
    {
        var_dump($var);
        die();
    }
}


if (!function_exists('dump')) {
    function dump($var): void {
        var_dump($var);
    }
}

function config($cfg) {
    $sp = explode('.', $cfg);
    $file = array_shift($sp);
    $next = Config::load($file);
    foreach ($sp as $it) {
        $next = $next[$it] ?? null;
    }
    return $next;
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
function respFail($message, $data=[], $code=400) {
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



function makeTree($list, $pk = 'id', $pid = 'parent_id', $root = 0, $fun = null): array
{
    $tree = [];
    if (!is_array($list)) {
        return $tree;
    }

    // 创建基于主键的数组引用
    $refer = [];
    foreach ($list as $key => $data) {
        $k = is_object($data) ? $data->$pk : $data[$pk];
        $refer[$k] = [
            'id' => $k,
            'data' => $fun == null ? $data : $fun($data),
            'children' => []
        ];
    }

    $rootItem = [];

    foreach ($list as $key => $data) {
        // 判断是否存在parent
        $parentId = is_object($data) ? $data->$pid : $data[$pid];
        $cId = is_object($data) ? $data->$pk : $data[$pk];

        if ($root == $parentId) {
            $rootItem[] = &$refer[$cId];
        } else {
            if (isset($refer[$parentId])) {
                $parent           = &$refer[$parentId];
                $parent['children'][] = &$refer[$cId];
            }
        }
    }

    return $rootItem;
}

function now() {
    return date('Y-m-d H:i:s');
}