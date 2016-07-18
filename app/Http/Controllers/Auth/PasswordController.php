<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;
	
	/** リダイレクト先 */
	protected $redirectTo = '/auth/login';

	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest:user', ['except' => ['login', 'auth.reminder']]);
		
		/**
		 * パスワードリマインダーの対象テーブルの切り分けをコンフィグで上手く記述する方法が不明なので、
		 * 暫定措置として直接セットする
		 */
		\Config::set('auth.defaults.passwords','user');
	}
	
	/**
	 * Get the password reset validation rules.
	 *
	 * @return array
	 */
	protected function getResetValidationRules()
	{
		return [
				'token'    => 'required',
				'email'    => 'required|email',
				'password' => 'required|confirmed|min:8|max:16',
		];
	}
}
