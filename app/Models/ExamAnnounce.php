<?php

namespace App\Models;

use Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExamAnnounce extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['exam_id', 'message', 'duration'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
