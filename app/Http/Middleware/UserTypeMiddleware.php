<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTypeMiddleware
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->type, $types)) {
                return $next($request);
            }
        }

        return redirect('/');
    }
}