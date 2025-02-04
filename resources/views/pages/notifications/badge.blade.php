@php
$unread_notifications_count = auth()->user()->unreadNotifications()->count();
@endphp
<a href="javascript:;" class="breadcrumb-elements-item d-flex" data-toggle="dropdown" title="Notifications">
    @if($unread_notifications_count > 0)
    <span class="badge"><span class="material-symbols-rounded mr-1">notifications_unread</span><span class="font-family-nunito align-middle unread-notifications-count">{{ $unread_notifications_count }}</span></span>
    <span class="sr-only">unread notifications</span>

    @elseif($unread_notifications_count == 0)
    <span class="badge"><span class="material-symbols-rounded mr-1">notifications_paused</span>0</span>
    <span class="sr-only">no new notifications</span>
    @endif
</a>

<div class="dropdown-menu dropdown-menu-right m-0">
    <div class="modal-body">
        @if(auth()->user()->notifications()->count() > 0)
        <div class="p-1">
            <ul class="list-style-circle text-default unread-notifications-list">
                @forelse(auth()->user()->unreadNotifications()->get() as $not)
                <li class="unread-{{ $not->id }}"><a target="_blank" @if(isset($not->data['receipt'])) href="{{ route('payments.receipts', [Qs::hash($not->data['receipt']['pr_id']), $not->id]) }}" @elseif($not->data['url']) href="{{ $not->data['url'] }}" @endif><strong>{{ $not->data['subject'] }}</strong></a><small class="float-right pl-2">{{ $not->created_at }}</small></li>
                @empty
                <h6 class="text-success">No new notifications</h6>
                @endforelse
            </ul>
            <hr class="divider my-1">
            <a class="float-right" href="{{ route('notifications.index') }}">Manage</a>
        </div>
        @else
        <small class="text-info">No new Notifications</small>
        <hr class="dropdown-divider"><a class="float-right" href="{{ route('notifications.index') }}">Manage</a>
        @endif
    </div>
</div>
