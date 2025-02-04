<?php

namespace App\Models;

use Eloquent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Grade extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['name', 'class_type_id', 'mark_from', 'mark_to', 'remark', 'point', 'credit'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
