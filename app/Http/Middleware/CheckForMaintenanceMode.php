<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Custom Maintenance Mode Class...
*/
class CheckForMaintenanceMode
{
    protected $app;
    protected $allow_ip = ['127.0.0.1'];
    //protected $allow_ip = ['124.37.140.162'];
    
    /**
     * コンストラクタ
     * 
     * @param Application $app
     * @param Request $request
     */
    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
    }
    
    /**
     * ハンドル
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance() &&
                !in_array(\Request::getClientIp(), $this->allow_ip))
        {
            throw new HttpException(503);
        }
    
        return $next($request);
    }
}
