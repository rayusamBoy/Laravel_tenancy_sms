<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Http\Requests\UserChangePass;
use App\Http\Requests\UserUpdate;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MyAccountController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function edit_profile()
    {
        $d['my'] = auth()->user();
        $d['staff_rec'] = $this->user->getStaffRecord(['user_id' => auth()->id()])->first();

        return view('pages.support_team.my_account', $d);
    }

    public function update_profile(UserUpdate $req)
    {
        $user = auth()->user();

        if (!$user->username && !$req->username && !$req->email)
            return back()->with('pop_error', __('msg.user_invalid'));

        $except = ['_token', '_method'];
        $d = $user->username ? $req->except(array_merge(Qs::getStaffRecord(['username']), $except)) : $req->except(array_merge(Qs::getStaffRecord(), $except));

        $d2 = $req->only(Qs::getStaffRecord());
        $d2['subjects_studied'] = isset($d2['subjects_studied']) ? json_encode($d2['subjects_studied']) : NULL;

        $user_type = $user->user_type;
        $code = $user->code;

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];

            if (Usr::tenancyInitilized()) {
                $f['path'] = $d['photo'] = Qs::getUploadPath($user_type) . $code . '/' . $f['name'];
                $photo->storeAs('public/' . $f['path']);
            } else {
                $f['path'] = $photo->storeAs(Qs::getUploadPath($user_type) . $code, $f['name']);
                $d['photo'] = 'storage/' . $f['path'];
            }
        }

        // If email changes on update; must verify again.
        if ($user->email !== $d['email'])
            $d['email_verified_at'] = NULL;

        $this->user->update($user->id, $d);
        $this->user->updateStaffRecord(['user_id' => $user->id], $d2);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function change_pass(UserChangePass $req)
    {
        $user_id = auth()->id();
        $my_pass = auth()->user()->password;
        $old_pass = $req->current_password;
        $new_pass = $req->new_password;

        if (password_verify($old_pass, $my_pass)) {
            $data['password'] = Hash::make($new_pass);
            $data['password_updated_at'] = \Carbon\Carbon::now();
            $this->user->update($user_id, $data);

            return back()->with('flash_success', __('msg.p_changed'));
        }

        return back()->with('flash_danger', __('msg.p_reset_fail'));
    }

    public function other(Request $req)
    {
        $keys = ['sidebar_minimized', 'show_charts', 'allow_system_sounds'];
        $this->user->update(auth()->id(), $req->only($keys));

        return back()->with('flash_success', __('msg.update_ok'));
    }
}
