@php
$headings_count = count($heading_names);
$number_of_headings_in_a_row = 4;
@endphp

<table>
    <thead style="text-align: center;">
        <tr>
            <th style="text-align: center" colspan="{{ $headings_count }}"><strong>{{ Qs::getSystemName() }}</strong></th>
        </tr>
        <tr>
            <th style="text-align: center" colspan="{{ $headings_count }}"><strong>STUDENTS ADD TEMPLATE</strong></th>
        </tr>
        <tr>
            <th style="text-align: center" colspan="{{ $span = round($headings_count / $number_of_headings_in_a_row) }}"><strong>CLASS: {{ strtoupper($class->name) }}</strong></th>
            <th style="text-align: center" colspan="{{ $span }}"><strong>YEAR: {{ $year }}</strong></th>
            <th style="text-align: center" colspan="{{ $span }}"><strong>NATIONALITY: {{ strtoupper($nationality->name) }}</strong></th>
            {{-- Get colspan as the difference between headings_count and the value of span used 3 times above --}}
            <th style="text-align: center" colspan="{{ $headings_count - $span * 3 }}"><strong>STATE: {{ strtoupper($state->name) }}</strong></th>
        </tr>
        <tr>
            {{-- Hidden parameter row; act as database reference to heading names --}}
            @foreach($heading_names as $name => $parameter)
            <th style="text-align: center"><strong>{{ $parameter }}</strong></th>
            @endforeach
        </tr>
        <tr>
            @foreach($heading_names as $name => $parameter)
            <th style="text-align: center"><strong>{{ in_array($parameter, $parameters_with_rule_required) ? "$name *" : $name }}</strong></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @for($row = 1; $row <= $end_data_row; $row++) 
        <tr>
            @foreach($heading_names as $name => $parameter)
            {{--
            Heading names is an associative array containing representative names (for user presentation) as keys and the names used in the database (for internal use - parameters) as values.
            We have already defined names/parameters (to be hidden and populated) with the same name as the parameter in the export class.
            Here, we check if the current parameter in the loop exists in the array of parameters to be hidden and populated."
            --}}
            @if(in_array($parameter, $parameters_columns_to_hide_and_populate))
            {{-- If it exists, we populate the cell with the value of the variable of the same name as declared in the StudentRecordController --}}
            <th style="text-align: center">{{ ${$parameter} }}</th>
            @else
            {{-- If it does not exist, we populate the cell with an empty string --}}
            <th></th>
            @endif
            @endforeach
        </tr>
        @endfor
    </tbody>
</table>
