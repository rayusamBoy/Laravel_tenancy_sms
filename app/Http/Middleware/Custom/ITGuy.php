<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\Qs;
use Closure;

class ITGuy
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
        return (auth()->check() && Qs::userIsItGuy()) ? $next($request) : redirect()->route('login');
    }
}
