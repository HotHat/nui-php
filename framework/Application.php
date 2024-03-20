<?php

namespace Niu;

class Application
{
    private static $instance;
    private string $root;
    private $container;

    public function __construct($root)
    {
        $this->root = $root;
        $this->container = new Container();

        $this->bindBasePath();

        self::$instance = $this;
    }

    public function getInstance(): Application|static
    {
        return self::$instance;
    }

    public function container(): Container
    {
        return $this->container;
    }

    public function make($abstract) {
        return $this->container[$abstract];
    }

    public function bindBasePath(): void {
        $this->container['path.app'] = $this->root . '/app';
        $this->container['path.config'] = $this->root . '/config';
    }

}