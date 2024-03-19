<?php

namespace App;

class Request
{
    public function getUri() {
        $reqUri = $_SERVER['REQUEST_URI'];
        $parseUri = parse_url($reqUri);
        return $parseUri['path'];
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

}