<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolProfileExists
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't redirect on dashboard or profile pages
        if ($request->routeIs('school.dashboard') || $request->routeIs('school.profile')) {
            return $next($request);
        }

        if (auth()->check() && !auth()->user()->school) {
            return redirect()->route('school.profile')->with('warning', 'Please complete your school profile first.');
        }

        return $next($request);
    }
}
