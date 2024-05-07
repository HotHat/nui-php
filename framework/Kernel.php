<?php
namespace Nui;

class Kernel
{
    protected $app;
    protected array $middleware = [];
    protected array $middlewareGroup = [];
    protected array $routeMiddleware = [];

    protected array $bootstraps = [
        \Nui\Bootstrap\HandleExceptions::class,
        \Nui\Bootstrap\RegisterFacades::class,
        \Nui\Bootstrap\RegisterProviders::class,
        \Nui\Bootstrap\BootProviders::class,
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootstrap(): void
    {
        foreach ($this->bootstraps as $bootstrap) {
            $instance = new $bootstrap;
            $instance->bootstrap($this->app->container());
        }
    }

    protected function sendRequestThroughRouter($request)
    {
        $this->app->bind('request', $request);

        // Facade::clearResolvedInstance('request');

        $this->bootstrap();

        return (new Pipeline($this->app->container()))
            ->send($request)
            ->through($this->app->shouldSkipMiddleware() ? [] :
                [
                    'must' => $this->middleware,
                    'group' => $this->middlewareGroup,
                    'route' => $this->routeMiddleware
                ]
            )
            ->then();
    }

    public function handle($request) {
        return $this->sendRequestThroughRouter($request);
    }

}