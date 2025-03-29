<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Repositories\ExamRepo;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;

class AjaxController extends Controller
{
    protected $loc, $my_class, $student, $exam;

    public function __construct(LocationRepo $loc, MyClassRepo $my_class, StudentRepo $student, ExamRepo $exam)
    {
        $this->loc = $loc;
        $this->student = $student;
        $this->my_class = $my_class;
        $this->exam = $exam;
    }

    public function get_table_columns($table_name)
    {
        $columns = Qs::getTableCols($table_name);

        return $columns;
    }

    public function get_state($nal_id)
    {
        $lgas = $this->loc->getStateByNationalityID($nal_id);

        return $lgas->map(fn($q) => ['id' => $q->id, 'name' => $q->name])->all();
    }

    public function get_lga($state_id)
    {
        $lgas = $this->loc->getLGAs($state_id);

        return $lgas->map(fn($q) => ['id' => $q->id, 'name' => $q->name])->all();
    }

    public function get_class_sections($class_id)
    {
        $sections = $this->my_class->getClassSections($class_id);

        return $sections->map(fn($q) => ['id' => $q->id, 'name' => $q->name])->all();
    }

    public function get_class_students($class_id)
    {
        $students = $this->student->getRecord(['my_class_id' => $class_id])->get()->whereNotNull('user');

        return $students->map(fn($q) => ['id' => $q->user->id, 'name' => $q->user->name])->all();
    }

    public function get_pre_defined_subjects()
    {
        $subjects = Usr::getPredefinedSubjects();

        return $subjects;
    }

    public function get_teacher_class_sections($class_id)
    {
        $sections = $this->my_class->getTeacherClassSections($class_id, auth()->id());

        return $sections->map(fn($q) => ['id' => $q->id, 'name' => $q->name])->all();
    }

    public function get_year_exams($year)
    {
        $exams = $this->exam->getExam(['year' => $year], false);

        return $exams->map(fn($q) => ['id' => $q->id, 'name' => $q->name])->all();
    }

    public function get_subject_section_teacher($subject_id, $section_id)
    {
        $rec = $this->my_class->whereSubjectRecord(['subject_id' => $subject_id, 'section_id' => $section_id])->with('teacher')->get()->first();

        return ['id' => Qs::hash($rec->teacher_id), 'name' => $rec->teacher->name];
    }

    public function get_class_subjects($class_id)
    {
        $subjects = Qs::userIsTeacher()
            ? $this->my_class->findSubjectByRecord(auth()->id(), $class_id)
            : $this->my_class->findSubjectByClass($class_id);

        return $subjects->map(fn($q) => ['id' => $q->id ?? $q->subject->id, 'name' => $q->name ?? $q->subject->name])->all();
    }
}
