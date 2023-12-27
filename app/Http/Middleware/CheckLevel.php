<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, Int $requiredLevel): Response
    {
        if (!auth()->check() || !auth()->user()->hasLevel($requiredLevel)) {
            abort(404, 'Not Found');
        }
        return $next($request);
    }
}
