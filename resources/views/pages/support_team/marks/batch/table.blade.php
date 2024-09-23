{{-- Plus 2 (S/N, ADN NO., and NAME) or 3 (S/N, ADM NO., NAME, and COMB) --}}
@php
$advance_level_id = 3;
$plus = ($class_type_id == $advance_level_id) ? 4 : 3;
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
            <th style="text-align: center" colspan="{{ $a = round($sub_count / 3) }}"><strong>CLASS: {{ strtoupper($m->my_class->name) }}</strong></th>
            <th style="text-align: center" colspan="{{ $a }}"><strong>YEAR: {{ $m->year }}</strong></th>
            <th style="text-align: center" colspan="{{ $sub_count - ($a * 2) }}"><strong>EXAM: {{ strtoupper($m->exam->name) }}</strong></th>
        </tr>
        <tr>
            <th style="text-align: center"><strong>S/N</strong></th>
            <th><strong>ADMISSION NUMBER</strong></th>
            <th><strong>FULL NAME</strong></th>

            @if($class_type_id == $advance_level_id)
            <th style="text-align: center"><strong>COMB</strong></th>
            @endif

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

            @if($class_type_id == $advance_level_id)
            <td style="text-align: center">{{ $mk->section->name }} </td>
            @endif

            @foreach($subjects as $sub)
            <td style="text-align: center">{{ $marks->where('subject_id', $sub->id)->where('student_id', $mk->user->id)->value('exm') }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
