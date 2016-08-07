<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Ajax Tests
// Route::get("/ajax/{testcode}", function(){
	
// 	return Response::json($_POST);
// });
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

	Route::get( '/',                                 'Auth\AuthController@index');
	Route::get( 'auth/login',                        'Auth\AuthController@getLogin');
	Route::post('auth/login',                        'Auth\AuthController@postLogin');
	Route::get( 'auth/logout',                       'Auth\AuthController@getLogout');
	// ユーザー登録
	Route::get( 'auth/register',                     'Auth\AuthController@getRegister');
	Route::post('auth/register',                     'Auth\AuthController@postRegister');
	// 確認メール再送信
	Route::get( 'auth/resend',                       'Auth\AuthController@getResend');
	Route::post('auth/resend',                       'Auth\AuthController@postResend');
	// 登録確認メール
	Route::get( 'auth/resend/{token}',               'Auth\AuthController@getConfirm');
	
	/**
	 * パスワードリマインダー関連
	 */
	Route::get( 'auth/password/email',               'Auth\PasswordController@getEmail');
	Route::post('auth/password/email',               'Auth\PasswordController@postEmail');
	Route::get( 'auth/password/reset/{token}',       'Auth\PasswordController@getReset');
	Route::post('auth/password/reset',               'Auth\PasswordController@postReset');
	
	/**
	 * ユーザ管理
	 */
	Route::get( 'user',                              'UserController@index');
	Route::post('user',                              'UserController@postIndex');
	Route::get( 'user/add',                          'UserController@add');
	Route::post('user/add',                          'UserController@modify');
	Route::get( 'user/edit/{id}',                    'UserController@edit');
	Route::post('user/edit/{id}',                    'UserController@modify');
	Route::get( 'user/delete/{id}',                  'UserController@delete');
	Route::get( 'user/restore/{id}',                 'UserController@restore');
	Route::get( 'user/search',                       'UserController@search');
	Route::post('user/search',                       'UserController@postIndex');
	Route::get( 'user/search/reset',                 'UserController@resetSearch');
	
	/**
	 * テストユーザ管理
	 */
	Route::get( 'test_user',                         'TestUserController@index');
	Route::post('test_user',                         'TestUserController@postIndex');
	Route::get( 'test_user/add',                     'TestUserController@add');
	Route::post('test_user/add',                     'TestUserController@modify');
	Route::get( 'test_user/edit/{id}',               'TestUserController@edit');
	Route::post('test_user/edit/{id}',               'TestUserController@modify');
	Route::get( 'test_user/delete/{id}',             'TestUserController@delete');
	Route::get( 'test_user/restore/{id}',            'TestUserController@restore');
	Route::get( 'test_user/search',                  'TestUserController@search');
	Route::post('test_user/search',                  'TestUserController@postIndex');
	Route::get( 'test_user/search/reset',            'TestUserController@resetSearch');
	
	
	/**
	 * Ajax...
	 */
	Route::post('ajax/chkDuplicateEmail',            'AjaxResponseController@chkDuplicateEmail');
	Route::post('ajax/isModifiedData',               'AjaxResponseController@isModifiedData');
	Route::post('ajax/chkExistsImage',               'AjaxResponseController@chkExistsImage');
	
	// test
	Route::get( 'ajax/test',                         'AjaxResponseController@test');
	
	/**
	 * API...
	 * (API通信時、Tokenを要求されたくない場合は、ミドルウェアのVerifyCsrfTokenクラスにて、Tokenの要求を受けない処理が必要)
	 */
	//Route::post('api/{version}/***',               'ApiResponseController@***');
	
	/**
	 * Cron Test...
	 */
	Route::get('cron/sendMailTest',                  'TestController@sendMailTest');
	
	/**
	 * Test
	 */
	Route::get( 'phpinfo',                           'TestController@phpinfo');
	Route::get( 'test',                              'TestController@getTest');
	Route::post('test',                              'TestController@postTest');
	Route::get( 'test/testmail',                     'TestController@sendTestMail');
	Route::get( 'test/get_json',                     'TestController@getJSON');
	Route::get( 'test/show_json',                    'TestController@showJSON');
	Route::get( 'test/output_xml',                   'TestController@outputXML');   // XML出力テスト
	
	Route::get( 'test/regexp/{number}',              'TestController@testRegExp');  // JavaScript正規表現テスト
	
	/**
	 * ドットインストールテスト
	 */
	Route::get( 'dotinstall/carbon',                 'DotInstallController@testCarbon');
});

