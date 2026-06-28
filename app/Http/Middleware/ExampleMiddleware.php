<?php

namespace App\Http\Middleware;

use Closure;

class ExampleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \MacropaySolutions\Kernel\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle($request, Closure $next): mixed
    {
        return $next($request);
    }
}
