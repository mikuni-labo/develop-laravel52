<?php

return [
    
    /**
     * AWS接続環境情報
     */
    
    'AWS' => [
            'AWS_VERSION'            => env('AWS_VERSION'),
            'AWS_REGION'             => env('AWS_REGION'),
            'CREDENTIALS_KEY'        => env('CREDENTIALS_KEY'),
            'CREDENTIALS_SECRET'     => env('CREDENTIALS_SECRET'),
    ],
    
    /**
     * S3バケット情報
     */
    'S3' => [
            'BUCKET_NAME'            => env('S3_BUCKET_NAME'),
    ],

];