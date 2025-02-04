<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class TenantDomain extends BaseDomain
{
    use LogsActivity;

    protected $table = "domains";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
