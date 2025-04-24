<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckIsPendingMember {
    public function handle(Request $request, Closure $next) {
        if (!Auth::user()->isPendingMember()) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to this page.');
        }

        return $next($request);
    }
}
