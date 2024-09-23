<div class="{{ $selected ? 'card' : 'card card-collapsed' }}">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Manage Events</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-events" class="{{ $selected ? 'nav-link' : 'nav-link active' }}" data-toggle="tab">Manage Events</a></li>
            @if($selected)
            <li class="nav-item"><a href="#edit-event" class="{{ $selected ? 'nav-link active' : 'nav-link' }}" data-toggle="tab"> Edit Event</a></li>
            @endif
        </ul>

        {{--All Events--}}
        <div class="tab-content">
            <div class="{{ $selected ? 'tab-pane fade' : 'tab-pane fade show active' }}" id="all-events">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $evt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $evt->name }}</td>
                            <td>{{ $evt->description }}</td>
                            <td>{{ $evt->year }}</td>
                            <td>{{ $evt->month }}</td>
                            <td>{{ $evt->day }}</td>
                            <td>{{ $evt->status }}</td>
                            <td>@if(Qs::userIsTeamSA()) <a href="{{ route('users.show', Qs::hash($evt->user_id)) }}">{{ $evt->user->name }}</a> @else {{ $evt->user->name }} @endif</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown">
                                            <i class="material-symbols-rounded">lists</i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            @if(Qs::userIsTeamSA())
                                            {{--Edit--}}
                                            <a href="{{ route('events.edit', $evt->id) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            @endif
                                            @if(Qs::userIsSuperAdmin())
                                            {{--Delete--}}
                                            <a id="{{ $evt->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="get" id="item-delete-{{ $evt->id }}" action="{{ route('events.delete') }}" class="hidden">@csrf <input type="hidden" name="id" value="{{ $evt->id }}"></form>
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

            @if($selected)
            {{--Edit Event--}}
            <div class="{{ $selected ? 'tab-pane fade show active' : 'tab-pane fade' }}" id="edit-event">

                <div class="row">
                    <div class="col-12">
                        <form class="ajax-update" method="post" action="{{ route('events.update', $event->id) }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label for="name" class="col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                    <input name="name" required type="text" class="form-control" value="{{ $event->name }}" id="name">
                                </div>
                                <div class="form-group col-md-7">
                                    <label for="description" class="col-form-label font-weight-semibold">Description <span class="text-danger">*</span></label>
                                    <input name="description" required type="text" class="form-control" value="{{ $event->description }}" id="description">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="year" class="col-form-label font-weight-semibold">Year <span class="text-danger">*</span></label>
                                    <input name="year" required type="number" min="0" class="form-control" value="{{ $event->year }}" id="year">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="month" class="col-form-label font-weight-semibold">Month <span class="text-danger">*</span></label>
                                    <input name="month" required type="number" min="0" class="form-control" value="{{ $event->month }}" id="month">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="day" class="col-form-label font-weight-semibold">Day <span class="text-danger">*</span></label>
                                    <input name="day" required type="number" min="0" class="form-control" value="{{ $event->day }}" id="day">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="status" class="col-form-label font-weight-semibold">Status</label>
                                    <select required data-placeholder="Select Class Type" class="form-control select" name="status" id="status">
                                        @foreach(Evt::getStatuses() as $st)
                                        <option {{ $event->status == $st ? 'selected' : '' }} value="{{ $st }}">{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>
</div>
