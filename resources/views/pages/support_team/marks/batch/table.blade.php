@php
$plus = 4; // Plus 4 - S/N, ADMISSION NUMBER, FULL NAME, and SECTION
$sub_count = $subjects->count() + $plus;
@endphp

<table>
    <thead style="text-align: center;">
        <tr>
            <th style="text-align: center" colspan="{{ $sub_count }}"><strong>{{ Qs::getSystemName() }}</strong></th>
        </tr>
        <tr>
            <th style="text-align: center" colspan="{{ $sub_count }}"><strong>STUDENTS EXAM MARKS TEMPLATE</strong></th>
        </tr>
        <tr>
            <th style="text-align: center" colspan="{{ $span = round($sub_count / 3) }}"><strong>CLASS: {{ strtoupper($marks->first()->my_class->name) }}</strong></th>
            <th style="text-align: center" colspan="{{ $span }}"><strong>YEAR: {{ $marks->first()->year }}</strong></th>
            <th style="text-align: center" colspan="{{ $sub_count - ($span * 2) }}"><strong>EXAM: {{ strtoupper($marks->first()->exam->name) }}</strong></th>
        </tr>
        <tr>
            <th style="text-align: center"><strong>S/N</strong></th>
            <th><strong>ADMISSION NUMBER</strong></th>
            <th><strong>FULL NAME</strong></th>
            <th style="text-align: center"><strong>SECTION</strong></th>

            @foreach($subjects as $sub)
            <th style="text-align: center"><strong>{{ $sub->name }}</strong></th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($marks->unique('user.id')->whereNotNull('user')->sortBy('user.name')->sortBy('user.gender') as $mk)
        <tr>
            <td style="text-align: center">{{ sprintf("%03d", $loop->iteration) }}</td>
            <td>{{ $mk->user->username }} </td>
            <td>{{ $mk->user->name }} </td>
            <td style="text-align: center">{{ $mk->section->name }} </td>

            @foreach($subjects as $sub)
            <td style="text-align: center">{{ $marks->where('subject_id', $sub->id)->where('student_id', $mk->user->id)->value('exm') }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
