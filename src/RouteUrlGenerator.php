<?php

namespace Lihatskiy\SeoRouting;

use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Contracts\Routing\UrlGenerator as InterfaceUrlGenerator;

class RouteUrlGenerator extends \Illuminate\Routing\RouteUrlGenerator
{
    /**
     * Generate a URL for the given route.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string
     *
     * @throws \Illuminate\Routing\Exceptions\UrlGenerationException
     */
    public function to($route, $parameters = [], $absolute = false)
    {
        $this->url = app(InterfaceUrlGenerator::class);

        return parent::to($route, $parameters, $absolute);
    }
}