<div class="tab-pane fade" id="add-sub">
    <div class="col-md-8">
        <form class="ajax-store" method="post" action="{{ route('tt.store') }}">
            @csrf
            <input name="ttr_id" value="{{ $ttr->id }}" type="hidden">

            @if($ttr->exam_id)
            {{--EXAM DATE--}}
            <div class="form-group row">
                <label class="col-lg-3 col-form-label font-weight-semibold">Exam Date <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input autocomplete="off" name="exam_date" value="{{ old('exam_date') }}" required type="text" class="form-control date-pick" placeholder="Select Date...">
                </div>
            </div>

            @else

            {{--DAY--}}
            <div class="form-group row">
                <label for="day" class="col-lg-3 col-form-label font-weight-semibold">Day <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <select id="day" name="day" required type="text" class="form-control select" data-placeholder="Select Day...">
                        <option value=""></option>
                        @foreach(Qs::getDaysOfTheWeek() as $dw)
                        <option @selected(old('day')==$dw) value="{{ $dw }}">{{ $dw }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            {{--SUBJECT--}}
            <div class="form-group row">
                <label for="subject_id" class="col-lg-3 col-form-label font-weight-semibold">Subject <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <select required data-placeholder="Select Subject" class="form-control select-search" name="subject_id" id="subject_id">
                        <option value=""></option>
                        @foreach($subjects as $sub)
                        <option @selected(old('subject_id')==$sub->id) value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{--TIME SLOT--}}
            <div class="form-group row">
                <label for="ts_id" class="col-lg-3 col-form-label font-weight-semibold">Time Slot <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <select data-placeholder="Select Time..." required class="select form-control" name="ts_id" id="ts_id">
                        <option value="">Select Time...</option>
                        @foreach($time_slots as $tms)
                        <option @selected(old('ts_id')==$tms->full) value="{{ $tms->id }}">{{ $tms->full}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
            </div>
        </form>
    </div>
</div>
