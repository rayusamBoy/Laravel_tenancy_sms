<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Mk;
use App\Repositories\ExamRepo;
use App\Repositories\AssessmentRepo;
use App\Http\Controllers\Controller;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\MarkRepo;
use App\Http\Requests\Assessment\AssessmentSelector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssessmentController extends Controller implements HasMiddleware
{
    protected $exam, $year, $assessment, $my_class, $student, $mark;
    public function __construct(ExamRepo $exam, AssessmentRepo $assessment, MyClassRepo $my_class, StudentRepo $student, MarkRepo $mark)
    {
        $this->exam = $exam;
        $this->assessment = $assessment;
        $this->my_class = $my_class;
        $this->student = $student;
        $this->mark = $mark;
        $this->year = Mk::getSetting('current_session');
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamSAT', except: ['show', 'year_selected', 'year_selector', 'print_detailed'])
        ];
    }

    public function list()
    {
        $d['assessments'] = $this->assessment->get()->whereNotNull('exam');
        $d['exams_cat'] = $this->exam->getCategories();

        return view('pages.support_team.assessments.list', $d);
    }

    public function index()
    {
        $d['assessments'] = $this->assessment->get();
        $d['my_classes'] = $this->my_class->all();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['selected'] = false;

        return view('pages.support_team.assessments.manage', $d);
    }

    public function selector(AssessmentSelector $req)
    {
        $data = $req->only(['exam_id', 'my_class_id', 'section_id', 'subject_id']);
        $d2 = $req->only(['exam_id', 'my_class_id', 'section_id']);
        $d = $req->only(['my_class_id', 'section_id']);
        $d['session'] = $data['year'] = $d2['year'] = $this->year;

        $sub_recs = $this->my_class->getSubjectRecord(['subject_id' => $req->subject_id])->where('subject.my_class_id', $req->my_class_id)->first();

        if ($sub_recs == NULL)
            return back()->with('pop_error', __('msg.rnf'));
        elseif ($req->section_id == NULL && $sub_recs->students_ids != NULL)
            $students = $this->student->getRecordByUserIDs(unserialize($sub_recs->students_ids))->get();
        else
            $students = $this->student->getRecord($d)->get();

        if ($students->count() < 1)
            return back()->with('pop_error', __('msg.rnf'));

        return redirect()->route('assessments.manage', [$req->exam_id, $req->my_class_id, $req->subject_id, $req->section_id]);
    }

    public function manage($exam_id, $class_id, $subject_id, $section_id = NULL)
    {
        if ($section_id == NULL)
            $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $this->year];
        else
            $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];

        $asmnts_created_at = $this->assessment->getRecordValue($d, 'created_at');
        $asmnts_updated_at = $this->assessment->getRecordValue($d, 'updated_at');

        $d['asmnt_records'] = $this->assessment->getRecords($d);

        if ($d['asmnt_records']->count() < 1)
            return back()->with('flash_danger', __('msg.srnf'));

        $d['m'] = $d['asmnt_records']->first();
        $d['assessments'] = $this->assessment->get();
        $d['exam'] = $this->exam->getById($exam_id);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();

        (Mk::userIsTeacher()) ? $d['subjects'] = $this->my_class->findSubjectByRecord(Auth::id(), $class_id) : $d['subjects'] = $this->my_class->getSubject(['my_class_id' => $class_id])->get();

        $d['selected'] = true;
        $d['class_type'] = $this->my_class->findTypeByClass($class_id);
        $d['created_at'] = Mk::onlyDateFormat($asmnts_created_at);
        $d['updated_at'] = Mk::onlyDateFormat($asmnts_updated_at);

        if ($section_id == NULL)
            $d['section_id_is_null'] = true;

        return view('pages.support_team.assessments.manage', $d);
    }

    public function bulk($class_id = NULL, $section_id = NULL)
    {
        $assessments_exists = $this->assessment->isNotEmpty();
        if (!$assessments_exists)
            return $this->noAssessmentRecaord();

        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        if ($class_id && $section_id) {
            $d['sections'] = $this->my_class->getAllSections()->where('my_class_id', $class_id);
            $d['students'] = $st = $this->student->getRecord(['my_class_id' => $class_id, 'section_id' => $section_id])->get()->sortBy('user.name');

            if ($st->count() < 1)
                return redirect()->route('assessments.bulk')->with('flash_danger', __('msg.srnf'));

            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
        }

        return view('pages.support_team.assessments.bulk', $d);
    }

    public function progressive($exam_id = NULL, $class_id = NULL, $section_id = NULL)
    {
        set_time_limit(300); // Extend excecution time from normal 1 minute to 3 minutes

        $d['my_classes'] = (Mk::userIsClassSectionTeacher()) ? $this->my_class->getMCByIds($this->my_class->getSections(["teacher_id" => Auth::id()])->pluck("my_class_id")) : $this->my_class->all();
        $d['assessments'] = $assessments = $this->assessment->get();
        $d['selected'] = FALSE;

        if ($class_id && $exam_id && $section_id) {
            $class_type = $this->my_class->findTypeByClass($class_id);
            // If the assessment do not belong to the selected class
            if ($assessments->where('exam.class_type_id', $class_type->id)->where('exam_id', $exam_id)->count() < 1)
                return back()->with('flash_danger', __('msg.arnf'));

            $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];

            $sub_ids = $this->mark->getSubjectIDs($wh);
            $st_ids = $this->mark->getStudentIDs($wh);

            $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
            $d['students'] = $this->student->getRecordByUserIDs2($st_ids)->get()->whereNotNull('user')->sortBy('user.name')->sortBy('user.gender');
            $d['sections'] = (Mk::userIsClassSectionTeacher()) ? $this->my_class->getTeacherClassSections($class_id, Auth::id()) : $this->my_class->getAllSections();
            $d['selected'] = TRUE;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
            $d['exam_id'] = $exam_id;
            $d['year'] = $this->year;
            $d['asr'] = $asr = $this->assessment->getRecords($wh);

            if (count($sub_ids) < 1 || count($st_ids) < 1 || count($asr) < 1)
                return Mk::goWithDanger('assessments.progressive', __('msg.srnf'));

            $d['my_class'] = $this->my_class->find($class_id);
            $d['section'] = $this->my_class->findSection($section_id);
            $d['ex'] = $exam = $this->exam->find($exam_id);
            $d['tex'] = 'tex' . $exam->term;
            $d['grades'] = $this->exam->getGrades()->pluck("name");
        }

        if (isset($class_id))
            $d['class_type_id'] = $this->my_class->find($class_id)->class_type_id;

        return view('pages.support_team.assessments.progressive.index', $d);
    }

    public function print_progressive($exam_id = NULL, $class_id = NULL, $section_id = NULL)
    {
        set_time_limit(300); // Extend excecution time from normal 1 minute to 3 minutes

        $d['my_classes'] = (Mk::userIsClassSectionTeacher()) ? $this->my_class->getMCByIds($this->my_class->getSections(["teacher_id" => Auth::id()])->pluck("my_class_id")) : $this->my_class->all();
        $d['assessments'] = $this->assessment->get();
        $d['selected'] = FALSE;

        if ($class_id && $exam_id && $section_id) {

            $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];

            $sub_ids = $this->mark->getSubjectIDs($wh);
            $st_ids = $this->mark->getStudentIDs($wh);

            if (count($sub_ids) < 1 or count($st_ids) < 1)
                return Mk::goWithDanger('assessments.progressive', __('msg.srnf'));

            $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
            $d['students'] = $this->student->getRecordByUserIDs2($st_ids)->get()->whereNotNull('user')->sortBy('user.name')->sortBy('user.gender');
            $d['sections'] = $this->my_class->getAllSections();
            $d['selected'] = TRUE;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
            $d['exam_id'] = $exam_id;
            $d['year'] = $this->year;
            $d['asr'] = $asr = $this->assessment->getRecords($wh);
            $d['my_class'] = $mc = $this->my_class->find($class_id);
            $d['section'] = $this->my_class->findSection($section_id);
            $d['ex'] = $exam = $this->exam->find($exam_id);
            $d['tex'] = 'tex' . $exam->term;
            $d['grades'] = $this->exam->getGrades()->pluck("name");
        }

        if (isset($class_id))
            $d['class_type_id'] = $this->my_class->find($class_id)->class_type_id;

        return view('pages.support_team.assessments.progressive.print', $d);
    }

    public function bulk_select(Request $req)
    {
        return redirect()->route('assessments.bulk', [$req->my_class_id, $req->section_id]);
    }

    protected function verifyStudentExamYear($student_id, $year = null)
    {
        $years = $this->assessment->getExamYears($student_id);
        $student_exists = $this->student->exists($student_id);

        if (!$year) {
            if ($student_exists && $years->count() > 0) {
                $d = ['years' => $years, 'student_id' => Mk::hash($student_id)];
                return view('pages.support_team.assessments.select_year', $d);
            }

            return $this->noStudentRecord();
        }

        return ($student_exists && $years->contains('year', $year)) ? true : false;
    }

    public function year_selector($student_id)
    {
        return $this->verifyStudentExamYear($student_id);
    }

    public function year_selected(Request $req, $student_id)
    {
        $year = $req->year;

        if (!$this->verifyStudentExamYear($student_id, $year))
            return $this->noStudentRecord();

        $student_id = Mk::hash($student_id);

        if ($this->assessment->getAssessments([])->where(['exam.year' => $year])->count() <= 0)
            return redirect()->route('assessments.year_selector', $student_id)->with("flash_info", "Oops! There are no published exams assessment(s) for the selected year " . $year);

        return redirect()->route('assessments.show', [$student_id, $req->year]);
    }

    public function show($student_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if (Auth::user()->id != $student_id && !Mk::userIsTeamSATCL() && !Mk::userIsMyChild($student_id, Auth::user()->id))
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));

        if (Mk::examIsLocked() && !Mk::userIsTeamSA()) {
            Session::put('assessments_url', route('assessments.show', [Mk::hash($student_id), $year]));

            if (!$this->checkPinVerified($student_id))
                return redirect()->route('pins.enter', Mk::hash($student_id));
        }

        if (!$this->verifyStudentExamYear($student_id, $year))
            return $this->noStudentRecord();

        $wh = ['student_id' => $student_id, 'year' => $year];
        $d['assessments_records'] = $asrs = $this->assessment->getRecords($wh);
        $d['assessments'] = $this->assessment->get()->where('exam.year', $year)->whereIn('exam.category_id', Mk::getSummativeExamCategoryIds())->whereIn('exam.class_type_id', $asrs->pluck('my_class.class_type_id')->unique('my_class.class_type_id')->toArray());
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first() ?? $this->student->getRecord2(['user_id' => $student_id])->first();
        $d['my_class'] = $mc = $this->my_class->getMC(['id' => $asrs->first()->my_class_id])->first();
        $d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;

        return view('pages.support_team.assessments.show.index', $d);
    }

    public function progressive_select(Request $req)
    {
        return redirect()->route('assessments.progressive', [$req->exam_id, $req->my_class_id, $req->section_id]);
    }

    public function update(Request $req, $exam_id, $class_id, $subject_id, $section_id = NULL)
    {
        if ($this->exam->isLocked($exam_id) && !Mk::headSA(auth()->id()))
            return Mk::jsonUpdateDenied();

        $exam = $this->exam->find($exam_id);

        /** Deny Updating Records, if Exam Edit is Disabled, and user is not team super adnim */
        if (Mk::isDisabled($exam->editable) && !Mk::userIsTeamSA()) {
            return Mk::jsonUpdateDenied();
        }

        if ($section_id == NULL)
            $p = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $this->year];
        else
            $p = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];

        $d = $all_st_ids = [];

        $asmnt_records = $this->assessment->getRecords($p);
        $class_type = $this->my_class->findTypeByClass($class_id);
        $assessment_id = $this->assessment->getAssessment(['exam_id' => $exam_id])->id;

        $mks = $req->all();

        $number_of_cw = 10; // Course work
        $number_of_hw = 5;  // Home work
        $number_of_tt = 3;  // Topic Test

        // NOTE: The added or plused numbers here after the assessment id must correspond to the nummbers 
        // added or plused in view. See 'resources\views\pages\support_team\assessments\edit.blade.php'.
        // The purpose is to make every element of a pariticular array unique, by making its key unique since the id is the same for some. 
        // If you change these, you must change those in the view first.
        foreach ($asmnt_records as $as) {
            $all_st_ids[] = $as->student_id;

            $d['id'] = $as->id;

            $d['cw1'] = $cw1 = $mks['cw_'][$as->id + 1] ?? NULL;
            $d['cw2'] = $cw2 = $mks['cw_'][$as->id + 2] ?? NULL;
            $d['cw3'] = $cw3 = $mks['cw_'][$as->id + 3] ?? NULL;
            $d['cw4'] = $cw4 = $mks['cw_'][$as->id + 4] ?? NULL;
            $d['cw5'] = $cw5 = $mks['cw_'][$as->id + 5] ?? NULL;
            $d['cw6'] = $cw6 = $mks['cw_'][$as->id + 6] ?? NULL;
            $d['cw7'] = $cw7 = $mks['cw_'][$as->id + 7] ?? NULL;
            $d['cw8'] = $cw8 = $mks['cw_'][$as->id + 8] ?? NULL;
            $d['cw9'] = $cw9 = $mks['cw_'][$as->id + 9] ?? NULL;
            $d['cw10'] = $cw10 = $mks['cw_'][$as->id + 10] ?? NULL;
            $cw_avg = ($cw1 + $cw2 + $cw3 + $cw4 + $cw5 + $cw6 + $cw7 + $cw8 + $cw9 + $cw10) / $number_of_cw;

            $d['hw1'] = $hw1 = $mks['hw_'][$as->id + 1] ?? NULL;
            $d['hw2'] = $hw2 = $mks['hw_'][$as->id + 2] ?? NULL;
            $d['hw3'] = $hw3 = $mks['hw_'][$as->id + 3] ?? NULL;
            $d['hw4'] = $hw4 = $mks['hw_'][$as->id + 4] ?? NULL;
            $d['hw5'] = $hw5 = $mks['hw_'][$as->id + 5] ?? NULL;
            $hw_avg = ($hw1 + $hw2 + $hw3 + $hw4 + $hw5) / $number_of_hw;

            $d['tt1'] = $tt1 = $mks['tt_'][$as->id + 1] ?? NULL;
            $d['tt2'] = $tt2 = $mks['tt_'][$as->id + 1] ?? NULL;
            $d['tt3'] = $tt3 = $mks['tt_'][$as->id + 1] ?? NULL;
            $tt_avg = ($tt1 + $tt2 + $tt3) / $number_of_tt;

            $temp_tca = round(($cw_avg + $hw_avg + $tt_avg), 1);

            if ($temp_tca != 0)
                $d['tca'] = $tca = $temp_tca;
            else
                $d['tca'] = $tca = NULL;

            $d['exm'] = $exm = $mks['exm_' . $as->id] ?? NULL;
            $d['assessment_id'] = $assessment_id;

            // Total
            $d['tex' . $exam->term] = $total = $tca + $exm;
            if ($total > 100)
                $d['tex' . $exam->term] = $d['t1'] = $d['t2'] = $d['t3'] = $d['t4'] = $d['tca'] = $exm = NULL;
            // Grade
            $grade = $this->assessment->getGrade($total, $class_type->id);
            $d['grade_id'] = $grade->id ?? NULL;
            // Subject position
            $d['sub_pos'] = $this->assessment->getSubPos($as->student_id, $exam, $class_id, $subject_id, $this->year);

            $d_collection[] = $d;
        }

        $this->assessment->massUpdateRecs($d_collection);

        /* Assessment Record Update */
        unset($p['subject_id']);

        foreach (array_unique($all_st_ids) as $st_id) {
            $d2['student_id'] = $st_id;
            $d2['ave'] = $ave = $this->assessment->getAssessmentAvgTerm($exam, $st_id, $class_id, $section_id, $this->year);
            $d2['class_ave'] = $this->assessment->getClassAvg($exam, $class_id, $this->year);
            $d2['total'] = $this->assessment->getAssessmentTotalTerm($exam, $st_id, $class_id, $this->year);
            $d2['pos'] = $this->assessment->getStudentPos($st_id, $exam_id, $class_id, $this->year, $exam->ca_student_position_by_value, $section_id);

            $d2_collection[] = $d2;
        }

        $this->assessment->massUpdateRecs($d2_collection, ['student_id']);
        /*Assessment Record End*/

        return Mk::jsonUpdateOk();
    }

    public function print_assessments($exam_id, $class_id, $subject_id, $year, $section_id = NULL)
    {
        if ($section_id == NULL) {
            $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $year];
            $wh = ['my_class_id' => $class_id, 'exam_id' => $exam_id, 'year' => $this->year];
        } else {
            $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];
            $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $year];
        }

        $marks_updated_at = $this->mark->getRecordValue($d, 'updated_at');
        $asmnts_updated_at = $this->assessment->getRecordValue($d, 'updated_at');
        $d2['year'] = $year;
        $d2['assessments'] = $assessment_records = $this->assessment->getRecords($d);

        if ($assessment_records->count() < 1)
            return $this->noStudentRecord();

        /*
         * Deny printing assessments if they are not updated.
         * The update compile the exam marks from exams.table and CA in the assessments_records.table.
         * The exam marks are automatically converted to out of 60 and created in assessments_records.table when they are created in marks.table.
         * check MarkContoller
         */
        if (strtotime($marks_updated_at) >= strtotime($asmnts_updated_at))
            return back()->with('flash_info', __('msg.update_assessments'));

        $d2['m'] = $d2['assessments']->first();
        $d2['exam'] = $exam = $this->exam->getById($exam_id);
        $d2['my_class'] = $this->my_class->find($class_id);
        $d2['selected'] = true;
        $d2['asr'] = $this->assessment->getRecords($wh);
        $d2['tex'] = $tex = 'tex' . $exam->term;
        $d2['ex'] = $this->exam->find($exam_id);
        $d2['stds_texs'] = $this->assessment->getStudentsTotals($d, $tex);

        // If section id is null ie., not applicable
        if ($section_id == NULL)
            $d2['section_id_is_null'] = true;

        return view('pages.support_team.assessments.print', $d2);
    }

    public function print_detailed($student_id, $exam_id, $year)
    {
        /* Prevent assessment print if super admin did not allow it - this applies to all users except super admin */
        if (!Mk::getSetting('allow_assessmentsheet_print') && !Mk::userIsSuperAdmin())
            return redirect()->back()->with('pop_error', __('msg.denied'));

        /* Prevent Other Students/Parents from viewing Result of others */
        if (Auth::user()->id != $student_id && !Mk::userIsTeamSAT() && !Mk::userIsMyChild($student_id, Auth::user()->id))
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));

        if (Mk::examIsLocked() && !Mk::userIsTeamSA()) {
            Session::put('assessments_url', route('assessments.show', [Mk::hash($student_id), $year]));

            if (!$this->checkPinVerified($student_id))
                return redirect()->route('pins.enter', Mk::hash($student_id));
        }

        if (!$this->verifyStudentExamYear($student_id, $year))
            return $this->noStudentRecord();

        $wh = ['student_id' => $student_id, 'exam_id' => $exam_id, 'year' => $year];
        $d['assessments_records'] = $asrs = $this->assessment->getRecords($wh);

        if ($asrs->isEmpty())
            return back()->with('flash_danger', __('msg.srnf'));

        $d['asr'] = $asr = $asrs->first();
        $d['my_class'] = $mc = $this->my_class->find($asr->my_class_id);
        $d['section'] = $this->my_class->findSection($asr->section_id);
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = 'tex' . $exam->term;
        $d['sr'] = $sr = $this->student->getRecord(['user_id' => $student_id])->first();
        $d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);
        $d['ct'] = $d['class_type']->code;
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['exam_id'] = $exam_id;
        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;

        return view('pages.support_team.assessments.print.detailed', $d);
    }

    public function print_minimal($student_id, $exam_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if (Auth::user()->id != $student_id && !Mk::userIsTeamSAT() && !Mk::userIsMyChild($student_id, Auth::user()->id))
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));

        if (Mk::examIsLocked() && !Mk::userIsTeamSA()) {
            Session::put('assessments_url', route('assessments.show', [Mk::hash($student_id), $year]));

            if (!$this->checkPinVerified($student_id))
                return redirect()->route('pins.enter', Mk::hash($student_id));
        }

        if (!$this->verifyStudentExamYear($student_id, $year))
            return $this->noStudentRecord();

        $wh = ['student_id' => $student_id, 'exam_id' => $exam_id, 'year' => $year];
        $d['assessments_records'] = $asrs = $this->assessment->getRecords($wh);

        if ($asrs->isEmpty())
            return back()->with('flash_danger', __('msg.srnf'));

        $d['asr'] = $asr = $asrs->first();
        $d['my_class'] = $mc = $this->my_class->find($asr->my_class_id);
        $d['section'] = $this->my_class->findSection($asr->section_id);
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = 'tex' . $exam->term;
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first();
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);
        $d['year'] = $year;
        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;

        return view('pages.support_team.assessments.print.minimal', $d);
    }

    protected function noStudentRecord()
    {
        return redirect()->route('dashboard')->with('flash_danger', __('msg.srnf'));
    }

    protected function noAssessmentRecaord()
    {
        return redirect()->route('dashboard')->with('flash_danger', __('msg.arnf'));
    }

    protected function checkPinVerified($st_id)
    {
        return Session::has('pin_verified') && Session::get('pin_verified') == $st_id;
    }

    public function print_cover($st_id)
    {
        $st_id = Mk::decodeHash($st_id);
        $d['student'] = $this->student->getRecord(["user_id" => $st_id])->first();

        return view('pages/support_team/assessments/print/cover', $d);
    }

    public function comment_update(Request $req, $as_id)
    {
        $d = Mk::userIsTeamSA() ? $req->only(['t_comment', 'p_comment']) : $req->only(['t_comment']);

        $this->assessment->updateRecord(['id' => $as_id], $d);

        return Mk::jsonUpdateOk();
    }

    public function skills_update(Request $req, $skill, $as_id)
    {
        $d = [];
        if ($skill == 'AF' || $skill == 'PS') {
            $sk = strtolower($skill);
            $d[$sk] = implode(',', $req->$sk);
        }

        $this->assessment->updateRecord(['id' => $as_id], $d);

        return Mk::jsonUpdateOk();
    }
}
