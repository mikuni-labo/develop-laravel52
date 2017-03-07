<?php

return [
    
    /**
     * データ取得元
     */
    
    'source' => [
            '1'          => 'BAMP',
            '2'          => 'BSJ',
        
    ],
        
    /**
     * BAMP 接続情報
     */
    'BAMP' => [
            'URL'       => env('BAMP_URL'),
            'AUTH_USER' => env('BAMP_AUTH_ID'),
            'AUTH_PASS' => env('BAMP_AUTH_PASS'),
    ],
    
    /**
     * BSJ 接続情報
     */
    'BSJ' => [
            'URL'       => env('BSJ_URL'),
            'AUTH_USER' => env('BSJ_AUTH_ID'),
            'AUTH_PASS' => env('BSJ_AUTH_PASS'),
    ],
    
];