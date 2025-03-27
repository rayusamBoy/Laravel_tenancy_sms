@if($user_as_participant->isNotEmpty() || Qs::userIsSuperAdmin())
<!-- Message Form Input -->
<form class="message ajax-store" action="{{ route('messages.update', $thread->id) }}" method="post">
    {{ method_field('put') }}
    {{ csrf_field() }}

    <div class="form-group input-group">
        <textarea name="message" id="message" class="form-control font-size-standard" placeholder="type to add a message...">{{ old('message') }}</textarea>
        <!-- Submit Form Input -->
        <button id="send-message-btn" type="submit" data-text=" " class="btn input-group-text"><i class="material-symbols-rounded">send</i></button>
    </div>

    @if($users->count() > 0 && Qs::userIsSuperAdmin())
    <div>
        <select id="recipients-ids" name="recipients[]" multiple="multiple" data-placeholder="Select to add recipient" class="form-control select">
            <option value=""></option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    @endif
</form>

@else
<em class="position-fixed bottom-0 bg-info pr-1 pl-1 right-2">Sorry, you are no longer a participant of this thread. You can't send a message.</em>
@endif
