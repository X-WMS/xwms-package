<?php

namespace LaravelShared\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use LaravelShared\Core\Controllers\LangController;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        (new LangController)->checkLocale($request);
        
        return $next($request);
    }
}
