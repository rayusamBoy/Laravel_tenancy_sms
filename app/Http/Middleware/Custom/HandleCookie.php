<?php

namespace App\Http\Middleware\Custom;

use App\Http\Controllers\CookieController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpFoundation\Response;

class HandleCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookie = CookieController::setForever('do_not_show_els_with_these_ids_again', auth()->user()?->hidden_alert_ids ?? "", null, null, null, false);
        $response = $next($request);

        // Check if the response is an instance of Illuminate\Http\Response (HTTP responses)
        if ($response instanceof IlluminateResponse) {
            return $response->withCookie($cookie); // Only add cookie to Illuminate's Response
        }

        // Return the response as is for other types like BinaryFileResponse
        return $response;
    }
}
