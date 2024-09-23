<div id="create-message" class="card position-fixed right-p5em bottom-15px mw-md-50 wmax-sm-500 wmin-300 display-none">
    <div class="card-body">
        <form action="{{ route('messages.store') }}" method="post">
            {{ csrf_field() }}
            <div>
                <!-- Subject Form Input -->
                <div class="form-group">
                    <label class="control-label">Subject</label>
                    <input type="text" class="form-control" name="subject" placeholder="Subject" value="{{ old('subject') }}">
                </div>

                <!-- Message Form Input -->
                <div class="form-group">
                    <label class="control-label">Message</label>
                    <textarea name="message" class="form-control" placeholder="type a message here...">{{ old('message') }}</textarea>
                </div>

                @if($users->count() > 0)
                <div>
                    <label class="control-label">Recipients</label>
                    <select id="recipients-ids" name="recipients[]" onchange="toggleElementDisabledState(this.value, '#user_types-ids')" multiple="multiple" data-placeholder="select recipient(s)" class="form-control select-search">
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <h6 class="text-info mb-0 mt-2">Or</h6>

                <div>
                    <label class="control-label">Broadcast to user type group(s) instead</label>
                    <select id="user_types-ids" name="user_types[]" onchange="toggleElementDisabledState(this.value, '#recipients-ids');" multiple="multiple" data-placeholder="select recipient(s)" class="form-control select">
                        @foreach($user_types as $type)
                        @if($type->title == 'student')
                        <option disabled value="{{ $type->title }}">{{ $type->name }}s</option>
                        <option value="0">&xrArr; Active</option>
                        <option value="1">&xrArr; Graduated</option>
                        <option value="students">&xrArr; All</option>
                        @continue
                        @endif
                        <option value="{{ $type->title }}">{{ $type->name }}s</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Form Input -->
                <div class="form-group mt-3 mb-0 w-100 d-flex space-btn">
                    <button type="submit" class="btn btn-primary w-fit">Create & Send <i class="material-symbols-rounded ml-1">send</i></button>
                    <button type="button" onclick="hideFormCard()" class="btn btn-secondary w-fit">Close </button>
                </div>
            </div>
        </form>
    </div>
</div>
