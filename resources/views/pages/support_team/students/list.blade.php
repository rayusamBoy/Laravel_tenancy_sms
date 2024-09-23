@extends('layouts.master')
@section('page_title', 'Student Information - '.$my_class->name)
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Students List</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-students" class="nav-link active" data-toggle="tab">All {{ $my_class->name }} Students</a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Sections</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($sections as $s)
                    <a href="#s{{ $s->id }}" class="dropdown-item" data-toggle="tab">{{ $my_class->name.' '.$s->name }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            {{-- Block all active students & Unblock all active students--}}
            @if (Qs::userIsTeamSA())
            <div class="card">
                <div class="card-body text-center">
                    <button id="students-block-all-active" value="{{ $my_class->id }}" class="btn btn-danger btn-large">Block All {{ $my_class->name }} Students</button>
                    <button id="students-unblock-all-active" value="{{ $my_class->id }}" class="btn btn-success btn-large mt-533-3px">Unblock All {{ $my_class->name }} Students</button>
                </div>
            </div>
            @endif

            {{-- All students --}}
            <div class="tab-pane fade show active" id="all-students">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM No</th>
                            <th>Section</th>
                            <th>Parent</th>
                            @if (Qs::userIsTeamSA())
                            <th>Blocked</th>
                            @endif
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students->sortBy('user.gender') as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ tenant_asset($s->user->photo) }}" alt="photo"></td>
                            <td>{{ $s->user->name }}</td>
                            <td>{{ $s->adm_no }}</td>
                            <td>{{ $s->section->name }}</td>
                            <td>@if(!is_null($s->my_parent))<a href="{{ route('users.show', Qs::hash($s->my_parent_id)) }}">{{ $s->my_parent->name }}</a> @else - @endif</td>
                            @if (Qs::userIsTeamSA())
                            <td><label class="form-switch m-0"><input id="checkbox-user-{{ $s->user->id }}" onchange="updateUserBlockedState(<?php echo $s->user->id ?>, this)" type="checkbox" @if($s->user->blocked) checked @endif><i></i></label></td>
                            @endif
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown">
                                            <i class="material-symbols-rounded">lists</i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('students.edit', Qs::hash($s->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            <a href="javascript:;" data-default_pass="student" data-href="{{ route('st.reset_pass', Qs::hash($s->user->id)) }}" class="dropdown-item needs-reset-pass-confirmation"><i class="material-symbols-rounded">lock_reset</i> Reset password</a>
                                            @endif
                                            <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">bottom_sheets</i> Marksheet</a>
                                            <a target="_blank" href="{{ route('assessments.year_selector', Qs::hash($s->user->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">two_pager</i> Assessmentsheet</a>

                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ Qs::hash($s->user->id) }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($s->user->id) }}" action="{{ route('students.destroy', Qs::hash($s->user->id)) }}" class="hidden">@csrf @method('delete')</form>
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

            @foreach($sections as $se)
            <div class="tab-pane fade" id="s{{ $se->id }}">
                <strong class="status-styled">{{ $my_class->name.' '.$se->name }}</strong>
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM No</th>
                            <th>Parent</th>
                            @if (Qs::userIsTeamSA())
                            <th>Blocked</th>
                            @endif
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students->where('section_id', $se->id) as $sr)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ tenant_asset($sr->user->photo) }}" alt="photo"></td>
                            <td>{{ $sr->user->name }}</td>
                            <td>{{ $sr->adm_no }}</td>
                            <td>@if(!is_null($sr->my_parent))<a href="{{ route('users.show', Qs::hash($sr->my_parent_id)) }}">{{ $sr->my_parent->name }}</a> @else - @endif</td>
                            @if (Qs::userIsTeamSA())
                            <td><label class="form-switch m-0"><input id="checkbox-user-{{ $s->user->id }}" onchange="updateUserBlockedState(<?php echo $s->user->id ?>, this)" type="checkbox" @if($s->user->blocked) checked @endif><i></i></label></td>
                            @endif
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown">
                                            <i class="material-symbols-rounded">lists</i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('students.show', Qs::hash($sr->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Info</a>
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('students.edit', Qs::hash($sr->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            <a href="{{ route('st.reset_pass', Qs::hash($sr->user->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">lock_reset</i> Reset password</a>
                                            @endif
                                            <a href="#" class="dropdown-item"><i class="material-symbols-rounded">bottom_sheets</i> Marksheet</a>

                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ Qs::hash($sr->user->id) }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($sr->user->id) }}" action="{{ route('students.destroy', Qs::hash($sr->user->id)) }}" class="hidden">@csrf @method('delete')</form>
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
{{--Student List Ends--}}

@section('scripts')
<script>
    /* Block all active students */
    $('#students-block-all-active').on('click', function() {
        let cid = $(this).val();
        confirmRequest(cid, 'block');
        return false;
    });

    /* Unblock all active students */
    $('#students-unblock-all-active').on('click', function() {
        let cid = $(this).val();
        confirmRequest(cid, "unblock")
        return false;
    });

    function ajaxProcessRequest(class_id, type) {
        if (type === "block")
            var url = "{{ route('students.block_all_class') }}";
        if (type === "unblock")
            var url = "{{ route('students.unblock_all_class') }}";
        $.ajax({
            url: url, 
            type: 'post', 
            data: {
                'class_id': class_id,
                '_token': $('#csrf-token').attr('content')
            }, 
            success: function(resp) {
                if (type === "block")
                    $("label.form-switch input").attr("checked", "checked");
                if (type === "unblock")
                    $("label.form-switch input").removeAttr("checked");
                flash({
                    msg: resp.msg, 
                    type: 'success'
                });
            }
        });
    }

    function confirmRequest(class_id, type) {
        if (type === "block")
            var text = "This will block selected students from accessing the system";
        if (type === "unblock")
            var text = "This will allow selected student to access the system";

        Swal.fire({
            title: "Are you sure?", 
            text: text, 
            focusConfirm: false, 
            returnFocus: false, 
            reverseButtons: true, 
            showCancelButton: true, 
            cancelButtonText: "No, Cancel", 
            customClass: {
                cancelButton: "bg-secondary", 
            }
        }).then((result) => {
            if (result.isConfirmed) {
                    return ajaxProcessRequest(class_id, type);
            };
        });
    }

</script>
@endsection

@endsection
