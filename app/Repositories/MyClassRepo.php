<?php

namespace App\Repositories;

use App\Models\ClassType;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\SubjectRecord;

class MyClassRepo
{
    /*********** My class ***************/

    public function all()
    {
        return MyClass::with(['class_type', 'section'])->orderBy('name', 'asc')->get();
    }

    public function get($where)
    {
        return MyClass::where($where)->get();
    }

    public function getMC($where)
    {
        return MyClass::where($where)->with('section');
    }

    public function getMCByIds($ids)
    {
        return MyClass::whereIn("id", $ids)->get();
    }

    public function getTrashed()
    {
        return MyClass::onlyTrashed()->get();
    }

    public function restore($id)
    {
        return MyClass::where('id', $id)->restore();
    }

    public function forceDelete($id)
    {
        return MyClass::where('id', $id)->forceDelete();
    }

    public function find($id)
    {
        return MyClass::find($id);
    }

    public function create($data)
    {
        return MyClass::create($data);
    }

    public function update($id, $data)
    {
        return MyClass::find($id)->update($data);
    }

    public function delete($id)
    {
        return MyClass::destroy($id);
    }

    /*********** Student record ***************/

    public function sectionHasStudent($section_id)
    {
        return StudentRecord::where('section_id', $section_id)->exists();
    }

    /*********** Class type ***************/

    public function getTypes()
    {
        return ClassType::orderBy('name', 'asc')->get();
    }

    public function findType($class_type_id)
    {
        return ClassType::find($class_type_id);
    }

    public function findTypeByClass($class_id)
    {
        return ClassType::find($this->find($class_id)->class_type_id);
    }

    /************* Section *******************/

    public function createSection(array $data)
    {
        return Section::create($data);
    }

    public function getSections(array $data)
    {
        return Section::where($data)->get();
    }

    public function findSection(int $id)
    {
        return Section::find($id);
    }

    public function updateSection(int $id, array $data)
    {
        return Section::find($id)->update($data);
    }

    public function deleteSection(int $id)
    {
        return Section::destroy($id);
    }

    public function isActiveSection(int $section_id)
    {
        return Section::where(['id' => $section_id, 'active' => 1])->exists();
    }

    public function getAllSections()
    {
        return Section::orderBy('name', 'asc')->with(['my_class', 'teacher'])->get();
    }

    public function getClassSections($class_id)
    {
        return Section::where(['my_class_id' => $class_id])->orderBy('name', 'asc')->get();
    }

    public function getTeacherClassSections($class_id, $teacher_id)
    {
        return Section::where(['my_class_id' => $class_id, 'teacher_id' => $teacher_id])->orderBy('name', 'asc')->get();
    }

    /************* Subject *******************/

    public function createSubject(array $data)
    {
        return Subject::create($data);
    }

    public function findSubject(int $id)
    {
        return Subject::whereId($id);
    }

    public function findSubjectByClass(int $class_id, string $order_by = 'name')
    {
        return $this->getSubject(['my_class_id' => $class_id])->orderBy($order_by)->get();
    }

    public function findSubjectsByTeacher(int $user_id)
    {
        $subject_ids = $this->whereSubjectRecord(['teacher_id' => $user_id])->get()->pluck('subject_id');
        return Subject::whereId('id', $subject_ids)->get();
    }

    public function getSubject(array $where)
    {
        return Subject::where($where);
    }

    public function getSubjectWithRecsWhereHas($section_id, $class_id)
    {
        return Subject::with('record')->whereHas('record', function ($query) use ($section_id) {
            $query->where('section_id', $section_id)->orWhere('section_id', NULL);
        })->where('my_class_id', $class_id)->get();
    }

    public function getSubjectsByIDs($ids)
    {
        return Subject::with('record')->whereIn('id', $ids)->orderBy('name')->get();
    }

    public function updateSubject(int $id, array $data)
    {
        return Subject::find($id)->update($data);
    }

    public function deleteSubject(int $id)
    {
        return Subject::destroy($id);
    }

    public function getAllSubjects()
    {
        return Subject::orderBy('name', 'asc')->with(['my_class', 'record'])->get();
    }

    public function getUniqueSubjectsNames()
    {
        return Subject::select('name')->orderBy('name', 'asc')->distinct()->with('my_class')->get();
    }

    public function getSubjectData($remove = [])
    {
        $data = ['name', 'slug', 'my_class_id', 'core'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public function getSubjectRecordData($remove = [])
    {
        $data = ['section_id', 'teacher_id', 'students_ids'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    /************* Subject records *******************/

    public function findSubjectRecord($id)
    {
        return SubjectRecord::with('subject')->find($id);
    }

    public function createSubjectRecord(array $data)
    {
        return SubjectRecord::create($data);
    }

    public function whereSubjectRecord(array $data)
    {
        return SubjectRecord::where($data);
    }

    public function getSubjectRecord($data)
    {
        return SubjectRecord::where($data)->with('subject')->get();
    }

    public function findSubjectRecByTeacher($teacher_id, $order_by = 'id')
    {
        return SubjectRecord::where(['teacher_id' => $teacher_id])->with('subject')->orderBy($order_by)->get();
    }

    public function updateSubjectRecord(array $where, array $data)
    {
        return SubjectRecord::where($where)->update($data);
    }

    public function deleteSubjectRecord(array $where)
    {
        return SubjectRecord::where($where)->delete();
    }

    public function getSubjectRecByTeacher($teacher_id, $relations)
    {
        return SubjectRecord::where('teacher_id', $teacher_id)->with($relations)->distinct()->get();
    }

    public function findSubjectByRecord($teacher_id, $class_id, $sort_by = 'subject.name')
    {
        return $this->getSubjectRecByTeacher($teacher_id, 'subject')->where('subject.my_class_id', $class_id)->sortBy($sort_by);
    }

    public function getClassSectionsBySubjectRec($teacher_id, $class_id, $sort_by = 'section.name')
    {
        return $this->getSubjectRecByTeacher($teacher_id, ['section', 'subject'])->where('subject.my_class_id', $class_id)->sortBy($sort_by);
    }
}
