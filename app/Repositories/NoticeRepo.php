<?php

namespace App\Repositories;

use App\Models\Notice;
use Auth;
use Qs;

class NoticeRepo
{
    /*********** Notices ***************/

    public function all()
    {
        return Qs::userIsSuperAdmin() ? Notice::all() : Notice::all()->where("from_id", "!=", Auth::id());
    }

    public function allOrOnlyMine()
    {
        return Qs::userIsSuperAdmin() ? Notice::all() : Notice::all()->where("from_id", Auth::id());
    }

    public function allExceptAuth()
    {
        return Qs::userIsSuperAdmin() ? Notice::with('user')->get() : Notice::with('user')->get()->where("from_id", "!=", Auth::id());
    }

    public function getIds()
    {
        return Notice::pluck('id');
    }

    public function getById($id)
    {
        return Notice::where('id', $id)->get();
    }

    public function create($data)
    {
        return Notice::create($data);
    }

    public function update($id, $data)
    {
        return Notice::find($id)->update($data);
    }

    public function delete($id)
    {
        return Notice::destroy($id);
    }
}
