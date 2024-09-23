@if(Qs::userIsTeamSAT())
    <div class="card">
        <div class="card-header header-elements-inline bg-dark">
            <h6 class="card-title font-weight-bold">Assessment Comments</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body collapse">
            <form class="ajax-update" method="post" action="{{ route('assessments.comment_update', $as->record->where('student_id', $student_id)->first()->id) }}">
                @csrf @method('PUT')

                @if(Qs::userIsTeamSAT())
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label font-weight-semibold">Teacher's Comment</label>
                        <div class="col-lg-10">
                            <input name="t_comment" value="{{ $as->record->where('student_id', $student_id)->first()->t_comment }}"  type="text" class="form-control" placeholder="Teacher's Comment">
                        </div>
                    </div>
                @endif

                @if(Qs::userIsTeamSA())
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label font-weight-semibold">Head Teacher's Comment</label>
                        <div class="col-lg-10">
                            <input name="p_comment" value="{{ $as->record->where('student_id', $student_id)->first()->p_comment }}"  type="text" class="form-control" placeholder="Head Teacher's Comment">
                        </div>
                    </div>
                @endif

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                </div>
            </form>
        </div>
    </div>
@endif
