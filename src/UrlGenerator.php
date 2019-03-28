<?php

namespace Lihatskiy\SeoRouting;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    /**
     * Format the given URL segments into a single URL.
     *
     * @param  string  $root
     * @param  string  $path
     * @return string
     * @throws \Exception
     */
    public function format($root, $path)
    {
        $path = '/'.trim($path, '/');

        // To SEO URL
        $path = app('seo.router')->toSeoUrl($path);

        if ($this->formatHostUsing) {
            $root = call_user_func($this->formatHostUsing, $root);
        }

        if ($this->formatPathUsing) {
            $path = call_user_func($this->formatPathUsing, $path);
        }

        return $root.$path;
    }
}