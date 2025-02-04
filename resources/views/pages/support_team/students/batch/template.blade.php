<div class="card card-collapsed">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">file_save</i> Batch Template </h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="get" action="{{ route('students.batch_template') }}">
            <div class="row">
                <div class="col-md-9">
                    <fieldset>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                                    <select data-placeholder="Choose..." required name="my_class_id" id="my_class_id" class="select-search form-control">
                                        <option value=""></option>
                                        @foreach($my_classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                                    <select onchange="getState(this.value, '#state_id-2')" data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                        <option value=""></option>
                                        @foreach($nationals as $nal)
                                        <option value="{{ $nal->id }}">{{ $nal->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="state_id-2">State: <span class="text-danger">*</span></label>
                                    <select required data-placeholder="Select Nationality First" class="select-search form-control" name="state_id" id="state_id-2">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 m-auto">
                                <div class="text-right mt-1">
                                    <button type="submit" class="btn btn-sm btn-primary">Download Spreadsheet Template <i class="material-symbols-rounded ml-2">download</i></button>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
        </form>
    </div>
</div>
