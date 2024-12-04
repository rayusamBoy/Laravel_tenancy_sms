<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\Qs;
use Closure;

class TeamSAT
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
        return (auth()->check() && Qs::userIsTeamSAT()) ? $next($request) : redirect()->route('login');
    }
}
