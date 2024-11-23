<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Mk;
use App\Http\Controllers\Controller;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Validator;

class StatisticsController extends Controller
{
    protected $user, $class, $exam, $payment, $notice, $student, $mark, $year, $assessment;
    public function __construct(UserRepo $user, MyClassRepo $class, ExamRepo $exam, StudentRepo $student, MarkRepo $mark)
    {
        $this->user = $user;
        $this->class = $class;
        $this->exam = $exam;
        $this->student = $student;
        $this->mark = $mark;
        $this->year = Mk::getSetting('current_session');
    }

    public function index($exam_id = "")
    {
        $exams = $this->exam->getPublishedOrderByLatest();
        if ($exams->isEmpty())
            return redirect()->back()->with('flash_error', __('msg.ernf'));

        $exam_id = Mk::decodeHash($exam_id);

        if (!empty($exam_id)) {
            $input = ['exam_id' => $exam_id];
            Validator::make($input, [
                'exam_id' => 'sometimes|nullable|exists:exams,id',
            ], [], ['exam_id' => 'exam'])->validate();
            $d['last_exam'] = $last_exm = $this->exam->getById($exam_id);
        } else {
            $d['last_exam'] = $last_exm = $exams->first();
            $exam_id = $last_exm->id;
        }

        $d['prev_exam_id'] = $exams->where('id', '<', $exam_id)->first()->id ?? null;
        $d['next_exam_id'] = $exams->where('id', '>', $exam_id)->first()->id ?? null;

        if (Mk::userIsParent()) {
            $s_ids = $this->student->getStudentsRecs()->where('my_parent_id', auth()->id())->pluck('user_id');

            $d['exams_recs'] = $this->exam->getRecordsByIds($s_ids)->where('year', $this->year)->get();
            $marks = $this->mark->getByIds($s_ids)->where('year', $this->year)->get();
            if ($marks->count() > 0) {
                $last_exm_marks = $marks->sortByDesc('id')->first();
                $d['last_exam'] = $last_exm = $this->exam->getById($last_exm_marks->exam_id);
                $d['last_exm_marks'] = $marks->where('exam_id', $last_exm->id);
            }
        } elseif (Mk::userIsStudent()) {
            $d['exams_recs'] = $this->exam->getRecordsByStudentId(auth()->id())->where('year', $this->year)->get();
            $d['last_exm_marks'] = $this->mark->getById(auth()->id())->where('exam_id', $last_exm->id)->get();
        } elseif (Mk::userIsTeamSATCL()) {
            $exams_recs = $this->exam->getRecordsById($last_exm->id)->get()->unique('my_class.id')->sortBy('my_class.id');

            // For class performance charts
            $d['classes_names'] = $exams_recs->pluck('my_class.name');
            $d['averages'] = $exams_recs->pluck('class_ave');

            $this_year_exams = $this->exam->getPublished(['year' => $this->year]);

            $d['exams_gpas'] = null;
            $d['exams_names'] = null;

            if ($this_year_exams->isNotEmpty()) {
                $class_types = $this->class->getTypes();
                foreach ($class_types as $type) {
                    foreach ($this_year_exams as $exam) {
                        $exams_names[] = $exam->name;
                        $exams_gpas[] = Mk::getGPA($exam->id, NULL, $type->id, NULL);
                    }
                }

                $d['exams_names'] = json_encode(array_unique($exams_names));
                $d['exams_gpas'] = json_encode(array_values(array_filter($exams_gpas)));
            }

            $d['year'] = $this->year;
        }

        return view('pages.statistics.index', $d);
    }
}
