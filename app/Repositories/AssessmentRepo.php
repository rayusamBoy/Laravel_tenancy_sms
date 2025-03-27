<?php

namespace App\Repositories;

use App\Models\Assessment;
use App\Models\AssessmentRecord;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class AssessmentRepo
{

    protected $assessments_table, $assessments_records_table;
    public function __construct()
    {
        $this->assessments_table = 'assessments';
        $this->assessments_records_table = 'assessments_records';
    }

    /*********** Assessments ***************/

    public function get()
    {
        return Assessment::with(['exam', 'record'])->get();
    }

    public function getAssessments($data)
    {
        return Assessment::where($data)->with('exam')->get()->whereNotNull('exam');
    }

    public function isNotEmpty()
    {
        return Assessment::with('exam')->get()->whereNotNull('exam')->isNotEmpty();
    }

    public function getAssessment($data)
    {
        return Assessment::where($data)->first();
    }

    public function create($data)
    {
        return Assessment::create($data);
    }

    public function firstOrCreate($data)
    {
        return Assessment::firstOrCreate($data);
    }

    public function delete($data)
    {
        return Assessment::where($data)->delete();
    }

    /*********** Assessments records ***************/

    public function createRecord($data)
    {
        return AssessmentRecord::firstOrCreate($data);
    }

    public function getRecords($data)
    {
        return AssessmentRecord::where($data)->with(['grade', 'exam'])->get()->whereNotNull('exam')->whereNotNull('user')->unique('mark_id');
    }

    public function getRecordById($id)
    {
        return AssessmentRecord::where('exam_id', $id)->with('exam')->first()->whereNotNull('exam');
    }

    public function massUpdateRecs(array $values, array $unique_by = ['id'])
    {
        return AssessmentRecord::massUpdate(
            values: $values,
            uniqueBy: $unique_by
        );
    }

    public function updateDBFacades(array $data, array $value)
    {
        return DB::table($$this->assessment_records_table)->where($data)->update($value);
    }

    public function getValueOutOfQuantity($value, $quantity, $precision = 0)
    {
        return round(($value / 100) * $quantity, $precision);
    }

    public function updateRecord(array $where, array $data)
    {
        return AssessmentRecord::where($where)->update($data);
    }

    public function updateRecordDBFacades(array $where, array $data)
    {
        return DB::table($this->assessments_table)->where($where)->update($data);
    }

    public function getExamYears($student_id)
    {
        return AssessmentRecord::where('student_id', $student_id)->select('year')->distinct()->get();
    }

    public function getRecordValue($data, $value)
    {
        return AssessmentRecord::where($data)->value($value);
    }

    public function getSubjectIDs($data)
    {
        return AssessmentRecord::distinct()->select('subject_id')->where($data)->get()->pluck('subject_id');
    }

    public function getStudentIDs($data)
    {
        return AssessmentRecord::distinct()->select('student_id')->where($data)->get()->pluck('student_id');
    }

    public function getAssessmentTotalTerm($exam, $st_id, $class_id, $year)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'year' => $year];

        $tex = "tex{$exam->term}";
        $mk = AssessmentRecord::where($d);

        return $mk->select($tex)->sum($tex);
    }

    public function getAssessmentAvgTerm($exam, $st_id, $class_id, $sec_id, $year)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'section_id' => $sec_id, 'year' => $year];

        $tex = "tex{$exam->term}";

        $mk = AssessmentRecord::where($d)->where($tex, '>=', 0);
        $avg = $mk->select($tex)->avg($tex);

        return round($avg, 1);
    }

    public function getClassAvg($exam, $class_id, $year)
    {
        $d = ['exam_id' => $exam->id, 'my_class_id' => $class_id, 'year' => $year];
        $tex = "tex{$exam->term}";

        $avg = AssessmentRecord::where($d)->select($tex)->avg($tex);

        return round($avg, 1);
    }

    public function getPos($st_id, $exam, $class_id, $sec_id, $year)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'section_id' => $sec_id, 'year' => $year];
        $all_mks = [];
        $tex = "tex{$exam->term}";

        $my_mk = AssessmentRecord::where($d)->select($tex)->sum($tex);

        unset($d['student_id']);
        $mk = AssessmentRecord::where($d);
        $students = $mk->select('student_id')->distinct()->get();
        foreach ($students as $s) {
            $all_mks[] = $this->getAssessmentTotalTerm($exam, $s->student_id, $class_id, $year);
        }

        rsort($all_mks);

        return array_search($my_mk, $all_mks) + 1;
    }

    public function getStudentValue($where, $value)
    {
        return AssessmentRecord::where($where)->select('ave')->get()->first()->$value;
    }

    public function getStudentPos($st_id, $exam_id, $class_id, $year, $value, $section_id = null)
    {
        $where = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'student_id' => $st_id, 'year' => $year];
        $std_value = $this->getStudentValue($where, $value);
        $data = $section_id != null
            ? ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $year]
            : ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'year' => $year];
        $std_values = AssessmentRecord::where($data)->whereNotNull($value)->orderBy($value, 'DESC')->select($value)->get()->pluck($value);

        $position = $std_values->isNotEmpty() ? $std_values->search($std_value) : false;
        return ($position !== false) ? $position + 1 : null;
    }

    public function getStudentsTotals($where, $tex)
    {
        return AssessmentRecord::where($where)->whereNotNull($tex)->orderBy($tex, 'DESC')->select($tex)->get()->pluck($tex);
    }

    public function getGrade($total, $class_type_id)
    {
        if ($total < 0 || $total === null) {
            return null;
        }

        $grade = Grade::where('class_type_id', $class_type_id)->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();

        return $grade ?: $this->getGrade2($total);
    }

    public function getGrade2($total)
    {
        $grades = Grade::whereNull('class_type_id')->get();

        return $grades->isNotEmpty() ? $grades->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first() : null;
    }

    public function getSubjectMark($exam, $class_id, $sub_id, $st_id, $year)
    {
        $d = ['exam_id' => $exam->id, 'my_class_id' => $class_id, 'subject_id' => $sub_id, 'student_id' => $st_id, 'year' => $year];
        $tex = "tex{$exam->term}";

        return AssessmentRecord::where($d)->select($tex)->get()->first()->$tex;
    }

    public function getSubPos($st_id, $exam, $class_id, $sub_id, $year)
    {
        $d = ['exam_id' => $exam->id, 'my_class_id' => $class_id, 'subject_id' => $sub_id, 'year' => $year];
        $tex = "tex{$exam->term}";

        $sub_mk = $this->getSubjectMark($exam, $class_id, $sub_id, $st_id, $year);
        $sub_mks = AssessmentRecord::where($d)->whereNotNull($tex)->orderBy($tex, 'DESC')->select($tex)->get()->pluck($tex);

        $position = $sub_mks->search($sub_mk);
        return ($position !== false) ? $position + 1 : null;
    }

    public function firstOrCreateRec($sid, $class_id, $sec_id, $exam_id, $year, $sub_id, $mark_id, $assessment_id)
    {
        return AssessmentRecord::firstOrCreate([
            'student_id' => $sid,
            'my_class_id' => $class_id,
            'section_id' => $sec_id,
            'exam_id' => $exam_id,
            'year' => $year,
            'subject_id' => $sub_id,
            'mark_id' => $mark_id,
            'assessment_id' => $assessment_id,
        ]);
    }
}
