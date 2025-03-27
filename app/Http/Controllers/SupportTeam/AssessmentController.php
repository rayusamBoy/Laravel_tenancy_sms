<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Mk;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\AssessmentSelector;
use App\Repositories\AssessmentRepo;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

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
        $d = $req->only(['my_class_id', 'section_id']);
        $d['session'] = $this->year;

        $sub_recs = $this->my_class->getSubjectRecord(['subject_id' => $req->subject_id])->where('subject.my_class_id', $req->my_class_id)->first();

        if ($sub_recs == null)
            return back()->with('pop_error', __('msg.rnf'));
        elseif ($req->section_id == null && $sub_recs->students_ids != null)
            $students = $this->student->getRecordByUserIDs(unserialize($sub_recs->students_ids))->get();
        else
            $students = $this->student->getRecord($d)->get();

        if ($students->isEmpty())
            return back()->with('pop_error', __('msg.rnf'));

        return redirect()->route('assessments.manage', [$req->exam_id, $req->my_class_id, $req->subject_id, $req->section_id]);
    }

    public function manage($exam_id, $class_id, $subject_id, $section_id = null)
    {
        $d = $section_id == null
            ? ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $this->year]
            : ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];

        $asmnts_created_at = $this->assessment->getRecordValue($d, 'created_at');
        $asmnts_updated_at = $this->assessment->getRecordValue($d, 'updated_at');

        $d['asmnt_records'] = $this->assessment->getRecords($d);

        if ($d['asmnt_records']->isEmpty())
            return back()->with('flash_danger', __('msg.srnf'));

        $d['m'] = $d['asmnt_records']->first();
        $d['assessments'] = $this->assessment->get();
        $d['exam'] = $this->exam->getById($exam_id);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();

        $d['subjects'] = Mk::userIsTeacher()
            ? $this->my_class->findSubjectByRecord(auth()->id(), $class_id)->pluck('subject')
            : $this->my_class->getSubject(['my_class_id' => $class_id])->get();

        $d['selected'] = true;
        $d['class_type'] = $this->my_class->findTypeByClass($class_id);
        $d['created_at'] = Mk::onlyDateFormat($asmnts_created_at);
        $d['updated_at'] = Mk::onlyDateFormat($asmnts_updated_at);
        $d['section_id'] = $section_id;

        return view('pages.support_team.assessments.manage', $d);
    }

    public function bulk($class_id = null, $section_id = null)
    {
        $assessments_exists = $this->assessment->isNotEmpty();
        if (!$assessments_exists)
            return $this->noAssessmentRecaord();

        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        if ($class_id && $section_id) {
            $d['sections'] = $this->my_class->getAllSections()->where('my_class_id', $class_id);
            $d['students'] = $st = $this->student->getRecord(['my_class_id' => $class_id, 'section_id' => $section_id])->get()->sortBy('user.name');

            if ($st->isEmpty())
                return redirect()->route('assessments.bulk')->with('flash_danger', __('msg.srnf'));

            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
        }

        return view('pages.support_team.assessments.bulk', $d);
    }

    public function progressive($exam_id = null, $class_id = null, $section_id = null)
    {
        set_time_limit(300); // Extend excecution time from normal 1 minute to 3 minutes

        $d['my_classes'] = (Mk::userIsClassSectionTeacher()) ? $this->my_class->getMCByIds($this->my_class->getSections(["teacher_id" => auth()->id()])->pluck("my_class_id")) : $this->my_class->all();
        $d['assessments'] = $assessments = $this->assessment->get();
        $d['selected'] = false;

        if ($class_id && $exam_id && $section_id) {
            $class_type = $this->my_class->findTypeByClass($class_id);
            // If the assessment do not belong to the selected class
            if ($assessments->where('exam.class_type_id', $class_type->id)->where('exam_id', $exam_id)->isEmpty())
                return back()->with('flash_danger', __('msg.arnf'));

            $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];

            $sub_ids = $this->mark->getSubjectIDs($wh);
            $st_ids = $this->mark->getStudentIDs($wh);

            $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
            $d['students'] = $this->student->getRecordByUserIDs2($st_ids)->get()->whereNotNull('user')->sortBy('user.name')->sortBy('user.gender');
            $d['sections'] = Mk::userIsClassSectionTeacher() ? $this->my_class->getTeacherClassSections($class_id, auth()->id()) : $this->my_class->getAllSections();
            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
            $d['exam_id'] = $exam_id;
            $d['year'] = $this->year;
            $d['asr'] = $asr = $this->assessment->getRecords($wh);

            if ($sub_ids->isEmpty() || $st_ids->isEmpty() || $asr->isEmpty())
                return Mk::goWithDanger('assessments.progressive', __('msg.srnf'));

            $d['my_class'] = $this->my_class->find($class_id);
            $d['section'] = $this->my_class->findSection($section_id);
            $d['ex'] = $exam = $this->exam->find($exam_id);
            $d['tex'] = "tex{$exam->term}";
            $d['grades'] = $this->exam->getGrades()->pluck("name");
        }

        if (isset($class_id))
            $d['class_type_id'] = $this->my_class->find($class_id)->class_type_id;

        return view('pages.support_team.assessments.progressive.index', $d);
    }

    public function print_progressive($exam_id = null, $class_id = null, $section_id = null)
    {
        set_time_limit(300); // Extend excecution time from normal 1 minute to 3 minutes

        $d['my_classes'] = Mk::userIsClassSectionTeacher() ? $this->my_class->getMCByIds($this->my_class->getSections(["teacher_id" => auth()->id()])->pluck("my_class_id")) : $this->my_class->all();
        $d['assessments'] = $this->assessment->get();
        $d['selected'] = false;

        if ($class_id && $exam_id && $section_id) {

            $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];

            $sub_ids = $this->mark->getSubjectIDs($wh);
            $st_ids = $this->mark->getStudentIDs($wh);

            if ($sub_ids->isEmpty() or $st_ids->isEmpty())
                return Mk::goWithDanger('assessments.progressive', __('msg.srnf'));

            $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
            $d['students'] = $this->student->getRecordByUserIDs2($st_ids)->get()->whereNotNull('user')->sortBy('user.name')->sortBy('user.gender');
            $d['sections'] = $this->my_class->getAllSections();
            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
            $d['exam_id'] = $exam_id;
            $d['year'] = $this->year;
            $d['asr'] = $this->assessment->getRecords($wh);
            $d['my_class'] = $this->my_class->find($class_id);
            $d['section'] = $this->my_class->findSection($section_id);
            $d['ex'] = $exam = $this->exam->find($exam_id);
            $d['tex'] = "tex{$exam->term}";
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

        if ($year === null) {
            if ($student_exists && $years->isNotEmpty()) {
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

        if ($this->assessment->getAssessments([])->where(['exam.year' => $year])->isEmpty())
            return redirect()->route('assessments.year_selector', $student_id)->with("flash_info", "Oops! There are no published exams assessments for the selected year $year");

        return redirect()->route('assessments.show', [$student_id, $req->year]);
    }

    public function show($student_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if (auth()->id() != $student_id && !Mk::userIsTeamSATCL() && !Mk::userIsMyChild($student_id, auth()->id()))
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
        $d['skills'] = $this->exam->getSkillByClassType() ?: null;

        return view('pages.support_team.assessments.show.index', $d);
    }

    public function progressive_select(Request $req)
    {
        return redirect()->route('assessments.progressive', [$req->exam_id, $req->my_class_id, $req->section_id]);
    }

    public function update(Request $req, $exam_id, $class_id, $subject_id, $section_id = null)
    {
        if ($this->exam->isLocked($exam_id) && !Mk::headSA(auth()->id()))
            return Mk::jsonUpdateDenied();

        $exam = $this->exam->find($exam_id);

        /** Deny Updating Records, if Exam Edit is disabled, and user is not team Super Adnim */
        if (Mk::isDisabled($exam->editable) && !Mk::userIsTeamSA())
            return Mk::jsonUpdateDenied();

        $p = $section_id == null
            ? ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $this->year]
            : ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];

        $d = $all_st_ids = [];

        $asmnt_records = $this->assessment->getRecords($p);
        $class_type = $this->my_class->findTypeByClass($class_id);

        $mks = $req->all();

        $number_of_cw = 10; // Course work
        $number_of_hw = 5;  // Home work
        $number_of_tt = 3;  // Topic Test

        // NOTE: Consider this '$mks['cw_'][$as->id][$i]' for example as an array of arrays. The $i is the index of the array.
        // The $i is used to make the key of the array unique. This is because the same key is used for all the arrays.
        // If you change something that affects the $i such as '$number_of_cw', you must also change that in the view. See 'resources\views\pages\support_team\assessments\edit.blade.php'.
        foreach ($asmnt_records as $as) {
            $all_st_ids[] = $as->student_id;

            $d['id'] = $as->id;

            // Course work
            for ($i = 1; $i <= $number_of_cw; $i++) {
                $cw_i = "cw$i";
                $d["cw$i"] = ${$cw_i} = $mks['cw_'][$as->id][$i] ?? null;
            }

            $cw_sum = 0;
            $all_cw_null = true;
            for ($i = 1; $i <= $number_of_cw; $i++) {
                $cw_i = "cw$i";
                if (${$cw_i} !== null) {
                    $all_cw_null = false;
                    $cw_sum += ${$cw_i};
                }
            }

            $cw_avg = $all_cw_null ? null : $cw_sum / $number_of_cw;

            // Home work
            for ($i = 1; $i <= $number_of_hw; $i++) {
                $hw_i = "hw$i";
                $d["hw$i"] = ${$hw_i} = $mks['hw_'][$as->id][$i] ?? null;
            }

            $hw_sum = 0;
            $all_hw_null = true;
            for ($i = 1; $i <= $number_of_hw; $i++) {
                $hw_i = "hw$i";
                if (${$hw_i} !== null) {
                    $all_hw_null = false;
                    $hw_sum += ${$hw_i};
                }
            }

            $hw_avg = $all_hw_null ? null : $hw_sum / $number_of_hw;

            // Topic Test
            for ($i = 1; $i <= $number_of_tt; $i++) {
                $tt_i = "tt$i";
                $d["tt$i"] = ${$tt_i} = $mks['tt_'][$as->id][$i] ?? null;
            }

            $tt_sum = 0;
            $all_tt_null = true;
            for ($i = 1; $i <= $number_of_tt; $i++) {
                $tt_i = "tt$i";
                if (${$tt_i} !== null) {
                    $all_tt_null = false;
                    $tt_sum += ${$tt_i};
                }
            }

            $tt_avg = $all_tt_null ? null : $tt_sum / $number_of_tt;

            $tca = $cw_avg === null && $hw_avg === null && $tt_avg === null
                ? null
                : round($cw_avg + $hw_avg + $tt_avg, 1);

            $d['tca'] = $tca;
            $d['exm'] = $exm = $mks["exm_{$as->id}"] ?? null;

            // Total
            $total = ($tca === null && $exm === null) ? null : $tca + $exm;
            $d["tex{$exam->term}"] = $total > 100
                ? $d['t1'] = $d['t2'] = $d['t3'] = $d['t4'] = $d['tca'] = $exm = null
                : $total;
            // Grade
            $grade = $this->assessment->getGrade($total, $class_type->id);
            $d['grade_id'] = $grade->id ?? null;
            // Subject position
            $d['sub_pos'] = $this->assessment->getSubPos($as->student_id, $exam, $class_id, $subject_id, $this->year);

            $d_collection[] = $d;
        }

        $this->assessment->massUpdateRecs($d_collection);

        /* Assessment Record Update */
        unset($p['subject_id']);

        foreach (array_unique($all_st_ids) as $st_id) {
            $d2['student_id'] = $st_id;
            $d2['ave'] = $this->assessment->getAssessmentAvgTerm($exam, $st_id, $class_id, $section_id, $this->year);
            $d2['class_ave'] = $this->assessment->getClassAvg($exam, $class_id, $this->year);
            $d2['total'] = $this->assessment->getAssessmentTotalTerm($exam, $st_id, $class_id, $this->year);
            $d2['pos'] = $this->assessment->getStudentPos($st_id, $exam_id, $class_id, $this->year, $exam->ca_student_position_by_value, $section_id);

            $d2_collection[] = $d2;
        }

        $this->assessment->massUpdateRecs($d2_collection, ['student_id']);
        /*Assessment Record End*/

        return Mk::jsonUpdateOk();
    }

    public function print_assessments($exam_id, $class_id, $subject_id, $year, $section_id = null)
    {
        switch ($section_id) {
            case null:
                $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $year];
                $wh = ['my_class_id' => $class_id, 'exam_id' => $exam_id, 'year' => $this->year];
                break;
            default:
                $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];
                $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $year];
                break;
        }

        $marks_updated_at = $this->mark->getRecordValue($d, 'updated_at');
        $asmnts_updated_at = $this->assessment->getRecordValue($d, 'updated_at');
        $d2['year'] = $year;
        $d2['assessments'] = $assessment_records = $this->assessment->getRecords($d);

        if ($assessment_records->isEmpty())
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
        $d2['tex'] = $tex = "tex{$exam->term}";
        $d2['ex'] = $this->exam->find($exam_id);
        $d2['stds_texs'] = $this->assessment->getStudentsTotals($d, $tex);
        $d2['section_id'] = $section_id;

        return view('pages.support_team.assessments.print', $d2);
    }

    public function print_detailed($student_id, $exam_id, $year)
    {
        /* Prevent assessment print if super admin did not allow it - this applies to all users except super admin */
        if (!Mk::getSetting('allow_assessmentsheet_print') && !Mk::userIsSuperAdmin())
            return redirect()->back()->with('pop_error', __('msg.denied'));

        /* Prevent Other Students/Parents from viewing Result of others */
        if (auth()->id() != $student_id && !Mk::userIsTeamSAT() && !Mk::userIsMyChild($student_id, auth()->id()))
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
        $d['tex'] = "tex{$exam->term}";
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first();
        $d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);
        $d['ct'] = $d['class_type']->code;
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['exam_id'] = $exam_id;
        $d['skills'] = $this->exam->getSkillByClassType() ?: null;

        return view('pages.support_team.assessments.print.detailed', $d);
    }

    public function print_minimal($student_id, $exam_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if (auth()->id() != $student_id && !Mk::userIsTeamSAT() && !Mk::userIsMyChild($student_id, auth()->id()))
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
        $d['tex'] = "tex{$exam->term}";
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first();
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);
        $d['year'] = $year;
        $d['skills'] = $this->exam->getSkillByClassType() ?: null;

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
