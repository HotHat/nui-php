<?php
namespace Niu;

class Router
{
    private array $middlewareStack = [];
    private array $routes = [];
    private array $prefixStack = [];

    public function get($uri, $action): static
    {
        $this->makeRoute('GET', $uri, $action);
        return $this;
    }

    public function post($uri, $action): static
    {
        $this->makeRoute('POST', $uri, $action);
        return $this;
    }

    protected function makeRoute($method, $uri, $action) {
        $pfx = implode('/', $this->prefixStack);
        $pfx = $pfx ? '/' . trim($pfx, '/') : '';

        $this->routes[] = [
            'method' => $method,
            'middleware' => $this->middlewareStack,
            'uri' => $pfx . $uri,
            'action' => $action
        ];
    }
    public function name($name): static
    {
        if ($this->routes) {
            $len = count($this->routes) - 1;
            $this->routes[$len]['name'] = $name;
        }
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function group($config, $fun) {

        $middlewares = $config['middleware'] ?? [];
        foreach ($middlewares as $it) {
            $this->middlewareStack[] = $it;
        }
        if (array_key_exists('prefix', $config)) {
            $this->prefixStack[] = $config['prefix'];
        }

        $fun($this);

        foreach ($middlewares as $it) {
            array_pop($this->middlewareStack);
        }

        if (array_key_exists('prefix', $config)) {
            array_pop($this->prefixStack);
        }
    }
}