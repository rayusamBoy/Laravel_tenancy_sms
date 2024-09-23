<?php

namespace App\Models;

use Eloquent;

class Division extends Eloquent
{
    protected $fillable = ['name', 'points_from', 'points_to', 'remark', 'class_type_id'];
}
