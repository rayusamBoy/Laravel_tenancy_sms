{{-- NAME , CLASS AND OTHER INFO --}}
<table style="width:100%; border-collapse:collapse; ">
    <tbody>
        <tr>
            <td><strong>NAME:</strong> {{ strtoupper($sr->user->name) }}</td>
            <td><strong>ADM NO:</strong> {{ $sr->adm_no }}</td>
            {{--<td><strong>HOUSE:</strong> {{ strtoupper($sr->house) }}</td>--}}
            <td><strong>CLASS:</strong> {{ strtoupper($my_class->name) . ' (' . $exr->section->name . ')' }}</td>
        </tr>
        <tr>
            <td><strong>RESULT SHEET FOR</strong> {!! strtoupper(Mk::getSuffix($ex->term)) !!} TERM</td>
            <td><strong>EXAM:</strong> {{ strtoupper($exr->exam->name) }} ({{ $ex->year }})</td>
            <td><strong>AGE:</strong> {{ $sr->age ?: ($sr->user->dob ? date_diff(date_create($sr->user->dob), date_create('now'))->y . ' years' : '-') }}</td>
        </tr>
    </tbody>
</table>

{{--Exam Table--}}
<table style="width:100%; border-collapse:collapse; border: 1px solid #000; margin: 10px auto;" border="1">
    <thead>
        <tr>
            <th rowspan="2">SUBJECTS</th>
            <th rowspan="2">MARK <br> ({{ $ex->exam_denominator }})</th>
            <th rowspan="2">GRADE</th>
            <th rowspan="2">SUBJECT <br> POSITION</th>
            <th rowspan="2">REMARKS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subjects as $sub)
        @php $subject_marks = $marks->where('subject_id', $sub->id)->where('exam_id', $ex->id)->where('section_id', $exr->section->id) @endphp
        @if($subject_marks->isNotEmpty())
        <tr>
            <td style="font-weight: bold">{{ $sub->name }}</td>
            @foreach($subject_marks as $mk)
            <td>{{ $mk->$tex ?: '-'}}</td>
            <td>{{ $mk->grade ? $mk->grade->name : '-' }}</td>
            <td>{!! ($mk->grade) ? Mk::getSuffix($mk->sub_pos) : '-' !!}</td>
            <td>{{ $mk->grade ? $mk->grade->remark : '-' }}</td>
            @endforeach
        </tr>
        @endif
        @endforeach
        <tr>
            <td colspan="1"><strong>TOTAL SCORES: </strong> {{ $exr->total }}</td>
            <td colspan="1"><strong>GPA: </strong> {{ $exr->gpa }} of 5</td>
            <td colspan="2"><strong>FINAL AVERAGE: </strong> {{ $exr->ave }}</td>
            <td colspan="2"><strong>CLASS AVERAGE: </strong> {{ $exr->class_ave }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>DIVISION: </strong>@if($exr->division == "") <em>INCOMPLETE POINTS</em> @else Division <strong> {{ $exr->division->name ?? '-' }} </strong> of points <strong>{{ $exr->points }} @endif</strong></td>
            <td colspan="2"><strong>FINAL AVERAGE GRADE: </strong> {{ $exr->grade->name }}</td>
            <td colspan="2"><strong>CLASS AVERAGE GRADE: </strong> {{ MK::getGrade($exr->class_ave) }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>FINAL POSITION SECTIONWISE ({{ $exr->section->name }}): </strong>{{ $exr->pos }} of {{ Mk::getSectionCount($ex->id, $exr->my_class_id, $exr->section_id) }}</td>
            <td colspan="3"><strong>FINAL POSITION CLASSWISE: </strong>{{ $exr->class_pos }} of {{ Mk::getClassCount($ex->id, $exr->my_class_id) }}</td>
        </tr>
    </tbody>
</table>
