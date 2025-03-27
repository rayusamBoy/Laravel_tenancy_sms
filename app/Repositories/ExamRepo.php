<?php

namespace App\Repositories;

use App\Models\ClassType;
use App\Models\Division;
use App\Models\Exam;
use App\Models\ExamAnnounce;
use App\Models\ExamCategory;
use App\Models\ExamNumberFormat;
use App\Models\ExamRecord;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Section;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use Qs;

class ExamRepo
{
    protected $marks_table, $exam_records_table;
    public function __construct()
    {
        $this->marks_table = 'marks';
        $this->exam_records_table = 'exam_records';
    }

    /*********** Exam category ***************/

    public function getCategories()
    {
        return ExamCategory::get();
    }

    /*********** Exam Announce ***************/

    public function announce($data)
    {
        return ExamAnnounce::firstOrCreate($data);
    }

    /*********** Exam number format ***************/

    public function createNumberFormat($data)
    {
        return ExamNumberFormat::firstOrCreate($data);
    }

    public function updateNumberFormat($id, $data)
    {
        return ExamNumberFormat::where('id', $id)->update($data);
    }

    public function updateOrCreateNumberFormat($attributes, $values)
    {
        return ExamNumberFormat::updateOrCreate($attributes, $values);
    }

    public function deleteNumberFormat($id)
    {
        return ExamNumberFormat::find($id)->delete();
    }

    /*********** Exam ***************/

    public function onlyPublished()
    {
        return Exam::where('published', true)->orderByDesc('created_at')->orderBy('year', 'desc')->with('category')->get();
    }

    public function getPublished(mixed $where)
    {
        return Exam::where($where)->where('published', 1)->get();
    }

    public function all()
    {
        return (Qs::userIsTeamSAT()) ? Exam::orderByDesc('created_at')->orderBy('year', 'desc')->with(['category', 'class_type'])->get() : $this->onlyPublished();
    }

    public function getById(int $id)
    {
        return Exam::where('id', $id)->where('published', 1)->with(['category', 'class_type'])->with('number_format')->first();
    }

    public function getExam(array $data, bool $withoutLockedOnes = true)
    {
        return ($withoutLockedOnes === true) ? Exam::where($data)->latest()->where('locked', '<>', 1)->get() : Exam::where($data)->latest()->get();
    }

    public function exists(int $exam_id)
    {
        return Exam::whereId($exam_id)->exists();
    }

    public function getTrashed()
    {
        return Exam::onlyTrashed()->get();
    }

    public function examsExists()
    {
        return Exam::get()->isNotEmpty();
    }

    public function find(int $id)
    {
        return Exam::find($id);
    }

    public function firstOrCreate(array $data)
    {
        return Exam::firstOrCreate($data);
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        return Exam::updateOrCreate($attributes, $values);
    }

    public function create(array $data)
    {
        return Exam::create($data);
    }

    public function getExamsCount()
    {
        return Exam::all()->count();
    }

    public function getPublishedOrderByLatest()
    {
        return Exam::latest()->where('published', 1)->get();
    }

    public function getPublishedExamsCount()
    {
        return Exam::where('published', 1)->get()->count();
    }

    public function update(int $id, array $data)
    {
        return Exam::find($id)->update($data);
    }

    public function delete($id)
    {
        return Exam::destroy($id);
    }

    public function classTypeExmCategoryExists($cat_id, $class_type_id, $year)
    {
        return Exam::where(['category_id' => $cat_id, 'class_type_id' => $class_type_id])->where('year', $year)->isNotEmpty();
    }

    public function restore(int $id)
    {
        return Exam::where('id', $id)->restore();
    }

    public function forceDelete(int $id)
    {
        return Exam::where('id', $id)->forceDelete();
    }

    public function isLocked(int $id)
    {
        return Exam::whereId($id)->value('locked') === 1;
    }

    public function getYears()
    {
        return Exam::select('year')->distinct()->get();
    }

    /*********** Exam Record ***************/

    public function createRecord($data)
    {
        return ExamRecord::firstOrCreate($data);
    }

    public function deleteRecord($where)
    {
        return ExamRecord::where($where)->delete();
    }

    public function countAllExamRecords()
    {
        return ExamRecord::all()->count();
    }

    public function updateRecord($where, $data)
    {
        return ExamRecord::where($where)->update($data);
    }

    public function updateRecordDBFacades($where, $data)
    {
        return DB::table($this->exam_records_table)->where($where)->update($data);
    }

    public function massUpdateExamRec(array $values, array $unique_by = ['id'])
    {
        return ExamRecord::massUpdate(
            values: $values,
            uniqueBy: $unique_by
        );
    }

    public function getRecord($data)
    {
        return ExamRecord::where($data)->get();
    }

    public function getRecordsWithRelations($with, $data)
    {
        return ExamRecord::where($data)->with($with)->get();
    }

    public function getRecordsByStudentId($id)
    {
        return ExamRecord::with('exam')->with('student')->where('student_id', $id);
    }

    public function getRecordsById($id)
    {
        return ExamRecord::with('my_class')->where('exam_id', $id);
    }

    public function getRecordsByIds($ids)
    {
        return ExamRecord::with('exam')->with('student')->whereIn('student_id', $ids);
    }

    public function firstOrCreateExmRec($sid, $class_id, $sec_id, $exam_id, $year)
    {
        return ExamRecord::firstOrCreate([
            'student_id' => $sid,
            'my_class_id' => $class_id,
            'section_id' => $sec_id,
            'exam_id' => $exam_id,
            'year' => $year,
        ]);
    }

    public function findRecord($id)
    {
        return ExamRecord::find($id);
    }

    public function getStudentValue($where, $value)
    {
        return ExamRecord::where($where)->select($value)->get()->first()->$value;
    }

    public function getStudentPos($st_id, $exam_id, $class_id, $year, $value, $section_id = NULL)
    {
        $where = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'student_id' => $st_id, 'year' => $year];
        $std_value = $this->getStudentValue($where, $value);

        $data = $section_id != NULL
            ? ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $year]
            : $data = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'year' => $year];

        $std_values = ExamRecord::where($data)->whereNotNull($value)->orderBy($value, 'DESC')->select($value)->get()->pluck($value);
        if ($std_values->count() <= 0)
            return NULL;

        $Tkey = $std_values->search($std_value);

        return $Tkey === false ? NULL : $Tkey + 1;
    }

    /*********** Grades ***************/

    public function allGrades()
    {
        return Grade::orderBy('class_type_id')->orderBy('name')->get();
    }

    public function getGrade($data)
    {
        return Grade::where($data)->get();
    }

    public function findGrade($id)
    {
        return Grade::find($id);
    }

    public function createGrade($data)
    {
        return Grade::create($data);
    }

    public function updateGrade($id, $data)
    {
        return Grade::find($id)->update($data);
    }

    public function deleteGrade($id)
    {
        return Grade::destroy($id);
    }

    /*********** Marks ***************/

    public function createMark($data)
    {
        return Mark::firstOrCreate($data);
    }

    public function destroyMark($id)
    {
        return Mark::destroy($id);
    }

    public function deleteMark($where)
    {
        return Mark::where($where)->delete();
    }

    public function updateMark(int $id, array $data)
    {
        return Mark::find($id)->update($data);
    }

    public function massUpdateMark(array $values, array $unique_by = ['id'])
    {
        return Mark::massUpdate(
            values: $values,
            uniqueBy: $unique_by
        );
    }

    public function updateMarkDBFacades(int $id, array $data)
    {
        return DB::table($this->marks_table)->find($id, 'id')->update($data);
    }

    public function getStudentExamYears($student_id)
    {
        return Mark::where('student_id', $student_id)->select('year')->distinct()->get();
    }

    public function getMark(array $where)
    {
        return Mark::where($where)->with(['grade', 'user', 'subject', 'section'])->get();
    }

    public function mark(array $where)
    {
        return Mark::where($where)->with(['grade', 'user', 'subject', 'section']);
    }

    public function getMarkByIds($ids)
    {
        return Mark::whereIn('student_id', $ids);
    }

    public function getRecordValue(array $where, $value)
    {
        return Mark::where($where)->value($value);
    }

    public function getGrades()
    {
        return Grade::get();
    }

    /*********** Skills ***************/

    public function getSkill($where)
    {
        return Skill::where($where)->orderBy('name')->get();
    }

    public function getSkillByClassType($class_type_id = NULL, $skill_type = NULL)
    {
        return $skill_type
            ? $this->getSkill(['class_type_id' => $class_type_id, 'skill_type' => $skill_type])
            : $this->getSkill(['class_type_id' => $class_type_id]);
    }

    /*********** Sections ***************/

    public function getSections($data)
    {
        return Section::where($data)->get();
    }

    /*********** Divisions ***************/

    public function allDivisions()
    {
        return Division::orderBy('class_type_id')->orderBy('name')->get();
    }

    public function getDivision($data)
    {
        return Division::where($data)->get();
    }

    public function findDivision($id)
    {
        return Division::find($id);
    }

    public function createDivision($data)
    {
        return Division::create($data);
    }

    public function updateDivision($id, $data)
    {
        return Division::find($id)->update($data);
    }

    public function deleteDivision($id)
    {
        return Division::destroy($id);
    }

    /*********** Class types ***************/

    public function getClassTypes()
    {
        return ClassType::get();
    }
}
