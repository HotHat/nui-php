<?php

namespace app\Exception;

class Handler
{
    public function handle(\Throwable $exception): void
    {
        file_put_contents(
            __DIR__ . '/../Storage/error.log',
            sprintf("%s: %s\n", date('Y-m-d H:i:s'), $exception->__toString()),
            FILE_APPEND
        );
    }
}