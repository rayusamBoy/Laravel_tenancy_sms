@extends('layouts.master')

@section('page_title', 'Manage Tickets')

@section('content')

<div class="card card-collapsed card-ticket">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Open a Ticket</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('tickets.store') }}">
            @csrf @method('post')
            <div class="row">
                <div class="col-12">
                    <p class="text-muted">Information</p>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" disabled class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-lg-6">
                            <label for="email">Contact</label>
                            @if(auth()->user()->hasVerifiedEmail())
                            <input type="text" name="email" id="email" disabled class="form-control" value="{{ auth()->user()->email }}" readonly>
                            @elseif(auth()->user()->phone || auth()->user()->phone2)
                            <input type="text" name="phone" id="phone" disabled class="form-control" value="{{ auth()->user()->phone ?? auth()->user()->phone2 }}" readonly>
                            @else
                            <input type="text" id="email" disabled class="form-control" placeholder="Not provided" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label for="department">Department <span class="text-danger">*</span></label>
                            <select id="department" name="department" class="form-control select" required data-fouc>
                                @foreach($departments as $department)
                                <option value="{{ $department }}">{{ ucfirst($department) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="priority">Priority <span class="text-danger">*</span></label>
                            <select id="priority" name="priority" class="form-control select" required data-fouc>
                                @foreach($priorities as $prs)
                                <option value="{{ $prs }}">{{ ucfirst($prs) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="category">Category <span class="text-danger">*</span></label>
                            <select id="category" data-placeholder="Select Category" name="category_id" class="form-control select" required data-fouc>
                                <option value=""></option>
                                @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" title="{{ $cat->description }}">{{ ucfirst($cat->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="label">Labels</label>
                            <select id="label" data-placeholder="Select Labels" name="labels_ids[]" class="form-control select" multiple="multiple" data-fouc>
                                <option value=""></option>
                                @foreach($labels as $lbl)
                                <option value="{{ $lbl->id }}" title="{{ $lbl->description }}">{{ ucfirst($lbl->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p class="text-muted">Message</p>

                    <div class="row pb-2">
                        <div class="col-12">
                            <label for="subject">Subject <span class="text-danger">*</span></label>
                            <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') ?? '' }}" placeholder="Enter the subject of your message" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="markdown-editor">Message <span class="text-danger">*</span></label>
                            <div class="form-group row">
                                <div class="col-12">
                                    <textarea placeholder="Message" name="message" id="markdown-editor" data-autosave-unique-id="user-message-{{ auth()->id() }}"></textarea>
                                </div>
                            </div>
                            <div class="form-group row float-right">
                                <div class="col-6">
                                    {{-- Submit button --}}
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-success">Submit<span class="material-symbols-rounded ml-2 pb-2px">send</span></button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    {{-- Cancel button --}}
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm btn-warning cancel-editor">Cancel<span class="material-symbols-rounded ml-2 pb-2px">cancel</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Active Tickets</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-users">
                <table class="table datatable-button-html5-image">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Department</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($active_tickets as $ticket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ticket->department }}</td>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ ucwords($ticket->status) }}</td>
                            <td>{{ Qs::fullDateTimeFormat($ticket->updated_at) }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- Reply --}}
                                            <a href="{{ route('tickets.reply', Qs::hash($ticket->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">reply</i> Reply</a>
                                            {{-- Archive --}}
                                            <a id="{{ $ticket->id }}" onclick="confirmArchive(this.id)" href="javascript:;" class="dropdown-item text-warning"><i class="material-symbols-rounded">archive</i> Archive</a>
                                            <form class="d-none" method="post" id="item-archive-{{ $ticket->id }}" action="{{ route('tickets.archive', $ticket->id) }}" class="hidden">@method('put') @csrf</form>
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

@if ($archived_tickets->isNotEmpty())
<div class="card card-collapsed ">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Archived Tickets</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-users">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Department</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archived_tickets as $ticket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ticket->department }}</td>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ ucwords($ticket->status) }}</td>
                            <td>{{ Qs::fullDateTimeFormat($ticket->updated_at) }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- Un-archive--}}
                                            <a id="{{ $ticket->id }}" onclick="$('form#item-unarchive-'+this.id).submit()" href="javascript:;" class="dropdown-item text-warning"><i class="material-symbols-rounded">unarchive</i> Unarchive</a>
                                            <form class="d-none" method="post" id="item-unarchive-{{ $ticket->id }}" action="{{ route('tickets.unarchive', $ticket->id) }}" class="hidden">@method('put') @csrf</form>
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
@endif

@include('pages.modals.simplemde_markdown_guide')

@endsection

@section('scripts')
@include('partials.js.tickets')
@endsection
