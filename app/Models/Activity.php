<?php

namespace App\Models;

use App\User;
use Spatie\Activitylog\Models\Activity as OriginalActivity;

class Activity extends OriginalActivity
{
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}