<?php

namespace Lihatskiy\SeoRouting;

use Illuminate\Routing;
use Lihatskiy\SeoRouting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\UrlGenerator as InterfaceUrlGenerator;

class SeoRoutingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->macros();

        $this->replaceUriValidator();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InterfaceUrlGenerator::class,  UrlGenerator::class);

        $this->app->singleton('seo.router', function ($app) {
            return new SeoRouter();
        });
        $this->app->alias('seo.router', SeoRouter::class);
    }

    protected function macros()
    {
        app('url')->macro('setRouteGenerator', function (Routing\RouteUrlGenerator $routeGenerator) {
            $this->routeGenerator = $routeGenerator;
        });

        app('request')->macro('setPathInfo', function ($pathInfo) {
            $this->pathInfo = $pathInfo;
            return $this;
        });
    }

    protected function replaceUriValidator()
    {
        // Replace UriValidator to deny leading slashes
        foreach (Routing\Route::getValidators() as $key => $validator)
        {
            if ($validator instanceof Routing\Matching\UriValidator)
            {
                Routing\Route::$validators[$key] = new SeoRouting\Matching\UriValidator;
            }
        }
    }
}
