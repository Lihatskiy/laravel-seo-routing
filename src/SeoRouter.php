<?php

namespace Lihatskiy\SeoRouting;

class SeoRouter
{
    protected $mask;
    protected $current = [];
    protected $old = [];

    function __construct()
    {
        if ( ! app()->runningInConsole())
        {
            $this->init();
        }
    }

    protected function init()
    {
        $this->mask = config('seo-routes.mask', '(:any)');

        $seoRoutes = SeoRouteModel::withTrashed()->get();
        $currentDomain = request()->getHost();

        foreach ($seoRoutes as $one)
        {
            if ( ! $one->deleted_at)
            {
                $this->current[$one->domain][$one->real_path] = $one->seo_url;
            }
            elseif ($one->domain == $currentDomain)
            {
                $this->old[$one->seo_url] = $one->real_path;
            }
        }
    }

    /**
     * @param string $requestString
     * @param string|null $domain
     * @return string
     */
    public function toSeoUrl($requestString, $domain = null)
    {
        /* Remove end slashes */
        $searchString = strval($requestString);
        $searchString = rtrim($searchString, '/');

        $routes = [];

        foreach ($this->getRoutes($domain) as $key => $value)
        {
            $key = rtrim($key, '/');
            $routes[$key] = $value;
        }

        /* Search in keys */
        if (false !== array_key_exists($searchString, $routes))
        {
            return $routes[$searchString];
        }

        $searchResult = $this->search($searchString, $routes, true);

        if (false !== $searchResult)
        {
            return $searchResult;
        }

        return $requestString;
    }

    /**
     * @param string $requestString
     * @param string|null $domain
     * @return string
     */
    public function toRealPath($requestString, $domain = null)
    {
        /* Remove end slashes */
        $searchString = strval($requestString);
        $searchString = rtrim($searchString, '/');

        $routes = array_map(
            function($input) { return rtrim($input, '/'); },
            $this->getRoutes($domain));

        /* Search in values */
        $exactMatch = array_search($searchString, $routes);

        if (false !== $exactMatch)
        {
            return $exactMatch;
        }

        $searchResult = $this->search($searchString, $routes, false);

        if (false !== $searchResult)
        {
            return $searchResult;
        }

        return $requestString;
    }

    /**
     * @param  string $requestString
     * @return bool|mixed
     */
    public function searchInOld($requestString)
    {
        /* Remove end slashes */
        $searchString = strval($requestString);
        $searchString = rtrim($searchString, '/');

        $routes = [];

        foreach ($this->getOldRoutes() as $key => $value)
        {
            $key = rtrim($key, '/');
            $routes[$key] = $value;
        }

        /* Search in keys */
        if (false !== array_key_exists($searchString, $routes))
        {
            return $routes[$searchString];
        }

        return $this->search($searchString, $routes, true);
    }

    /**
     * @param string $searchString
     * @param array $routes
     * @param bool $searchInKeys
     * @return bool|mixed
     */
    protected function search($searchString, array $routes, $searchInKeys)
    {
        foreach ($routes as $key => $value)
        {
            $strToCheck = $searchInKeys ? $key : $value;
            $strToReplace = $searchInKeys ? $value : $key;

            /* If (:any) not found or found more than once */
            if (strpos($key, $this->mask) === false
                || substr_count ($key, $this->mask) > 1
                || substr_count ($value, $this->mask) > 1)
            {
                continue;
            }

            $parts = explode($this->mask, $strToCheck);

            $start = strlen($parts[0]);
            $lenght = 0 - strlen($parts[1]);

            if ($lenght < 0)
            {
                $maskedSubstr = substr($searchString, $start, $lenght);
            }
            else
            {
                $maskedSubstr = substr($searchString, $start);
            }

            /* If found match, return it */
            if ($parts[0] . $maskedSubstr . $parts[1] === $searchString)
            {
                return str_replace($this->mask, $maskedSubstr, $strToReplace);
            }
        }

        return false;
    }

    /**
     * @param string|null $domain
     * @return array
     */
    public function getRoutes($domain = null)
    {
        if ( ! $domain)
        {
            $domain = request()->getHost();
        }

        $routes = [];

        if (array_key_exists($domain, $this->current))
        {
            $routes = $this->current[$domain];
        }

        return array_merge($routes, config('seo-routes.current', []));
    }

    /**
     * @return array
     */
    public function getOldRoutes()
    {
        return array_merge($this->old, config('seo-routes.old', []));
    }
}