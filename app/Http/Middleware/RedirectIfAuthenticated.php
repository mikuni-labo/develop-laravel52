<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::guard($guard)->check() && $this->checkStatus($guard)) {
			return $next($request);
		}
		
		return redirect('/auth/login');
	}
	
	/**
	 * リクエスト毎ステータスチェック
	 * 条件追加時はここで
	 *
	 * @param unknown $guard
	 * @return bool
	 */
	public function checkStatus($guard)
	{
		if(Auth::guard($guard)->user()->status != '1')
		{
			Auth::guard($guard)->logout();// ログアウト
			\Flash::error('ステータスが無効です。');
			return false;
		}
		
		return true;
	}
}
