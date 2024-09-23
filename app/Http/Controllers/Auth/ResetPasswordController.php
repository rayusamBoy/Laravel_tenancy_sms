<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        $data['token'] = $request->route()->parameter('token');
        $data['email'] = $request->email;
        $settings = Qs::getSettings();
        $data['colors'] = $colors = $settings->where('type', 'login_and_related_pgs_txts_and_bg_colors')->value('description');

        if (!is_null($colors)) {
            $colors_exploaded = explode(Qs::getDelimiter(), $colors);
            $data['texts_color'] = $colors_exploaded[0];
            $data['bg_color'] = $colors_exploaded[1];
        }

        return view('auth.passwords.reset', $data);
    }
}
