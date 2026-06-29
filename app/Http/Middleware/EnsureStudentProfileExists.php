<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentProfileExists
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !$request->routeIs('student.profile')) {
            if (!auth()->user()->studentProfile) {
                return redirect()->route('student.profile')->with('warning', 'Please complete your profile first.');
            }
        }

        return $next($request);
    }
}
