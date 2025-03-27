<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title"><i class="material-symbols-rounded mr-2">upload_2</i> Batch Upload </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form class="ajax-update" method="post" action="{{ route('marks.batch_upload') }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-12">
                    <fieldset>

                        <div class="form-group">
                            <label class="d-block">Upload Template File: <span class="text-danger">*</span></label>
                            <input value="{{ old('template') }}" accept=".xlsx" type="file" name="template" class="file-input-preview-none" data-show-caption="true" data-show-upload="true" data-fouc>
                            <span class="form-text text-muted">Accepted Files: xlsx, xlx</span>
                        </div>

                    </fieldset>
                </div>
            </div>
        </form>
    </div>
</div>
