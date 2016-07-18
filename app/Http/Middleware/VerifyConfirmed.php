<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class VerifyConfirmed
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$user = User::where('email', '=', $request->input('email'))->first();
		if ($user) {
			if(! $user->isConfirmed()) {
				\Flash::error('ユーザー登録認証が完了していません。<br />ユーザー登録確認メールに従って、アカウントを有効化してください。');
				return redirect()->back()->withInput($request->only('email'));
			}
		}
		
		return $next($request);
	}
}
