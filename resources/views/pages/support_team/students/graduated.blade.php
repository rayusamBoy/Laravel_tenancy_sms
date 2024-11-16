@extends('layouts.master')
@section('page_title', 'Graduated Students')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Students Graduated</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-students" class="nav-link active" data-toggle="tab">All Graduated Students</a></li>
            <li class="nav-item dropdown">
                <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown">Select Class</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($my_classes as $c)
                    <a href="#c{{ $c->id }}" class="dropdown-item" data-toggle="tab">{{ $c->name }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            {{-- Block all students & Unblock all students--}}
            @if (Qs::userIsTeamSA() && $grad_students->isNotEmpty())
            <div class="card">
                <div class="card-body text-center">
                    <button id="students-block-all-grad" value="block_all_grad" class="btn btn-danger btn-large">Block All Graduated Students</button>
                    <button id="students-unblock-all-grad" value="unbloack_all_grad" class="btn btn-success btn-large">Unlock All Graduated Students</button>
                </div>
            </div>
            @endif

            <div class="tab-pane fade show active" id="all-students">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM No</th>
                            <th>Section</th>
                            <th>Grad Year</th>
                            <th>Remarks</th>
                            @if (Qs::userIsTeamSA())
                            <th>Blocked</th>
                            @endif
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grad_students as $gs)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ tenant_asset($gs->user->photo) }}" alt="photo"></td>
                            <td>{{ $gs->user->name }}</td>
                            <td>{{ $gs->student->adm_no }}</td>
                            <td>{{ $gs->fc->name.' '.$gs->fs->name }}</td>
                            <td>{{ $gs->from_session }}</td>
                            <td>{{ $gs->remarks }}</td>
                            @if (Qs::userIsTeamSA())
                            <td><label class="form-switch m-0"><input id="checkbox-user-{{ $gs->student_id }}" onchange="updateUserBlockedState(<?php echo $gs->student_id ?>, this)" type="checkbox" @if($gs->student->blocked) checked @endif><i></i></label></td>
                            @endif
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            <a href="{{ route('students.show', Qs::hash($gs->student_id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('students.edit', Qs::hash($gs->student_id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            <a href="javascript:;" data-default_pass="student" data-href="{{ route('st.reset_pass', Qs::hash($gs->student_id)) }}" class="dropdown-item needs-reset-pass-confirmation"><i class="material-symbols-rounded">lock_reset</i> Reset password</a>

                                            {{--Not Graduated--}}
                                            <a id="{{ Qs::hash($gs->student_id) }}" href="javascript:;" onclick="confirmReset(this.id)" class="dropdown-item"><i class="material-symbols-rounded">close</i> Not Graduated</a>
                                            <form method="post" id="item-reset-{{ Qs::hash($gs->student_id) }}" action="{{ route('st.not_graduated', [Qs::hash($gs->student_id), $gs->id]) }}" class="hidden">@csrf @method('put')</form>
                                            @endif

                                            <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($gs->user_id)) }}" class="dropdown-item"><i class="material-symbols-rounded">bottom_sheets</i> Marksheet</a>

                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ Qs::hash($gs->user->id) }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($gs->user->id) }}" action="{{ route('students.destroy', Qs::hash($gs->user->id)) }}" class="hidden">@csrf @method('delete')</form>
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

            @foreach($my_classes as $mc)
            <div class="tab-pane fade" id="c{{$mc->id}}">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM No</th>
                            <th>Section</th>
                            <th>Grad Year</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grad_students->where('from_class', $mc->id) as $gs)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ tenant_asset($gs->user->photo) }}" alt="photo"></td>
                            <td>{{ $gs->user->name }}</td>
                            <td>{{ $gs->student->adm_no }}</td>
                            <td>{{ $gs->fc->name.' '.$gs->fs->name }}</td>
                            <td>{{ $gs->grad_date }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            <a href="{{ route('students.show', Qs::hash($gs->student_id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('students.edit', Qs::hash($gs->student_id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            <a href="{{ route('st.reset_pass', Qs::hash($gs->student_id)) }}" class="dropdown-item"><i class="material-symbols-rounded">lock_reset</i> Reset password</a>

                                            {{--Not Graduated--}}
                                            <a id="{{ Qs::hash($gs->student_id) }}" href="javascript:;" onclick="confirmReset(this.id)" class="dropdown-item"><i class="material-symbols-rounded">close</i> Not Graduated</a>
                                            <form method="post" id="item-reset-{{ Qs::hash($gs->student_id) }}" action="{{ route('st.not_graduated', [Qs::hash($gs->student_id), $gs->id]) }}" class="hidden">@csrf @method('put')</form>
                                            @endif

                                            <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($gs->user_id)) }}" class="dropdown-item"><i class="material-symbols-rounded">bottom_sheets</i> Marksheet</a>

                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ Qs::hash($gs->student_id) }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($gs->student_id) }}" action="{{ route('students.destroy', Qs::hash($gs->student_id)) }}" class="hidden">@csrf @method('delete')</form>
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
    /* Block all graduated students */
    $('#students-block-all-grad').on('click', function() {
        let cid = $(this).val();
        confirmRequest(cid, 'block');
        return false;
    });

    /* Unblock all graduated students */
    $('#students-unblock-all-grad').on('click', function() {
        let cid = $(this).val();
        confirmRequest(cid, "unblock")
        return false;
    });

    function ajaxProcessRequest(class_id, type) {
        if (type === "block")
            var url = "{{ route('students.block_all_graduated') }}";
        if (type === "unblock")
            var url = "{{ route('students.unblock_all_graduated') }}";
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
            var text = "This will block all graduated students from accessing the system";
        if (type === "unblock")
            var text = "This will allow all graduated student to access the system";
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
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                    return ajaxProcessRequest(class_id, type);
            };
        });
    }

</script>
@endsection

@endsection
