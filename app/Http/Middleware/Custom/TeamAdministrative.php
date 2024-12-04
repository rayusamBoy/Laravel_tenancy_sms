<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\Qs;
use Closure;

class TeamAdministrative
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return (auth()->check() && Qs::userIsAdministrative()) ? $next($request) : redirect()->route('login');
    }
}
