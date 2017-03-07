<?php

return [
        // Mail Driver
        'driver' => env('MAIL_DRIVER', 'smtp'),// 実際には送信せずに本文を確認したい場合は.envを'log'に
        
        // SMTP Host Address
        'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
        
        // SMTP Host Port
        'port' => env('MAIL_PORT', 587),
        
        // Global "From" Address
        'from' => [
                'address' => env('MAIL_FROM_ADDRESS', null),
                'name' => env('MAIL_FROM_NAME', null)
        ],
        
        // E-Mail Encryption Protocol
        'encryption' => env('MAIL_ENCRYPTION', null),
        
        // SMTP Server Username
        'username' => env('MAIL_USERNAME', null),
        
        // SMTP Server Password
        'password' => env('MAIL_PASSWORD', null),
        
        // Sendmail System Path
        'sendmail' => '/usr/sbin/sendmail -bs',
        
        // Mail "Pretend"
        'pretend' => env('MAIL_PRETEND', false),// 実際には送信せずにログ出力のみで留める時はtrue (本文は出力されない)
];
