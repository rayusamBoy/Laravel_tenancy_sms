<form class="ajax-update" action="{{ route('marks.update', [$exam->id, $my_class_id, $subject_id, $section_id ?? null]) }}" method="post">
    @csrf @method('put')
    <table class="table table-striped data-table marks float-head">
        <thead>
            <tr class="text-center">
                <th>S/N</th>
                <th>NAME</th>
                <th>ADMISSION NO</th>
                <th>EXAM ({{ $exam->exam_denominator }})</th>
            </tr>
        </thead>
        <tbody class="exam-tbody">
            @foreach($marks->sortBy('user.gender')->sortBy('user.name') as $mk)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $mk->user->name }}</td>
                <td class="text-center">{{ $mk->user->student_record->adm_no }}</td>
                <td class="text-center"><input @readonly($is_restricted) title="EXAM" min="0" max="{{ $mk->exam->exam_denominator }}" class="{{ $is_restricted ? 'text-center form-control form-control-sm cursor-not-allowed' : 'text-center form-control form-control-sm' }}" name="exm_{{ $mk->id }}" value="{{ $mk->exm }}" type="number"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Update Button --}}
    <div class="text-center mt-2">
        <button type="submit" class="btn btn-primary" id="update">Update Marks <i class="material-symbols-rounded ml-2">send</i></button>
    </div>
</form>
