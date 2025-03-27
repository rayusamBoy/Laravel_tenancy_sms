@extends('layouts.master')

@section('page_title', 'Edit Book')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Edit Book</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form method="post" enctype="multipart/form-data" class="ajax-update" action="{{ route('books.update', $book->id) }}" data-fouc>
                    @csrf @method('PUT')
                    <div class="row">
                        {{--Class--}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="class"> Class: <span class="text-danger">*</span></label>
                                <select required data-placeholder="Select Class" class="form-control select" name="my_class_id" id="class">
                                    @foreach ($my_classes as $class)
                                    <option @if($book->my_class->id == $class->id) selected @endif value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--Name--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name: <span class="text-danger">*</span></label>
                                <input value="{{ $book->name }}" required type="text" name="name" placeholder="Book Name" class="form-control text-capitalize">
                            </div>
                        </div>
                        {{--Description--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea class="form-control" name="description">{{ $book->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{--Author--}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Author: <span class="text-danger">*</span></label>
                                <input value="{{ $book->author }}" type="author" name="author" class="form-control" placeholder="Author or Authors" required>
                            </div>
                        </div>
                        {{--Type--}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Book Type: </label>
                                <input value="{{ $book->book_type }}" type="text" name="book_type" class="form-control" placeholder="Book Type">
                            </div>
                        </div>
                        {{--Url--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Url:</label>
                                <input value="{{ $book->url }}" type="text" name="url" class="form-control" placeholder="Url">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Total Copies --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="d-block">Total Copies:</label>
                                <input value="{{ $book->total_copies }}" type="number" min="1" name="total_copies" class="form-control" placeholder="Eg., 123">
                            </div>
                        </div>
                        {{-- Status --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="d-block">Status:</label>
                                <select required data-placeholder="Select..." class="form-control select" name="status" id="class">
                                    @foreach (Usr::getBookStatuses() as $key => $value)
                                    <option title="{{ $value }}" @if($book->status == $key) selected @endif value="{{ $key }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Location --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location:</label>
                                <input value="{{ $book->location }}" type="text" name="location" class="form-control" placeholder="Location">
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button id="ajax-btn" type="submit" data-text="Updating..." class="btn btn-primary">Update <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--Book Edit Ends--}}

@endsection
