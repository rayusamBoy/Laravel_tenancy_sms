<?php

namespace App\Models;

use App\User;
use Eloquent;

class Event extends Eloquent
{
    protected $fillable = ['name', 'description', 'year', 'month', 'day', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
