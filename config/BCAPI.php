<?php

return [
    
    /**
     * ブライトコーブAPI 接続情報
     */
    
    'BRIGHTCOVE_API' => [
            'CMS_URL'       => env('BC_CMS_URL'),
            'CMS_PROXY'     => env('BC_CMS_PROXY'),
            
            'DI_URL'        => env('BC_DI_URL'),
            'DI_PROXY'      => env('BC_DI_PROXY'),
            'DI_PROFILE'      => env('BC_DI_PROFILE'),
    ],
    
    /**
     * VIDEOCLOUD アカウント情報
     */
    'VIDEOCLOUD' => [
            'ACCOUNT_ID'    => env('BC_ACCOUNT_ID'),
            'CLIENT_ID'     => env('BC_CLIENT_ID'),
            'CLIENT_SECRET' => env('BC_CLIENT_SECRET'),
    ],

];