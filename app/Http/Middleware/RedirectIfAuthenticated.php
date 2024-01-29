<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, string ...$guards): Response
    // {
    //     $guards = empty($guards) ? [null] : $guards;

    //     foreach ($guards as $guard) {
    //         if (Auth::guard($guard)->check()) {
    //             // return redirect(RouteServiceProvider::HOME);
    //             return redirect('/login');
    //         }
    //     }

    //     return $next($request);
    // }

    // public function handle(Request $request, Closure $next, string ...$guards): Response
    // {
    //     $guards = empty($guards) ? [null] : $guards;

    //     foreach ($guards as $guard) {
    //         if (Auth::guard($guard)->check()) {
    //             // ログインしている場合はそのまま次のミドルウェアへ進む
    //             return $next($request);
    //         }
    //     }

    //     // ログインしていない場合はログイン画面へリダイレクト
    //     return redirect('/login');
    // }
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // ログインしている場合は `/register` へのアクセス時にはリダイレクトしない
                if ($request->is('register')) {
                    return $next($request);
                }
                // ログインしている場合は `/login` へのアクセス時にはリダイレクトしない
                if ($request->is('login')) {
                    return $next($request);
                }

                return redirect('/login');
            }
        }

        return $next($request);
    }

}