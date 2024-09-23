<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CookieController;
use App\Events\UserLoggedIn;
use App\Helpers\Qs;
use App\Helpers\Usr;

class LoginController extends Controller implements HasMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $cookie;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $settings = Qs::getSettings();
        $account_status = tenant('account_status');

        $data['colors'] = $colors = $settings->where('type', 'login_and_related_pgs_txts_and_bg_colors')->value('description');
        if (!is_null($colors)) {
            $colors_exploaded = explode(Qs::getDelimiter(), $colors);
            $data['texts_color'] = $colors_exploaded[0];
            $data['bg_color'] = $colors_exploaded[1];
        }

        // If account not active ie., blocked, susupended etc., abort as forbidden.
        if (in_array($account_status, Usr::getAccountStatuses(['active']))) {
            $message = 'ACCOUNT ' . strtoupper($account_status) . '.';
            session()->flash('tenant_account_not_active', $message);
            abort(403, $message);
        }

        // If the user was logged out due to session expire
        if (!$this->cookie->has('has_logged_out'))
            if ($this->cookie->has('was_logged_in') && $this->cookie->get('was_logged_in') == true)
                session()->flash('session_expired', __('msg.session_expired'));

        return view('auth.login', $data);
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout']),
        ];
    }

    /**
     * Create a new controller instance.
     *
     * @return void$field
     */
    public function __construct(CookieController $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     *  Login with Username or Email
     * 
     */
    public function username()
    {
        $identity = request()->identity;
        $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $identity]);
        
        return $field;
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        // Set cookie - has logged out
        $this->cookie->set('has_logged_out', true, env('COOKIE_LIFETIME'));
        // Delete was logged in
        $this->cookie->delete('was_logged_in');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user = Auth::user();
        if ($user->blocked) {
            Auth::logout();
            return redirect()->route('login')->with('access_denied', __('msg.restricted') . ' ACCOUNT BLOCKED.');
        }

        // Store User's login in DB.
        event(new UserLoggedIn($user));

        $this->cookie->delete('has_logged_out');
        // Set was_logged_in cookie; default minutes if lifetime not set in env: 3 days = 4320 minutes
        $this->cookie->set('was_logged_in', true, env('COOKIE_LIFETIME', 4320));
    }
}
