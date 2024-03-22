<?php

namespace Niu\Exception;

class AuthenticationException extends \Exception
{

    /**
     * Create a new authentication exception.
     *
     * @param string $message
     * @return void
     */
    public function __construct(string $message = 'Unauthenticated.')
    {
        parent::__construct($message);
    }

}
