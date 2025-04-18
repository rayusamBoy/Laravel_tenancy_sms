<?php

namespace App\Http\Controllers\ItGuy;

use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserBlockedState;
use App\Http\Requests\UserRequest;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller implements HasMiddleware
{
    protected $user, $loc, $my_class;

    public function __construct(UserRepo $user, LocationRepo $loc, MyClassRepo $my_class)
    {
        $this->user = $user;
        $this->loc = $loc;
        $this->my_class = $my_class;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('itGuy'),
            new middleware('headSA', only: ['store', 'reset_pass', 'destroy', 'edit', 'update', 'update_user_blocked_state']),
        ];
    }

    public function index()
    {
        $ut = $this->user->getAllNotStudentType();
        $ut2 = $ut->where('level', '>', 2);

        $d['user_types'] = Qs::userIsAdmin() ? $ut2 : $ut;
        $d['users'] = $this->user->getPTAUsers();
        $d['nationals'] = $this->loc->getAllNationals();
        $d['blood_groups'] = $this->user->getBloodGroups();

        return view('pages.it_guy.users.index', $d);
    }

    public function edit($id)
    {
        $id = Qs::decodeHash($id);
        $ut = $this->user->getAllNotStudentType();
        $ut2 = $ut->where('level', '>', 2);

        $d['user_types'] = Qs::userIsAdmin() ? $ut2 : $ut;
        $d['user'] = $this->user->find($id);
        $d['staff_rec'] = $this->user->getStaffRecord(['user_id' => $id])->first();
        $d['users'] = $this->user->getPTAUsers();
        $d['blood_groups'] = $this->user->getBloodGroups();
        $d['nationals'] = $this->loc->getAllNationals();

        return view('pages.it_guy.users.edit', $d);
    }

    public function reset_pass($id)
    {
        $data['password'] = Hash::make('user');
        $data['password_updated_at'] = NULL;
        $this->user->update($id, $data);

        return back()->with('flash_success', __('msg.pu_reset'));
    }

    public function store(UserRequest $req)
    {
        $user_type = $this->user->findType($req->user_type)->title;
        $except = ['_token', '_method'];
        $data = $req->except(array_merge(Qs::getStaffRecord(), Qs::getParentRelativeRecord(), $except));

        $data['name'] = $name = ucwords(strtolower($req->name));
        $data['user_type'] = $user_type;
        $data['code'] = $code = strtoupper(Str::random(10));
        $data['photo'] = "storage/" . Usr::createAvatar($name, $code, $user_type);
        $data['dob'] = $req->dob;

        $emp_date = $req->emp_date ?? now();
        $staff_id = Qs::getAppCode() . '/STAFF/' . date('Y/m', strtotime($emp_date)) . '/' . mt_rand(1000, 9999);
        $data['username'] = $uname = $req->username ?? $staff_id;

        $pass = $req->password ?: $user_type;
        $data['password'] = Hash::make($pass);
        $data['message_media_heading_color'] = '#' . substr(md5($name), 0, 6); // Use a unique text color based on the name

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath($user_type) . $data['code'], $f['name']);
            $data['photo'] = 'storage/' . $f['path'];
        }

        /* Ensure that both username and Email are not blank*/
        if (!$uname && !$req->email)
            return Qs::json(__('msg.user_invalid'), FALSE);

        $user = $this->user->create($data); // Create User

        /* Create staff record */
        $d2 = $req->only(Qs::getStaffRecord());
        $d2['user_id'] = $user->id;
        $d2['code'] = $staff_id;
        $d2['subjects_studied'] = isset($d2['subjects_studied']) ? json_encode(explode(",", $d2['subjects_studied'])) : NULL;

        $this->user->createStaffRecord($d2);

        return Qs::jsonStoreOk();
    }

    public function update(UserRequest $req, $id)
    {
        $id = (int) Qs::decodeHash($id);
        $user_type_id = (int) Qs::decodeHash($req->user_type_id);
        $user = $this->user->find($id);
        $user_type = $this->user->findType($user_type_id)->title ?? $user->user_type;
        $except = array_merge(Qs::getStaffRecord(), ['_token', '_method', 'user_type_id']);
        $data = $req->except($except);

        $data['name'] = $name = ucwords(strtolower($req->name));
        $data['user_type'] = $user_type;
        $data['message_media_heading_color'] = '#' . substr(md5($name), 0, 6); // Use a unique text color based on the name

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath($user_type) . $user->code, $f['name']);
            $data['photo'] = 'storage/' . $f['path'];
        }

        $this->user->update($id, $data);   /* Update user record */

        /* Update staff record */
        $d2 = $req->only(Qs::getStaffRecord());
        $d2['code'] = $user->code;
        $d2['subjects_studied'] = isset($d2['subjects_studied']) ? json_encode(explode(",", $d2['subjects_studied'])) : NULL;
        $this->user->updateStaffRecord(['user_id' => $id], $d2);

        return Qs::jsonUpdateOk();
    }

    public function show($user_id)
    {
        $user_id = Qs::decodeHash($user_id);
        if (!$user_id)
            return back();

        $data['user'] = $this->user->find($user_id);

        /* Prevent other Users from viewing Profile of others */
        if (auth()->id() != $user_id && !Qs::userIsHead())
            return back()->with('pop_error', __('msg.denied'));

        $data['staff_rec'] = $this->user->getStaffRecord(['user_id' => $user_id])->first() ?: null;

        return view('pages.it_guy.users.show', $data);
    }

    public function destroy($id)
    {
        $id = Qs::decodeHash($id);
        $user = $this->user->find($id);
        $path = Qs::getUploadPath($user->user_type) . $user->code;

        Storage::exists($path) ? Storage::deleteDirectory($path) : true;

        $this->user->forceDelete($user->id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    public function update_user_blocked_state(UserBlockedState $req)
    {
        $user_id = $req->id;
        $data = $req->only("blocked");
        $this->user->update($user_id, $data);

        return Qs::json("ok");
    }
}
