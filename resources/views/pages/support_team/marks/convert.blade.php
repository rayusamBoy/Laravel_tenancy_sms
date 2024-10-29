@extends('layouts.master')
@section('page_title', 'Convert Exam Marks')
@section('content')
<div class="card marks-converter">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Marks Converter</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info border-0 alert-dismissible has-do-not-show-again-button" id="marks-converter-info">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span>
                    When adding items; a positive number if entered will add the items as usual, while a negative number if entered will remove the item(s)
                    starting with the last one depending on the number provided. <br/> <strong>Note:</strong> The converted value will always be rounded to a whole number.
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-xs-5 col-sm-5">
                <div class="form-group">
                    <label class="col-form-label font-weight-semibold w-100 display-i-on-hover">From Out Of <span class="text-danger">*</span>
                        <i class="material-symbols-rounded float-right text-info display-none" data-toggle="tooltip" title="The total score, out of which the current scrore is based (old score demoninator).">info</i>
                    </label>
                    <input class="form-control border-gray-500" placeholder="Eg., 60" type="number" min="0" max="100" id="from-out-of">
                    <div class="invalid-feedback font-size-md"><i class="material-symbols-rounded mr-1">block</i><span></span></div>
                </div>
            </div>

            <div class="offset-2 col-xs-5 col-sm-5">
                <div class="form-group">
                    <label class="col-form-label font-weight-semibold w-100 display-i-on-hover">To Out Of <span class="text-danger">*</span>
                        <i class="material-symbols-rounded float-right text-info display-none" data-toggle="tooltip" title="The overall score, on which the new score should based (new denominator)">info</i>
                    </label>
                    <input class="form-control" placeholder="Eg., 100" type="number" min="0" max="100" id="to-out-of">
                    <div class="invalid-feedback font-size-md"><i class="material-symbols-rounded mr-1">block</i><span></span></div>
                </div>
            </div>
        </div>

        <div class="labels row">
            <div class="col-xs-5 col-sm-5">
                <h6 class="status-styled text-center">Original</h6>
            </div>
            <div class="col-xs-2 col-sm-2 material-symbols-rounded m-auto">line_end_arrow</div>
            <div class="col-xs-5 col-sm-5">
                <h6 class="status-styled text-center item-converted">Converted</h6>
            </div>
        </div>

        <div class="items-wrapper card-columns">
            <div class="row mb-3 item position-relative">
                {{-- <i class="material-symbols-rounded cursor-pointer position-absolute right-2 opacity-50 opacity-100-on-hover transition-all display-none" title="remove item" onclick="$(this).parent().remove();">close</i> --}}
                <div class="col-8 col-sm-5">
                    <input class="form-control item-input" placeholder="Enter a Mark" type="number">
                    <div class="invalid-feedback font-size-md"><i class="material-symbols-rounded mr-1">block</i><span></span></div>
                </div>
                <div class="col-2 col-sm-2 material-symbols-rounded m-auto">line_end_arrow</div>

                <div class="col-2 col-sm-5 m-auto text-blue-800 text-center"><span class="item-output"></span><button disabled data-toggle="tooltip" title="Copy" class="item-copy material-symbols-rounded btn bg-transparent float-right font-size-xl p-0">content_copy</button></div>
            </div>
        </div>

        <div class="d-flex justify-content-between w-100 mt-3">
            <div class="d-flex">
                <span class="input-group-text border-0 bg-transparent">Add</span> 
                <input class="form-control" min="-9999" value="1" max="9999" type="number"></input>
                <span class="input-group-text border-0 bg-transparent">item(s)</span> 
                <span class="input-group-text cursor-pointer" id="add-item">Go</span> 
            </div>
            <div>
                <button id="btn-convert" type="submit" class="btn btn-primary">Convert <i class="material-symbols-rounded ml-2">sync_alt</i></button>
            </div>
        </div>

    </div>
</div>
@endsection