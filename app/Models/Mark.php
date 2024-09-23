<?php

namespace App\Models;

use App\User;
use Eloquent;
use Iksaku\Laravel\MassUpdate\MassUpdatable;

class Mark extends Eloquent
{
    use MassUpdatable;
    
    protected $fillable = ['exm', 'tex1', 'tex2', 'tex3', 'sub_pos', 'cum', 'cum_ave', 'grade_id', 'year', 'exam_id', 'subject_id', 'my_class_id', 'student_id', 'section_id'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
