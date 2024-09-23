<!-- Modal -->
<div class="modal fade" id="notifications-modal" tabindex="-1" aria-labelledby="notifications" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="notifications">Unread Notifications</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body">
                @if(auth()->user()->is_notifiable)

                @if(auth()->user()->notifications()->count() > 0)
                <div class="card-body p-1">
                    <ul class="list-style-circle" id="unread-notifications-list">
                        @forelse(auth()->user()->unreadNotifications()->get() as $not)
                            <li id="unread-{{ $not->id }}"><strong>{{ $not->data['subject'] }}</strong> - <a target="_blank" href="{{ route('payments.receipts', [Qs::hash($not->data['receipt']['pr_id']), $not->id]) }}">View Receipt</a> <small class="float-right">{{ $not->created_at }}</small></li>
                        @empty
                            <h6 class="text-success">No new notifications.</h6>
                        @endforelse
                    </ul>
                    <hr class="divider m-1">
                    <a class="float-right" href="{{ route('notifications.index') }}">Manage</a>
                </div>
                @else
                    <h6 class="text-info">Notifications empty. <a class="float-right" href="{{ route('notifications.index') }}">Manage</a></h6>
                @endif

                @else
                <h6 class="text-warning">Notifications were turned off. <a class="float-right" href="{{ route('notifications.index') }}">Manage</a></h6>
                @endif
            </div>
        </div>
    </div>
</div>