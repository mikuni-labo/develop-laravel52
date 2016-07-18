<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Add Validation Rules...
		\Validator::extend('true',              'App\Lib\CustomValidator@validateTrue');
		\Validator::extend('false',             'App\Lib\CustomValidator@validateFalse');
		\Validator::extend('role_name',         'App\Lib\CustomValidator@validateRoleName');
		\Validator::extend('input_status',      'App\Lib\CustomValidator@validateInputStatus');
		\Validator::extend('jp_zip_code',       'App\Lib\CustomValidator@validateJpZipCode');
		\Validator::extend('card_number',       'App\Lib\CustomValidator@validateCardNumber');
		\Validator::extend('card_holder_name',  'App\Lib\CustomValidator@validateCardHolderName');
		\Validator::extend('security_code',     'App\Lib\CustomValidator@validateSecurityCode');
		\Validator::extend('card_expire',       'App\Lib\CustomValidator@validateCardExpire');
		\Validator::extend('account_name_kana', 'App\Lib\CustomValidator@validateAccountNameKana');
		\Validator::extend('only_zenkaku',      'App\Lib\CustomValidator@validateOnlyZenkaku');
		\Validator::extend('mime_images',       'App\Lib\CustomValidator@validateImages');
		\Validator::extend('mime_csv',          'App\Lib\CustomValidator@validateCSV');
	}
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
