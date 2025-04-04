<form method="post" action="{{ route('marks.selector') }}">
    @csrf
    <div class="row">
        <div class="col-md-10">
            <fieldset>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                            <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select">
                                @foreach($exams as $ex)
                                <option @selected($selected && $exam_id==$ex->id) value="{{ $ex->id }}">{{ $ex->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                            <select required onchange="getClassSubjects(this.value);getClassSections(this.value, '#section_id_add_not_applicable')" id="my_class_id" name="my_class_id" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                <option @selected($selected && $my_class_id==$c->id) value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="section_id_add_not_applicable" class="col-form-label font-weight-bold">Section:</label>
                            <select required id="section_id_add_not_applicable" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                @if($selected)
                                @if(isset($section_id))
                                @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                <option @selected($section_id==$s->id) value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                                @endif
                                <option @selected(!isset($section_id)) value=" ">Not Applicable</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subject_id" class="col-form-label font-weight-bold">Subject:</label>
                            <select required id="subject_id" name="subject_id" data-placeholder="Select Class First" class="form-control select-search">
                                @if($selected)
                                @if(Qs::userIsTeacher())
                                @foreach($subjects->where('subject.my_class_id', $my_class_id) as $s)
                                <option @selected($subject_id==$s->subject->id) value="{{ $s->subject->id }}">{{ $s->subject->name }}</option>
                                @endforeach
                                @else
                                @foreach($subjects->where('my_class_id', $my_class_id) as $s)
                                <option @selected($subject_id==$s->id) value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                                @endif
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="col-md-2 mt-4">
            <div class="text-right mt-1">
                <button type="submit" class="btn btn-primary">Manage <i class="material-symbols-rounded ml-2">send</i></button>
            </div>
        </div>
    </div>
</form>
