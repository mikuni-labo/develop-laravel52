<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserServiceInterface;

class RepositoryController extends Controller
{
	private $UserServiceInterface;
	
	/**
	 * コンストラクタ
	 */
	public function __construct(UserServiceInterface $UserServiceInterface)
	{
		$this->UserServiceInterface = $UserServiceInterface;
	}
	
	/**
	 * GET Test
	 * 
	 * @method GET
	 */
	public function index()
	{
		dd( $this->UserServiceInterface );
		
		dd( $this->UserServiceInterface->getList() );
		
		dd( $this->UserServiceInterface->get(1) );
		
		dd( $this->UserServiceInterface->delete(2) );
		
		dd( $this->UserServiceInterface->restore(2) );
		
		dd( $this->UserServiceInterface->createEntity() );
	}
	
}
