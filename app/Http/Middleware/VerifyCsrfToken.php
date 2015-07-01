<?php namespace LootTracker\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Do not run CSRF checks while testing.
        if (app()->environment() === 'testing') {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
