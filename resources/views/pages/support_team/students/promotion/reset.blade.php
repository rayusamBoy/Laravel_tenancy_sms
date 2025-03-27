@extends('layouts.master')

@section('page_title', 'Manage Promotions')

@section('content')

{{--Reset All--}}
@if($promotions->count() > 3)
<div class="card">
    <div class="card-body text-center">
        <button id="promotion-reset-all" class="btn btn-danger btn-large">Reset All Promotions for the Session</button>
    </div>
</div>
<form class="page-block" id="promotion-reset-all" method="post" action="{{ route('students.promotion_reset_all') }}">@csrf @method('DELETE')</form>
@endif

{{-- Reset Promotions --}}
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-semibold">Manage Promotions - Students Who Were Promoted From <span class="text-danger">{{ $old_year }}</span> TO <span class="text-success">{{ $new_year }}</span> Session
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">

        <table id="promotions-list" class="table datatable-button-html5-columns">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>From Class</th>
                    <th>To Class</th>
                    <th>Status</th>
                    <th>Date Promoted</th>
                    <th>Remarks</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotions->sortBy('user.name') as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ tenant_asset($p->user->photo) }}" alt="photo"></td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->fc->name.' '.$p->fs->name }}</td>
                    <td>{{ $p->tc->name.' '.$p->ts->name }}</td>
                    @if($p->status === 'P')
                    <td><span class="text-success">Promoted</span></td>
                    @elseif($p->status === 'D')
                    <td><span class="text-danger">Not Promoted</span></td>
                    @else
                    <td><span class="text-primary">Graduated</span></td>
                    @endif
                    <td>{{ Qs::onlyDateFormat($p->created_at) }}</td>
                    <td>
                        <form id="{{ Qs::hash($p->id) }}" method="post" class="ajax-update" action="{{ route('students.promotion_remarks', Qs::hash($p->id)) }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 input-group">
                                    <textarea rows="1" class="form-control form-control-sm" name="remarks" placeholder="Add remarks">{{ $p->remarks }}</textarea>
                                    <button data-text="Updating..." class="btn input-group-text" type="submit" data-toggle="" title="Update Remarks"><i class="material-symbols-rounded">send</i></button>
                                </div>
                            </div>
                        </form>
                    </td>
                    <td class="text-center">
                        <button data-id="{{ $p->id }}" class="btn btn-danger promotion-reset">Reset</button>
                        <form class="page-block" id="promotion-reset-{{ $p->id }}" method="post" action="{{ route('students.promotion_reset', $p->id) }}">@csrf @method('DELETE')</form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')

<script>
    /* Single Reset */
    $('.promotion-reset').on('click', function() {
        let pid = $(this).data('id');
        confirmReset(pid, 'item');
        return false;
    });

    /* Reset All Promotions */
    $('#promotion-reset-all').on('click', function() {
        confirmReset(null, "all")
        return false;
    });

    function confirmReset(id, type) {
        if (type === "item")
            var text = "This will reset this item to default state";
        if (type === "all")
            var text = "This will reset all the items to default state";
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
                blockUI();
                if (type === "item")
                    return $('form#promotion-reset-' + id).submit();
                if (type === "all")
                    return $('form#promotion-reset-all').submit();
            };
        });
    }

</script>

@endsection
