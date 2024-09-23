<?php

namespace App\Models;

use App\User;
use Eloquent;

class LoginHistory extends Eloquent
{
    protected $fillable = ['user_id', 'login_times', 'last_login', 'last_reset'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
