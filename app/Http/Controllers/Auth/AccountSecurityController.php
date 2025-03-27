<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use PragmaRX\Google2FALaravel\Google2FA;
use PragmaRX\Recovery\Recovery;

class AccountSecurityController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $google2fa = app('pragmarx.google2fa');
        $data["twofa_secret_code"] = $google2fa->generateSecretKey();
        $data["google2FAIsActive"] = $this->getGoogle2FASecretKey();

        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            Qs::getSetting('system_email') ?? auth()->user()->email,
            $data['twofa_secret_code']
        );

        $data['QR_Image'] = $QR_Image;
        $data['recovery_codes'] = $this->get_recovery_codes();
        $data['browser_sessions'] = $this->get_browser_sessions();

        if (Qs::userIsHead())
            $data['users_with_2fa_enabled'] = $this->user->all()->whereNotNull('twofa_secret_code');

        return view('auth.2fa.account_security', $data);
    }

    public function get_recovery_codes(): array
    {
        $recovery = new Recovery();
        $recovery->setBlocks(3);

        // Put recovery codes into session to be updated letter to the database
        session()->put('twofa_recovery_codes', $recovery->toJson());

        $recovery = $recovery->toArray();

        return $recovery;
    }

    public function account_recovery(Request $request)
    {
        if ($request->method() === 'POST') {
            $recovery_code = $request->recovery_codes;

            if (in_array($recovery_code, json_decode(auth()->user()->twofa_recovery_codes))) {
                $data['twofa_secret_code'] = null;
                $data['twofa_recovery_codes'] = null;
                $this->user->update(auth()->id(), $data);
            } else
                throw ValidationException::withMessages([
                    'recovery_codes' => 'Invalid recovery codes.'
                ]);
            return redirect()->route('index');
        }

        return view('auth.2fa.account_recovery');
    }

    public function showGoogle2FAVerification()
    {
        return view('auth.2fa.verify');
    }

    public function update_secret_codes(Request $request)
    {
        $data = $request->only('twofa_secret_code');
        // Get recovery codes from session that were put in the 'index' method above.
        $data['twofa_recovery_codes'] = $twofarc = session()->get('twofa_recovery_codes');

        // If the recovery codes missed in any way (ie., they are not in the session). Redirect back to the index page for re-processing or re-trying.
        if ($twofarc === null)
            return redirect()->route('account_security.index')->with('flash_danger', 'The system did not catch the recovery codes correctly. Please try again.');

        $this->user->update(auth()->id(), $data);

        // Remove recovery codes from session after update/store
        session()->forget('twofa_recovery_codes');
        // Make valid current authenticated user - For this session to continue without checking the OTP.
        $google2fa = new Google2FA($request);
        $google2fa->login();

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function null_secret_code(Request $request)
    {
        $id = $request->user_id ?? auth()->id();
        $this->user->update($id, ['twofa_secret_code' => NULL]);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function getGoogle2FASecretKey()
    {
        $secret = auth()->user()->twofa_secret_code;

        return $secret !== null && !empty($secret);
    }

    /* 
     * The route for this method is bound to '2fa' middleware. Meaning this method will not run unless the request passed the middleware.
     * Thus, if the user entered wrong otp in the verification form the middleware will return the response with error message to the form.
     * This method will only run if the user logged in by the middleware; hence we will redirect the user to the intended route (hopefully; dashboard).
     */
    public function authenticate(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->intended();
    }

    public function get_session_connection()
    {
        return DB::connection(config(key: 'session.connection'))->table(table: config(key: 'session.table', default: 'sessions'));
    }

    public function get_browser_sessions(): Collection
    {
        if (config(key: 'session.driver') !== 'database')
            return collect();

        return collect(
            value: $this->get_session_connection()
                ->where(column: 'user_id', operator: auth()->user()->getAuthIdentifier())
                ->latest(column: 'last_activity')
                ->get()
        )->map(callback: function ($session): object {
            $agent = $this->create_agent($session);

            return (object) [
                'device' => [
                    'browser' => $agent->browser(),
                    'desktop' => $agent->isDesktop(),
                    'mobile' => $agent->isMobile(),
                    'tablet' => $agent->isTablet(),
                    'platform' => $agent->platform(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    protected function create_agent(mixed $session)
    {
        return tap(
            value: new Agent(),
            callback: fn($agent) => $agent->setUserAgent(userAgent: $session->user_agent)
        );
    }

    public function logout_other_browser_sessions()
    {
        Validator::make(request()->toArray(), ['password' => ['required', 'current_password:web']])->validate();

        $password = request()->password;
        auth()->logoutOtherDevices($password);

        $this->delete_other_session_records();

        return redirect()->back()->with('flash_success', __('msg.other_bs_logout_ok'));
    }

    protected function delete_other_session_records(): void
    {
        if (config(key: 'session.driver') !== 'database')
            return;

        $this->get_session_connection()
            ->where(column: 'user_id', operator: '=', value: auth()->user()->getAuthIdentifier())
            ->where(column: 'id', operator: '!=', value: request()->session()->getId())
            ->delete();
    }

    public function get_user_last_activity(bool $human = false): Carbon|string
    {
        $lastActivity = $this->get_session_connection()
            ->where(column: 'user_id', operator: '=', value: auth()->user()->getAuthIdentifier())
            ->latest(column: 'last_activity')
            ->first();

        return $human
            ? Carbon::createFromTimestamp($lastActivity->last_activity)->diffForHumans()
            : Carbon::createFromTimestamp($lastActivity->last_activity);
    }
}
