<form method="post" action="{{ route('assessments.selector') }}">
    @csrf
    <div class="row">
        <div class="col-md-10">
            <fieldset>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exam_id" class="col-form-label font-weight-bold">Assessments for:</label>
                            <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select">
                                <option selected disabled value="">Select Exam</option>
                                @foreach ($assessments->where('exam.year', Qs::getCurrentSession()) as $as)
                                <option {{ $selected && $exam->id == $as->exam->id ? 'selected' : '' }} value="{{ $as->exam->id }}">{{ $as->exam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                            <select required data-placeholder="Select Class" onchange="getClassSubjects(this.value);getClassSections(this.value, '#section_id_add_not_applicable')" id="my_class_id" name="my_class_id" class="form-control select">
                                <option disabled selected value="">Select Class</option>
                                @foreach($my_classes as $c)
                                <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="section_id_add_not_applicable" class="col-form-label font-weight-bold">Section:</label>
                            <select required id="section_id_add_not_applicable" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                @if($selected)
                                @if(isset($section_id))
                                @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                                @endif
                                <option {{ !isset($section_id) ? 'selected' : '' }} value=" ">Not Applicable</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subject_id" class="col-form-label font-weight-bold">Subject:</label>
                            <select required id="subject_id" name="subject_id" data-placeholder="Select Class First" class="form-control select-search">
                                @if($selected)
                                @foreach($subjects as $s)
                                <option {{ $subject_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>

        <div class="col-md-2 mt-4">
            <div class="text-right mt-1">
                <button type="submit" class="btn btn-primary">Manage Assessments <i class="material-symbols-rounded ml-2">send</i></button>
            </div>
        </div>
    </div>
</form>