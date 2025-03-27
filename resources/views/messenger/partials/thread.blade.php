@if($thread->hasParticipant(Auth::id()) || Qs::userIsSuperAdmin())
@php $unread = $thread->userUnreadMessagesCount(Auth::id()) @endphp
<div class="{{ $unread == 0 ? 'card card-collapsed' : 'card' }}">
    <div class="card-header header-elements-inline">
        <h6 class="media-heading card-title">
            <a href="{{ route('messages.show', Qs::hash($thread->id)) }}">{{ $thread->subject }}</a>
            <small> ({{ $unread }} unread)</small>
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <?php $class = $thread->isUnread(Auth::id()) ? 'alert-info' : ''; ?>

    <div class="media alert {{ $class }} border-none">
        <div class="row w-100">
            <div class="col-md-7">
                <small class="mb-1"><strong>Latest Message</strong></small>
                <p>
                    {{ $thread->latestMessage->body ?? 'The thread has no message(s) yet. Or, the message(s) were deleted.' }}
                </p>
                <p>
                    <small><strong>Creator:</strong> {{ $thread->creator()->name }}</small>
                </p>
            </div>
            <div class="col-md-5">
                <small><strong>Participants:</strong></small>
                <p>
                    @include('pages/modals/participants')
                    <td><a href="javascript:;" data-toggle="modal" data-target="#thread-participants-{{ $thread->id }}">View Participants</a></td>
                </p>
            </div>
        </div>
    </div>
</div>

@elseif($thread->isDirtyParticipant(Auth::id()))
<p>Sorry, you are no longer a participant of the thread with the subject: <strong>{{ $thread->subject }}</strong>.</p>

@else
<p class="text-default">Sorry, you are not the participant of this thread.</p>

@endif
