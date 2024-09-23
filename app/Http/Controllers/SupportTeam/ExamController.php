<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Mk;
use App\Http\Requests\Exam\ExamCreate;
use App\Http\Requests\Exam\ExamUpdate;
use App\Http\Requests\Exam\ExamUpdateEditState;
use App\Repositories\ExamRepo;
use App\Repositories\AssessmentRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ExamAnnounce;
use App\Http\Requests\Exam\ExamUpdateLockState;
use App\Repositories\MyClassRepo;
use App\Rules\HasStudentExamNumberPlaceholder;
use App\Rules\Uppercase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ExamController extends Controller implements HasMiddleware
{
    protected $exam, $year, $assessment, $my_class;
    public function __construct(ExamRepo $exam, AssessmentRepo $assessment, MyClassRepo $my_class)
    {
        $this->exam = $exam;
        $this->assessment = $assessment;
        $this->year = Mk::getSetting('current_session');
        $this->my_class = $my_class;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamSA', except: ['destroy']),
            new Middleware('superAdmin', only: ['destroy', 'publish']),
            new Middleware('headSA', only: ['update_lock_state'])
        ];
    }

    public function index()
    {
        $d['exams'] = $this->exam->all();
        $d['exams_cat'] = $this->exam->getCategories();
        $d['class_types'] = $this->exam->getClassTypes();
        $d['my_classes'] = $this->my_class->all();

        return view('pages.support_team.exams.index', $d);
    }

    public function store(ExamCreate $req)
    {
        $category_id = $req->category_id;
        $class_type_id = $req->class_type_id;

        if (Mk::isTerminalExam($category_id) or Mk::isAnnualExam($category_id)) {
            $keys = Mk::getExamData();
            if ($this->exam->classTypeExmCategoryExists($category_id, $class_type_id, $this->year))
                return back()->with('flash_info', __('msg.exam_cat_exists'));
        } else
            $keys = Mk::getExamData(['ca_student_position_by_value', 'cw_denominator', 'hw_denominator', 'tt_denominator', 'tdt_denominator']);

        $data = $req->only($keys);
        $data['year'] = Mk::getSetting('current_session');

        $exam = $this->exam->firstOrCreate($data);
        $classes = $this->my_class->get(["class_type_id" => $class_type_id]);

        foreach ($classes as $class) {
            // Validate inputs for exam number format
            Validator::make(
                $req->toArray(),
                [
                    'class_id_' . $class->id => 'filled|required_unless:exam_number_format_' . $class->id . ', null',
                    'exam_number_format_' . $class->id => ['nullable', 'regex:/^[\p{L}\p{N}\/\\-*]+$/u', 'required_unless:class_id_' . $class->id . ', null', new Uppercase, new HasStudentExamNumberPlaceholder]
                ],
                [],
                [
                    'class_id_' . $class->id => $class->name . ' class',
                    'exam_number_format_' . $class->id => $class->name . ' exam number format'
                ]
            )->validate();

            // If exam_number_format field is not null (Note: class_id field will always be filled)
            $format = $req->only('exam_number_format_' . $class->id)['exam_number_format_' . $class->id];
            if ($format != null)
                $this->exam->createNumberFormat(['exam_id' => $exam->id, 'my_class_id' => $class->id, 'format' => $format]);
        }

        if (Mk::isTerminalExam($category_id) or Mk::isAnnualExam($category_id))
            /* Create Continous Assessment */
            $this->assessment->create(['exam_id' => $exam->id]);

        return back()->with('flash_success', __('msg.store_ok'));
    }

    public function edit($id)
    {
        $d['ex'] = $ex = $this->exam->getById($id);
        $d['exams_cat'] = $this->exam->getCategories();
        $d['my_classes'] = $this->my_class->get(["class_type_id" => $ex->class_type_id]);

        return view('pages.support_team.exams.edit', $d);
    }

    public function update(ExamUpdate $req, $id)
    {
        if ($this->exam->isLocked($id))
            return Mk::jsonUpdateDenied();

        $exam = $this->exam->getExam(['id' => $id])->first();
        $keys = (Mk::isTerminalExam($exam->category_id) || Mk::isAnnualExam($exam->category_id)) ? Mk::getExamData() : Mk::getExamData(['ca_student_position_by_value', 'cw_denominator', 'hw_denominator', 'tt_denominator', 'tdt_denominator']);
        $data = $req->only($keys);
  
        $this->exam->update($id, $data);

        $classes = $this->my_class->get(["class_type_id" => $exam->class_type_id]);

        foreach ($classes as $class) {
            // Validate inputs for exam number format
            Validator::make(
                $req->toArray(),
                [
                    'class_id_' . $class->id => 'filled|required_unless:exam_number_format_' . $class->id . ',null',
                    'exam_number_format_' . $class->id => ['nullable', 'regex:/^[\p{L}\p{N}\/\\-*]+$/u', 'required_unless:class_id_' . $class->id . ',null', new Uppercase, new HasStudentExamNumberPlaceholder]
                ],
                [],
                [
                    'class_id_' . $class->id => $class->name . ' class',
                    'exam_number_format_' . $class->id => $class->name . ' exam number format'
                ]
            )->validate();

            // If exam_number_format field is not null (Note: class_id field will always be filled)
            $format = $req->only('exam_number_format_' . $class->id)['exam_number_format_' . $class->id] ?? null;
            $number_format_id = $req->only('number_format_' . $class->id)['number_format_' . $class->id] ?? null;

            if ($format != null)
                $this->exam->updateOrCreateNumberFormat(["id" => $number_format_id], ['exam_id' => $exam->id, 'my_class_id' => $class->id, 'format' => $format]);
            elseif ($format == null && $number_format_id != null)
                $this->exam->deleteNumberFormat($number_format_id);
        }

        return Mk::jsonUpdateOk();
    }

    public function destroy($id)
    {
        if ($this->exam->isLocked($id))
            return back()->with('pop_warning', __('msg.denied'));

        $this->exam->delete($id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    public function update_edit_state(ExamUpdateEditState $req)
    {
        $exam_id = $req->id;
        if ($this->exam->isLocked($exam_id))
            return Mk::jsonUpdateDenied();

        $this->exam->update($exam_id, $req->only("editable"));

        return Mk::json("ok");
    }

    public function update_lock_state(ExamUpdateLockState $req)
    {
        $exam_id = $req->id;
        $this->exam->update($exam_id, $req->only("locked"));

        return Mk::json("ok");
    }

    public function publish($exam_id)
    {
        $this->exam->update($exam_id, ["published" => true]);
        $exam = $this->exam->getById($exam_id);

        return back()->with('flash_success', __('msg.exam_published'))->with('announce_exam', true)->with('exam_id', $exam_id)->with('exam_name', $exam->name);
    }

    public function announce(ExamAnnounce $req)
    {
        $this->exam->announce($req->only(['exam_id', 'message', 'duration']));

        return Mk::jsonStoreOk();
    }
}
