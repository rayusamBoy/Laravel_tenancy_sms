<form method="post" action="{{ route('students.promote_selector') }}">
    @csrf
    <div class="row">
        <div class="col-md-10">
            <fieldset>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status" class="col-form-label font-weight-bold">Students Status:</label>
                            <select required id="status" name="status" class="form-control select">
                                @foreach(Qs::getStudentStatuses() as $key => $value)
                                <option @selected($selected && $key==$status) value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fc" class="col-form-label font-weight-bold">From Class:</label>
                            <select required onchange="getClassSections(this.value, '#fs')" id="fc" name="fc" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                <option @selected($selected && $fc==$c->id) value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fs" class="col-form-label font-weight-bold">From Section:</label>
                            <select required id="fs" name="fs" data-placeholder="Select Class First" class="form-control select">
                                @if($selected && $fs)
                                <option value="{{ $fs }}">{{ $sections->where('id', $fs)->first()->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tc" class="col-form-label font-weight-bold">To Class:</label>
                            <select required onchange="getClassSections(this.value, '#ts')" id="tc" name="tc" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                <option @selected($selected && $tc==$c->id) value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ts" class="col-form-label font-weight-bold">To Section:</label>
                            <select required id="ts" name="ts" data-placeholder="Select Class First" class="form-control select">
                                @if($selected && $ts)
                                <option value="{{ $ts }}">{{ $sections->where('id', $ts)->first()->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                </div>

            </fieldset>
        </div>

        <div class="col-md-2 mt-4">
            <div class="text-right mt-1">
                <button type="submit" class="btn btn-primary">Manage Promotion <i class="material-symbols-rounded ml-2">send</i></button>
            </div>
        </div>
    </div>
</form>
