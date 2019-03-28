<?php

namespace Lihatskiy\SeoRouting\Middleware;

use Closure;
use Lihatskiy\SeoRouting;

class RouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = app('url');

        $url->setRouteGenerator(
            new SeoRouting\RouteUrlGenerator($url, $url->getRequest())
        );

        return $next($request);
    }
}
