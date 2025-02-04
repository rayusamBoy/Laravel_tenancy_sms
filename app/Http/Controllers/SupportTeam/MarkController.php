<?php

namespace App\Http\Controllers\SupportTeam;

use App\Exports\MarksExport;
use App\Helpers\Mk;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mark\MarkSelector;
use App\Imports\MarksImport;
use App\Repositories\AssessmentRepo;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Rules\HasOrderedClassExamSession;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MarkController extends Controller implements HasMiddleware
{
    protected $my_class, $exam, $student, $year, $user, $mark, $assessment;

    public function __construct(MyClassRepo $my_class, ExamRepo $exam, StudentRepo $student, MarkRepo $mark, AssessmentRepo $assessment)
    {
        $this->exam = $exam;
        $this->mark = $mark;
        $this->student = $student;
        $this->my_class = $my_class;
        $this->assessment = $assessment;
        $this->year = Mk::getCurrentSession();
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('superAdmin', only: ['destroy', 'batch_delete']),
            new Middleware('teamSAT', except: ['show', 'year_selected', 'year_selector', 'print_view']),
        ];
    }

    public function index()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['selected'] = false;

        return view('pages.support_team.marks.index', $d);
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

        if ($this->getExams($year)->isEmpty())
            return redirect()->route('marks.year_selector', $student_id)->with("flash_info", "Oops! There are no published exam result(s) for the selected year - $year");

        return redirect()->route('marks.show', [$student_id, $year]);
    }

    public function show($student_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if (auth()->id() != $student_id && !Mk::userIsTeamSATCL() && !Mk::userIsMyChild($student_id, auth()->id()))
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));

        if (Mk::examIsLocked() && !Mk::userIsTeamSA()) {
            Session::put('marks_url', route('marks.show', [Mk::hash($student_id), $year]));

            if (!$this->checkPinVerified($student_id))
                return redirect()->route('pins.enter', Mk::hash($student_id));
        }

        if (!$this->verifyStudentExamYear($student_id, $year))
            return $this->noStudentRecord();

        $wh = ['student_id' => $student_id, 'year' => $year];

        $d['marks'] = $this->exam->getMark($wh);
        $d['exam_records'] = $exr = $this->exam->getRecordsWithRelations(['section'], $wh);
        $d['exams'] = $this->getExams($year);
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->latest()->first() ?? $this->student->getRecord2(['user_id' => $student_id])->latest()->first();
        $d['my_class'] = $this->my_class->getMC(['id' => $exr->last()->my_class_id])->first();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;

        return view('pages.support_team.marks.show.index', $d);
    }

    public function getExams($year)
    {
        $exams = $this->exam->getExam(['year' => $year], false);

        return (Mk::userIsTeamSA()) ? $exams : $exams->where('published', true);
    }

    public function print_view($student_id, $exam_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if ((auth()->id() != $student_id && !Mk::userIsTeamSAT() && !Mk::userIsMyChild($student_id, auth()->id())) && (!Mk::marksheetPrintNotAllowed() || Mk::userIsSuperAdmin()))
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));

        if (Mk::examIsLocked() && !Mk::userIsTeamSA()) {
            Session::put('marks_url', route('marks.show', [Mk::hash($student_id), $year]));

            if (!$this->checkPinVerified($student_id))
                return redirect()->route('pins.enter', Mk::hash($student_id));
        }

        if (!$this->verifyStudentExamYear($student_id, $year))
            return $this->noStudentRecord();

        $wh = ['student_id' => $student_id, 'exam_id' => $exam_id, 'year' => $year];

        $d['marks'] = $this->exam->getMark($wh);
        $d['exr'] = $exr = $this->exam->getRecordsWithRelations(['section'], $wh)->first();
        $d['my_class'] = $mc = $this->my_class->find($exr->my_class_id);
        $d['section'] = $this->my_class->findSection($exr->section_id);
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = "tex{$exam->term}";
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first() ?? $this->student->getRecord2(['user_id' => $student_id])->first();
        $d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);
        $d['ct'] = $d['class_type']->code;
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['exam_id'] = $exam_id;
        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;
        $d['settings'] = Mk::getSettings();

        return view('pages.support_team.marks.print.index', $d);
    }

    public function selector(MarkSelector $req)
    {
        $data = $req->only(['exam_id', 'my_class_id', 'section_id', 'subject_id']);
        $d2 = $req->only(['exam_id', 'my_class_id', 'section_id']);
        $d = $req->only(['my_class_id', 'section_id']);
        $d['session'] = $data['year'] = $d2['year'] = $this->year;
        $exam = $this->exam->find($req->exam_id);

        $class_type = $this->my_class->findTypeByClass($req->my_class_id);
        // If the exam do not belong to the selected class
        if ($exam->class_type_id != $class_type->id)
            return back()->with('flash_danger', __('msg.invalid_exam_and_class'));

        $sub_recs = $this->my_class->getSubjectRecord(['subject_id' => $req->subject_id])->where('subject.my_class_id', $req->my_class_id);

        if ($req->section_id == null && $sub_recs->whereNull('section_id')->whereNotNull('students_ids')->count() > 0)
            $students = $this->student->getRecordByUserIDs(unserialize($sub_recs->students_ids))->get();
        elseif (($req->section_id == null && $sub_recs->whereNull('section_id')->whereNull('students_ids')->count() > 0) || ($req->section_id != null && $sub_recs->whereNotNull('students_ids')->count() > 0))
            return back()->with('pop_error', __('msg.rnf'));
        else
            $students = $this->student->getRecord($d)->get();

        if ($students->count() < 1)
            return back()->with('pop_error', __('msg.rnf'));

        foreach ($students as $s) {
            $data['student_id'] = $d2['student_id'] = $s->user_id;
            $data['section_id'] = $d2['section_id'] = $s->section_id;
            $mark = $this->exam->createMark($data);
            $this->exam->createRecord($d2);

            // If the exam is of type terminal or annual, create associated assessment records
            if (Mk::isTerminalExam($exam->category_id) || Mk::isAnnualExam($exam->category_id)) {
                $data['mark_id'] = $mark->id;
                $data['assessment_id'] = $this->assessment->getAssessment(['exam_id' => $req->exam_id])->id;
                $this->assessment->createRecord($data);
                unset($data['mark_id'], $data['assessment_id']);
            }
        }

        return redirect()->route('marks.manage', [$exam, $req->my_class_id, $req->subject_id, $req->section_id]);
    }

    public function manage($exam_id, $class_id, $subject_id, $section_id = null)
    {
        $d = $section_id == null ? ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $this->year] : ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];

        $marks_created_at = $this->exam->getRecordValue($d, 'created_at');
        $marks_updated_at = $this->exam->getRecordValue($d, 'updated_at');

        $d['marks'] = $this->exam->getMark($d)->whereNotNull('user');
        if ($d['marks']->count() < 1)
            return $this->noStudentRecord();

        $d['m'] = $d['marks']->first();
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['exam'] = $this->exam->getById($exam_id);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['selected'] = true;
        $d['class_type'] = $this->my_class->findTypeByClass($class_id);
        $d['created_at'] = Mk::onlyDateFormat($marks_created_at);
        $d['updated_at'] = Mk::onlyDateFormat($marks_updated_at);
        $d['subjects'] = $this->my_class->getAllSubjects();

        if (Mk::userIsTeacher())
            $d['subjects'] = $this->my_class->findSubjectByRecord(auth()->id(), $class_id);

        if ($section_id == null)
            $d['section_id_is_null'] = false;

        return view('pages.support_team.marks.manage', $d);
    }

    public function update(Request $req, $exam_id, $class_id, $subject_id, $section_id = null)
    {
        $exam = $this->exam->find($exam_id);

        if ($this->exam->isLocked($exam_id) or (Mk::isDisabled($exam->editable) and !Mk::userIsTeamSA()))
            return Mk::jsonUpdateDenied();

        switch ($section_id) {
            case NULL:
                $students_ids = $this->my_class->getSubjectRecord(['subject_id' => $subject_id, 'section_id' => NULL])->value('students_ids');
                $p = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'subject_id' => $subject_id, 'year' => $this->year];
                $wh = ['my_class_id' => $class_id, 'exam_id' => $exam_id, 'year' => $this->year];
                $marks = $this->exam->getMarkByIds(unserialize($students_ids))->where($p)->get();
                break;
            default:
                $p = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];
                $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];
                $marks = $this->exam->getMark($p)->whereNotNull('user');
                break;
        }

        $d = $d3 = $all_st_ids = $section_ids = [];

        $class_type = $this->my_class->findTypeByClass($class_id);

        $mks = $req->all();

        /** Exam marks, total marks, grade, subject position **/
        foreach ($marks as $mk) {
            $all_st_ids[] = $mk->student_id;
            $section_ids[$mk->student_id] = $mk->section_id;

            $d['id'] = $mk->id;
            $d['exm'] = $exm = $mks["exm_{$mk->id}"];
            $d['exam_id'] = $exam_id;
            $d["tex{$exam->term}"] = $total = $exm;

            if ($total > 100)
                $d["tex{$exam->term}"] = $d['exm'] = NULL;

            $grade = $this->mark->getGrade($total, $class_type->id);
            $d['grade_id'] = $grade?->id;
            $d['sub_pos'] = $this->mark->getSubPos($mk->student_id, $exam, $class_id, $subject_id, $this->year);

            $d_collection[] = $d;
        }

        $this->exam->massUpdateMark($d_collection);

        /** Marks update ends */

        /** Update Assessments records exam marks for terminal or annual exam */
        if (Mk::isTerminalExam($exam->category_id) || Mk::isAnnualExam($exam->category_id)) {
            foreach ($marks->sortBy('user.name') as $mk) {
                $d2 = $d;
                $d2['mark_id'] = $mk->id;
                $d2["tex{$exam->term}"] = NULL;
                $evaluated_val = $this->assessment->getValueOutOfQuantity($mks["exm_{$mk->id}"], $exam->tdt_denominator);
                $d2['exm'] = ($evaluated_val == 0) ? NULL : $evaluated_val;

                // Remove 'id's from data2 collection
                unset($d2['id']);
                $d2_collection[] = $d2;
            }

            $this->assessment->massUpdateRecs($d2_collection, ['mark_id']);
        }

        $sub_ids = $this->mark->getSubjectIDs($wh);
        $subjects = $this->my_class->getSubjectsByIDs($sub_ids);
        $marks2 = $this->exam->getMark($wh);

        if (isset($class_id)) {
            $class_type_id = $this->my_class->find($class_id)->class_type_id;
            $subjects_considered = $this->my_class->findType($class_type_id)->value('subjects_considered');
        }

        /* Exam Record Update */
        $points = [];
        foreach (array_unique($all_st_ids) as $st_id) {
            // Mass update Unique by's
            $d3['student_id'] = $st_id;
            $d3['exam_id'] = $exam_id;
            // Data to update
            $d3['total'] = $this->mark->getExamTotalTerm($exam, $st_id, $class_id, $this->year);
            $d3['ave'] = $ave = $this->mark->getLimitedExamAvgTerm($exam, $st_id, $class_id, $section_id ?? $section_ids[$st_id], $this->year, $subjects_considered);
            $d3['grade_id'] = $grade = $this->mark->getGrade($ave, $class_type->id)->id ?? NULL;
            $d3['class_ave'] = $this->mark->getClassAvg($exam, $class_id, $this->year) ?? NULL;
            // Position Section wise
            $d3['pos'] = $this->exam->getStudentPos($st_id, $exam->id, $class_id, $this->year, $exam->exam_student_position_by_value, $section_id ?? $section_ids[$st_id]);
            // Get all points
            foreach ($subjects as $sub) {
                $points[] = $marks2->where('student_id', $st_id)->where('subject_id', $sub->id)->first()->grade->point ?? NULL;
                $grade_points[] = $marks2->where('student_id', $st_id)->where('subject_id', $sub->id)->where('subject.core', 1)->first()->grade->credit ?? NULL;
            }

            $d3['points'] = $total_points = $this->mark->getExtractedSumOf($points, 0, $subjects_considered);
            $d3['gpa'] = $this->mark->getExtractedSumOf($grade_points, 0, $subjects_considered) / $subjects_considered;
            $d3['division_id'] = Mk::getDivision($total_points, $class_type_id)->id ?? NULL;

            $d3_collection[] = $d3;
            unset($points);
        }

        $this->exam->massUpdateExamRec($d3_collection, ['student_id', 'exam_id']);
        /*Exam Record End*/

        foreach (array_unique($all_st_ids) as $st_id) {
            $d4['student_id'] = $st_id;
            $d4['exam_id'] = $exam->id;
            $d4['my_class_id'] = $class_id;
            // Position Class wise
            $d4['class_pos'] = $this->exam->getStudentPos($st_id, $exam->id, $class_id, $this->year, $exam->exam_student_position_by_value);

            $d4_collection[] = $d4;
        }

        $this->exam->massUpdateExamRec($d4_collection, ['student_id', 'exam_id', 'my_class_id']);

        return Mk::jsonUpdateOk();
    }

    public function batch()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        return view('pages.support_team.marks.batch.index', $d);
    }

    public function batch_template(Request $req)
    {
        set_time_limit(1800); // Extend excecution time from normal 1 minute to 30 minutes

        $data['class'] = $class = $this->my_class->find($req->my_class_id);
        $data['exam'] = $exam = $this->exam->find($req->exam_id);
        $data['class_type_id'] = $class_type_id = $class->class_type_id;

        // If the selected class's class type is not equal to the exam class type
        if ($exam->class_type_id != $class_type_id)
            return back()->with('flash_error', __('msg.invalid_exam_and_class'));

        if ($this->my_class->getSubject(['my_class_id' => $class->id])->count() < 0)
            return back()->with('flash_error', __('msg.assign_subjects_to_class'));

        $d = ['exam_id' => $exam->id, 'my_class_id' => $class->id, 'year' => $this->year];

        $p = $this->student->getRecord(['my_class_id' => $req->my_class_id, 'session' => $this->year])->get();
        if ($p->count() <= 0)
            return redirect()->back()->with('flash_danger', __('msg.srnf'));

        $assessment_id = $this->assessment->getAssessment(['exam_id' => $req->exam_id])->id ?? NULL;

        foreach ($this->my_class->getSections(['my_class_id' => $class->id]) as $sec) {

            $subjects_with_recs = $this->my_class->getSubjectWithRecsWhereHas($sec->id, $class->id);

            foreach ($p->where('section_id', $sec->id)->all() as $st_rec) {

                foreach ($subjects_with_recs as $sub) {
                    $students_ids = $this->my_class->whereSubjectRecord(['subject_id' => $sub->id])->where('students_ids', '!=', NULL)->value('students_ids');

                    if ($students_ids != NULL) {
                        $students_ids = unserialize($students_ids);

                        array_walk($students_ids, function (&$arr) { // note the reference (&)
                            $arr = intval($arr); // Convert each array value to integer
                        });

                        if (in_array((int) $st_rec->user_id, $students_ids)) {
                            $mk = $this->mark->firstOrCreate($st_rec->user_id, $class->id, $sec->id, $exam->id, $this->year, $sub->id);
                            $this->exam->firstOrCreateExmRec($st_rec->user_id, $class->id, $sec->id, $exam->id, $this->year);
                            if (Mk::isTerminalExam($exam->category_id) || Mk::isAnnualExam($exam->category_id))
                                $this->assessment->firstOrCreateRec($st_rec->user_id, $class->id, $sec->id, $exam->id, $this->year, $sub->id, $mk->id, $assessment_id);
                        }
                    } else {
                        $mk = $this->mark->firstOrCreate($st_rec->user_id, $class->id, $sec->id, $exam->id, $this->year, $sub->id);
                        $this->exam->firstOrCreateExmRec($st_rec->user_id, $class->id, $sec->id, $exam->id, $this->year);
                        if (Mk::isTerminalExam($exam->category_id) || Mk::isAnnualExam($exam->category_id))
                            $this->assessment->firstOrCreateRec($st_rec->user_id, $class->id, $sec->id, $exam->id, $this->year, $sub->id, $mk->id, $assessment_id);
                    }
                }

                $old_section_id = $this->exam->getRecord(['student_id' => $st_rec->user_id, 'exam_id' => $exam->id, 'my_class_id' => $class->id])->value('section_id');
                // If there exists old student record for the particular exam, and class (ie., section changes for particular level)
                if ($req->delete_old_section_record == 1 and $old_section_id != $st_rec->section_id) {
                    // delete old marks and exam records
                    $where = ['student_id' => $st_rec->user_id, 'exam_id' => $exam->id, 'my_class_id' => $class->id, 'section_id' => $old_section_id];
                    $this->exam->deleteRecord($where);
                    $this->exam->deleteMark($where);
                }
            }
        }

        // Delay excecution of followed statement(s) until the database is approximated complete updated
        sleep(30);
        $data['marks'] = $this->exam->getMark($d);
        $data['year'] = $this->year;
        $data['subjects'] = Mk::getSubjects($class->id);
        $delimiter = Mk::getDelimiter();

        return Excel::download(new MarksExport($data), ucfirst(strtolower(str_replace(' ', '_', "{$class->name}{$delimiter}{$exam->name}{$delimiter}{$exam->year}.xlsx"))));
    }

    public function batch_upload(Request $req)
    {
        $template = $req->file('template');
        $f = Mk::getFileMetaData($template);
        // Remove '.' and extension by replacing it with empty string
        $name = str_replace('.' . $f['ext'], '', $f['name']);
        $req['template_name'] = $name;

        Validator::make($req->toArray(), [
            'template' => 'required|file|mimes:xlsx,xlx|max:4096',
            'template_name' => new HasOrderedClassExamSession,
        ], [], ['template' => 'marks template'])->validate();

        if ($req->hasFile('template')) {
            $delimiter = Mk::getDelimiter();
            $exploded_name = explode($delimiter, $name);
            $data['year'] = $year = "$exploded_name[2]-$exploded_name[3]";
            // Get class_id and exam_id by class and exam names.
            $class = $this->my_class->get(['name' => str_replace('_', ' ', $exploded_name[0])]);
            $data['class_id'] = $class_id = $class->value('id');
            $exam = $this->exam->getExam(['name' => str_replace('_', ' ', $exploded_name[1]), 'year' => $year]);
            $data['exam_id'] = $exam_id = $exam->value('id');

            if ($exam->value('class_type_id') != $class->value('class_type_id'))
                return Mk::json(__('msg.invalid_exam_and_class'), false);

            if (!$this->exam->exists((int) $exam_id))
                return Mk::json(__('msg.ernf'), false);

            $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'year' => $year];
            $data['st_mark'] = $this->exam->getMark($d);
            $data['session'] = $this->year;

            Excel::import(new MarksImport($data), request()->file('template'));
            return Mk::jsonStoreOk();
        }
    }

    public function batch_update(Request $req)
    {
        set_time_limit(1800); // Extend excecution time from normal 1 minute to 30 minutes

        $exam_id = $req->exam_id;
        $class_id = $req->my_class_id;
        $d = $d3 = $all_st_ids = $section_ids = [];
        $class_subjects = $this->my_class->getSubject(['my_class_id' => $class_id])->get();
        $class_sections = $this->my_class->getSections(['my_class_id' => $class_id]);
        $class_type = $this->my_class->findTypeByClass($class_id);
        $exam = $this->exam->find($exam_id);

        if ($exam->class_type_id != $class_type->id)
            return back()->with('flash_error', __('msg.invalid_exam_and_class'));

        if ($this->exam->getMark($req->only(['exam_id', 'my_class_id']))->isEmpty())
            return back()->with('flash_info', __('msg.rnf'));

        foreach ($class_sections as $sec) {
            $wh = ['my_class_id' => $class_id, 'section_id' => $sec->id, 'exam_id' => $exam_id, 'year' => $this->year];
            $p = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $sec->id, 'year' => $this->year];

            $marks_p = $this->exam->getMark($p);

            foreach ($class_subjects as $sub) {
                $marks = $marks_p->where('subject_id', $sub->id);

                /** Total marks, grade, subject position **/
                foreach ($marks as $mk) {
                    $all_st_ids[] = $mk->student_id;
                    $section_ids[$mk->student_id] = $mk->section_id;

                    $d['id'] = $mk->id;
                    $d["tex{$exam->term}"] = $total = $mk->exm;

                    if ($total > 100)
                        $d["tex{$exam->term}"] = $d['exm'] = NULL;

                    $grade = $this->mark->getGrade($total, $class_type->id);
                    $d['grade_id'] = $grade?->id;
                    $d['sub_pos'] = $this->mark->getSubPos($mk->student_id, $exam, $class_id, $sub->id, $this->year);

                    $d_collection[] = $d;
                }

                /** Marks update ends */

                /** Update Assessments records exam marks for terminal or annual exam */
                if (Mk::isTerminalExam($exam->category_id) || Mk::isAnnualExam($exam->category_id)) {
                    foreach ($marks as $mk) {
                        $d2 = $d;
                        $d2['mark_id'] = $mk->id;
                        $d2["tex{$exam->term}"] = NULL;
                        $evaluated_val = $this->assessment->getValueOutOfQuantity($mk->exm, $exam->tdt_denominator);
                        $d2['exm'] = ($evaluated_val == 0) ? NULL : $evaluated_val;

                        // Remove 'id's from data2 collection
                        unset($d2['id']);
                        $d2_collection[] = $d2;
                    }
                }
            }

            $sub_ids = $this->mark->getSubjectIDs($wh);
            $subjects = $this->my_class->getSubjectsByIDs($sub_ids);
            $marks2 = $this->exam->getMark($wh);

            if (isset($class_id)) {
                $class_type_id = $this->my_class->find($class_id)->class_type_id;
                $subjects_considered = $this->my_class->findType($class_type_id)->subjects_considered;
            }

            /** Exam Record Update */
            $points = [];
            foreach (array_unique($all_st_ids) as $st_id) {
                // Mass update Unique by's
                $d3['student_id'] = $st_id;
                $d3['exam_id'] = $exam_id;
                // Data to update
                $d3['total'] = $this->mark->getExamTotalTerm($exam, $st_id, $class_id, $this->year);
                $d3['ave'] = $ave = $this->mark->getLimitedExamAvgTerm($exam, $st_id, $class_id, $sec->id, $this->year, $subjects_considered);
                $d3['grade_id'] = $grade = $this->mark->getGrade($ave, $class_type->id)->id ?? NULL;
                $d3['class_ave'] = $this->mark->getClassAvg($exam, $class_id, $this->year) ?? NULL;
                // Position Section wise
                $d3['pos'] = $this->exam->getStudentPos($st_id, $exam->id, $class_id, $this->year, $exam->exam_student_position_by_value, $section_ids[$st_id]);
                // Get all points
                foreach ($subjects as $sub) {
                    $points[] = $marks2->where('student_id', $st_id)->where('subject_id', $sub->id)->where('subject.core', 1)->first()->grade->point ?? NULL;
                    $grade_points[] = $marks2->where('student_id', $st_id)->where('subject_id', $sub->id)->where('subject.core', 1)->first()->grade->credit ?? NULL;
                }

                $d3['points'] = $total_points = $this->mark->getExtractedSumOf($points, 0, $subjects_considered);
                $grade_points_sum_extracted = $this->mark->getExtractedSumOf($grade_points, 0, $subjects_considered);
                $d3['gpa'] = $grade_points_sum_extracted == NULL ? NULL : $grade_points_sum_extracted / $subjects_considered;
                $d3['division_id'] = Mk::getDivision($total_points, $class_type_id)->id ?? NULL;

                $d3_collection[] = $d3;
                unset($points);
                unset($grade_points);
            }

            /** Exam Record update ends */
        }

        // Mass update the array collections
        $this->exam->massUpdateMark($d_collection);
        $this->exam->massUpdateExamRec($d3_collection, ['student_id', 'exam_id']);
        if (Mk::isTerminalExam($exam->category_id) || Mk::isAnnualExam($exam->category_id))
            $this->assessment->massUpdateRecs($d2_collection, ['mark_id']);

        foreach (array_unique($all_st_ids) as $st_id) {
            $d4['student_id'] = $st_id;
            $d4['exam_id'] = $exam->id;
            $d4['my_class_id'] = $class_id;
            // Position Class wise
            $d4['class_pos'] = $this->exam->getStudentPos($st_id, $exam->id, $class_id, $this->year, $exam->exam_student_position_by_value);

            $d4_collection[] = $d4;
        }

        $this->exam->massUpdateExamRec($d4_collection, ['student_id', 'exam_id', 'my_class_id']);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function batch_delete(Request $req)
    {
        Validator::make($req->toArray(), [
            'exam_id' => 'required|integer',
            'my_class_id' => 'required|integer',
        ], [], ['exam_id' => 'exam', 'my_class_id' => 'class'])->validate();

        $class_type = $this->my_class->findTypeByClass($req->my_class_id);
        $exam = $this->exam->find($req->exam_id);
        if ($exam->class_type_id != $class_type->id)
            return back()->with('flash_error', __('msg.invalid_exam_and_class'));

        if ($req->section_id == null && $req->subject_id == null) {
            $d = ['exam_id' => $req->exam_id, 'my_class_id' => $req->my_class_id, 'year' => $this->year];
        } elseif ($req->section_id == null && $req->subject_id != null) {
            $d = ['exam_id' => $req->exam_id, 'my_class_id' => $req->my_class_id, 'subject_id' => $req->subject_id, 'year' => $this->year];
        } elseif ($req->section_id != null && $req->subject_id == null) {
            $d = ['exam_id' => $req->exam_id, 'my_class_id' => $req->my_class_id, 'section_id' => $req->subject_id, 'year' => $this->year];
        } else {
            $d = ['exam_id' => $req->exam_id, 'my_class_id' => $req->my_class_id, 'subject_id' => $req->subject_id, 'section_id' => $req->section_id, 'year' => $this->year];
        }

        $marks = $this->exam->getMark($d);

        if ($marks->isEmpty())
            return back()->with('flash_info', __('msg.rnf'));
        else
            $return_value = $this->exam->deleteMark($d);

        return back()->with('flash_success', __('msg.delete_ok') . ' with ' . $return_value . ' affected rows.');
    }

    public function comment_update(Request $req, $exam_id)
    {
        if ($this->exam->isLocked($exam_id))
            return Mk::jsonUpdateDenied();

        $d = Mk::userIsTeamSA() ? $req->only(['t_comment', 'p_comment']) : $req->only(['t_comment']);

        $this->exam->updateRecord(['exam_id' => $exam_id], $d);

        return Mk::jsonUpdateOk();
    }

    public function skills_update(Request $req, $skill, $exr_id)
    {
        $d = [];
        if ($skill == 'AF' || $skill == 'PS') {
            $sk = strtolower($skill);
            $d[$skill] = implode(',', $req->$sk);
        }

        $this->exam->updateRecord(['id' => $exr_id], $d);

        return Mk::jsonUpdateOk();
    }

    public function bulk($class_id = NULL, $section_id = NULL)
    {
        $exams_exists = $this->exam->isNotEmpty();
        if (!$exams_exists)
            return $this->noExamsRecord();

        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        if ($class_id && $section_id) {
            $d['sections'] = $this->my_class->getAllSections()->where('my_class_id', $class_id);
            $d['students'] = $st = $this->student->getRecord(['my_class_id' => $class_id, 'section_id' => $section_id])->get()->whereNotNull('user')->sortBy('user.name');
            if ($st->count() < 1)
                return redirect()->route('marks.bulk')->with('flash_danger', __('msg.srnf'));

            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
        }

        return view('pages.support_team.marks.bulk', $d);
    }

    public function bulk_select(Request $req)
    {
        return redirect()->route('marks.bulk', [$req->my_class_id, $req->section_id]);
    }

    public function select_year(Request $req)
    {
        return Mk::goToRoute(['marks.tabulation', $req->year]);
    }

    public function tabulation_select(Request $req)
    {
        return redirect()->route('marks.tabulation', [$req->exam_id, $req->my_class_id, $req->section_id, $req->year]);
    }

    public function tabulation($exam_id = null, $class_id = null, $section_id = null, $year = null)
    {
        set_time_limit(300); // Extend excecution time from normal 1 minute to 5 minutes

        $d['my_classes'] = $this->my_class->all();
        $d['years'] = $this->exam->getYears();
        $d['selected'] = false;
        $d['year'] = $year;

        if ($class_id && $exam_id) {
            $class_type = $this->my_class->findTypeByClass($class_id);
            $exam = $this->exam->find($exam_id);
            if ($exam->class_type_id != $class_type->id)
                return back()->with('flash_error', __('msg.invalid_exam_and_class'));

            switch ($section_id) {
                // If request is for all sections, ie., whole class
                case "all":
                    $wh = ['my_class_id' => $class_id, 'exam_id' => $exam_id, 'year' => $this->year];

                    $sub_ids = $this->mark->getSubjectIDs($wh);
                    $st_ids = $this->mark->getStudentIDs($wh);

                    if (count($sub_ids) < 1 or count($st_ids) < 1)
                        return Mk::goWithDanger('marks.tabulation', __('msg.srnf'));

                    $d['exr'] = $exr = $this->exam->getRecord($wh);

                    if ($exr->isEmpty())
                        return Mk::goWithDanger('marks.tabulation', __('msg.ernf'));

                    $d['exams'] = $this->exam->getExam(['year' => $year], false);
                    $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
                    $d['students'] = $this->student->getRecordByUserIDs2($st_ids)->get()->whereNotNull('user')->sortBy('user.name');
                    $d['sections'] = $this->my_class->getAllSections();
                    $d['selected'] = TRUE;
                    $d['section_id'] = $section_id;
                    $d['exam_id'] = $exam_id;
                    $d['marks'] = $this->exam->getMark($wh);
                    $d['my_class'] = $this->my_class->find($class_id);
                    $d['ex'] = $exam = $this->exam->find($exam_id);
                    $d['tex'] = "tex{$exam->term}";
                    $d['grades'] = $this->exam->getGrades();
                    break;
                default:
                    $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $year];

                    $sub_ids = $this->mark->getSubjectIDs($wh);
                    $st_ids = $this->mark->getStudentIDs($wh);

                    if (count($sub_ids) < 1 || count($st_ids) < 1)
                        return Mk::goWithDanger('marks.tabulation', __('msg.srnf'));

                    $d['exr'] = $exr = $this->exam->getRecord($wh);

                    if ($exr->isEmpty())
                        return Mk::goWithDanger('marks.tabulation', __('msg.ernf'));

                    $d['exams'] = $this->exam->getExam(['year' => $year], false);
                    $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
                    $d['students'] = $this->student->getRecordByUserIDs($st_ids)->get()->whereNotNull('user')->sortBy('user.name');
                    $d['sections'] = $this->my_class->getAllSections();
                    $d['selected'] = TRUE;
                    $d['section_id'] = $section_id;
                    $d['exam_id'] = $exam_id;
                    $d['marks'] = $mks = $this->exam->getMark($wh);
                    $d['my_class'] = $mc = $this->my_class->find($class_id);
                    $d['section'] = $this->my_class->findSection($section_id);
                    $d['ex'] = $exam = $this->exam->find($exam_id);
                    $d['tex'] = "tex{$exam->term}";
                    $d['grades'] = $this->exam->getGrades()->pluck("name");
                    break;
            }
        }

        if (isset($class_id))
            $d['class_type_id'] = $this->my_class->find($class_id)->class_type_id;

        return view('pages.support_team.marks.tabulation.index', $d);
    }

    public function print_tabulation($exam_id, $class_id, $section_id, $year)
    {
        set_time_limit(300); // Extend excecution time from normal 1 minute to 5 minutes
        $d['year'] = $year;

        if ($class_id && $exam_id) {
            $class_type = $this->my_class->findTypeByClass($class_id);
            $exam = $this->exam->find($exam_id);
            if ($exam->class_type_id != $class_type->id)
                return back()->with('flash_error', __('msg.invalid_exam_and_class'));

            switch ($section_id) {
                case 'all':
                    $wh = ['my_class_id' => $class_id, 'exam_id' => $exam_id, 'year' => $year];

                    $sub_ids = $this->mark->getSubjectIDs($wh);
                    $st_ids = $this->mark->getStudentIDs($wh);

                    if (count($sub_ids) < 1 or count($st_ids) < 1)
                        return Mk::goWithDanger('marks.tabulation', __('msg.srnf'));

                    $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
                    $d['students'] = $this->student->getRecordByUserIDs($st_ids)->get()->whereNotNull('user')->sortBy('user.name');
                    $d['sections'] = $this->my_class->getAllSections();
                    $d['selected'] = TRUE;
                    $d['my_class_id'] = $class_id;
                    $d['section_id'] = $section_id;
                    $d['exam_id'] = $exam_id;
                    $d['marks'] = $mks = $this->exam->getMark($wh);
                    $d['exr'] = $exr = $this->exam->getRecord($wh);
                    $d['my_class'] = $mc = $this->my_class->find($class_id);
                    $d['ex'] = $exam = $this->exam->find($exam_id);
                    $d['tex'] = "tex{$exam->term}";
                    $d['grades'] = $this->exam->getGrades();
                    break;
                default:
                    $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $year];

                    $sub_ids = $this->mark->getSubjectIDs($wh);
                    $st_ids = $this->mark->getStudentIDs($wh);

                    if (count($sub_ids) < 1 or count($st_ids) < 1)
                        return Mk::goWithDanger('marks.tabulation', __('msg.srnf'));

                    $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
                    $d['students'] = $this->student->getRecordByUserIDs($st_ids)->get()->whereNotNull('user')->sortBy('user.name');
                    $d['my_class_id'] = $class_id;
                    $d['section_id'] = $section_id;
                    $d['exam_id'] = $exam_id;
                    $wh = ['exam_id' => $exam_id, 'my_class_id' => $class_id];
                    $d['marks'] = $this->exam->getMark($wh);
                    $d['exr'] = $this->exam->getRecord($wh);
                    $d['my_class'] = $this->my_class->find($class_id);
                    $d['section'] = $this->my_class->findSection($section_id);
                    $d['ex'] = $exam = $this->exam->find($exam_id);
                    $d['tex'] = "tex{$exam->term}";
                    break;
            }
        }

        if (isset($class_id))
            $d['class_type_id'] = $this->my_class->find($class_id)->class_type_id;

        $d['settings'] = Mk::getSettings();

        return view('pages.support_team.marks.tabulation.print', $d);
    }

    protected function verifyStudentExamYear($student_id, $year = null)
    {
        $years = $this->exam->getStudentExamYears($student_id);
        $student_exists = $this->student->exists($student_id);

        if (!$year) {
            if ($student_exists && $years->count() > 0) {
                $d = ['years' => $years, 'student_id' => Mk::hash($student_id)];

                return view('pages.support_team.marks.select_year', $d);
            }

            return $this->noStudentRecord();
        }

        return ($student_exists && $years->contains('year', $year)) ? true : false;
    }

    protected function noStudentRecord()
    {
        return redirect()->route('dashboard')->with('flash_danger', __('msg.srnf'));
    }

    protected function noExamsRecord()
    {
        return redirect()->route('dashboard')->with('flash_danger', __('msg.ernf'));
    }

    protected function checkPinVerified($st_id)
    {
        return Session::has('pin_verified') && Session::get('pin_verified') == $st_id;
    }

    public function convert()
    {
        return view("pages.support_team.marks.convert");
    }
}
