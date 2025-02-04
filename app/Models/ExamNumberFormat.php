<?php

namespace App\Models;

use Eloquent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExamNumberFormat extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['exam_id', 'my_class_id', 'format'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
