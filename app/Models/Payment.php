<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Eloquent
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['title', 'amount', 'my_class_id', 'description', 'year', 'ref_no', 'can_notify_on_pay'];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
