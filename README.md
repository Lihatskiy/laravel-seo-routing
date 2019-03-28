## Service Provider

'providers' => [

    Lihatskiy\SeoRouting\SeoRoutingServiceProvider::class,
    
]

## Middlewares

protected $middleware = [

    \Lihatskiy\SeoRouting\Middleware\GlobalMiddleware::class,        
];

protected $middlewareGroups = [

    'web' => [
        \Lihatskiy\SeoRouting\Middleware\RouteMiddleware::class,
    ],
];