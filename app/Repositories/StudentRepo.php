<?php

namespace App\Repositories;

use App\Helpers\Qs;
use App\Models\Dorm;
use App\Models\Promotion;
use App\Models\StudentRecord;

class StudentRepo
{
    /*********** Student record ***************/

    public function all()
    {
        return StudentRecord::with(['my_class', 'section', 'user'])->get();
    }

    public static function getRecordValue($id, $col_name)
    {
        return StudentRecord::where('id', $id)->value($col_name);
    }

    public function findStudentsByClass($class_id)
    {
        // Get all students by class except deleted ones. If user deleted, the user will return null, hence exclude null from the collection
        return $this->activeStudents()->where(['my_class_id' => $class_id])->with(['my_class', 'user', 'my_parent'])->get()->whereNotNull('user')->sortBy('user.name');
    }

    public function activeStudents()
    {
        return StudentRecord::where(['grad' => 0]);
    }

    public function getStudentsRecs()
    {
        return StudentRecord::where(['grad' => 0]);
    }

    public function gradStudents()
    {
        return Promotion::where(['grad' => 1]);
    }

    public function allActiveStudents()
    {
        return $this->activeStudents()->with(['my_class', 'section', 'user'])->get()->sortBy('user.name');
    }

    public function allGradOnlyStudents()
    {
        return $this->gradStudents()->get();
    }

    public function allGradStudents()
    {
        return $this->gradStudents()->with(['student', 'user', 'fc', 'tc', 'fs', 'ts'])->get()->sortBy('user.name');
    }

    public function findStudentsBySection($sec_id)
    {
        return $this->activeStudents()->where('section_id', $sec_id)->with(['user', 'my_class'])->get();
    }

    public function createRecord($data)
    {
        return StudentRecord::create($data);
    }

    public function updateRecord($value, array $data, $column = 'id')
    {
        return StudentRecord::where($column, $value)->update($data);
    }

    public function updateOrCreateRecord(array $where, array $data)
    {
        return StudentRecord::updateOrCreate($where, $data);
    }

    public function getRecord(array $where)
    {
        return $this->activeStudents()->where($where)->with(['user', 'my_parent']);
    }

    public function getRecord2(array $where)
    {
        return StudentRecord::where(['grad' => 1])->where($where)->with('user');
    }

    public function getRecordByUserIDs($ids)
    {
        return $this->activeStudents()->whereIn('user_id', $ids)->with('user');
    }

    public function getRecordByUserIDs2($ids)
    {
        return StudentRecord::whereIn('user_id', $ids)->with('user');
    }

    public function getAll()
    {
        return $this->activeStudents()->with('user');
    }

    public function getGradRecord($data = [])
    {
        return $this->gradStudents()->where($data)->with('user');
    }

    public function getAllDorms()
    {
        return Dorm::orderBy('name', 'asc')->get();
    }

    public function exists($student_id)
    {
        $exists_as_active = $this->getRecord(['user_id' => $student_id])->exists();
        return $exists_as_active == true ? $exists_as_active : $this->getRecord2(['user_id' => $student_id])->exists();
    }

    /************* Promotions *************/

    public function createPromotion(array $data)
    {
        return Promotion::create($data);
    }

    public function updatePromotion(array $where, array $data)
    {
        return Promotion::where($where)->update($data);
    }

    public function findPromotion($id)
    {
        return Promotion::find($id);
    }

    public function deletePromotion(int $id)
    {
        return Promotion::destroy($id);
    }

    public function getAllPromotions()
    {
        return Promotion::with(['student', 'fc', 'tc', 'fs', 'ts'])->where(['from_session' => Qs::getCurrentSession(), 'to_session' => Qs::getNextSession()])->get();
    }

    public function getPromotions(array $where)
    {
        return Promotion::where($where)->get();
    }
}
