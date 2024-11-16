<?php

namespace App\Models;

use App\User;
use Eloquent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StaffRecord extends Eloquent
{
    use LogsActivity;

    protected $fillable = ['code', 'emp_date', 'emp_no', 'user_id', 'confirmation_date', 'licence_number', 'file_number', 'bank_acc_no', 'tin_number', 'education_level', 'year_graduated', 'ss_number', 'college_attended', 'primary_id', 'secondary_id', 'role', 'dob', 'subjects_studied', 'bank_name', 'no_of_periods', 'lga_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
