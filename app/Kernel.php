<?php
namespace App;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\SessionStart;
use Throwable;

class Kernel
{
    protected array $middleware = [
        SessionStart::class
    ];
    protected array $middlewareGroup = [
        'admin' => [
            Authenticate::class
        ],
        'api' => [
        ]
    ];
    protected array $routeMiddleware = [

    ];

    protected array $routes = [];

    public function __construct()
    {
        $this->registerExceptionHandler();

        //
        $this->routeProvider();
    }

    protected function registerExceptionHandler(): void {
        set_error_handler(function ($errno, $errStr, $errFile, $errLine) {
            if (!(error_reporting() & $errno)) {
                return false;
            }
            $errStr = htmlspecialchars($errStr);

            throw new \ErrorException($errStr, $errno, 1, $errFile, $errLine);
        });

        set_exception_handler(function (Throwable $exp) {
            file_put_contents(
                __DIR__ . '/../Storage/error.log',
                sprintf("%s: %s\n", date('Y-m-d H:i:s'), $exp->__toString()),
                FILE_APPEND
            );
        });
    }

    protected function routeDispatch($action): \Closure
    {
        return function ($req) use ($action) : Response {

            $resp = $action($req);

            $echo = ob_get_contents();
            ob_end_clean();
            if (is_string($resp)) {
                return new Response(200, [], $echo . $resp);
            } else if ($resp instanceof Response) {
                return new Response($resp->getStatusCode(), $resp->getHeaders(), $echo . $resp->rawBody());
            }

            return new Response(200, $resp->getHeaders(), $echo);
        };
    }

    protected function pipe($action, $middlewares) {
        return array_reduce($middlewares, function($carry, $middleware) {
            $next = new $middleware();
            return function($req) use ($carry, $next) {
                return $next->handle($req, $carry);
            };
        }, $this->routeDispatch($action));
    }

    protected function makeAction($callArray): \Closure
    {
        [$class, $method] = $callArray;
        return function ($request) use ($class, $method) {
            $instance = new $class;
            return $instance->{$method}($request);
        };
    }

    protected function routeGroup($middleware, $routes): array {
        return array_map(function ($key, $action) use ($middleware) {
            return [
                'middleware' => $middleware,
                'uri' => $key,
                'action' => $this->makeAction($action)
            ];
        }, array_keys($routes), $routes);
    }
    public function routeProvider() {

    }

    public function handle(Request $request): Response | string {
        $uri = $request->getUri();
        $match = null;
        foreach ($this->routes as $route) {
            if ($uri === $route['uri']) {
                $match = $route;
            }
        }

        if (!$match) {
            return new Response(404);
        }

        $method = $request->getMethod();
        if ($method !== $match['method']) {
            return new Response(400, [], 'request method not support!');
        }

        $mds = $this->middleware;

        foreach ($match['middleware'] as $md) {
            $g = $this->middlewareGroup[$md] ?? null;
            if ($g) {
                $mds = array_merge($mds, $g);
            } else {
                $g = $this->middleware[$md] ?? null;
                if ($g) {
                    $mds[] = $g;
                }
            }
        }

        $action = $this->makeAction($match['action']);
        $dispatch = $this->pipe($action, array_reverse($mds));

        return $dispatch($request);
    }

}