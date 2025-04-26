<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Masmerise\Toaster\Toaster;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $types): Response
    {
        $allowedTypes = explode('|', $types);

        if (!auth()->check() || !in_array(auth()->user()->type, $allowedTypes, true)) {
            Toaster::error('You do not have permission to access this page.');
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }

}
