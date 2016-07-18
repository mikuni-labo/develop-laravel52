<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class UserRoleMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  $level
	 * @return mixed
	 */
	public function handle($request, Closure $next, $roles = [])
	{
		$role = \Auth::guard('user')->user()->role;
		$arrRole = explode('|', $roles);
		
		// ロールグループ制御
		if( !in_array($role, $arrRole) )
			return redirect('/');
		
		// (ロールレベル別)
		//if ((int)$role > (int)$level ) {
		//	\Flash::error('権限がありません');
		//	return redirect('/');
		//}
		
		return $next($request);
	}
}
