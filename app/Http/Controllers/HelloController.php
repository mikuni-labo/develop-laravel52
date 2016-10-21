<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HelloController extends Controller
{
	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		$this->middleware('guest:user', ['except' => []]);
	}
	
	// getでhello/にアクセスされた場合
	public function index()
	{
		dd('index');
	}
	
	// getでhello/createにアクセスされた場合
	public function create()
	{
		dd('create');
	}
	
	// postでhello/にアクセスされた場合
	public function store()
	{
		dd('store');
	}
	
	// getでhello/idにアクセスされた場合
	public function show($id)
	{
		dd('show');
	}
	
	// getでhello/id/editにアクセスされた場合
	public function edit($id)
	{
		dd($id);
	}
	
	// putまたはpatchでhello/idにアクセスされた場合
	public function update($id)
	{
		dd('update');
	}
	
	// deleteでhello/idにアクセスされた場合
	public function destroy($id)
	{
		dd('destroy');
	}
}
