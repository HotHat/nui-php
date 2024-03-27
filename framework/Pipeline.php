<?php

namespace Niu;

class Pipeline
{
    private $container;
    private $passable;
    private $pipes;
    private $middleware;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function send($request) {
        $this->passable = $request;
        return $this;
    }

    public function through($middlewares) {
        $this->middleware = $middlewares;
        return $this;
    }

    public function pipe($action, $middlewares) {
        return array_reduce($middlewares, function($carry, $middleware) {
            $next = new $middleware();
            return function($req) use ($carry, $next) {
                return $next->handle($req, $carry);
            };
        }, $this->onionCore($action));
    }

    public function then() {
        $uri = $this->passable->getUri();
        $router = $this->container->get('app.router');

        $matchGroup = [];
        foreach ($router->getRoutes() as $route) {
            $pattern = $this->getUrlPattern($route['uri']);

            preg_match($pattern, $uri, $match);
            if ($match) {
                array_shift($match);
                $route['bind_params'] = $match;
                $matchGroup[] = $route;
            }
            unset($match);
        }

        if (!$matchGroup) {
            return new Response(404);
        }

        $match = null;
        $method = $this->passable->getMethod();

        foreach ($matchGroup as $it) {
            if ($method == $it['method']) {
                $match = $it;
            }
        }

        if (!$match) {
            return new Response(400, [], 'request method not support!');
        }

        $mds = $this->middleware['must'];

        foreach ($match['middleware'] as $md) {
            $g = $this->middleware['group'][$md] ?? null;
            if ($g) {
                $mds = array_merge($mds, $g);
            } else {
                $g = $this->middleware['route'][$md] ?? null;
                if ($g) {
                    $mds[] = $g;
                }
            }
        }

        $action = $this->makeAction($match['action'], $match['bind_params']);
        $dispatch = $this->pipe($action, array_reverse($mds));

        return $dispatch($this->passable);
    }

    private function onionCore($action): \Closure
    {
        return function ($req) use ($action) : Response {
            ob_start();
            $resp = $action($req);

            $echo = ob_get_contents();
            ob_end_clean();
            if (is_string($resp) || is_null($resp)) {
                return new Response(200, [], $echo . $resp);
            } else if ($resp instanceof Response) {
                return new Response($resp->getStatusCode(), $resp->getHeaders(), $echo . $resp->rawBody());
            }

            return new Response(200, $resp->getHeaders(), $echo);
        };
    }

    protected function makeAction($callArray, $params): \Closure
    {
        [$class, $method] = $callArray;
        return function ($request) use ($class, $method, $params) {
            $instance = new $class;
            return $instance->{$method}($request, ...$params);
        };
    }

    private function getUrlPattern($urlPattern) {
        preg_match_all('/:\w+/', $urlPattern, $match);
        if ($match) {
            $pattern = array_map(function ($it) {return '/' . $it . '/';}, $match[0]);
            $replace = array_map(function ($it) {return '(\w+)';}, $match[0]);
            $pregUrl = preg_replace($pattern, $replace, $urlPattern);
            $pt = '/^' . str_replace('/', '\\/', $pregUrl) . '$/';
            return $pt;
        } else {
            return $urlPattern;
        }
    }
}