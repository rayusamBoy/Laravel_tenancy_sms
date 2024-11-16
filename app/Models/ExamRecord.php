<?php

namespace App\Models;

use App\User;
use Eloquent;
use Iksaku\Laravel\MassUpdate\MassUpdatable;

class ExamRecord extends Eloquent
{
    use MassUpdatable;
    
    protected $fillable = ['exam_id', 'my_class_id', 'student_id', 'section_id', 'af', 'af_id', 'ps', 'ps_id', 't_comment', 'p_comment', 'year', 'total', 'ave', 'class_ave', 'pos', 'class_pos', 'grade_id', 'points', 'division_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

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
}
