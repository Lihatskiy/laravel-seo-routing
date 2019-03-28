<?php

namespace Lihatskiy\SeoRouting\Middleware;

use Closure;

class GlobalMiddleware
{
    protected $pathInfo;
    protected $queryString;
    protected $args = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->initRequest($request);

        if ($redirect = $this->checkForRedirect()) {
            return $redirect;
        }

        $seoRouter = app('seo.router');

        $realPath = $seoRouter->toRealPath($this->pathInfo);

        if ($this->pathInfo != $realPath) {
            $request->setPathInfo($realPath);
        }

        return $next($request);
    }

    /**
     * @return void
     */
    protected function initRequest($request)
    {
        /*
         * Note: Don't use $request->path()
         * Because it return string trimmed from all slashes
         */
        $this->pathInfo = urldecode($request->getPathInfo());

        /*
         * Note: Don't use $request->getQueryString()
         * Because it return reordered string
         */
        $this->queryString = urldecode($request->server->get('QUERY_STRING'));

        parse_str($this->queryString, $this->args);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    protected function checkForRedirect()
    {
        $path = $this->pathInfo;
        $queryString = $this->queryString;
        $seoRouter = app('seo.router');

        /*
         * Check current routes
         */
        $seoUrl = $seoRouter->toSeoUrl($path);

        if ($seoUrl != $path)
        {
            if ($queryString)
                $seoUrl .= "?{$queryString}";

            // Make full and valid URL to exclude trimming slashes in path
            return redirect(request()->root() . $seoUrl, 301);
        }

        /*
         * Check old routes
         */
        $redirectTo = $seoRouter->searchInOld($path);

        if (false !== $redirectTo)
        {
            $redirectTo = $seoRouter->toSeoUrl($redirectTo);

            if ($redirectTo != $path)
            {
                if ($queryString)
                    $redirectTo .= "?{$queryString}";

                // Make full and valid URL to exclude trimming slashes in path
                return redirect(request()->root() . $redirectTo, 301);
            }
        }

        return null;
    }
}
