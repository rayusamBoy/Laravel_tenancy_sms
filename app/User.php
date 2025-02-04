<?php

namespace App;

use App\Models\BloodGroup;
use App\Models\Group;
use App\Models\Lga;
use App\Models\Nationality;
use App\Models\StaffRecord;
use App\Models\State;
use App\Models\StudentRecord;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, Messagable, CanResetPassword, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'phone2',
        'dob',
        'gender',
        'photo',
        'address',
        'bg_id',
        'password',
        'nal_id',
        'state_id',
        'lga_id',
        'code',
        'user_type',
        'email_verified_at',
        'work',
        'religion',
        'blocked',
        'primary_id',
        'secondary_id',
        'twofa_secret_code',
        'twofa_recovery_codes',
        'sidebar_minimized',
        'allow_system_sounds',
        'is_notifiable',
        'show_charts',
        'message_media_heading_color',
        'firebase_device_token',
        'hidden_alert_ids',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'twofa_secret_code',
        'twofa_recovery_codes',
        'firebase_device_token',
    ];

    public function student_record()
    {
        return $this->hasOne(StudentRecord::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nal_id');
    }

    public function blood_group()
    {
        return $this->belongsTo(BloodGroup::class, 'bg_id');
    }

    public function staff()
    {
        return $this->hasMany(StaffRecord::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }

    public function getDeviceTokens()
    {
        return auth()->user()->firebase_device_token === null ? [] : unserialize(auth()->user()->firebase_device_token);
    }

    /**
     * Specifies the user's FCM token
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        return $this->getDeviceTokens();
    }

    /**
     * Route notifications for the Vonage channel.
     */
    public function routeNotificationForVonage(): string
    {
        return auth()->user()->phone ?? auth()->user()->phone2;
    }
}
