<a href="javascript:;" class="breadcrumb-elements-item d-flex" data-toggle="dropdown" title="Notifications">
    @php $unread_notifications_count = auth()->user()->unreadNotifications()->count() @endphp
    @if(auth()->user()->is_notifiable)

    @if($unread_notifications_count > 0)
    <span class="badge badge-info" data-toggle="modal" data-target="#notifications-modal"><span class="material-symbols-rounded mr-1">notifications_unread</span><span class="font-family-nunito align-middle unread-notifications-count">{{ $unread_notifications_count }}</span></span>
    <span class="sr-only">unread notifications</span>

    @elseif($unread_notifications_count == 0)
    <span class="badge badge-secondary" data-toggle="modal" data-target="#notifications-modal"><span class="material-symbols-rounded mr-1">notifications_paused</span>0</span>
    <span class="sr-only">no new notifications</span>
    @endif

    @else
    <span class="badge badge-secondary" data-toggle="modal" data-target="#notifications-modal"><span class="material-symbols-rounded mr-1">notifications_off</span></span>
    <span class="sr-only">notifications off</span>

    @endif
</a>

<div class="dropdown-menu dropdown-menu-right m-0">
    <div class="modal-body">
        @if(auth()->user()->is_notifiable)

        @if(auth()->user()->notifications()->count() > 0)
        <div class="p-1">
            <ul class="list-style-circle" id="unread-notifications-list">
                @forelse(auth()->user()->unreadNotifications()->get() as $not)
                <li id="unread-{{ $not->id }}"><strong>{{ $not->data['subject'] }}</strong> - <a target="_blank" href="{{ route('payments.receipts', [Qs::hash($not->data['receipt']['pr_id']), $not->id]) }}">View Receipt</a> <small class="float-right">{{ $not->created_at }}</small></li>
                @empty
                <h6 class="text-success">No new notifications</h6>
                @endforelse
            </ul>
            <hr class="divider m-1">
            <a class="float-right" href="{{ route('notifications.index') }}">Manage</a>
        </div>
        @else
        <i class="text-info">Notifications empty</i><hr class="dropdown-divider"><a class="float-right" href="{{ route('notifications.index') }}">Manage</a>
        @endif

        @else
        <i class="text-warning">Notifications were turned off</i><hr class="dropdown-divider"><a class="float-right" href="{{ route('notifications.index') }}">Manage</a>
        @endif
    </div>
</div>
