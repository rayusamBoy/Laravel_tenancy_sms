<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Exam extends Eloquent
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'term',
        'year',
        'category_id',
        'editable',
        'published',
        'locked',
        'class_type_id',
        'exam_denominator',
        'exam_student_position_by_value',
        'ca_student_position_by_value',
        'cw_denominator',
        'hw_denominator',
        'tt_denominator',
        'tdt_denominator',
    ];

    public function category()
    {
        return $this->belongsTo(ExamCategory::class, 'category_id');
    }

    public function number_format()
    {
        return $this->hasMany(ExamNumberFormat::class);
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
