<?php

namespace App\Http\Middleware;

use App\Request;
use Closure;

class SessionStart
{
    public function handle(Request $request, Closure $next)
    {
        // session_start();
        return $next($request);
        // 模板环境变量
        // TemplateRender::add('request_uri', $request->uri());
        //
        // $resp = $next($request);
        //
        // // print_r($request->session());
        // $session = $request->session();
        //
        // // 新生成的session ID
        // if ($request->cookie(\PhpMan\Http\Session::$name, '') != $session->getId()) {
        //     $cookie = $session->getCookieParams();
        //     $resp->cookie(\PhpMan\Http\Session::$name, $session->getId(),
        //         // $cookie['lifetime'],
        //         1440,
        //         $cookie['path'],
        //         $cookie['domain'],
        //         $cookie['secure'],
        //         $cookie['httponly'],
        //         $cookie['samesite'],
        //     );
        // }
        // return $resp;
    }

}