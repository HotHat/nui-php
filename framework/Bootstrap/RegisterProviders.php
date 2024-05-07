<?php

namespace Nui\Bootstrap;

use Nui\Config;

class RegisterProviders
{
    public function bootstrap($container) {
        $providers = Config::get('app.providers');

        foreach ($providers as $provider) {
            $instance = new $provider();
            $instance->register($container);
        }
    }
}