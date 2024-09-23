<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Group extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function hasUser($user_id)
    {
        foreach ($this->users as $user) {
            if($user->id == $user_id) {
                return true;
            }
        }
    }
}
