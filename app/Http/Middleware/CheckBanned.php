<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_banned) {
            // Allow access to banned route and logout
            if ($request->routeIs('banned') || $request->routeIs('logout')) {
                return $next($request);
            }
            
            return redirect()->route('banned');
        }

        return $next($request);
    }
}
