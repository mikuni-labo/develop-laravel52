<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;

class RepositoryProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// インターフェースとリポジトリの連結
		$this->app->bind(UserRepositoryInterface::class, UserRepository::class);
	}
}