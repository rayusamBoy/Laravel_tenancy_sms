<?php

namespace App\Repositories;


use App\Models\Setting;

class SettingRepo
{
    /*********** Setting ***************/

    public function update($type, $desc)
    {
        return Setting::where('type', $type)->update(['description' => $desc]);
    }

    public function getSetting(string $type)
    {
        return Setting::where('type', $type)->get();
    }

    public function all()
    {
        return Setting::all();
    }

    public function pluck($column, $key = null)
    {
        return Setting::pluck($column, $key);
    }
}
