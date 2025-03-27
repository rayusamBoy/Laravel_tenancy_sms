<!-- Modal -->
<div class="modal fade" id="ticket-info-modal-{{ $ticket->id }}" tabindex="-2" aria-labelledby="ticket-info-heading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center" id="ticket-info-heading">Ticket Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-responsive">
                    <tr>
                        <td class="font-weight-bold">Subject</td>
                        <td>{{ ucfirst($ticket->subject) }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Department</td>
                        <td>{{ ucfirst($ticket->department) }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Created</td>
                        <td><span>{{ Qs::fullDateTimeFormat($ticket->created_at) }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Updated</td>
                        <td><span class="bread-all">{{ Qs::fullDateTimeFormat($ticket->updated_at) }}</span></td>
                    </tr>
                    @if(Qs::userIsHead())
                    <tr>
                        <td class="font-weight-bold">Assignee</td>
                        <td>
                            <form action="{{ route('tickets.update_assignee', $ticket->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-sm-9 pr-0">
                                        <select required data-placeholder="Select User" class="form-control select" name="assigned_to">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                            <option @selected($ticket->assigned_user?->id == $user->id) value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 pl-0">
                                        <button type="submit" class="btn btn-sm btn-primary input-group-text">Update</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
