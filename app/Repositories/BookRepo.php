<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\BookRequest;

class BookRepo
{
    /*********** Books ***************/

    public function create($data)
    {
        return Book::create($data);
    }

    public function getAll($order_by = 'name')
    {
        return Book::with(['requests', 'my_class'])->orderBy($order_by)->get();
    }

    public function countAll()
    {
        return Book::all()->count();
    }

    public function geWhere($data)
    {
        return Book::where($data)->get();
    }

    public function update($id, $data)
    {
        return Book::find($id)->update($data);
    }

    public function find($id)
    {
        return Book::find($id);
    }

    /*********** Book Rquests ***************/

    public function createRequest($data)
    {
        return BookRequest::create($data);
    }

    public function getRequests()
    {
        return BookRequest::with(['book', 'user', 'borrower'])->get();
    }

    public function allRequests()
    {
        return BookRequest::all();
    }

    public function getRequest($data)
    {
        return BookRequest::with(['book', 'user', 'borrower'])->where($data)->get();
    }

    public function updateRequest($id, $data)
    {
        return BookRequest::find($id)->update($data);
    }

    public function findRequest($id)
    {
        return BookRequest::with(['book', 'user', 'borrower'])->find($id);
    }
}
