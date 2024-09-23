<?php

namespace App\Helpers;

use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Division;
use App\Models\Section;
use App\Models\SubjectRecord;
use App\Models\AssessmentRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class Mk extends Qs
{
    public static function examIsLocked()
    {
        return (int) self::getSetting('lock_exam') === 1;
    }

    public static function marksheetPrintNotAllowed()
    {
        return (int) self::getSetting('allow_marksheet_print') === 1;
    }

    public static function assessmentsheetPrintNotAllowed()
    {
        return (int) self::getSetting('allow_assessmentsheet_print') === 1;
    }

    public static function getRemarks()
    {
        return ['Average', 'Credit', 'Distinction', 'Excellent', 'Satisfactory', 'Subsidiary', 'Fail', 'Fair', 'Good', 'Pass', 'Poor', 'Very Good', 'Very Poor'];
    }

    public static function getAverage($sum, $number, $precision = 1)
    {
        return round($sum / $number, $precision);
    }

    public static function getPercentage($item, $sum, $precicion)
    {
        return round(($item / $sum) * 100, $precicion) . '%';
    }

    public static function getAssessmentRec($student_id, $sub_id, $exam_id)
    {
        return AssessmentRecord::where(["student_id" => $student_id, "subject_id" => $sub_id, "exam_id" => $exam_id])->get()->first();
    }

    // Base method for marks - must be used for all the functions rendering content in marks tabulation view - for consistent, uniform rendering
    public static function getDistinctMrksSbjcts($data)
    {
        return Mark::where($data)->select('subject_id')->distinct()->orderBy('subject_id')->get();
    }

    public static function getGrades($class_type_id)
    {
        return Grade::where('class_type_id', $class_type_id)->orderBy('name')->get();
    }

    public static function renderSubsPerformanceSummary($section_id, $exam_id, $my_class)
    {
        $collection = '';
        $all_avg_sum = [];
        $all_grd_count = $all_total = 0;
        $grades = self::getGrades($my_class->class_type_id);

        if ($section_id == "all") {
            $marks_all_subjects = self::getDistinctMrksSbjcts(["my_class_id" => $my_class->id, "exam_id" => $exam_id]);
            // Get all subject exam sums - for finding subject rank
            foreach ($marks_all_subjects as $mark) {
                $p = Mark::where(['subject_id' => $mark->subject->id, 'my_class_id' => $my_class->id, 'exam_id' => $exam_id]);
                $reg = (($sids = $p->pluck('student_id')) != NULL) ? $sids->count() : (($sids = SubjectRecord::where('subject_id', $mark->subject->id)->get()->value('students_ids')) != NULL ?: count(json_decode($sids)));
                $q = Mark::whereIn('student_id', $sids);

                $avg_sum = $q->where(['subject_id' => $mark->subject->id, 'exam_id' => $exam_id, 'my_class_id' => $my_class->id])->avg('exm');
                $all_avg_sum[] = (int) $avg_sum;
            }

            // Sort the array in reverse order
            rsort($all_avg_sum);
            $all_avg_sum_flipped = array_flip($all_avg_sum); // Flip the array so that we search the exm_sum to get the rank / position

            foreach ($marks_all_subjects as $mark) {
                $p = Mark::where(['subject_id' => $mark->subject->id, 'my_class_id' => $my_class->id, 'exam_id' => $exam_id]);
                $reg = (($sids = $p->pluck('student_id')) != NULL) ? $sids->count() : (($sids = SubjectRecord::where('subject_id', $mark->subject->id)->get()->value('students_ids')) != NULL ?: count(json_decode($sids)));
                $q = Mark::whereIn('student_id', $sids);

                $sat = $q->where(['exam_id' => $exam_id, 'subject_id' => $mark->subject->id])->whereNotNull('exm')->count();
                $s = $q->where(['subject_id' => $mark->subject->id, 'exam_id' => $exam_id, 'my_class_id' => $my_class->id]);
                $avg_sum = $s->avg('exm');
                $avg = $sat == 0 ? $sat : round($s->sum('exm') / $sat, 1);

                $collection .=
                    '<tr>
                        <td class="text-center">' . $mark->subject->slug . '</td>
                        <td class="text-center">' . $mark->subject->name . '</td>
                        <td class="text-center">' . $reg . '</td>
                        <td class="text-center">' . $sat . '</td>
                        <td class="text-center">' . $reg - $sat . '</td>
                        <td class="text-center">' . $avg . '</td>
                        <td class="text-green float-left pl-2">' . self::getGrade($avg, true) . '</td>
                        <td><table style="border-collapse: collapse;">
                    <tr>';

                foreach ($grades as $grd) {
                    $r = $p->get()->where('grade_id', $grd->id);

                    $collection .= '<td class="w-1pcnt unstyled text-center">' . '<strong>' . $grd->name . '</strong>' . ': ' . sprintf("%02d", $grd_cnt = $r->count()) . '</td>';
                    // GPA = sum of (grade_count times grade_credit for all grades) divide by sum of all grade_count only. 
                    $all_grd_count += $grd_cnt;

                    $product = $grd_cnt * $grd->credit;
                    $all_total += $product;
                }
                $collection .= '</tr></table></td><td class="text-center">' . ($all_grd_count == 0 ? $all_grd_count : round($all_total / $all_grd_count, 1)) . '</td><td class="text-center">' . Mk::getSuffix((int) $all_avg_sum_flipped[(int) $avg_sum] + 1) . '</td></tr>';
                // Re-initialize variables to zero before next iteration - avoid data re-addition
                $all_total = $all_grd_count = 0;
            }
        } else {
            $marks_per_section = self::getDistinctMrksSbjcts(["my_class_id" => $my_class->id, "exam_id" => $exam_id, 'section_id' => $section_id]);
            // Get all subject exam sums - for finding subject rank
            foreach ($marks_per_section as $mark) {
                $p = Mark::where(['subject_id' => $mark->subject->id, 'my_class_id' => $my_class->id, 'exam_id' => $exam_id, 'section_id' => $section_id]);
                $reg = (($sids = $p->pluck('student_id')) != NULL) ? $sids->count() : (($sids = SubjectRecord::where('subject_id', $mark->subject->id)->get()->value('students_ids')) != NULL ?: count(json_decode($sids)));
                $q = Mark::whereIn('student_id', $sids);

                $avg_sum = $q->where(['subject_id' => $mark->subject->id, 'exam_id' => $exam_id, 'my_class_id' => $my_class->id, 'section_id' => $section_id])->avg('exm');
                $all_avg_sum[] = (int) $avg_sum;
            }

            // Sort the array in reverse order
            rsort($all_avg_sum);
            $all_avg_sum_flipped = array_flip($all_avg_sum); // Flip the array so that we search the exm_sum to get the rank / position (the key)

            foreach ($marks_per_section as $mark) {
                $p = Mark::where(['subject_id' => $mark->subject->id, 'my_class_id' => $my_class->id, 'exam_id' => $exam_id, 'section_id' => $section_id]);
                $reg = (($sids = $p->pluck('student_id')) != NULL) ? $sids->count() : (($sids = SubjectRecord::where('subject_id', $mark->subject->id)->get()->value('students_ids')) != NULL ?: count(json_decode($sids)));
                $q = Mark::whereIn('student_id', $sids);

                $sat = $q->where(['exam_id' => $exam_id, 'subject_id' => $mark->subject->id, 'section_id' => $section_id])->whereNotNull('exm')->count();
                $s = $q->where(['subject_id' => $mark->subject->id, 'exam_id' => $exam_id, 'my_class_id' => $my_class->id, 'section_id' => $section_id]);
                $avg_sum = $s->avg('exm');
                $avg = $sat == 0 ? $sat : round($s->sum('exm') / $sat, 1);
                $collection .=
                    '<tr>
                        <td class="text-center">' . $mark->subject->slug . '</td>
                        <td class="text-center">' . $mark->subject->name . '</td>
                        <td class="text-center">' . $reg . '</td>
                        <td class="text-center">' . $sat . '</td>
                        <td class="text-center">' . $reg - $sat . '</td>
                        <td class="text-center">' . $avg . '</td>
                        <td class="text-green float-left pl-2">' . self::getGrade($avg, true) . '</td>
                        <td><table style="border-collapse: collapse;">
                    <tr>';

                foreach ($grades as $grd) {
                    $r = $p->get()->where('grade_id', $grd->id);

                    $collection .= '<td class="w-1pcnt unstyled text-center">' . '<strong>' . $grd->name . '</strong>' . ': ' . sprintf("%02d", $grd_cnt = $r->count()) . '</td>';

                    $all_grd_count += $grd_cnt;

                    $product = $grd_cnt * $grd->credit;
                    $all_total += $product;
                }

                $collection .= '</tr></table></td><td class="text-center">' . ($all_grd_count == 0 ? $all_grd_count : round($all_total / $all_grd_count, 1)) . '</td><td class="text-center">' . Mk::getSuffix((int) $all_avg_sum_flipped[(int) $avg_sum] + 1) . '</td></tr>';
                $all_total = $all_grd_count = 0;
            }
        }

        return $collection;
    }
    /** GRADES SUMMARY ENDS **/

    /** GPA */
    public static function getGPA($exam_id, $my_class_id, $class_type_id, $section_id, $precicion = 1)
    {
        $all_grds_cnt = $all_prods = 0;
        foreach (Grade::where('class_type_id', $class_type_id)->get() as $grd) {
            if ($my_class_id != NULL) {
                $grd_cnt = ($section_id == NULL || $section_id == "all") ? Mark::where(['exam_id' => $exam_id, 'my_class_id' => $my_class_id, 'grade_id' => $grd->id])->get()->count() : Mark::where(['exam_id' => $exam_id, 'my_class_id' => $my_class_id, 'grade_id' => $grd->id, 'section_id' => $section_id])->get()->count();
            } elseif ($my_class_id == NULL) {
                $grd_cnt = Mark::where(['exam_id' => $exam_id, 'grade_id' => $grd->id])->get()->count();
            }

            $all_grds_cnt += $grd_cnt;
            $prod = $grd_cnt * $grd->credit;
            $all_prods += $prod;
        }
        return ($all_grds_cnt == 0) ? $all_grds_cnt : round($all_prods / $all_grds_cnt, $precicion);
    }

    /** DIVISION SUMMARY */
    public static function getExamRecords($section_id, $exam_id, $class_id)
    {
        $d = ($section_id == 'all') ? ['exam_id' => $exam_id, 'my_class_id' => $class_id] : ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id];
        return ExamRecord::where($d)->with('student')->distinct()->get();
    }

    public static function renderDivisionsSummary($exam_id, $class_id, $class_type_id, $section_id)
    {
        $divison_name = $male_count = $female_count = $count_per_div = $attended = $absent = '';

        $exam_records = self::getExamRecords($section_id, $exam_id, $class_id)->whereNotNull('student'); // Exclude soft deleted users (students)

        // Take only exam records where among others total marks not equal to zero.
        // If total marks equals zero implies the student did not perform any exam.
        // Literary is not possible for student to get zero summation of all exams.
        $exr_attended = $exam_records->where('total', '<>', 0);
        $exr_absent = $exam_records->where('total', 0); // Take only absent ie., where total is zero

        $class_type_divisions = Division::where('class_type_id', $class_type_id);
        $divisions = ($class_type_divisions->count() > 0) ? $class_type_divisions->get() : Division::whereNull('class_type_id')->get();

        foreach ($divisions as $div) {
            $divison_name .= '<th class="text-center">' . $div->name . '</th>';
            $male_count .= '<td class="text-center">' . $mc = $exr_attended->where('student.gender', 'Male')->where('division_id', $div->id)->whereNotNull('division_id')->count() ?? '-' . '</td>';
            $female_count .= '<td class="text-center">' . $fc = $exr_attended->where('student.gender', 'Female')->where('division_id', $div->id)->whereNotNull('division_id')->count() ?? '-' . '</td>';
            $count_per_div .= '<td class="text-center">' . ($mc + $fc) ?? '-' . '</td>';
        }

        $colspan = ($count = $divisions->count() + 1) / 2; // 1 - for Division column header, and 2 for two required columns - attended and absent

        // Sometimes the count may be odd. Thus, take  the count minus the arleady used colspan (for absent) so that to span to all the remaining columns.
        $attended .= '</tr><tr><td class="text-center" colspan="' . round($count - $colspan) . '">SAT: ' . $exr_attended->whereNotNull('division_id')->count() ?? '-' . '</td>';
        $absent .= '<td class="text-center" colspan="' . round($colspan) . '">ABS: ' . $exr_absent->count() ?? '-' . '</td>';

        $a = '<table class="table-styled styled"><thead style="background: #f5f5f5;"><tr><th class="text-center">Division</th>';
        $b = '</tr></thead><tbody><tr><td class="text-center">Male</td>';
        $c = '</tr><tr><td class="text-center">Female</td>';
        $d = '</tr><tr><td class="text-center">Total</td>';
        $g = '</tr></tbody></table>';

        $collection = $a . $divison_name . $b . $male_count . $c . $female_count . $d . $count_per_div . $attended . $absent . $g;
        return $collection;
    }

    /** DIVISION SUMMARY ENDS */

    /** ADD ORDINAL SUFFIX TO POSITION **/
    public static function getSuffix($number)
    {
        if ($number < 1)
            return NULL;

        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . '<sup>th</sup>';
        else
            return $number . '<sup>' . $ends[$number % 10] . '</sup>';
    }

    /*Get Subject Total Per Term*/
    public static function getSubTotalTerm($st_id, $sub_id, $term, $class_id, $year)
    {
        $d = ['student_id' => $st_id, 'subject_id' => $sub_id, 'my_class_id' => $class_id, 'year' => $year];
        $tex = 'tex' . $term;
        $sub_total = Mark::where($d)->select($tex)->get()->where($tex, '>', 0);

        return $sub_total->count() > 0 ? $sub_total->first()->$tex : '-';
    }

    public static function countDistinctions(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'A%')->orWhere('name', 'LIKE', 'B%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countPasses(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'D%')->orWhere('name', 'LIKE', 'E%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countCredits(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'C%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countFailures(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'F%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countStudents($exam_id, $class_id, $section_id, $year)
    {
        $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $year];
        return Mark::where($d)->select('student_id')->distinct()->get()->count();
    }

    protected static function markGradeFilter(Collection $marks, $gradeIDS)
    {
        return $marks->filter(function ($mks) use ($gradeIDS) {
            return in_array($mks->grade_id, $gradeIDS);
        })->count();
    }

    public static function countSubjectsOffered(Collection $mark)
    {
        return $mark->filter(function ($mk) {
            return ($mk->tca + $mk->exm) > 0;
        })->count();
    }

    /*Get Exam Avg Per Term*/
    public static function getTermAverage($st_id, $term, $year)
    {
        $exam = self::getExamByTerm($term, $year);
        $d = ['exam_id' => $exam->id, 'student_id' => $st_id, 'year' => $year];

        if ($term < 3) {
            $exr = ExamRecord::where($d);
            $avg = $exr->first()->ave ?: NULL;
            return $avg > 0 ? round($avg, 1) : $avg;
        }

        $mk = Mark::where($d)->whereNotNull('tex3');
        $avg = $mk->select('tex3')->avg('tex3');
        return round($avg, 1);
    }

    public static function getTermTotal($st_id, $term, $year)
    {
        $exam = self::getExamByTerm($term, $year);
        $d = ['exam_id' => $exam->id, 'student_id' => $st_id, 'year' => $year];

        if ($term < 3) {
            return ExamRecord::where($d)->first()->total ?? NULL;
        }

        $mk = Mark::where($d)->whereNotNull('tex3');
        return $mk->select('tex3')->sum('tex3');
    }

    public static function getExamByTerm($term, $year)
    {
        $d = ['term' => $term, 'year' => $year];
        return Exam::where($d)->first();
    }

    public static function getGradeList($class_type_id)
    {
        $grades = Grade::where(['class_type_id' => $class_type_id])->orderBy('name')->get();

        if ($grades->count() < 1) {
            $grades = Grade::whereNull('class_type_id')->orderBy('name')->get();
        }
        return $grades;
    }

    public static function gateDivisionList($class_type_id)
    {
        $divisions = Division::where(['class_type_id' => $class_type_id])->orderBy('name')->get();

        if ($divisions->count() < 1)
            $divisions = Division::whereNull('class_type_id')->orderBy('name')->get();

        return $divisions;
    }

    // The ids corresponds to exam categories in 'exam_categories' table
    public static function getTerminalExamCategoryId()
    {
        return 4;
    }

    public static function getAnnualExamCategoryId()
    {
        return 5;
    }

    public static function isTerminalExam(int $category_id): bool
    {
        return $category_id === self::getTerminalExamCategoryId();
    }

    public static function isAnnualExam(int $category_id): bool
    {
        return $category_id === self::getAnnualExamCategoryId();
    }

    public static function getSummativeExamCategoryIds(): array
    {
        return [self::getTerminalExamCategoryId(), self::getAnnualExamCategoryId()];
    }

    public static function isEnabled(int $value): bool
    {
        return $value === 1;
    }

    public static function isDisabled(int $value): bool
    {
        return $value === 0;
    }

    public static function getSectionCount($exam_id, $class_id, $section_id)
    {
        return ExamRecord::where(['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id])->count();
    }

    public static function getClassCount($exam_id, $class_id)
    {
        return ExamRecord::where(['exam_id' => $exam_id, 'my_class_id' => $class_id])->count();
    }

    /**
     * If Class/Section is Changed in Same Year,
     * Delete Marks/ExamRecord of Previous Class/Section
     *
     * @param int $st_id
     * @param int $class_id
     * @return bool
     * @static
     */
    public static function deleteOldRecord($st_id, $class_id)
    {
        $d = ['student_id' => $st_id, 'year' => self::getCurrentSession()];

        $marks = Mark::where('my_class_id', '<>', $class_id)->where($d);
        if ($marks->get()->count() > 0) {
            $exr = ExamRecord::where('my_class_id', '<>', $class_id)->where($d);
            $marks->delete();
            $exr->delete();
        }
        return true;
    }

    public static function getDvivisionByClassType($id)
    {
        return Division::where(['class_type_id' => $id])->get();
    }

    public static function getDivision($points, $class_type_id)
    {
        $divisions = self::getDvivisionByClassType($class_type_id);

        if ($divisions->count() > 0) {
            $dv = $divisions->where('points_from', '<=', $points)->where('points_to', '>=', $points);
            return $dv->count() > 0 ? $dv->first() : self::getDivision2($points);
        }
        return self::getDivision2($points);
    }

    public static function getDivision2($points)
    {
        $divisions = Division::whereNull('class_type_id')->get();
        if ($divisions->count() > 0) {
            return $divisions->where('points_from', '<=', $points)->where('points_to', '>=', $points)->first();
        }
        return NULL;
    }

    public static function jsonResponse($title, $message)
    {
        return response()->json([
            $title => $message
        ]);
    }

    public static function getAllMksTotal($students, $exam_id, $my_class_id)
    {
        $all_mks = array();
        foreach ($students as $s) {
            $all_mks[] = ExamRecord::where(['student_id' => $s->user_id, 'exam_id' => $exam_id, 'my_class_id' => $my_class_id])->value('total');
        }
        rsort($all_mks);
        $flipped_mks = array_flip($all_mks);

        return $flipped_mks;
    }

    public static function getGrade($marks_avg, $with_remark = false)
    {
        $marks_avg = round((int) $marks_avg);
        $grd = Grade::where('mark_from', '<=', $marks_avg)->where('mark_to', '>=', $marks_avg);

        return $with_remark ? $grd->value('name') . ' - ' . $grd->value('remark') : $grd->value('name');
    }

    public static function renderGrades($class_type_id)
    {
        $grades_name = $marks_from = $marks_to = $points = $credits = '';
        $grades = ($class_type_id == 1) ? Grade::where('class_type_id', 1)->orderBy('name')->get() : ($class_type_id == 2 ? Grade::where('class_type_id', 2)->orderBy('name')->get() : Grade::where('class_type_id', 3)->orderBy('name')->get());

        foreach ($grades as $grade) {
            $grades_name .= '<th class="text-center">' . $grade->name . '</th>';
            $marks_from .= '<td class="text-center">' . $grade->mark_from . '</td>';
            $marks_to .= '<td class="text-center">' . $grade->mark_to . '</td>';
            $points .= '<td class="text-center">' . $grade->point . '</td>';
            $credits .= '<td class="text-center">' . $grade->credit . '</td>';
        }

        $a = '<table class="table-styled"><thead style="background: #f5f5f5;"><tr><th class="text-center">Grade</th>';
        $b = '</tr></thead><tbody><tr><td class="text-center">Mark From</td>';
        $c = '</tr><tr><td class="text-center">Mark To</td>';
        $d = '</tr><tr><td class="text-center">Point</td>';
        $e = '</tr><tr><td class="text-center">Credit</td>';
        $f = '</tr></tbody></table>';

        $collection = $a . $grades_name . $b . $marks_from . $c . $marks_to . $d . $points . $e . $credits . $f;
        return $collection;
    }

    public static function renderDivisions($class_type_id)
    {
        $name = $points_from = $points_to = '';

        $divisions = ($class_type_id == 1) ? Division::where('class_type_id', 1)->orderBy('name')->get() : ($class_type_id == 2 ? Division::where('class_type_id', 2)->orderBy('name')->get() : Division::where('class_type_id', 3)->orderBy('name')->get());

        foreach ($divisions as $division) {
            $name .= '<th class="text-center">' . $division->name . '</th>';
            $points_from .= '<td class="text-center">' . $division->points_from . '</td>';
            $points_to .= '<td class="text-center">' . $division->points_to . '</td>';
        }

        $a = '<table class="table-styled"><thead style="background: #f5f5f5;"><tr><th class="text-center">Division</th>';
        $b = '</tr></thead><tbody><tr><td class="text-center">Points From</td>';
        $c = '</tr><tr><td class="text-center">Points To</td>';
        $d = '</tr></tbody></table>';

        $collection = $a . $name . $b . $points_from . $c . $points_to . $d;
        return $collection;
    }

    public static function getClassSections($class_id)
    {
        return Section::where(['my_class_id' => $class_id])->orderBy('name', 'asc')->get();
    }

    public static function getSubjectRecords($sub_id)
    {
        return SubjectRecord::where('subject_id', $sub_id)->with(['section', 'teacher'])->get();
    }

    public static function getSubjects($class_id)
    {
        $subjects_table = 'subjects';
        $subject_recs_table = 'subject_records';

        if (Qs::userIsTeacher())
            return DB::table($subject_recs_table)->where('teacher_id', auth()->id())->rightJoin($subjects_table, function ($rightjoin) use ($subjects_table, $subject_recs_table) {
                $rightjoin->on($subject_recs_table . '.subject_id', '=', $subjects_table . '.id');
            })->select('*')->where($subjects_table . '.my_class_id', '=', $class_id)->distinct()->get();
        else
            return DB::table($subjects_table)->where('my_class_id', $class_id)->distinct()->get();
    }

    public static function getStudentExamPositionByValues()
    {
        return [
            'total' => 'Total Marks',
            'ave' => 'Average Mark',
            'points' => 'Division Points',
            'GPA' => 'GPA',
        ];
    }

    public static function getStudentCAPositionByValues()
    {
        return [
            'total' => 'Total Marks',
            'ave' => 'Average Mark',
        ];
    }
}
