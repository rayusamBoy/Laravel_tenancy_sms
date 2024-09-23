<?php

namespace App\Repositories;

use App\Models\Grade;
use App\Models\Mark;

class MarkRepo
{
    /*********** Marks ***************/

    public function getByIds($ids)
    {
        return Mark::with(['subject', 'user'])->whereIn('student_id', $ids);
    }

    public function getById($id)
    {
        return Mark::with(['subject', 'user'])->where('student_id', $id);
    }

    public function getSubTotalTerm($st_id, $sub_id, $term, $class_id, $year)
    {
        $d = ['student_id' => $st_id, 'subject_id' => $sub_id, 'my_class_id' => $class_id, 'year' => $year];

        $tex = 'tex' . $term;
        $sub_total = Mark::where($d)->select($tex)->get()->where($tex, '>', 0);

        return $sub_total->count() > 0 ? $sub_total->first()->$tex : NULL;
    }

    public function getExamTotalTerm($exam, $st_id, $class_id, $year)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'year' => $year];

        $tex = 'tex' . $exam->term;
        $mk = Mark::where($d);

        return $mk->select($tex)->sum($tex);
    }

    public function getExamTotalTermSection($exam, $st_id, $class_id, $section_id, $year)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $year];

        $tex = 'tex' . $exam->term;
        $mk = Mark::where($d);
        return $mk->select($tex)->sum($tex);
    }

    public function getExamAvgTerm($exam, $st_id, $class_id, $sec_id, $year)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'section_id' => $sec_id, 'year' => $year];

        $tex = 'tex' . $exam->term;

        $mk = Mark::where($d)->where($tex, '>=', 0);
        $avg = $mk->select($tex)->avg($tex);

        return round($avg, 1);
    }

    public function getLimitedExamAvgTerm($exam, $st_id, $class_id, $sec_id, $year, $limit)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'section_id' => $sec_id, 'year' => $year];

        $tex = 'tex' . $exam->term;

        $avg = Mark::with('subject')->where($d)->where($tex, '>=', 0)->get()->where('subject.core', 1)->sortByDesc($tex)->take($limit)->avg($tex);

        return round($avg, 1);
    }

    public function getSubCumTotal($tex3, $st_id, $sub_id, $class_id, $year)
    {
        $tex1 = $this->getSubTotalTerm($st_id, $sub_id, 1, $class_id, $year);
        $tex2 = $this->getSubTotalTerm($st_id, $sub_id, 2, $class_id, $year);

        return $tex1 + $tex2 + $tex3;
    }

    public function getSubCumAvg($tex3, $st_id, $sub_id, $class_id, $year)
    {
        $count = 0;
        $tex1 = $this->getSubTotalTerm($st_id, $sub_id, 1, $class_id, $year);
        $count = $tex1 ? $count + 1 : $count;
        $tex2 = $this->getSubTotalTerm($st_id, $sub_id, 2, $class_id, $year);
        $count = $tex2 ? $count + 1 : $count;
        $count = $tex3 ? $count + 1 : $count;
        $total = $tex1 + $tex2 + $tex3;

        return ($total > 0) ? round($total / $count, 1) : 0;
    }

    public function getSubjectMark($exam, $class_id, $sub_id, $st_id, $year)
    {
        $d = ['exam_id' => $exam->id, 'my_class_id' => $class_id, 'subject_id' => $sub_id, 'student_id' => $st_id, 'year' => $year];
        $tex = 'tex' . $exam->term;

        return Mark::where($d)->select($tex)->get()->first()->$tex;
    }

    public function getSubPos($st_id, $exam, $class_id, $sub_id, $year)
    {
        $d = ['exam_id' => $exam->id, 'my_class_id' => $class_id, 'subject_id' => $sub_id, 'year' => $year];
        $tex = 'tex' . $exam->term;

        $sub_mk = $this->getSubjectMark($exam, $class_id, $sub_id, $st_id, $year);
        $sub_mks = Mark::where($d)->whereNotNull($tex)->orderBy($tex, 'DESC')->select($tex)->get()->pluck($tex);

        return $sub_mks->count() > 0 ? $sub_mks->search($sub_mk) + 1 : NULL;
    }

    public function countExSubjects($exam, $st_id, $class_id, $year)
    {
        $d = ['exam_id' => $exam->id, 'my_class_id' => $class_id, 'student_id' => $st_id, 'year' => $year];
        $tex = 'tex' . $exam->term;

        if ($exam->term == 3)
            unset($d['exam_id']);

        return Mark::where($d)->whereNotNull($tex)->count();
    }

    public function getClassAvg($exam, $class_id, $year)
    {
        $d = ['exam_id' => $exam->id, 'my_class_id' => $class_id, 'year' => $year];
        $tex = 'tex' . $exam->term;

        $avg = Mark::where($d)->select($tex)->avg($tex);

        return round($avg, 1);
    }

    public function getClassPos($st_id, $exam, $class_id, $year)
    {
        $d = ['student_id' => $st_id, 'exam_id' => $exam->id, 'my_class_id' => $class_id, 'year' => $year];
        $all_mks = [];
        $tex = 'tex' . $exam->term;

        $my_mk = Mark::where($d)->select($tex)->sum($tex);

        unset($d['student_id']);

        $mk = Mark::where($d);
        $students = $mk->select('student_id')->distinct()->get();
        foreach ($students as $s) {
            $all_mks[] = $this->getExamTotalTerm($exam, $s->student_id, $class_id, $year);
        }
        rsort($all_mks);
        $searched = array_search($my_mk, $all_mks);

        return ($searched == false) ? null : ($searched + 1);
    }

    public function getExtractedSumOf($weight, $offset = 0, $length)
    {
        sort($weight); // Sort points
        $weight = array_filter($weight);
        if (count($weight) < $length) // If the points are not suffucient compared to subjects considered, return null
            return NULL;
        // Start from offset (default 0). Take only the sum of the first considered points
        return array_sum(array_slice($weight, $offset, $length));
    }

    public function getRecordValue($data, $value)
    {
        return Mark::where($data)->value($value);
    }

    public function getSubjectIDs($data)
    {
        return Mark::distinct()->select('subject_id')->where($data)->get()->pluck('subject_id');
    }

    public function getStudentIDs($data)
    {
        return Mark::distinct()->select('student_id')->where($data)->get()->pluck('student_id');
    }

    public function firstOrCreate($sid, $class_id, $sec_id, $exam_id, $year, $sub_id)
    {
        return Mark::firstOrCreate([
            'student_id' => $sid,
            'my_class_id' => $class_id,
            'section_id' => $sec_id,
            'exam_id' => $exam_id,
            'year' => $year,
            'subject_id' => $sub_id,
        ]);
    }

    /*********** Grade ***************/

    public function getGrade($total, $class_type_id)
    {
        if ($total <= 0)
            return NULL;

        $total = round($total);
        $grades = Grade::where(['class_type_id' => $class_type_id])->get();

        if ($grades->count() > 0) {
            $gr = $grades->where('mark_from', '<=', $total)->where('mark_to', '>=', $total);
            return $gr->count() > 0 ? $gr->first() : $this->getGrade2($total);
        }

        return $this->getGrade2($total);
    }

    public function getGrade2($total)
    {
        $grades = Grade::whereNull('class_type_id')->get();

        if ($grades->count() > 0)
            return $grades->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();

        return NULL;
    }
}