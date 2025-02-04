<div class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3" id="message-{{ $message->id }}">
    <li class="position-relative {{ $message->user->id == Auth::id() ? 'float-right' : 'float-left' }}">
        @if ($message->user->id != Auth::id())
        <!-- Display user photo on the left followed by the message box -->
        <img src="{{ tenant_asset($message->user->photo) }}" alt="{{ $message->user->name }}" class="rounded-circle f-left">

        <!-- Absolute positioned elements -->
        @if (Qs::userIsSuperAdmin() && $message->deleted_at == NULL)
        <!-- If is super admin; allow the user to delete other users' messages -->
        <button data-toggle="popover" data-placement="right" data-html="true" class="material-symbols-rounded position-absolute right-neg-25 text-info-400 opacity-75 opacity-100-on-hover bg-transparent border-0 outline-0" data-content='
            <div class="d-flex justify-center">
                <button type="button" data-text="Deleting..." id="{{ $message->id }}" onclick="prepareDeleteUserMessageForm(this.id)" data-toggle="modal" data-target="#confirm-message-delete" class="btn btn-sm p-0 pr-1 pl-1 m-auto text-danger w-100 text-left bg-transparent"><span class="text-danger">Delete</span></button>
            </div>
            '>more_vert
        </button>

        @endif
        @endif

        {{-- The message box --}}
        <div class="{{ $message->user->id == Auth::id() ? 'right' : 'left' }}">
            <span class="media-body break-all">
                <small class="media-heading" style="color: {{ $message->user->message_media_heading_color }}">{{ $message->user->id == Auth::id() ? 'Me' : $message->user->name . ' (' . str_replace('_', ' ', $message->user->user_type) . ')' }}</small>
                <br>
                @if ($message->deleted_at !== null)
                <!-- If the message is deleted -->
                @if ($message->deleted_by == Auth::id())
                <p class="text-muted"><i class="material-symbols-rounded font-size-sm pb-1">block</i><i>You deleted this message.</i></p>
                @elseif ($message->deleted_by != Auth::id())
                <!-- If the message was deleted by someone else -->
                @if ($message->deletor->user_type === 'super_admin' && $message->user_id != $message->deleted_by)
                <p class="text-muted"><i class="material-symbols-rounded font-size-sm pb-1">block</i><i>This message was deleted by super admin {{ $message->deletor->name }}.</i></p>
                @else
                <p class="text-muted"><i class="material-symbols-rounded font-size-sm pb-1">block</i><i>This message was deleted.</i></p>
                @endif
                @endif
                @else
                {{-- If the message is not deleted --}}
                {{ $message->body }}
                <br>
                <span class="text-muted float-right">
                    <small><i>{{ date('d M Y, h:m:s a', strtotime($message->created_at)) }}</i></small>
                </span>
                @endif
            </span>
        </div>

        @if ($message->user->id == Auth::id())
        @if ($message->deleted_at == null)
        <!-- Allow user to delete own message -->
        <button data-toggle="popover" data-placement="left" data-html="true" class="material-symbols-rounded position-absolute left-neg-25 text-info-400 opacity-75 opacity-100-on-hover border-0 outline-0 action-btn" data-content='
            <div class="d-flex justify-center">
                <button type="button" data-text="Deleting..." id="{{ $message->id }}" onclick="prepareDeleteUserMessageForm(this.id)" data-toggle="modal" data-target="#confirm-message-delete" class="btn btn-sm p-0 pr-1 pl-1 m-auto text-danger w-100 text-left bg-transparent"><span class="text-danger">Delete</span></button>
            </div>
            '>more_vert
        </button>

        @endif

        <!-- Display the message box first followed by the user photo -->
        <span>
            <img src="{{ tenant_asset($message->user->photo) }}" alt="{{ $message->user->name }}" class="rounded-circle f-right">
        </span>
        @endif
    </li>
</div>