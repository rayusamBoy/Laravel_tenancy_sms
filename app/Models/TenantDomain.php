<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TenantDomain extends BaseDomain
{
    use LogsActivity;

    protected $table = "domains";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
