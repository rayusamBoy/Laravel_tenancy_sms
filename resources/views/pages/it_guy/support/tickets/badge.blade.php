@php $assigned_tickets = Usr::userAssignedTickets(); @endphp
@if($assigned_tickets->isNotEmpty())
<a href="{{ route('tickets.index_central') }}" class="badge"><span class="material-symbols-rounded mr-1">local_activity</span><span class="font-family-nunito align-middle unread-messages-count">{{ $assigned_tickets->count() }}</span></a>
<span class="sr-only">unclosed tickets</span>

@else 
<span class="badge"><span class="material-symbols-rounded mr-1">local_activity</span><span class="font-family-nunito align-middle unread-messages-count">0</span></span> 
<span class="sr-only">no unclosed tickets</span>
@endif