@extends('layouts.master')
@section('page_title', 'Manage Book Requests')
@section('content')

<div class="card w-fit wmin-100-pcnt">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Book Requests</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#new-user" class="nav-link active" data-toggle="tab">Add a Rrquest</a></li>

            @if(count($requests) > 0)
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Requests</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach ($requests->groupBy("book.id") as $group)
                    <a href="#grp-{{ $group->first()->id }}" class="dropdown-item" data-toggle="tab">{{ $group->first()->book->name }}</a>
                    @endforeach
                </div>
            </li>
            @endif
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="new-user">
                <form method="post" enctype="multipart/form-data" class="ajax-store" action="{{ route('book_requests.store') }}" data-fouc>
                    @csrf
                    <div class="row">
                        {{--Book--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="book_id"> Book: <span class="text-danger">*</span></label>
                                <select required data-placeholder="Select Book" class="form-control select select-search" name="book_id" id="book_id">
                                    @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }} @if($book->status) - ({{ $book->status }}) @endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--Borrower--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="borrower_id"> Borrower: <span class="text-danger">*</span></label>
                                <select required data-placeholder="Select User" class="form-control select select-search" name="borrower_id" id="borrower_id">
                                    <option value="">Select User</option>
                                    @foreach ($users->chunk(200) as $chunk)
                                    @foreach ($chunk as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--Start date--}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Date: <span class="text-danger">*</span></label>
                                <input required autocomplete="off" id="confirmation-date" name="start_date" value="{{ old('start_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                            </div>
                        </div>
                        {{--End date--}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Date: <span class="text-danger">*</span></label>
                                <input required autocomplete="off" id="confirmation-date" name="end_date" value="{{ old('end_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>

            @foreach ($requests->groupBy("book.id") as $group)
            <div class="tab-pane fade" id="grp-{{ $group->first()->id }}">
                <table class="table datatable-button-html5-columns w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Book</th>
                            <th>Borrower</th>
                            <th>Returned</th>
                            <th>Remarks</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Issued By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($group as $book_req)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $book_req->book->name }}</td>
                            <td><a href="{{ route('users.show', Qs::hash($book_req->borrower->id)) }}">{{ $book_req->borrower->name }}</a></td>
                            <td>{{ $book_req->returned == 1 ? 'Yes' : 'No' }}</td>
                            <td>{{ $book_req->remarks }}</td>
                            <td>{{ $book_req->start_date }}</td>
                            <td>{{ $book_req->end_date }}</td>
                            <td><a href="{{ route('users.show', Qs::hash($book_req->user->id)) }}">{{ $book_req->user->name }}</a></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown">
                                            <i class="material-symbols-rounded">lists</i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- Edit --}}
                                            <a href="{{ route('book_requests.edit', Qs::hash( $book_req->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            {{-- Delete --}}
                                            @if (Qs::userIsSuperAdmin() or Qs::userIsLibrarian())
                                            <a id="{{ Qs::hash( $book_req->id) }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash( $book_req->id) }}" action="{{ route('book_requests.destroy', Qs::hash( $book_req->id)) }}" class="hidden">@csrf @method('delete')</form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach

        </div>
    </div>
</div>

@endsection
