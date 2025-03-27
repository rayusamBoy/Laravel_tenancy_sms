<!-- Modal -->
<div class="modal fade" id="thread-participants-{{ $thread->id }}" tabindex="-1" aria-labelledby="message-thread-participants" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center" id="message-thread-participants">Mesage Thread Participants</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card-body">
                    <ul class="nav nav-tabs-highlight">
                        @if(count($thread->participantsArray(auth()->id())) <= 0) 
                        <i>The message thread has no partitipants</i>
                        @else
                        @foreach($thread->participantsArray(auth()->id()) as $pt)
                        <span class="participant">
                            <li><a target="_blank" href="{{ route('users.show', Qs::hash($pt->id)) }}">{{ $loop->iteration }} - {{ $pt->name }}</a></li>
                            @if(Qs::userIsSuperAdmin())
                            <a href="{{ route('messages.remove_participant', [Qs::hash($thread->id), Qs::hash($pt->id)]) }}" class="material-symbols-rounded pl-1" aria-label="remove" title="Remove {{ $pt->name }}">close</a>
                            @endif
                        </span>
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            @if($thread->hasDirtyParticipant($thread->id) && Qs::userIsSuperAdmin())
            <div class="modal-footer">
                <a href="{{ route('thread.activate_all_participants', Qs::hash($thread->id)) }}" type="button" class="btn">Activate all removed participants</a>
            </div>
            @endif
        </div>
    </div>
</div>
