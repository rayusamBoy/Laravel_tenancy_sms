<table class="table table-bordered table-responsive text-center">
    <thead>
        <tr>
            <th rowspan="2">S/N</th>
            <th rowspan="2">SUBJECTS</th>
            <th rowspan="2">MARK<br>({{ $ex->exam_denominator }})</th>
            <th rowspan="2">GRADE</th>
            <th rowspan="2">SUBJECT <br> POSITION</th>
            <th rowspan="2">REMARKS</th>
        </tr>
    </thead>

    <tbody>
        @foreach($subjects->where('my_class_id', $exr->my_class_id) as $sub)
        @php $subject_marks = $marks->where('subject_id', $sub->id)->where('exam_id', $ex->id)->where('section_id', $exr->section_id) @endphp
        @if($subject_marks->isNotEmpty())
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $sub->name }}</td>
            @foreach($subject_marks as $mk)
            <td>
                @if($ex->term === 1) {{ $mk->tex1 ?? '-' }}
                @elseif ($ex->term === 2) {{ $mk->tex2 ?? '-' }}
                @elseif ($ex->term === 3) {{ $mk->tex3 ?? '-' }}
                @else {{ '-' }}
                @endif
            </td>
            {{--Grade, Subject Position & Remarks--}}
            <td>{{ $mk->grade ? $mk->grade->name : '-' }}</td>
            <td>{!! $mk->grade ? Mk::getSuffix($mk->sub_pos) : '-' !!}</td>
            <td>{{ $mk->grade ? $mk->grade->remark : '-' }}</td>
            @endforeach
        </tr>
        @endif
       @endforeach
        <tr>
            <td colspan="2"><strong>TOTAL SCORES: </strong> {{ $exr->total }}</td>
            <td colspan="1"><strong>GPA: </strong> {{ $exr->gpa }} of 5</td>
            <td colspan="2"><strong>FINAL AVERAGE: </strong> @if($exr->division == "") <em>N/A</em> @else {{ $exr->ave ?? '-' }} @endif</td>
            <td colspan="1"><strong>CLASS AVERAGE: </strong> {{ $exr->class_ave }}</td>
       </tr>
        <tr>
            <td colspan="2"><strong>DIVISION: </strong>@if($exr->division == "") <em>INCOMPLETE POINTS</em> @else Division <strong> {{ $exr->division->name ?? '-' }} </strong> of points <strong>{{ $exr->points }} @endif</strong></td>
            <td colspan="2"><strong>FINAL AVERAGE GRADE: </strong>@if($exr->division == "") <em>N/A</em> @else {{ $exr->grade->name ?? '-' }} @endif</td>
            <td colspan="2"><strong>CLASS AVERAGE GRADE: </strong> {{ Mk::getGrade($exr->class_ave) }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>FINAL POSITION SECTIONWISE: </strong>@if($exr->division == "") <em>N/A</em> @else ({{ $exr->section->name }}){{ $exr->pos }} of {{ Mk::getSectionCount($ex->id, $exr->my_class_id, $exr->section_id) }} @endif</td>
            <td colspan="3"><strong>FINAL POSITION CLASSWISE: </strong>@if($exr->division == "") <em>N/A</em> @else {{ $exr->class_pos }} of {{ Mk::getClassCount($ex->id, $exr->my_class_id) }}@endif</td>
        </tr>
    </tbody>
</table>
