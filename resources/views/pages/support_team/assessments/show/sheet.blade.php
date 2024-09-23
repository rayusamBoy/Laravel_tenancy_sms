<table class="table table-bordered table-responsive text-center">
    <thead>
        <tr>
            <th rowspan="2">S/N</th>
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
            <th rowspan="2">SUBJECT <br /> POSITION</th>
            <th rowspan="2">REMARKS</th>
        </tr>
    </thead>

    <tbody>
        @foreach($subjects as $sub)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $sub->name }}</td>
            @foreach($assessments_records->where('subject_id', $sub->id)->where('assessment_id', $as->id) as $asr)
            {{--Course Work--}}
            <td>@php $cw_avg = Mk::getAverage($asr->cw1 + $asr->cw2 + $asr->cw3 + $asr->cw4 + $asr->cw5 + $asr->cw6 + $asr->cw7 + $asr->cw8 + $asr->cw9 + $asr->cw10, 10) @endphp {{ $cw_avg != 0 ? $cw_avg : '-' }}</td>
            {{--Home Work--}}
            <td>@php $hw_avg = Mk::getAverage($asr->hw1 + $asr->hw2 + $asr->hw3 + $asr->hw4 +$asr->hw5, 5) @endphp {{ $hw_avg != 0 ? $hw_avg : '-' }}</td>
            {{--Topic Test--}}
            <td>@php $tt_avg = Mk::getAverage($asr->tt1 + $asr->tt2 + $asr->tt2, 3) @endphp {{ $tt_avg != 0 ? $tt_avg : '-'  }}</td>
            <td>{{ ($asr->exm) ?: '-' }}</td>
            <td>
                @if($asr->exam->term === 1) {{ ($asr->tex1) }}
                @elseif ($asr->exam->term === 2) {{ ($asr->tex2) }}
                @elseif ($asr->exam->term === 3) {{ ($asr->tex3) }}
                @else {{ '-' }}
                @endif
            </td>
            {{--Grade, Subject Position & Remarks--}}
            <td>{{ ($asr->grade) ? $asr->grade->name : '-' }}</td>
            <td>{!! ($asr->grade) ? Mk::getSuffix($asr->sub_pos) : '-' !!}</td>
            <td>{{ ($asr->grade) ? $asr->grade->remark : '-' }}</td>
            @endforeach
        </tr>
        @endforeach
        @php $asr = $assessments_records->where('assessment_id', $as->id)->first(); @endphp
        <tr>
            <td colspan="5"><strong>TOTAL SCORES OBTAINED: </strong> {{ $asr->total ?? '-' }}</td>
            <td colspan="4"><strong>FINAL AVERAGE: </strong> {{ $asr->ave ?? '-' }}</td>
            <td colspan="3"><strong>CLASS AVERAGE: </strong> {{ $asr->class_ave ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="5"><strong>FINAL POSITION: </strong>{{ $asr->pos ?? '-' }} out-of {{ Mk::getSectionCount($asr->exam->id, $my_class->id, $sr->section->id) }}</td>
            <td colspan="4"><strong>FINAL AVERAGE GRADE: </strong> {{ $asr->grade->name ?? '-' }}</td>
            <td colspan="3"><strong>CLASS AVERAGE GRADE: </strong> {{ ($asr->class_ave != NULL) ? MK::getGrade($asr->class_ave) : '-' }}</td>
        </tr>
    </tbody>
</table>
