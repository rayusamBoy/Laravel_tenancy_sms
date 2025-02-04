<?php

namespace App\Models;

use App\User;
use Eloquent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pin extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['code', 'user_id', 'student_id', 'times_used', 'used'];

    public function user($foreign = NULL)
    {
        return $this->belongsTo(User::class, $foreign);
    }

    public function student()
    {
        return $this->user('student_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
