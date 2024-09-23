<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">deployed_code_history</i> Batch Update </h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info border-0 alert-dismissible has-do-not-show-again-button" id="attention-info">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span><strong class="text-danger">Attention:</strong> This automatic batch marks update (recommended) may take little long (approximate 2 to 15 minutes). Make sure you have enough wait time and strong
                    internet connection before taking this step. Or, you may update class sections manually in the <a href="{{ route('marks.index') }}"><strong>Manage Exam Marks</strong></a> section,
                    where you need to update per each subject of the particular class or section. The manual update may take a while, and may be tedious. It is up to you, to decide.
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form class="page-block" method="post" action="{{ route('marks.batch_update') }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <fieldset>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exam_id" class="col-form-label font-weight-bold">Exam: <span class="text-danger">*</span></label>
                                    <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select-search">
                                        <option disabled selected value="">Select Exam</option>
                                        @foreach($exams as $ex)
                                        <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="my_class_id" class="col-form-label font-weight-bold">Class: <span class="text-danger">*</span></label>
                                    <select required data-placeholder="Select Class" id="my_class_id" name="my_class_id" class="form-control select">
                                        <option disabled selected value="">Select Class</option>
                                        @foreach($my_classes as $c)
                                        <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                    </fieldset>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="text-right mt-1">
                        <button type="submit" data-text="Updating" class="btn btn-sm btn-success">Update Batch Marks <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
