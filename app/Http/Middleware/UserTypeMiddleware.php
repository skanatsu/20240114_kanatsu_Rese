<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTypeMiddleware
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        // ユーザーがログインしているかどうかを確認
        if (Auth::check()) {
            // ユーザーが指定されたタイプのいずれかに属しているかどうかを確認
            if (in_array(Auth::user()->type, $types)) {
                return $next($request);
            }
        }

        // 権限がない場合はトップページにリダイレクト
        return redirect('/');
    }
}