<?php

namespace Niu;

class Application
{
    private static $instance;
    private string $root;
    private Container $container;

    public function __construct($root)
    {
        $this->root = $root;
        $this->container = new Container();

        //
        $this->loadFiles();

        // first of all
        $this->bindBasePath();
        //
        // Config::setApplication($this);

        // $this->registerProvider();

        self::$instance = $this;
    }

    public static function getInstance(): Application|static
    {
        if (!self::$instance) {
            throw new \Exception('need initialize Application::class first');
        }
        return self::$instance;
    }

    public function singleton($abstract, \Closure $create) {
        $this->container->offsetSet($abstract, $this->container->factory($create));
    }

    public function bind($abstract, $create) {
        $this->container->offsetSet($abstract, $create);
    }

    public function container(): Container
    {
        return $this->container;
    }

    public function make($abstract) {
        return $this->container->get($abstract);
    }

    public function bindBasePath(): void {
        $this->container['path.base'] = $this->root;
        $this->container['path.app'] = $this->root . '/app';
        $this->container['path.config'] = $this->root . '/config';
    }



    protected function loadFiles() {
        $files = [
            '/Support/helpers.php',
        ];
        foreach ($files as $file) {
            require __DIR__ . $file;
        }
    }
}