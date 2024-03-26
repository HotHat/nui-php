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

    public function get($key=null, $default=null) {
        if ($key) {
            return $_GET[$key] ?? $default;
        }
        return $_GET;
    }

    public function post($key=null, $default=null) {
        if ($this->header('content_type') == 'application/json') {
            $data = json_decode(file_get_contents('php://input'),true);
        } else {
            $data = $_POST;
        }
        if ($key) {
            return $data[$key] ?? $default;
        }
        return $data;
    }

    public function header($key, $default='') {
        $exist = $_SERVER[strtoupper('http_'. $key)] ?? null;
        return $exist ?? $_SERVER[strtoupper($key)] ?? $default;
    }

    public function bearerToken(): string {
        $header = $this->header('Authorization', '');

        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return '';
    }


}