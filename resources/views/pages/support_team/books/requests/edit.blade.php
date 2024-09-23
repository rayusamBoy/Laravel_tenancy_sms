@extends('layouts.master')
@section('page_title', 'Edit Book Request')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Edit Book Request</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form method="post" enctype="multipart/form-data" class="ajax-update" action="{{ route('book_requests.update', $book_request->id) }}" data-fouc>
                    @csrf @method('PUT')
                    <div class="row">
                        {{--Book--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="book"> Book: <span class="text-danger">*</span></label>
                                <input disabled class="form-control" id="book" value="{{ $book_request->book->name }}" type="text">
                            </div>
                        </div>
                        {{--Borrower--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="borrower_id"> Borrower: <span class="text-danger">*</span></label>
                                <input disabled class="form-control" id="borrower_id" value="{{ $book_request->borrower->name }}" type="text">
                            </div>
                        </div>
                        {{--Start date--}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Date:</label>
                                <input autocomplete="off" id="confirmation-date" name="start_date" value="{{ $book_request->start_date }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                            </div>
                        </div>
                        {{--End date--}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Date:</label>
                                <input autocomplete="off" id="confirmation-date" name="end_date" value="{{ $book_request->end_date }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{--Returned--}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="returned" class="font-weight-semibold">Returned</label>
                                <div class="form-group text-center">
                                    <select class="form-control select" name="returned" id="returned">
                                        <option @if($book_request->returned == 1) selected @endif value="1">Yes</option>
                                        <option @if($book_request->returned == 0) selected @endif value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{--Remarks--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Remarks: </label>
                                <textarea name="remarks" class="form-control" placeholder="Remarks">{{ $book_request->remarks }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button id="ajax-btn" type="submit" class="btn btn-primary">Update <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--Book Edit Ends--}}

@endsection
