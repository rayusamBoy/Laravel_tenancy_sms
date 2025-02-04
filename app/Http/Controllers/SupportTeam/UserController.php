<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserBlockedState;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserStaffDataEditState;
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
            new Middleware('superAdmin', only: ['reset_pass', 'destroy', 'update_staff_data_edit_state', 'update_user_blocked_state']),
            new middleware('teamSA', only: ['index', 'store', 'edit', 'update']),
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

        return view('pages.support_team.users.index', $d);
    }

    public function edit($id)
    {
        $id = Qs::decodeHash($id);
        $ut = $this->user->getAllNotStudentType();
        $ut2 = $ut->whereNotIn('title', Qs::getTeamAdministrative());

        $d['user_types'] = Qs::userIsAdmin() ? $ut2 : $ut;
        $d['user'] = $this->user->find($id);
        $d['staff_rec'] = $this->user->getStaffRecord(['user_id' => $id])->first();
        $d['users'] = $this->user->getPTAUsers();
        $d['blood_groups'] = $this->user->getBloodGroups();
        $d['nationals'] = $this->loc->getAllNationals();

        return view('pages.support_team.users.edit', $d);
    }

    public function reset_pass($id)
    {
        // Redirect if Making Changes to Head of Super Admins
        if (Qs::headSA($id) && !Qs::userIsHead())
            return back()->with('flash_danger', __('msg.denied'));

        $data['password'] = Hash::make('user');
        $data['password_updated_at'] = NULL;

        $this->user->update($id, $data);

        return back()->with('flash_success', __('msg.pu_reset'));
    }

    public function store(UserRequest $req)
    {
        $except = ['_token', '_method'];
        $user_type = $this->user->findType($req->user_type)->title;
        $data = $req->except(array_merge(Qs::getStaffRecord(), Qs::getParentRelativeRecord(), $except));

        $data['name'] = $name = ucwords(strtolower($req->name));
        $data['user_type'] = $user_type;
        $data['code'] = $code = strtoupper(Str::random(10));
        $data['photo'] = Usr::createAvatar($name, $code, $user_type);
        $data['dob'] = $req->dob;

        $user_is_staff = in_array($user_type, Qs::getStaff());
        $user_is_teamSA = in_array($user_type, Qs::getTeamSA());
        $user_is_parent = in_array($user_type, Qs::getParent());

        $emp_date = $req->emp_date ?? now();
        $staff_id = Qs::getAppCode() . '/STAFF/' . date('Y/m', strtotime($emp_date)) . '/' . mt_rand(1000, 9999);
        $data['username'] = $uname = $user_is_teamSA ? $req->username : $staff_id;

        // If user a parent get the work he/she does
        $data['work'] = $user_is_parent ? $req->work : NULL;

        $pass = $req->password ?: $user_type;
        $data['password'] = Hash::make($pass);
        $data['message_media_heading_color'] = '#' . substr(md5($name), 0, 6); // Use a unique text color based on the name

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $data['photo'] = Qs::getUploadPath($user_type) . $data['code'] . '/' . $f['name'];
            $photo->storeAs('public/' . $f['path']);
        }

        /* Ensure that both username and Email are not blank*/
        if (!$uname && !$req->email)
            return Qs::json(__('msg.user_invalid'), FALSE);

        $user = $this->user->create($data); // Create User

        /* CREATE STAFF RECORD */
        if ($user_is_staff) {
            $d2 = $req->only(Qs::getStaffRecord());
            $d2['user_id'] = $user->id;
            $d2['code'] = $staff_id;
            $d2['subjects_studied'] = json_encode(explode(",", $d2['subjects_studied']));

            $this->user->createStaffRecord($d2);
        }

        /* CREATE PARENT RELATIVE RECORD */
        if ($user_is_parent) {
            $d3 = $req->only(Qs::getParentRelativeRecord(['name2']));
            $d3['user_id'] = $user->id;
            // Use name2 from the request (removed above) as name
            $d3['name'] = ucwords(strtolower($req->name2));
            $d3['relation'] = $req->relation;

            $this->user->createParentRelativeRecord($d3);
        }

        return Qs::jsonStoreOk();
    }

    public function update(UserRequest $req, $id)
    {
        $id = (int) Qs::decodeHash($id);
        $user_type_id = (int) Qs::decodeHash($req->user_type);

        // Redirect if Making Changes to Head of Super Admins
        if (Qs::headSA($id) && !Qs::headSA(auth()->id()))
            return Qs::json(__('msg.denied'), FALSE);
        elseif (Qs::headSA(auth()->id()) && !Qs::userIsHead())
            return Qs::json(__('msg.denied'), FALSE);

        $user = $this->user->find($id);

        $user_type = $this->user->findType($user_type_id)->title ?? $user->user_type;
        $user_was_staff = in_array($user->user_type, Qs::getStaff());
        $user_is_staff = in_array($user_type, Qs::getStaff());
        $user_is_parent = in_array($user_type, Qs::getParent());

        $except = array_merge(Qs::getStaffRecord(), Qs::getParentRelativeRecord(), ['_token', '_method']);
        $data = $req->except($except);

        $data['name'] = $name = ucwords(strtolower($req->name));
        $data['user_type'] = $user_type;
        $data['work'] = $user_is_parent ? $req->work : NULL;
        $data['message_media_heading_color'] = '#' . substr(md5($name), 0, 6); // Use a unique text color based on the name

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $data['photo'] = Qs::getUploadPath($user_type) . $user->code . '/' . $f['name'];
            $photo->storeAs('public/' . $f['path']);
        }

        $this->user->update($id, $data);   /* UPDATE USER RECORD */

        /* UPDATE OR CREATE NEW STAFF RECORD */
        if ($user_was_staff) {
            if ($user_is_staff) {
                $d2 = $req->only(Qs::getStaffRecord());
                $d2['code'] = $user->code;
                $d2['subjects_studied'] = json_encode(explode(",", $d2['subjects_studied']));
                $this->user->updateStaffRecord(['user_id' => $id], $d2);
            } else
                $this->user->deleteStaffRecord(['user_id' => $id]);
        } elseif ($user_is_staff) {
            $d2 = $req->only(Qs::getStaffRecord());
            $d2['code'] = $user->code;
            $d2['subjects_studied'] = json_encode(explode(",", $d2['subjects_studied']));
            $this->user->updateStaffRecord(['user_id' => $id], $d2);
        }

        /* UPDATE PARENT RELATIVE RECORD */
        if ($user_is_parent) {
            $d3 = $req->only(Qs::getParentRelativeRecord(['user_id', 'name2']));
            $d3['name'] = ucwords(strtolower($req->name2));
            $d3['relation'] = $req->relation;
            $this->user->updateParentRelativeRec(['user_id' => $id], $d3);
        }

        return Qs::jsonUpdateOk();
    }

    public function show($user_id)
    {
        $user_id = Qs::decodeHash($user_id);
        if (!$user_id)
            return back();

        $data['user'] = $this->user->find($user_id);

        /* Prevent Other Students from viewing Profile of others*/
        if (auth()->id() != $user_id && !Qs::userIsTeamSA() && !Qs::userIsMyChild(auth()->id(), $user_id))
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));

        if (Qs::userIsTeamSATCL())
            $data['staff_rec'] = $this->user->getStaffRecord(['user_id' => $user_id])->first() ?: null;

        return view('pages.support_team.users.show', $data);
    }

    public function destroy($id)
    {
        $id = Qs::decodeHash($id);

        // Redirect if Making Changes to Head of Super Admins or Head master
        if (Qs::headSA($id))
            return back()->with('pop_error', __('msg.denied'));

        $user = $this->user->find($id);

        if ($user->user_type == 'teacher' && $this->userTeachesSubject($user))
            return back()->with('pop_error', __('msg.del_teacher'));

        $path = Qs::getUploadPath($user->user_type) . $user->code;
        Storage::exists($path) ? Storage::deleteDirectory($path) : true;
        $this->user->delete($user->id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    protected function userTeachesSubject($user)
    {
        $subjects = $this->my_class->findSubjectRecByTeacher($user->id);
        return ($subjects->count() > 0) ? true : false;
    }

    public function update_staff_data_edit_state(UserStaffDataEditState $req)
    {
        $where = ['user_id' => $req->id];
        $data = $req->only("staff_data_edit");
        $this->user->updateStaffRecord($where, $data);

        return Qs::json("ok");
    }

    public function update_user_blocked_state(UserBlockedState $req)
    {
        $user_id = $req->id;
        $data = $req->only("blocked");
        $this->user->update($user_id, $data);

        return Qs::json("ok");
    }
}
