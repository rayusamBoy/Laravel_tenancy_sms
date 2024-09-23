<?php

namespace App\Models;

use Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Receipt extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['pr_id', 'year', 'balance', 'amt_paid'];

    public function pr()
    {
        return $this->belongsTo(PaymentRecord::class, 'pr_id');
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
