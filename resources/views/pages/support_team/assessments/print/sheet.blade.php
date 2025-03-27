{{--NAME , CLASS AND OTHER INFO --}}
<table style="width:100%; border-collapse:collapse; ">
    <tbody>
        <tr>
            <td><strong>NAME:</strong> {{ strtoupper($sr->user->name) }}</td>
            <td><strong>ADM NO:</strong> {{ $sr->adm_no }}</td>
            {{--<td><strong>HOUSE:</strong> {{ strtoupper($sr->house) }}</td>--}}
            <td><strong>CLASS:</strong> {{ strtoupper($my_class->name) }}</td>
        </tr>
        <tr>
            <td><strong>ASSESSMENTS MARKS FOR </strong>{!! strtoupper(Mk::getSuffix($ex->term)) !!} TERM</td>
            <td><strong>EXAM: </strong>{{ strtoupper($asr->exam->name) }} ({{ $ex->year }})</td>
            <td><strong>AGE: </strong>{{ $sr->age ?: ($sr->user->dob ? date_diff(date_create($sr->user->dob), date_create('now'))->y : '-') }}</td>
        </tr>
    </tbody>
</table>

{{--Exam Table--}}
<table style="width:100%; border-collapse:collapse; border: 1px solid #000; margin: 10px auto;" border="1">
    <thead>
        <tr>
            <th rowspan="2">SUBJECTS</th>
            {{--Course Work--}}
            <th rowspan="2">CW<br />(10)</th>
            {{--Home Work--}}
            <th rowspan="2">HW<br />(10)</th>
            {{--Topic Test--}}
            <th rowspan="2">TT<br />(20)</th>
            <th rowspan="2">EXAMS<br />(60)</th>
            <th rowspan="2">TOTAL<br />(100)</th>
            <th rowspan="2">GRADE</th>
            <th rowspan="2">SUBJECT <br> POSITION</th>
            <th rowspan="2">REMARKS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subjects as $sub)
        <tr>
            <td style="font-weight: bold">{{ $sub->name }}</td>
            @foreach($assessments_records->where('subject_id', $sub->id)->where('exam_id', $ex->id) as $as)
            {{--Course Work--}}
            <td>@php $cw_avg = Mk::getAverage($as->cw1 + $as->cw2 + $as->cw3 + $as->cw4 + $as->cw5 + $as->cw6 + $as->cw7 + $as->cw8 + $as->cw9 + $as->cw10, 10) @endphp {{ $cw_avg != 0 ? $cw_avg : '-' }}</td>
            {{--Home Work--}}
            <td>@php $hw_avg = Mk::getAverage($as->hw1 + $as->hw2 + $as->hw3 + $as->hw4 +$as->hw5, 5) @endphp {{ $hw_avg != 0 ? $hw_avg : '-' }}</td>
            {{--Topic Test--}}
            <td>@php $tt_avg = Mk::getAverage($as->tt1 + $as->tt2 + $as->tt2, 3) @endphp {{ $tt_avg != 0 ? $tt_avg : '-'  }}</td>
            <td>{{ $as->exm ?: '-' }}</td>

            <td>
                @if($ex->term === 1) {{ $as->tex1 }}
                @elseif ($ex->term === 2) {{ $as->tex2 }}
                @elseif ($ex->term === 3) {{ $as->tex3 }}
                @else {{ '-' }}
                @endif
            </td>
            <td>{{ $as->grade ? $as->grade->name : '-' }}</td>
            <td>{!! $as->grade ? Mk::getSuffix($as->sub_pos) : '-' !!}</td>
            <td>{{ $as->grade ? $as->grade->remark : '-' }}</td>
            @endforeach
        </tr>
        @endforeach
        <tr>
            <td colspan="3"><strong>TOTAL SCORES OBTAINED: </strong> {{ $asr->total ?? '-' }}</td>
            <td colspan="3"><strong>FINAL AVERAGE: </strong> {{ $asr->ave ?? '-' }}</td>
            <td colspan="3"><strong>CLASS AVERAGE: </strong> {{ $asr->class_ave ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>FINAL POSITION: </strong>{{ $asr->pos }} out-of {{ Mk::getSectionCount($ex->id, $my_class->id, $sr->section->id) }}</td>
            <td colspan="3"><strong>FINAL AVERAGE GRADE: </strong> {{ $asr->grade->name ?? '-' }}</td>
            <td colspan="3"><strong>CLASS AVERAGE GRADE: </strong> {{ MK::getGrade($asr->class_ave) ?? '-' }}</td>
        </tr>
    </tbody>
</table>

<p><strong style="text-decoration: underline;">KEY</strong>: CW => Course Work, HW => Home Work, TT => Topic Test</p>
