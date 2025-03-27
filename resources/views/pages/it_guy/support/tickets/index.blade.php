@extends('layouts.master')

@section('page_title', 'Support Tickets')

@section('content')

<div class="card card-collapsed card-ticket">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Define Properties</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" class="ajax-update" action="{{ route('tickets.update_properties') }}">
            @csrf @method('post')

            <div class="row">
                <!-- Categories Section -->
                <div class="col-md-6">
                    <h6>Categories</h6>
                    <div id="categories-wrapper">
                        @foreach($categories as $cat)
                        <div class="category-row row mb-2">
                            <div class="col-md-4 form-group">
                                <input type="text" name="categories[name][]" class="form-control" placeholder="Name" value="{{ $cat->name ?? '' }}">
                            </div>
                            <div class="col-md-5 form-group">
                                <input type="text" name="categories[description][]" class="form-control" placeholder="Description" value="{{ $cat->description ?? '' }}">
                            </div>
                            <div class="col-md-2 form-group">
                                <select name="categories[is_visible][]" class="form-control select">
                                    <option value="1" {{ $cat->is_visible == 1 ? 'selected' : '' }}>Visible</option>
                                    <option value="0" {{ $cat->is_visible == 0 ? 'selected' : '' }}>Hidden</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-danger remove-category-row material-symbols-rounded float-right" title="Remove Category">remove</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-category" class="btn btn-primary mt-2 btn-sm float-right"><span class="material-symbols-rounded mr-1">add</span>Category</button>
                </div>

                <!-- Labels Section -->
                <div class="col-md-6 mt-2 mt-md-0">
                    <h6>Labels</h6>
                    <div id="labels_wrapper">
                        @foreach($labels as $lab)
                        <div class="label-row row mb-2">
                            <div class="col-md-4 form-group">
                                <input type="text" name="labels[name][]" class="form-control" placeholder="Name" value="{{ $lab->name ?? '' }}">
                            </div>
                            <div class="col-md-5 form-group">
                                <input type="text" name="labels[description][]" class="form-control" placeholder="Description" value="{{ $lab->description ?? '' }}">
                            </div>
                            <div class="col-md-2 form-group">
                                <select name="labels[is_visible][]" class="form-control select">
                                    <option value="1" {{ $lab->is_visible == 1 ? 'selected' : '' }}>Visible</option>
                                    <option value="0" {{ $lab->is_visible == 0 ? 'selected' : '' }}>Hidden</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-danger remove-label-row material-symbols-rounded float-right" title="Remove Label">remove</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-label" class="btn btn-primary mt-2 btn-sm float-right"><span class="material-symbols-rounded mr-1">add</span>Label</button>
                </div>
            </div>

            <div class="form-group row float-right mt-3">
                <div class="col-md-6">
                    {{-- Submit button --}}
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-success">Submit<span class="material-symbols-rounded ml-2 pb-2px">send</span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Tickets</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-users">
                <table class="table datatable-button-html5-image">
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Assignee</th>
                            <th>Owner</th>
                            <th>Archived</th>
                            <th>Locked</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $displayed_assignees = [];
                        $displayed_owners = [];
                        $displayed_tenants = [];
                        @endphp
                        @foreach($tickets as $ticket)
                        @php
                        $tenant = json_decode($ticket->tenant);
                        @endphp
                        <tr>
                            <td>
                                @if(!in_array($tenant->id, $displayed_tenants))
                                @php
                                $displayed_tenants[] = $tenant->id;
                                @endphp
                                <a target="_blank" href="{{ route('tenants.show', Qs::hash($tenant->id)) }}">{{ $tenant->name }}</a>
                                @else
                                <span>{{ $tenant->name }}</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($ticket->status) }}</td>
                            <td>{{ ucfirst($ticket->priority) }}</td>
                            <td>
                                @if($ticket->assigned_user)
                                @if(!in_array($ticket->assigned_user->id, $displayed_assignees))
                                @php
                                $displayed_assignees[] = $ticket->assigned_user->id;
                                @endphp
                                <a target="_blank" href="{{ route('users.show', Qs::hash($ticket->assigned_user->id)) }}">{{ $ticket->assigned_user->name }}</a>
                                @else
                                <span>{{ $ticket->assigned_user->name }}</span>
                                @endif
                                @else
                                <span>none</span>
                                @endif
                            </td>
                            <td>
                                @php
                                $owner = $ticket->get_tenant_user($tenant->tenancy_db_name, $ticket->user_id);
                                @endphp
                                @if(!in_array($ticket->user_id, $displayed_owners))
                                @php
                                $displayed_owners[] = $ticket->user_id;
                                @endphp
                                @include("pages.modals.ticket_owner")
                                <a href="javascript:;" data-toggle="modal" data-target="#ticket-owner-modal-{{ $owner->id }}">{{ $owner->name }}</a>
                                @else
                                <span>{{ $owner->name }}</span>
                                @endif
                            </td>
                            <td>{{ $ticket->is_archived == 1 ? 'Yes' : 'No' }}</td>
                            <td>{{ $ticket->is_locked == 1 ? 'Yes' : 'No' }}</td>
                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>
                                        {{-- Information Modal --}}
                                        @include('pages.modals.ticket_information')

                                        <div class="dropdown-menu dropdown-menu-left">

                                            @if(!$ticket->is_closed())
                                            {{-- Answer --}}
                                            <a href="{{ route('tickets.answer', Qs::hash($ticket->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">person_raised_hand</i> Answer</a>
                                            @endif

                                            {{-- Information --}}
                                            <a href="javascript:;" data-toggle="modal" data-target="#ticket-info-modal-{{ $ticket->id }}" class="dropdown-item"><i class="material-symbols-rounded">info</i> Information</a>

                                            @if(Usr::userIsHead())

                                            @if($ticket->is_locked())
                                            {{-- Unlock --}}
                                            <a href="{{ route('tickets.unlock', $ticket->id) }}" class="dropdown-item text-success"><i class="material-symbols-rounded">lock_open</i> UnLock</a>
                                            @else
                                            {{-- Lock --}}
                                            <a href="{{ route('tickets.lock', $ticket->id) }}" class="dropdown-item text-warning"><i class="material-symbols-rounded">lock</i> Lock</a>
                                            @endif

                                            {{-- Delete --}}
                                            <a id="{{ $ticket->id }}" onclick="confirmPermanentDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form class="d-none" method="post" id="item-delete-{{ $ticket->id }}" action="{{ route('tickets.delete', $ticket->id) }}" class="hidden">@csrf</form>

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
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Append a new category row
        $('#add-category').click(function() {
            var new_row = `
                <div class="category-row row mb-2">
                    <div class="col-md-4 form-group">
                        <input type="text" name="categories[name][]" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-md-5 form-group">
                        <input type="text" name="categories[description][]" class="form-control" placeholder="Description">
                    </div>
                    <div class="col-md-2 form-group">
                        <select name="categories[is_visible][]" class="form-control select">
                            <option value="1" selected>Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-danger remove-category-row material-symbols-rounded float-right" title="Remove Category">remove</button>
                    </div>
                </div>`;
            $('#categories-wrapper').append(new_row);
            Select2Selects.init(); // Initialize newly created select element
        });

        // Append a new label row
        $('#add-label').click(function() {
            var new_row = `
                <div class="label-row row mb-2">
                    <div class="col-md-4 form-group">
                        <input type="text" name="labels[name][]" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-md-5 form-group">
                        <input type="text" name="labels[description][]" class="form-control" placeholder="Description">
                    </div>
                    <div class="col-md-2 form-group">
                        <select name="labels[is_visible][]" class="form-control select">
                            <option value="1" selected>Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-danger remove-label-row material-symbols-rounded float-right" title="Remove Label">remove</button>
                    </div>
                </div>`;
            $('#labels_wrapper').append(new_row);
            Select2Selects.init(); // Initialize newly created select element
        });

        // Remove a category row
        $(document).on('click', '.remove-category-row', function() {
            $(this).closest('.category-row').remove();
        });

        // Remove a label row
        $(document).on('click', '.remove-label-row', function() {
            $(this).closest('.label-row').remove();
        });
    });

</script>
@endsection
