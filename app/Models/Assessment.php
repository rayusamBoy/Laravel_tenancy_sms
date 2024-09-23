<?php

namespace App\Models;

use Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Assessment extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['exam_id'];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function record()
    {
        return $this->hasMany(AssessmentRecord::class, 'assessment_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
