<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Http\Requests\Student\StudentRecordUpdate;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use App\Rules\StartsWithProperPhoneCode;
use App\Rules\Uppercase;
use File;
use Illuminate\Http\Request as HttpReq;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentRecordController extends Controller implements HasMiddleware
{
    protected $loc, $my_class, $user, $student;

    public function __construct(LocationRepo $loc, MyClassRepo $my_class, UserRepo $user, StudentRepo $student)
    {
        $this->loc = $loc;
        $this->my_class = $my_class;
        $this->user = $user;
        $this->student = $student;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamSA', only: ['edit', 'update', 'reset_pass', 'create', 'store', 'graduated', 'block_all_class', 'unblock_all_class']),
            new Middleware('superAdmin', only: ['destroy', 'print_selected_id_cards', 'print_class_id_cards', 'id_cards_manage', 'id_cards']),
        ];
    }

    public function reset_pass($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        $data['password'] = Hash::make('student');
        $this->user->update($st_id, $data);

        return back()->with('flash_success', __('msg.p_reset'));
    }

    public function create()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();

        return view('pages.support_team.students.add', $data);
    }

    public function store(StudentRecordCreate $req)
    {
        $data = $req->only(Qs::getUserRecord());
        $sr = $req->only(Qs::getStudentData());

        $ct = $this->my_class->findTypeByClass($req->my_class_id)->code;

        $data['user_type'] = $user_type = 'student';
        $data['name'] = $name = strtoupper($req->name);
        $data['code'] = $code = strtoupper(Str::random(10));
        $data['password'] = Hash::make('student');
        $data['photo'] = Usr::createAvatar($name, $code, $user_type);

        $adm_no = $req->adm_no;
        $data['username'] = strtoupper(Qs::getAppCode() . '/' . $ct . '/' . date('Y', strtotime($sr['date_admitted'])) . '/' . ($adm_no ?: mt_rand(1000, 99999)));

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $data['photo'] = Qs::getUploadPath('student') . $data['code'] . '/' . $f['name'];
            $photo->storeAs('public/' . $f['path']);
        }

        if ($req->hasFile('birth_certificate')) {
            $birth_certificate = $req->file('birth_certificate');
            $f = Qs::getFileMetaData($birth_certificate);
            $f['name'] = 'birth_certificate.' . $f['ext'];
            $f['path'] = $sr['birth_certificate'] = Qs::getUploadPath('student') . $data['code'] . '/' . $f['name'];
            $birth_certificate->storeAs('public/' . $f['path']);
        }

        $sr['adm_no'] = $data['username'];
        $sr['session'] = Qs::getSetting('current_session');
        // Format string according to the enforced input
        $sr['house_no'] = strtoupper($sr['house_no']);
        $sr['ps_name'] = ucfirst($sr['ps_name']);
        $sr['ss_name'] = ucfirst($sr['ss_name']);
        $sr['p_status'] = ucfirst($sr['p_status']);

        $try_user = $this->user->get(['name' => $data['name'], 'user_type' => $data['user_type'], 'gender' => $req->gender])->first();

        if (!empty($try_user)) {
            $user = $this->user->update($try_user->id, $data); // If user exists, update data
            $sr['user_id'] = $try_user->id;
            $this->student->updateRecord($try_user->id, $sr); // Update record
        } else {
            $user = $this->user->create($data); // Create User
            $sr['user_id'] = $user->id ?? $try_user->id;
            $this->student->createRecord($sr); // Create record
        }

        return Qs::jsonStoreOk();
    }

    public function list_by_class($class_id)
    {
        $data['my_class'] = $mc = $this->my_class->getMC(['id' => $class_id])->first();
        $data['students'] = $this->student->findStudentsByClass($class_id);
        $data['sections'] = $this->my_class->getClassSections($class_id);

        return $mc === null ? Qs::goWithDanger() : view('pages.support_team.students.list', $data);
    }

    public function graduated()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['grad_students'] = $this->student->allGradStudents()->whereNotNull('user');

        return view('pages.support_team.students.graduated', $data);
    }

    public function not_graduated($sr_id, $prom_id)
    {
        $d['grad'] = 0;
        $d['grad_date'] = NULL;
        $d['session'] = Qs::getSetting('current_session');
        $sr_id = Qs::decodeHash($sr_id);

        $this->student->updateRecord($sr_id, $d);
        $this->student->deletePromotion($prom_id);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function show($sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if (!$sr_id)
            return Qs::goWithDanger();

        // Try getRecord for the active students, or getRecord2 for the graduated students.
        $data['sr'] = $sr = $this->student->getRecord(['id' => $sr_id])->first() ?? $this->student->getRecord2(['id' => $sr_id])->first();

        /* Prevent Other Students/Parents from viewing Profile of others */
        if (Auth::user()->id != $sr->user_id && !Qs::userIsTeamSATCL() && !Qs::userIsMyChild($sr->user_id, Auth::user()->id))
            if (Qs::userIsStudent2($sr->user->user_type) && !(Qs::userIsLibrarian() or Qs::userIsAccountant()))
                return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));

        return view('pages.support_team.students.show', $data);
    }

    public function edit($sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if (!$sr_id)
            return Qs::goWithDanger();

        $data['sr'] = $sr = $this->student->getRecord(['id' => $sr_id])->first() ?? $this->student->getRecord2(['id' => $sr_id])->first();
        $data['my_classes'] = $this->my_class->all();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        $data['class_sections'] = $this->my_class->getClassSections($sr->my_class_id);

        return view('pages.support_team.students.edit', $data);
    }

    public function update(StudentRecordUpdate $req, $sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if (!$sr_id)
            return Qs::goWithDanger();

        $sr = $this->student->getRecord(['id' => $sr_id])->first() ?? $this->student->getRecord2(['id' => $sr_id])->first();
        $d = $req->only(Qs::getUserRecord());
        $d['name'] = strtoupper($req->name);
        $d['username'] = $req->adm_no;

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $d['photo'] = Qs::getUploadPath('student') . $sr->user->code . '/' . $f['name'];
            $photo->storeAs('public/' . $f['path']);
        }

        // $d['photo'] = Usr::createAvatar($d['name'], $sr->user->code, $sr->user->user_type);

        $this->user->update($sr->user->id, $d); // Update User Details

        $srec = $req->only(Qs::getStudentData());

        if ($req->hasFile('birth_certificate')) {
            $birth_certificate = $req->file('birth_certificate');
            $f = Qs::getFileMetaData($birth_certificate);
            $f['name'] = 'birth_certificate.' . $f['ext'];
            $f['path'] = $srec['birth_certificate'] = Qs::getUploadPath('student') . $sr->user->code . '/' . $f['name'];
            $birth_certificate->storeAs('public/' . $f['path']);
        }
        // Format string according to the enforced input
        $srec['house_no'] = strtoupper($srec['house_no']);
        $srec['ps_name'] = ucfirst($srec['ps_name']);
        $srec['ss_name'] = ucfirst($srec['ss_name']);
        $srec['p_status'] = ucfirst($srec['p_status']);

        $srec['adm_no'] = $req->adm_no;
        $this->student->updateRecord($sr_id, $srec); // Update Student Record

        /*** If Class/Section is Changed in Same Year, Delete Marks/ExamRecord of Previous Class/Section ****/
        // Mk::deleteOldRecord($sr->user->id, $srec['my_class_id']);

        return Qs::jsonUpdateOk();
    }

    public function destroy($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        if (!$st_id)
            return Qs::goWithDanger();

        $sr = $this->student->getRecord(['user_id' => $st_id])->first();
        $this->user->delete($sr->user->id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    private function getThemeFilesNames()
    {
        $dir = Qs::getTenancyAwareIDCardsThemeDir();
        $file_names_to_copy = ['0001.blade.php'];
        $dir_names_to_copy = ['backgrounds', 'others'];

        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true); // Make it a directory if not yet there
            $default_dir = resource_path() . '/views/pages/support_team/students/id_cards/themes/samples/';

            foreach ($file_names_to_copy as $fname) {
                File::copy("$default_dir$fname", "$dir$fname");
            }

            foreach ($dir_names_to_copy as $dname) {
                File::copyDirectory("$default_dir$dname", "$dir$dname");
            }
        }

        $exclude = array_merge(['.', '..'], $dir_names_to_copy);
        $files = array_diff(scandir($dir), $exclude);
        array_walk_recursive($files, function (&$arr) {
            $arr = explode(".", $arr)[0]; // Leave out '.blade.php' part
        });

        return $files;
    }

    public function id_cards()
    {
        $d['my_classes'] = $this->my_class->all();
        $d['theme_names'] = $this->getThemeFilesNames();
        $d['selected'] = false;

        return view('pages.support_team.students.id_cards.index', $d);
    }

    public function id_cards_manage(HttpReq $req)
    {
        Validator::make($req->toArray(), [
            'my_class_id' => 'required|exists:my_classes,id',
            'expire_date' => 'required|string',
            'phone' => ['required', 'string', 'min:10', 'max:16', new StartsWithProperPhoneCode],
            'class_from' => 'sometimes|nullable|exists:my_classes,name',
            'class_to' => 'sometimes|nullable|exists:my_classes,name',
            'motto' => ['required', 'string', new Uppercase],
            'brightness' => 'sometimes|nullable|decimal:0,2|min:1|max:100',
        ], [], ['my_class_id' => 'Class', 'phone' => 'phone number'])->validate();

        $d = $req->except('_token');
        $d['my_classes'] = $this->my_class->all();

        if ($req->filled(['my_class_id', 'issued_date', 'expire_date', 'id_theme'])) {
            // Exclude students with soft deleted user record
            $d['students'] = $st = $this->student->getRecord(['my_class_id' => $req->my_class_id])->get()->whereNotNull('user')->sortBy('user.name');
            if ($st->count() < 1)
                return Qs::goWithDanger('students.id_cards');

            $d['theme_names'] = $this->getThemeFilesNames();
            $d['selected'] = true;
        }

        return view('pages.support_team.students.id_cards.index', $d);
    }

    public function print_class_id_cards($class_id, $issued, $expire, $theme, $phone, $class_from = NULL, $class_to = NULL, $motto, $brightness = 1, $website_link = NULL)
    {
        $d['students'] = $this->student->getRecord(['my_class_id' => $class_id])->with('my_class')->get()->whereNotNull('user')->sortBy('user.name');
        $d['issued'] = $issued;
        $d['expire'] = $expire;
        $d["year_from"] = explode(",", $issued)[1];
        $d["year_to"] = explode(",", $expire)[1];
        $d['phone'] = $phone;
        $d['class_from'] = $class_from;
        $d['class_to'] = $class_to;
        $d['motto'] = $motto;
        $d['brightness'] = $brightness;
        $d['web_link'] = $website_link;
        $d['settings'] = Qs::getSettings();

        // Theme name should be a four digit code name like '0001' etc.
        return view('pages.support_team.students.id_cards.themes.' . config('tenancy.database.prefix') . tenant('id') . '/' . $theme, $d);
    }

    public function print_selected_id_cards(HttpReq $req)
    {
        // If no student(s) selected at all
        if ($req->students_ids == null)
            return Qs::goWithDanger('students.id_cards', 'You must select at least a student to print ID card');

        $d = $req->except('__token');
        $d['students'] = $this->student->getRecordByUserIDs($req->students_ids)->with('my_class')->get()->sortBy('user.name');
        $d["year_from"] = explode(",", $req->issued)[1];
        $d["year_to"] = explode(",", $req->expire)[1];
        $d['phone'] = $req->phone;
        $d['settings'] = Qs::getSettings();

        return view('pages.support_team.students.id_cards.themes.' . config('tenancy.database.prefix') . tenant('id') . '/' . $d['theme'], $d);
    }

    public function block_all_class(HttpReq $req)
    {
        $students_ids = $this->student->getRecord(['my_class_id' => $req->class_id])->get()->pluck('user_id');
        $this->user->updateByIds($students_ids, ['blocked' => 1]);

        return Qs::json("Selected class sutdents have been Blocked successfully");
    }

    public function unblock_all_class(HttpReq $req)
    {
        $students_ids = $this->student->getRecord(['my_class_id' => $req->class_id])->get()->pluck('user_id');
        $this->user->updateByIds($students_ids, ['blocked' => 0]);

        return Qs::json("Selected class sutdents have been Unblocked successfully");
    }

    public function block_all_graduated()
    {
        $students_ids = $this->student->allGradOnlyStudents()->pluck('user_id');
        $this->user->updateByIds($students_ids, ['blocked' => 1]);

        return Qs::json("Graduated sutdents have been Blocked successfully");
    }

    public function unblock_all_graduated()
    {
        $students_ids = $this->student->allGradOnlyStudents()->pluck('user_id');
        $this->user->updateByIds($students_ids, ['blocked' => 0]);

        return Qs::json("Graduated have been Unblocked successfully");
    }
}
