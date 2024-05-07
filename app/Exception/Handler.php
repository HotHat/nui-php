<?php

namespace app\Exception;

use Nui\Exception\AuthenticationException;

class Handler
{
    public function handle(\Throwable $exception): void
    {
        if ($exception instanceof AuthenticationException) {
            die(respFail($exception->getMessage(), [], 401));
        } else {
            echo respFail($exception->getMessage());
        }

        file_put_contents(
            appPath() . '/../storage/error.log',
            sprintf("%s: %s\n", date('Y-m-d H:i:s'), $exception->__toString()),
            FILE_APPEND
        );
    }
}