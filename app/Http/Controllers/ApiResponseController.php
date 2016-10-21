<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Api Response...
 * 
 * @author Kuniyasu_Wada
 */
class ApiResponseController extends Controller
{
	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		$this->middleware('web');
	}
	
	/**
	 * Test...
	 */
	public function test()
	{
		dd('test');
	}
	
	/**
	 * phpunit
	 */
	public function echoTest()
	{
		$res['status'] = "OK";
		$res['message'] = "No problem";
		return \Response::json($res);
	}
	
}
