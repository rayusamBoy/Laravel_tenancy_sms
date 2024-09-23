<?php

namespace App\Models;

use Eloquent;

class Subject extends Eloquent
{
    protected $fillable = ['name', 'my_class_id', 'slug', 'core'];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function record()
    {
        return $this->hasMany(SubjectRecord::class);
    }
}
