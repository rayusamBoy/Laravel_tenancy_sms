@if(Qs::userIsTeamSAT())
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header header-elements-inline bg-danger">
                <h6 class="card-title font-weight-bold">AFFECTIVE TRAITS</h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="card-body collapse">
                <form class="ajax-update" method="post" action="{{ route('assessments.skills_update', ['AF', $as->record->where('student_id', $student_id)->first()->id]) }}">
                    @csrf @method('PUT')
                    @foreach($skills->where('skill_type', 'AF') as $af)
                    <div class="form-group row">
                        <label for="af" class="col-lg-6 col-form-label font-weight-semibold">{{ $af->name }}</label>
                        <div class="col-lg-6">
                            <select data-placeholder="Select" name="af[]" id="af" class="form-control select">
                                <option value=""></option>
                                @for($i=1; $i<=5; $i++) <option @selected($as->record->where('student_id', $student_id)->first()->af && explode(',', $as->record->where('student_id', $student_id)->first()->af)[$loop->index] == $i) value="{{ $i }}">{{ $i }}</option>@endfor
                            </select>
                        </div>
                    </div>
                    @endforeach

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header header-elements-inline bg-success">
                <h6 class="card-title font-weight-bold">PSYCHOMOTOR SKILLS</h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="card-body collapse">
                <form class="ajax-update" method="post" action="{{ route('assessments.skills_update', ['PS', $as->record->where('student_id', $student_id)->first()->id]) }}">
                    @csrf @method('PUT')
                    @foreach($skills->where('skill_type', 'PS') as $ps)
                    <div class="form-group row">
                        <label for="ps" class="col-lg-6 col-form-label font-weight-semibold">{{ $ps->name }}</label>
                        <div class="col-lg-6">
                            <select data-placeholder="Select" name="ps[]" id="ps" class="form-control select">
                                <option value=""></option>
                                @for($i=1; $i<=5; $i++) <option @selected($as->record->where('student_id', $student_id)->first()->ps && explode(',', $as->record->where('student_id', $student_id)->first()->ps)[$loop->index] == $i) value="{{ $i }}">{{ $i }}</option>@endfor
                            </select>
                        </div>
                    </div>
                    @endforeach

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
