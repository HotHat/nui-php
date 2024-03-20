<?php

namespace Niu;

class Request
{
    public function getUri() {
        $reqUri = $_SERVER['REQUEST_URI'];
        $parseUri = parse_url($reqUri);
        return $parseUri['path'];
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get($key=null) {
        if ($key) {
            return $_GET[$key] ?? '';
        }
        return $_GET;
    }

    public function post($key=null) {
        if ($key) {
            return $_POST[$key] ?? '';
        }
        return $_POST;
    }

    public function header($key, $default='') {
        $exist = $_SERVER[strtoupper('http_'. $key)] ?? null;
        return $exist ?? $default;
    }

    public function bearerToken(): string {
        $header = $this->header('Authorization', '');

        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return '';
    }


}