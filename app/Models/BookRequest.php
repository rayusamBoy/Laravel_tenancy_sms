<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    protected $fillable = ['book_id', 'borrower_id', 'user_id', 'start_date', 'end_date', 'returned', 'remarks'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
