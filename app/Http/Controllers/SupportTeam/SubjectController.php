<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Subject\SubjectCreate;
use App\Http\Requests\Subject\SubjectRecordUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubjectController extends Controller implements HasMiddleware
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->my_class = $my_class;
        $this->user = $user;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamSA', except: ['destroy']),
            new Middleware('superAdmin', only: ['destroy']),
        ];
    }

    public function index()
    {
        $d['my_classes'] = $this->my_class->all();
        $d['teachers'] = $this->user->getUserByTypes(['teacher', 'admin', 'super_admin']);
        $d['subjects'] = $this->my_class->getAllSubjects();

        return view('pages.support_team.subjects.index', $d);
    }

    public function store(SubjectCreate $req)
    {
        $d = $req->only($this->my_class->getSubjectData());

        if ($req->students_ids != NULL) {
            $d2 = $req->only($this->my_class->getSubjectRecordData(['section_id']));
            $d2['students_ids'] = serialize($d2['students_ids']);
        } else {
            $d2 = $req->only($this->my_class->getSubjectRecordData(['students_ids']));
            $d2['students_ids'] = NULL;
        }

        $subject = $this->my_class->getSubject(['name' => $req->name, 'my_class_id' => $req->my_class_id])->with('record')->first();

        if (!is_null($subject)) {
            if ($subject->record->where('section_id', null)->isNotEmpty())
                return Qs::json(__('msg.class_sub_exists'), false);
            $d2['subject_id'] = $subject->id;
        } else {
            $new_subject = $this->my_class->createSubject($d);
            $d2['subject_id'] = $new_subject->id;
        }

        $this->my_class->createSubjectRecord($d2);

        return Qs::jsonStoreOk();
    }

    public function edit_record($id)
    {
        $d['sub_rec'] = $sub_rec = $this->my_class->getSubjectRecord(['id' => $id])->first();
        $d['teachers'] = $this->user->getUserByTypes(['teacher', 'admin', 'super_admin']);

        return is_null($sub_rec) ? Qs::goWithDanger('subjects.index') : view('pages.support_team.subjects.edit_record', $d);
    }

    public function update_record(SubjectRecordUpdate $req, $id)
    {
        $d = $req->only($this->my_class->getSubjectData(['name', 'my_class_id']));
        $teacher_id = $req->teacher_id;
        $students_ids = is_null($req->students_ids) ? NULL : serialize($req->students_ids);

        $this->my_class->updateSubject($req->subject_id, $d);
        $this->my_class->updateSubjectRecord(['id' => $id], ['teacher_id' => $teacher_id, 'students_ids' => $students_ids]);

        return Qs::jsonUpdateOk();
    }

    public function delete_record($sub_id, $id)
    {
        $sub_recs = $this->my_class->getSubject(['id' => $sub_id])->with('record')->get();
        $this->my_class->deleteSubjectRecord(['id' => $id]); // Delete subject record
        if ($sub_recs->count() <= 1) // If the deleted record was the only one.
            $this->my_class->deleteSubject($sub_id); // Delete the subject as well.

        return back()->with('flash_success', __('msg.del_ok'));
    }
}
