<?php

namespace App\Models;

use Eloquent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TimeTableRecord extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['name', 'my_class_id', 'exam_id', 'year', 'section_id'];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
