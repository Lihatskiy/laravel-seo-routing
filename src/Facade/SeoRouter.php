<?php

namespace Lihatskiy\SeoRouting\Facade;

use Lihatskiy\SeoRouting;
use Illuminate\Support\Facades\Facade;

class SeoRouter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SeoRouting\SeoRouter::class;
    }
}