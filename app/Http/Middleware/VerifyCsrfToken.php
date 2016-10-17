<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Closure;

class VerifyCsrfToken extends BaseVerifier
{
	/**
	 * The URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except = [
		/**
		 * Tokenの要求を回避したい場合はここで
		 */
		'api/*',
		//'ajax/*',
	];
	
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 *
	 * @throws \Illuminate\Session\TokenMismatchException
	 */
	public function handle($request, Closure $next)
	{
		if (
			$this->isReading($request) ||
			$this->runningUnitTests() ||
			$this->shouldPassThrough($request) ||
			$this->tokensMatch($request)
		) {
			return $this->addCookieToResponse($request, $next($request));
		}
		
		//throw new TokenMismatchException;
		throw new HttpException(408);
	}
}
