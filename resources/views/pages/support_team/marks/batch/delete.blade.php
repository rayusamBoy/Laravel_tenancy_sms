<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">delete</i> Batch Delete </h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form class="page-block" method="POST" action="{{ route('marks.batch_delete') }}" id="item-delete-batch" >
            @csrf
            <div class="row">
                <div class="col-md-9">
                    <fieldset>

                        <div class="row">
                            <div class="col-md-6">
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="my_class_id" class="col-form-label font-weight-bold">Class: <span class="text-danger">*</span></label>
                                    <select required data-placeholder="Select Class" onchange="getClassSections(this.value, '#sec_id_add_not_applicable')" id="my_class_id" name="my_class_id" class="form-control select">
                                        <option disabled selected value="">Select Class</option>
                                        @foreach($my_classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sec_id_add_not_applicable" class="col-form-label font-weight-bold">Section:</label>
                                    <select id="sec_id_add_not_applicable" name="section_id" class="form-control select">
                                        <option value="">Not Applicable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>

                <div class="col-md-3 mt-4">
                    <div class="text-right mt-1">
                        <button type="button" onclick="confirmPermanentDeleteTwice('batch');" class="btn btn-sm btn-danger">Delete Exam Records <i class="material-symbols-rounded ml-2">delete</i></button>
                    </div>
                </div>

            </div>

        </form>

    </div>
</div>