<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StripEmptyParamsFromQueryString
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the current query and the number of query parameters.
        $query = request()->query();
        $queryCount = count($query);

        // Strip empty query parameters.
        foreach ($query as $param => $value) {
            if (!isset($value) || $value == '') {
                unset($query[$param]);
            }
        }

        // If there were empty query parameters, redirect to a new url with the
        // non empty query parameters. Otherwise keep going with the current
        // request.
        if ($queryCount > count($query)) {
            return redirect()->route($request->route()->getName(), $query);
        }

        return $next($request);
    }
}
