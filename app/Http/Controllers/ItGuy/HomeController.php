<?php

namespace App\Http\Controllers\ItGuy;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Repositories\TenantRepo;
use App\Repositories\UserRepo;
use Illuminate\Http\Request as HttpReq;

class HomeController extends Controller
{
    protected $user, $tenant;
    public function __construct(UserRepo $user, TenantRepo $tenant)
    {
        $this->user = $user;
        $this->tenant = $tenant;
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');

        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');

        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard(HttpReq $request)
    {
        $d['tenants'] = $this->tenant->getAll();
        $d['users'] = $this->user->all();

        return view('pages.it_guy.dashboard', $d);
    }
}
