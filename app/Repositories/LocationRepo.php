<?php

namespace App\Repositories;

use App\Models\Lga;
use App\Models\Nationality;
use App\Models\State;

class LocationRepo
{
    /*********** State ***************/

    public function getStates()
    {
        return State::all();
    }

    public function getAllStates()
    {
        return State::orderBy('name', 'asc')->get();
    }

    public function getState($nal_id)
    {
        return State::where('nationality_id', $nal_id)->orderBy('name', 'asc')->get();
    }

    /*********** Nationals ***************/

    public function getAllNationals()
    {
        return Nationality::orderBy('name', 'asc')->get();
    }

    /*********** Lgas ***************/

    public function getLGAs($state_id)
    {
        return Lga::where('state_id', $state_id)->orderBy('name', 'asc')->get();
    }
}
