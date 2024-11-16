<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentRelative extends Eloquent
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'phone3', 'phone4', 'relation'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
