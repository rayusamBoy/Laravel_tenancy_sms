<?php

namespace App\Models;

use App\User;
use Eloquent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SubjectRecord extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['subject_id', 'section_id', 'teacher_id', 'students_ids'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
