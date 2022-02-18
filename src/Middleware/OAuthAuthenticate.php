<?php

namespace LaravelFeiShu\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class OAuthAuthenticate
 */
class OAuthAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string                   $account
     * @param string|null              $scope
     * @param string|null              $type
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $account = 'default', $scope = null, $type = 'service')
    {
        // todo
        return $next($request);
    }
}
