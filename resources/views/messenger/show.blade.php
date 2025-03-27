@extends('layouts.master')

@section('page_title', 'Show Messages')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="media-heading card-title status-styled pr-2 pl-2">
            <strong>{{ $thread->subject }}</strong>
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body pt-2">
        <div class="row">
            <div class="col-12 p-0">
                <div class="messages position-relative">
                    @if($thread->messagesWithTrashed->count() > $thread->messagesWithTrashed->take(-($unread_messages_count + $extra_messages_to_count))->count())
                    <div class="loading-previous display-none">
                        <div class="dot-fire"></div>
                    </div>
                    <button type="button" class="material-symbols-rounded btn btn-light load-previous" title="Load Previous Messages">arrow_upward</button>
                    @endif

                    <ul>
                        <div class="row position-relative" id="messages-row">
                            @each('messenger.partials.messages', $thread->messagesWithTrashed->take(-($unread_messages_count + $extra_messages_to_count)), 'message')
                            @include('pages/modals/confirm_message_delete')
                        </div>
                        <div class="row display-none" id="typing-indicator">
                            <div class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                                <div class="d-flex">
                                    <span class="font-size-xs text-info-800 font-italic" id="user-is-typing"></span>
                                    <div class="dot-typing ml-1 mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </ul>
                    <div class="row display-none message-row pb-5 pb-xl-0">
                        <div class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                            @include('messenger.partials.form-message')
                        </div>
                    </div>
                    <div class="row alert-row mb-3 clear">
                        <div class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3 d-inline">
                            <div class="alert alert-info border-0 text-center position-relative">
                                <div class="display-none font-size-sm mr-1"></div><i>No connection</i>
                                <div class="dot-indicator display-none"></div>
                            </div>
                        </div>
                    </div>

                    @if (Qs::userIsSuperAdmin())
                    <button id="{{ $thread->id }}" type="button" onclick="confirmDelete(this.id)" class="btn btn-danger position-absolute left-neg-4 bottom-0"><i class="material-symbols-rounded">delete</i> Delete Thread</button>
                    <form method="post" id="item-delete-{{ $thread->id }}" action="{{ route('messages.destroy', $thread->id) }}" class="hidden">@csrf @method('delete')</form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@include('messenger.partials.scripts')
@endsection
