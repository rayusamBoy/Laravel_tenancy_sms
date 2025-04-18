@php $unread_messages_count = auth()->user()->unreadMessagesCount() @endphp
@if($unread_messages_count > 0)
<span class="badge"><span class="material-symbols-rounded mr-1">mark_chat_unread</span><span class="font-family-nunito align-middle unread-messages-count">{{ $unread_messages_count }}</span></span>
<span class="sr-only">unread messages</span>

@else
<span class="badge"><span class="material-symbols-rounded mr-1">mark_chat_read</span><span class="font-family-nunito align-middle unread-messages-count">0</span></span> 
<span class="sr-only">unread messages</span>
@endif