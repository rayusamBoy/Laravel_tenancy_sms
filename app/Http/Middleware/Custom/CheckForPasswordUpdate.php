<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\Qs;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForPasswordUpdate
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
        // If the user is authenticated and did not update password, as well as did not want to logout.
        if (Auth::check() && Auth::user()->password_updated_at == null && !Qs::isCurrentRoute('logout')) {
            // If the user try to submit the change password form, just go as usual; allow for any errors to be shown.
            if (Qs::isCurrentRoute('my_account.change_pass'))
                return $next($request);
            elseif (!Qs::isCurrentRoute('my_account'))
                // If the route the user is in, is not my account (the actual route that contain change password form).
                // Force the user to my_account route.
                return redirect()->route('my_account')->with('pop_warning', __('msg.update_password'));
        }
        
        return $next($request);
    }
}
