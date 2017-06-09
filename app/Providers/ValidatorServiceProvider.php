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
        \Validator::extend('true',              'App\Http\Validator\CustomValidator@validateTrue');
        \Validator::extend('false',             'App\Http\Validator\CustomValidator@validateFalse');
        \Validator::extend('role_name',         'App\Http\Validator\CustomValidator@validateRoleName');
        \Validator::extend('input_status',      'App\Http\Validator\CustomValidator@validateInputStatus');
        \Validator::extend('jp_zip_code',       'App\Http\Validator\CustomValidator@validateJpZipCode');
        \Validator::extend('card_number',       'App\Http\Validator\CustomValidator@validateCardNumber');
        \Validator::extend('card_holder_name',  'App\Http\Validator\CustomValidator@validateCardHolderName');
        \Validator::extend('security_code',     'App\Http\Validator\CustomValidator@validateSecurityCode');
        \Validator::extend('card_expire',       'App\Http\Validator\CustomValidator@validateCardExpire');
        \Validator::extend('account_name_kana', 'App\Http\Validator\CustomValidator@validateAccountNameKana');
        \Validator::extend('only_zenkaku',      'App\Http\Validator\CustomValidator@validateOnlyZenkaku');
        \Validator::extend('mime_images',       'App\Http\Validator\CustomValidator@validateImages');
        \Validator::extend('mime_csv',          'App\Http\Validator\CustomValidator@validateCSV');
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
