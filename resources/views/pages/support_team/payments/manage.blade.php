@extends('layouts.master')

@section('page_title', 'Student Payments')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title"><i class="material-symbols-rounded mr-2">payments</i> Student Payments</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('payments.select_class') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                <select required id="my_class_id" name="my_class_id" class="form-control select">
                                    <option value="">Select Class</option>
                                    @foreach($my_classes as $c)
                                    <option @selected($selected && $my_class_id==$c->id) value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 mt-4">
                            <div class="text-right mt-1">
                                <button type="submit" class="btn btn-primary">Submit <i class="material-symbols-rounded ml-2">send</i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selected)
<div class="card">
    <div class="card-body">
        <table class="table datatable-button-html5-columns">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>ADM No</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ Usr::getTenantAwarePhoto($s->user->photo) }}" alt="photo"></td>
                    <td>{{ $s->user->name }}</td>
                    <td>{{ $s->adm_no }}</td>
                    <td class="text-center">
                        <div class="dropdown">
                            <a href="javascript:;" data-toggle="dropdown">
                                <i class="material-symbols-rounded">lists</i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-left">
                                {{-- View Profile --}}
                                <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                <hr class="m-0">
                                <span class="dropdown-item opacity-75 disabled">Manage Payments</span>
                                <a href="{{ route('payments.invoice', [Qs::hash($s->user_id)]) }}" class="dropdown-item">All Payments</a>
                                @foreach(Pay::getYears($s->user_id) as $py)
                                @if($py)
                                <a href="{{ route('payments.invoice', [Qs::hash($s->user_id), $py]) }}" class="dropdown-item">{{ $py }}</a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
