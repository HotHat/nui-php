<?php

namespace Nui\Bootstrap;

class HandleExceptions
{
    public function bootstrap($container) {
        set_error_handler(function ($errno, $errStr, $errFile, $errLine) {
            if (!(error_reporting() & $errno)) {
                return false;
            }

            $errStr = htmlspecialchars($errStr);
            throw new \ErrorException($errStr, $errno, 1, $errFile, $errLine);
        });

        if ($container->has('http.exception')) {
            $handler = $container->get('http.exception');
            set_exception_handler(function (\Throwable $exp) use ($handler) {
                $handler->handle($exp);
            });
        }
    }

}