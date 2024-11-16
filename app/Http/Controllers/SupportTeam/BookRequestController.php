<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequestCreate;
use App\Http\Requests\Book\BookRequestUpdate;
use App\Repositories\BookRepo;
use App\Repositories\UserRepo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BookRequestController extends Controller implements HasMiddleware
{
    protected $book, $user;

    public function __construct(BookRepo $book, UserRepo $user)
    {
        $this->book = $book;
        $this->user = $user;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamLibrary'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d["requests"] = $this->book->getRequests();
        $d["books"] = $this->book->getAll();
        $d["users"] = $this->user->getUserByTypes(['student', 'teacher', 'companion', 'admin', 'super_admin']);

        return view('pages.support_team.books.requests.index', $d);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequestCreate $request)
    {
        $data = $request->except(["_token", "_method"]);

        $data["user_id"] = auth()->id();
        $this->book->createRequest($data);

        return Qs::jsonStoreOk();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($book_req_id)
    {
        $book_req_id = Qs::decodeHash($book_req_id);
        $d["book_request"] = $this->book->findRequest($book_req_id);
        $d["books"] = $this->book->getAll();

        return view("pages.support_team.books.requests.edit", $d);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequestUpdate $request, $book_req_id)
    {
        $data = $request->all();
        $this->book->updateRequest($book_req_id, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($book_req_id)
    {
        $book_req_id = Qs::decodeHash($book_req_id);
        $this->book->findRequest($book_req_id)->delete();

        return Qs::deleteOk('book_requests.index');
    }
}
