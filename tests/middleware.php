<?php declare(strict_types=1);

/*
$middleware = [
    function($request, $next) {
        echo '中间件1', PHP_EOL;
        return $next($request);
    },
    function($request, $next) {
        echo '中间件2', PHP_EOL;
        return $next($request);
    }
];

$result = array_reduce($middleware, function($carry, $item) {
    return function($param) use ($carry, $item) {
        return $item($param, $carry);
    };
}, function($request) {  // 这里是最中间处理action
    echo '处理 ', $request ,PHP_EOL;
    return '返回控制器处理结果';
});

$resp = $result('http 请求');

print_r($resp);

*/

$data = include __DIR__ . '/../config/database.php';
var_dump($data);
