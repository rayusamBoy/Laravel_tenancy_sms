@extends('layouts.master')

@section('page_title', 'Manage Class Sections')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Class Sections</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#new-section" class="nav-link active" data-toggle="tab">Create New Section</a></li>
            <li class="nav-item dropdown">
                <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Sections</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($my_classes as $c)
                    <a href="#c{{ $c->id }}" class="dropdown-item" data-toggle="tab">{{ $c->name }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show  active fade" id="new-section">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('sections.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of Section">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Select Class <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Class" class="form-control select" name="my_class_id" id="my_class_id">
                                        @foreach($my_classes as $c)
                                        <option @selected(old('my_class_id') == $c->id) value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Teacher</label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Teacher" class="form-control select-search" name="teacher_id" id="teacher_id">
                                        <option value=""></option>
                                        @foreach($teachers as $t)
                                        <option @selected(old('teacher_id') == Qs::hash($t->id)) value="{{ Qs::hash($t->id) }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach($my_classes as $d)
            <div class="tab-pane fade" id="c{{ $d->id }}">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Teacher</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections->where('my_class.id', $d->id) as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->name }} @if($s->active)<i class='material-symbols-rounded'>check_small</i>@endif</td>
                            <td>{{ $s->my_class->name }}</td>

                            @if($s->teacher_id)
                            <td><a target="_blank" href="{{ route('users.show', Qs::hash($s->teacher_id)) }}">{{ $s->teacher->name }}</a></td>
                            @else
                            <td> - </td>
                            @endif

                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>
                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{--edit--}}
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('sections.edit', $s->id) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            @endif
                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ $s->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ $s->id }}" action="{{ route('sections.destroy', $s->id) }}" class="hidden">@csrf @method('delete')</form>
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

{{--Section List Ends--}}

@endsection
