<?php
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

Route::group(['middleware' => ['web']], function()
{
	Route::get( '/',                                 'Auth\AuthController@index');
	Route::get( 'auth/login',                        'Auth\AuthController@getLogin');
	Route::post('auth/login',                        'Auth\AuthController@postLogin');
	Route::get( 'auth/logout',                       'Auth\AuthController@getLogout');
	Route::get( 'auth/register',                     'Auth\AuthController@getRegister');// ユーザー登録
	Route::post('auth/register',                     'Auth\AuthController@postRegister');
	Route::get( 'auth/resend',                       'Auth\AuthController@getResend');// 確認メール再送信
	Route::post('auth/resend',                       'Auth\AuthController@postResend');
	Route::get( 'auth/resend/{token}',               'Auth\AuthController@getConfirm');// 登録確認メール
	
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
	 * Ajax...
	 */
	Route::post('ajax/chkDuplicateEmail',            'AjaxResponseController@chkDuplicateEmail');
	Route::post('ajax/isModifiedData',               'AjaxResponseController@isModifiedData');
	Route::post('ajax/chkExistsImage',               'AjaxResponseController@chkExistsImage');
	Route::get( 'ajax/test',                         'AjaxResponseController@test');
	
	/**
	 * API...
	 * (API通信時、Tokenを要求されたくない場合は、ミドルウェアのVerifyCsrfTokenクラスにて、Tokenの要求を受けない処理が必要)
	 */
	//Route::post('api/{version}/***',               'ApiResponseController@***');
	Route::get('apis/echo',                          'ApiResponseController@echoTest');
	
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
	Route::get( 'test/output_xml',                   'TestController@outputXML');
	Route::get( 'test/hash_test',                    'TestController@hashTest');
	Route::get( 'test/bukkengaiyou_pdf',             'TestController@importPDF');
	
	Route::get( 'test/javascript/regexp/{number}',   'TestController@javascriptRegExp');  // JavaScript正規表現テスト
	
	/**
	 * ドットインストールテスト
	 */
	Route::get( 'dotinstall/carbon',                 'DotInstallController@testCarbon');
});

