<?php

namespace App\Traits;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * ロガートレイト
 * 
 * @author Kuniyasu Wada
 */
trait Log
{
    /**
     * ログオブジェクトをセット
     * 
     * @param  string $name
     * @param  string $filepath
     * @return Logger
     */
    public function createLogger($name, $filepath)
    {
        $ym  = \Carbon::now()->format('Y-m');
        $ymd = \Carbon::now()->format('Y-m-d');
        
        $Logger = new Logger($name);
        $Logger->pushHandler(new StreamHandler("{$filepath}/{$ym}/{$ymd}.log", Logger::INFO, true, 0770, false));
        
        return $Logger;
    }

}
