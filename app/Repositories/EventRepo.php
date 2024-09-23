<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepo
{
    /*********** Events ***************/

    public function countAll()
    {
        return Event::all()->count();
    }

    public function create($data)
    {
        return  Event::create($data);
    }

    public function getRecord($data)
    {
        return  Event::where($data)->get();
    }

    public function all()
    {
        return Event::with('user')->get();
    }

    public function update($id, $data)
    {
        return Event::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Event::destroy($id);
    }
}
