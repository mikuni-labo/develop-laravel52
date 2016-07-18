<?php

namespace App\Providers;

use View; // Illuminate\Contracts\View\Factory
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
	/**
	 * Register bindings in the container.
	 *
	 * return void
	 */
	public function boot()
	{
		// クラスベースのcomposer
		View::composer('*', 'App\Http\ViewComposers\FixedComposer');
		//View::composer('view名', '使用するViewComposerへのパス');
		
		// クロージャーベースのcomposer
		View::composer('dashboard', function($view)
		{

		});
	}

	/**
	 * Register
	 */
	public function register() 
	{
	}
}
