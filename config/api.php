<?php

return [

    /**
     * VIDEOCLOUD
     */
    'videocloud' => [
        'account_id'    => env('VIDEOCLOUD_ACCOUNT_ID'),
        'client_id'     => env('VIDEOCLOUD_CLIENT_ID'),
        'client_secret' => env('VIDEOCLOUD_CLIENT_SECRET'),
        'video_profile' => env('VIDEOCLOUD_VIDEO_PROFILE'),
        'callback_url'  => env('VIDEOCLOUD_CALLBACK_URL'),
        
        'auth_url'      => env('VIDEOCLOUD_AUTH_URL'),
        'cms_url'       => env('VIDEOCLOUD_CMS_URL'),
        'di_url'        => env('VIDEOCLOUD_DI_URL'),
        'proxy_url'     => env('VIDEOCLOUD_PROXY_URL'),
    ],

];
