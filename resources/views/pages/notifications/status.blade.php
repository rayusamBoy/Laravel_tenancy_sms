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