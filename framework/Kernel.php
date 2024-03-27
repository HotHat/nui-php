<?php
namespace Niu;

class Kernel
{
    protected $app;
    protected array $middleware = [];
    protected array $middlewareGroup = [];
    protected array $routeMiddleware = [];

    protected $bootstrappers = [
        \Niu\Bootstrap\HandleExceptions::class,
        \Niu\Bootstrap\RegisterFacades::class,
        \Niu\Bootstrap\RegisterProviders::class,
        \Niu\Bootstrap\BootProviders::class,
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootstrap() {
        foreach ($this->bootstrappers as $bootstrap) {
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