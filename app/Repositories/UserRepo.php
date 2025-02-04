<?php

namespace App\Repositories;

use App\Models\BloodGroup;
use App\Models\Notification;
use App\Models\ParentRelative;
use App\Models\StaffRecord;
use App\Models\UserType;
use App\User;

class UserRepo
{
    /*********** User ***************/

    public function getIds()
    {
        return User::pluck('id');
    }

    public function update(int $value, array $data, $column = 'id')
    {
        return User::where($column, $value)->update($data);
    }

    public function updateByIds(mixed $ids, array $data)
    {
        return User::whereIn('id', $ids)->update($data);
    }

    public function delete(int $id)
    {
        return User::destroy($id);
    }

    public function restore(int $id)
    {
        return User::where('id', $id)->restore();
    }

    public function forceDelete(int $id)
    {
        return User::where('id', $id)->forceDelete();
    }

    public function get(mixed $where)
    {
        return User::where($where)->get();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function firstOrCreate(array $data)
    {
        return User::firstOrCreate($data);
    }

    public function updateOrCreate(array $where, array $data)
    {
        return User::updateOrCreate($where, $data);
    }

    public function firstOrNew(array $data)
    {
        return User::firstOrNew($data);
    }

    public function getUserByType(string $type)
    {
        return User::where(['user_type' => $type])->orderBy('name', 'asc')->get();
    }

    public function getUserByTypes(array $types)
    {
        return User::whereIn('user_type', $types)->orderBy('name', 'asc')->get();
    }

    public function find(int $id)
    {
        return User::find($id);
    }

    public function findOnlyTrashed(int $id)
    {
        return User::onlyTrashed()->where('id', $id)->first();
    }

    public function all()
    {
        return User::all();
    }

    public function getPTAUsers()
    {
        return User::with('staff')->where('user_type', '<>', 'student')->orderBy('name', 'asc')->get();
    }

    public function isStudent(int $id)
    {
        return User::where(['id' => $id, 'user_type' => 'student'])->exists();
    }

    public function getTrashed()
    {
        return User::onlyTrashed()->get();
    }

    /*********** User type ***************/

    public function getAllTypes()
    {
        return UserType::all();
    }

    public function getAllNotStudentType()
    {
        return UserType::where('title', '!=', 'student')->get();
    }

    public function findType(int $id)
    {
        return UserType::find($id);
    }

    /********** STAFF RECORD ********/

    public function createStaffRecord(array $data)
    {
        return StaffRecord::create($data);
    }

    public function updateStaffRecord(array $where, array $data)
    {
        return StaffRecord::where($where)->update($data);
    }

    public function getStaffRecord(array $where)
    {
        return StaffRecord::where($where)->with(['user', 'lga'])->get();
    }

    public function deleteStaffRecord(array $where)
    {
        return StaffRecord::where($where)->delete();
    }

    /********** BLOOD GROUPS ********/

    public function getBloodGroups()
    {
        return BloodGroup::orderBy('name')->get();
    }

    /********** PARENT CLOSE RELATIVE ********/

    public function createParentRelativeRecord($data)
    {
        return ParentRelative::create($data);
    }

    public function updateParentRelativeRec($where, $data)
    {
        return ParentRelative::where($where)->update($data);
    }

    /********** Notifications ********/

    public function deleteNotification(mixed $id)
    {
        return Notification::find($id)->delete();
    }

    public function getNotification(array $where)
    {
        return Notification::where($where)->get();
    }

    public function getDeviceTokens()
    {
        return User::whereNotNull("firebase_device_token")->pluck("firebase_device_token")->toArray();
    }
}
