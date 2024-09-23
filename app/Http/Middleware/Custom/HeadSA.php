<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;

class HeadSA
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
        return (Auth::check() && Qs::headSA(auth()->id())) ? $next($request) : redirect()->route('login');
    }
}
