@extends('layouts.master')
@section('page_title', 'Query Builder')

@section('content')

{{--Query Print--}}
<div class="card card-collapsed">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">print</i> Query Print</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible border-0">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                Print user(s) data by selecting the user(s) from the selection below on any paper size. Make sure to type the suffient number of data records that will fit in the size of paper you chose.
                If the typed number appear to be too large or too small you can always come and change it from here. The default paper size is &rArr; a3 and &rArr; portrait as orientation.
            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="get" target="_blank" action="{{ route('query_builder.print_staff_data') }}">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="user-types">Select user type(s) below to print the user data</label>
                    <select required data-placeholder="Select..." class="form-control select" multiple="multiple" name="user_types[]" id="user-types">
                        @foreach ($user_types as $ut)
                        <option value="{{ $ut->title }}">{{ $ut->name }}s</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="data-per-page">Enter a number (3 - 15)</label>
                    <input class="form-control" type="number" min="3" max="15" name="per_page" id="data-per-page" placeholder="Eg,. 8" required>
                </div>
                <div class="col-md-2 form-group">
                    <label for="paper-size">Select Paper</label>
                    <select data-placeholder="Select..." class="form-control select select-search" name="paper_size" id="paper-size">
                        @foreach(Qs::getDompdfSupportedPaperSizes() as $p_name => $p_size)
                        <option @if ($p_name === "a3") selected @endif value="{{ $p_name }}" title="{{ Qs::convertPointsToCm($p_size[2]) }} (height) x {{ Qs::convertPointsToCm($p_size[3]) }} (width)">{{ $p_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="paper-setup">Paper Orientation (default: portrait)</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            Landscape
                            <input type="checkbox" name="paper_orientation" value="landscape" class="form-input-styled" data-fouc>
                        </label>
                    </div>
                </div>
                <div class="col-md-2 form-group">
                    <div class="text-right mt-md-0">
                        <button type="submit" class="btn btn-primary position-md-absolute right-0 bottom-0">Print <i class="material-symbols-rounded ml-1">print</i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{--Query Select--}}
<div class="card card-collapsed" id="query-box">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">lasso_select</i> Query Select</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="get" action="{{ route('query_builder.select') }}">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group text-center">
                                <input type="text" disabled class="form-control" value="select">
                                <input type="hidden" class="form-control" name="type" value="select" id="type">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select data-placeholder="select" required id="select" name="select[]" multiple="multiple" class="form-control select"></select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <select data-placeholder="from" onchange="getTableColumns(this.value)" required id="from" name="from" class="form-control select">
                                    <option disabled selected>from *</option>
                                    @php $arr = Qs::getTableNames() @endphp
                                    @foreach(array_keys($arr) as $key)
                                    <option value="{{ $key }}">{{ $arr[$key] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select data-placeholder="where" onchange="toggleElDisableState(this.value, '#condition, #input')" required name="where" class="form-control select where"></select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select disabled data-placeholder="condition" data-placeholder="condition" required id="condition" name="condition" class="form-control select">
                                    <option disabled selected>condition</option>
                                    <option value="=">equals</option>
                                    <option value="<>">not equal</option>
                                    <option value=">">greater than</option>
                                    <option value="<">less than</option>
                                    <option value="like">like</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input disabled type="text" class="form-control" placeholder="input" id="input" name="input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group text-center">
                        <input type="text" disabled class="form-control" value="And">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select data-placeholder="where" onchange="toggleElDisableState(this.value, '#condition_two, #input_two')" required name="where_two" class="form-control select where"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select disabled data-placeholder="condition" data-placeholder="condition" required id="condition_two" name="condition_two" class="form-control select condition">
                            <option disabled selected>condition</option>
                            <option value="=">equals</option>
                            <option value="<>">not equal</option>
                            <option value=">">greater than</option>
                            <option value="<">less than</option>
                            <option value="like">like</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input disabled type="text" class="form-control" placeholder="input" id="input_two" name="input_two">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-right input-group form-group">
                        <label class="input-group-text">Limit/Take</label>
                        <input min="0" type="number" value="" class="form-control" placeholder="All (default)" id="limit" name="limit">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="text-right input-group form-group">
                        <label class="input-group-text">Order by</label>
                        <div>
                            <select data-placeholder="id (default)" required name="orderby_column" class="form-control select orderby_column">
                                <option selected value="id">id (default)</option>
                            </select>
                        </div>
                        <div>
                            <select data-placeholder="id (default)" required name="orderby_direction" class="form-control select input-group-text orderby_direction">
                                <option selected value="asc" title="Direction: ascending">ascending</option>
                                <option value="desc" title="Direction: descending">descending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 offset-md-5">
                    <div class="text-right">
                        <button type="submit" disabled class="btn btn-primary cursor-not-allowed">Go <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- Query Results --}}
@if($queryed)
<div class="card w-fit wmin-100-pcnt">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Query Results</h5>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="alert alert-info border-0 alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <span class="cursor-pointer" id="secured-query"><strong>Query - </strong><span class="text-secured">{{ $query }}</span></span>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-payments" class="nav-link active" data-toggle="tab">Results</a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Actions</a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" id="return-to-query" class="dropdown-item" data-toggle="tab">Return to Query Box</a>
                    <button id="display-count" onclick="alertInfo(this.value)" value="{{ $count }}" class="btn dropdown-item" data-toggle="tab">Get Records Count</button>
                </div>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="results">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            @foreach($placeholder as $ph)
                            <th>{{ ucwords(str_replace("_", " ", $ph)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $key)
                        <tr>
                            @foreach(explode(",", $select) as $s)
                            @php
                            $exploded = explode(" ", $s); // Explode any space separated string
                            $s = end($exploded); // Take the last part
                            @endphp
                            @if($s === "photo")
                            <td><img style="width: 60px; height: 60px;" alt="Photo" src="{{ asset($key->$s) }}"></td>
                            @continue
                            @endif
                            {{--Remove square brackets, and replace " with empty string for the values from 'subjects_studied' columns--}}
                            <td>{{ str_replace('"', "", trim($key->$s, "[]")) ?? "-" }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<script type="text/javascript">
    $("select#select").on("change", function() {
        var selected_values = $(this).val();
        const submit_btn = $(this).parents("form").eq(0).find("button[type='submit']");
        // If default value (all) is one of the values and is not the only one (ie., there are more selected)
        if ($.inArray('*', selected_values) != -1 && selected_values.length > 1) {
            $(this).siblings(".select2").find(".select2-selection").addClass("border-danger"); // Add danger border
            submit_btn.prop("disabled", true); // Disable submit button
            return pop({
                msg: "The default selection and individual selections cannot be selected together.", type: "warning"
            }); // Exit with warning message
        }
        // Otherwise
        $(this).siblings(".select2").find(".select2-selection").removeClass("border-danger");
        submit_btn.prop("disabled", false);
    });

</script>

@endsection
