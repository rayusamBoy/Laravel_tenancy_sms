<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    protected $loc, $my_class, $student;

    public function __construct(LocationRepo $loc, MyClassRepo $my_class, StudentRepo $student)
    {
        $this->loc = $loc;
        $this->student = $student;
        $this->my_class = $my_class;
    }

    public function get_table_columns($table_name)
    {
        $columns = Qs::getTableCols($table_name);
        
        return $columns;
    }

    public function get_state($nal_id)
    {
        $lgas = $this->loc->getState($nal_id);

        return $lgas->map(function ($q) {
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_lga($state_id)
    {
        $lgas = $this->loc->getLGAs($state_id);

        return $lgas->map(function ($q) {
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_class_sections($class_id)
    {
        $sections = $this->my_class->getClassSections($class_id);

        return $sections = $sections->map(function ($q) {
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_class_students($class_id)
    {
        $students = $this->student->getRecord(['my_class_id' => $class_id])->get()->whereNotNull('user');

        return $students->map(function ($q) {
            return ['id' => $q->user->id, 'name' => $q->user->name];
        })->all();
    }

    public function get_class_type_subjects($class_type_id)
    {
        $class_type_id = (int) $class_type_id;
        $subjects = match ($class_type_id) {
            1 => null, // Primary level
            2 => Usr::getOLevelSubjects(), // Secondary level
            3 => Usr::getALevelSubjects(), // Advance level
        };

        return $subjects;
    }

    public function get_teacher_class_sections($class_id)
    {
        $sections = $this->my_class->getTeacherClassSections($class_id, Auth::id());

        return $sections->map(function ($q) {
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_subject_section_teacher($subject_id, $section_id)
    {
        $rec = $this->my_class->whereSubjectRecord(['subject_id' => $subject_id, 'section_id' => $section_id])->with('teacher')->get()->first();

        return ['id' => Qs::hash($rec->teacher_id), 'name' => $rec->teacher->name];
    }

    public function get_class_subjects($class_id)
    {
        $sections = $this->my_class->getClassSections($class_id);
        $subjects = (Qs::userIsTeacher()) ? $this->my_class->findSubjectByRecord(Auth::id(), $class_id) : $this->my_class->findSubjectByClass($class_id);

        if (Qs::userIsTeacher()) {
            $records = $sections = $this->my_class->getClassSectionsBySubjectRec(Auth::id(), $class_id);
            // If teacher has a subject to teach for the particular class, but has no sections such that section_id is Null (Not Apllicable). Get all the class sections.
            if (!isset($records->first()->section->id) && $subjects->first() != NULL)
                $sections = $this->my_class->getClassSections($class_id);
        }

        if (Qs::userIsTeacher()) {
            $d['sections'] = (!isset($records->first()->section->id) && $subjects->first() != NULL)
                ? $sections->map(function ($q) {
                    return ['id' => $q->id, 'name' => $q->name];
                })->all()
                : $d['sections'] = $sections->map(function ($q) {
                    return ['id' => $q->section->id, 'name' => $q->section->name];
                })->all();

            $d['subjects'] = $subjects->map(function ($q) {
                return ['id' => $q->subject_id, 'name' => $q->subject->name];
            })->all();
        } else {
            $d['sections'] = $sections->map(function ($q) {
                return ['id' => $q->id, 'name' => $q->name];
            })->all();

            $d['subjects'] = $subjects->map(function ($q) {
                return ['id' => $q->id, 'name' => $q->name];
            })->all();
        }

        return $d;
    }
}
