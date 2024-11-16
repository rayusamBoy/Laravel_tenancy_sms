<?php

namespace App\Helpers;

use App\Models\Event;

class Evt
{
    public static function all()
    {
        return Event::all();
    }

    public static function getStatuses()
    {
        return ["new", "completed", "cancelled", "active"];
    }
}
