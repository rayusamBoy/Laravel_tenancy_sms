<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = ['name', 'my_class_id', 'description', 'author', 'book_type', 'url', 'location', 'total_copies', 'issued_copies', 'status'];

    public function requests()
    {
        return $this->hasMany(BookRequest::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }
}
