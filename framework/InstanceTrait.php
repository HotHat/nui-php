<?php
/**
 * User: lyhux
 * Date: 2020/7/13
 * Time: 2:19 PM
 */

namespace Nui;

trait InstanceTrait
{
    private static $instance;

    protected function __construct(...$params)
    {
    }

    private function __clone()
    {
    }

    /**
     * @return static
     */
    public static function instance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}