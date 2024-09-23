<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ForgotPasswordController extends Controller implements HasMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
        ];
    }

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        $settings = Qs::getSettings();
        $data['colors'] = $colors = $settings->where('type', 'login_and_related_pgs_txts_and_bg_colors')->value('description');

        if (!is_null($colors)) {
            $colors_exploaded = explode(Qs::getDelimiter(), $colors);
            $data['texts_color'] = $colors_exploaded[0];
            $data['bg_color'] = $colors_exploaded[1];
        }

        return view('auth.passwords.email', $data);
    }
}
