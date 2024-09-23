<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\User;
use Eloquent;

class ParentRelative extends Eloquent
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'phone3', 'phone4', 'relation'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
