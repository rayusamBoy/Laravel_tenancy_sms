<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookCreate;
use App\Http\Requests\Book\BookUpdate;
use App\Repositories\BookRepo;
use App\Repositories\MyClassRepo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BookController extends Controller implements HasMiddleware
{
    protected $book, $my_class;

    public function __construct(BookRepo $book, MyClassRepo $my_class)
    {
        $this->book = $book;
        $this->my_class = $my_class;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamLibrary')
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d["books"] = $this->book->getAll();
        $d["my_classes"] = $this->my_class->all();

        return view('pages.support_team.books.index', $d);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookCreate $request)
    {
        $data = $request->except(["_token", "_method"]);
        $this->book->create($data);

        return Qs::jsonStoreOk();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($book_id)
    {
        $book_id = Qs::decodeHash($book_id);
        $d["book"] = $this->book->find($book_id);
        $d["my_classes"] = $this->my_class->all();

        return view("pages.support_team.books.edit", $d);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdate $request, $book_id)
    {
        $data = $request->all();
        $this->book->update($book_id, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($book_id)
    {
        $book_id = Qs::decodeHash($book_id);
        $this->book->find($book_id)->delete();

        return Qs::deleteOk('books.index');
    }
}
