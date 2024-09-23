<?php

namespace App\Models;

use Eloquent;
use App\User;

class Event extends Eloquent
{
    protected $fillable = ['name', 'description', 'year', 'month', 'day', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
