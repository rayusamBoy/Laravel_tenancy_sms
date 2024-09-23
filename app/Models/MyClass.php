<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class MyClass extends Eloquent
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'class_type_id'];

    public function section()
    {
        return $this->hasMany(Section::class);
    }

    public function class_type()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }
}
