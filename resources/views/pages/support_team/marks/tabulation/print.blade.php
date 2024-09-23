<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tabulation Sheet - {{ $my_class->name }} @if(isset($section)) {{ $section->name }} @endif {{ $ex->name.' ('.$year.')' }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/print_tabulation.css') }}" />

    @laravelPWA
</head>

<body>
    {{--Print Button--}}
    <div class="hide">
        {{-- Exam number format notification --}}
        @if($ex->number_format->isNotEmpty())
        <p>
            You can <strong>edit the exam number (if set) or section number</strong> as per your need(s). Also, you can choose the column or section to hide or reveal from below on the right side.
            When you're done click on the <strong>Print</strong> button below on the left side to print.
        </p>
        @endif
        <button onclick="printPage();" class="btn"><strong>Print</strong></button>
    </div>
    {{-- Unhide selections --}}
    <div class="hide float-right">
        <select onchange="unhideSelected(this.value)">
            <option disabled selected>Select to Unhide</option>
            <option disabled id="total-unhide" value="total">Total</option>
            <option disabled id="average-unhide" value="average">Average</option>
            <option disabled id="grade-unhide" value="grade">Grade</option>
            <option disabled id="points-unhide" value="points">Points</option>
            <option disabled id="division-unhide" value="division">Division</option>
            <option disabled id="position-unhide" value="position">Position</option>
            <option disabled id="gpa-unhide" value="gpa">GPA</option>
            <option disabled id="performance-summary-unhide" value="performance-summary">Performance Summary</option>
            <option disabled id="division-summary-unhide" value="division-summary">Division Summary</option>
            <option disabled id="grades-criteria-unhide" value="grades-criteria">Grades Criteria</option>
            <option disabled id="division-criteria-unhide" value="division-criteria">Division Criteria</option>
        </select>
    </div>
    {{-- Hide selections --}}
    <div class="hide float-right">
        <select onchange="hideSelected(this.value)">
            <option disabled selected>Select to Hide</option>
            <option id="total-hide" value="total">Total</option>
            <option id="average-hide" value="average">Average</option>
            <option id="grade-hide" value="grade">Grade</option>
            <option id="points-hide" value="points">Points</option>
            <option id="division-hide" value="division">Division</option>
            <option id="gpa-hide" value="gpa">GPA</option>
            <option id="position-hide" value="position">Position</option>
            <option id="performance-summary-hide" value="performance-summary">Performance Summary</option>
            <option id="division-summary-hide" value="division-summary">Division Summary</option>
            <option id="grades-criteria-hide" value="grades-criteria">Grades Criteria</option>
            <option id="division-criteria-hide" value="division-criteria">Division Criteria</option>
        </select>
    </div>

    <div class="container">
        <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
            {{-- Logo and School Details--}}
            <table width="100%">
                <tr>
                    <td><img src="{{ tenant_asset($settings->where('type', 'logo')->value('description')) }}" style="max-height : 100px;"></td>
                    <td>
                        <strong><span style="color: #1b0c80; font-size: 25px;">{{ strtoupper(Qs::getSetting('system_name')) }}</span></strong><br /><br />
                        <strong><span style="color: #000; font-size: 15px;"><span>{{ ucwords($settings->where('type', 'address')->value('description')) }}</span></span></strong><br />
                        <strong><span style="color: #000; font-size: 15px;"> RESULTS SHEET FOR {{ strtoupper($my_class->name) }} @if(isset($section)) {{ strtoupper($section->name) }} @endif {{ strtoupper($ex->name.' ('.$year.')') }}</span></strong>
                    </td>
                </tr>
            </table>
            <br />

            {{--Background Logo--}}
            <div style="position: relative; text-align: center; ">
                <img src="{{ tenant_asset($settings->where('type', 'logo')->value('description')) }}" class="bg-logo" />
            </div>

            {{-- Summaries --}}
            <div style="display: flex;">
                {{-- Division Summary --}}
                <div id="division-summary" class="mw-fit w-60">
                    <strong style="color: #000; font-size: 15px;">Division Summary</strong>
                    {!! Mk::renderDivisionsSummary($exam_id, $my_class->id, $my_class->class_type_id, $section_id) !!}
                </div>
                {{-- Grades Criteria --}}
                <div id="grades-criteria" class="mw-fit w-60" style="margin-left: 1em;">
                    <strong style="color: #000; font-size: 15px;">Grades Criteria</strong>
                    {!! Mk::renderGrades($class_type_id) !!}
                </div>
                {{-- Division Criteria --}}
                <div id="division-criteria" class="mw-fit w-60" style="margin-left: 1em;">
                    <strong style="color: #000; font-size: 15px;">Division Criteria</strong>
                    {!! Mk::renderDivisions($class_type_id) !!}
                </div>
                {{--School GPA--}}
                <div class="w-60 text-center" style="margin: 0 auto">
                    <strong style="color: #000; font-size: 15px;">School GPA <i>(out of 5)</i></strong>
                    <table class="table-styled">
                        <thead style="background: #f5f5f5;">
                            <tr>
                                <td>{{ Mk::getGPA($exam_id, $my_class->id, $my_class->class_type_id, $section_id) }}</td>
                            </tr>
                    </table>
                </div>
            </div>

            {{-- Tabulation Begins --}}
            <table class="table-styled">
                <thead style="background: #f5f5f5;">
                    <tr>
                        <th class="font-weight-bold">{{ $ex->number_format->isNotEmpty() ? 'EXAM NUMBER' : 'NUMBER' }}</th>
                        <th class="font-weight-bold">STUDENT NAME</th>
                        <th class="font-weight-bold"><span class="text-vertical">GENDER</span></th>

                        @foreach($subjects as $sub)
                        {{-- Subject Name --}}
                        <th class="font-weight-bold" title="{{ $sub->name }}" rowspan="2"><span class="text-vertical">{{ strtoupper($sub->slug ?: $sub->name) }}</span></th>
                        {{-- Grade --}}
                        <th class="font-weight-bold" title="Grade"><span class="text-vertical">Grade</span></th>
                        @endforeach

                        <th class="font-weight-bold total" style="color: darkred"><span class="text-vertical">Total</span></th>
                        <th class="font-weight-bold average" style="color: darkblue"><span class="text-vertical">Average</span></th>
                        <th class="font-weight-bold grade" title="Average Grade" style="color: darkblue"><span class="text-vertical">Grade</span></th>
                        <th class="font-weight-bold points" title="Division Points" style="color: darkviolet;"><span class="text-vertical">Points</span></th>
                        <th class="font-weight-bold division" title="Division" style="color: maroon;"><span class="text-vertical">Division</span></th>
                        <th class="font-weight-bold gpa" title="Gpa" style="color: darkslategray;"><span class="text-vertical">GPA</span></th>
                        <th class="font-weight-bold position" style="color: darkgreen"><span class="text-vertical">Position</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students->sortBy('user.name')->sortBy('user.gender') as $s)

                    @php
                    $gender = $s->user->gender;
                    @endphp

                    <tr>
                        {{-- Exam Number --}}
                        <td contenteditable="true">
                            @if($ex->number_format->isNotEmpty())
                            @php
                            $student_number_placeholder = Usr::getStudentExamNumberPlaceholder();
                            $number_format = $ex->number_format->where('exam_id', $exam_id)->where('my_class_id', $my_class->id)->value('format');
                            $loop = sprintf("%0" . substr_count($number_format, $student_number_placeholder) . "d", $loop->iteration) 
                            @endphp
                            {{ str_replace(str_repeat($student_number_placeholder, substr_count($number_format, $student_number_placeholder)), $loop, $number_format) }}
                            @else
                            {{ sprintf("%04d", $loop->iteration) }}
                            @endif
                        </td>
                        {{-- Student Name --}}
                        <td style="text-align: left; padding-left: 0.8em">{{ $s->user->name }}</td>
                        {{-- Gender --}}
                        <td class="gender" style="text-align: center;">{{ substr($gender, 0, 1) }}</td>

                        @php $total = $exr->where('student_id', $s->user_id)->first()->total; @endphp

                        @foreach($subjects as $sub)
                        {{-- Exam Mark --}}
                        <td>{{ $total === 0 ? Qs::convertEncoding('&#10006;') : (($marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->$tex ?? '-')) }}</td>
                        {{-- Grade --}}
                        <td style="background: lightblue;">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->grade->name ?? '-') }}</td>
                        @endforeach

                        {{--Exams total marks--}}
                        <td class="total" style="color: darkred">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->total ?? '-') }}</td>
                        {{--Exam average--}}
                        <td class="average" style="color: darkblue"> {{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->ave ?? '-') }}</td>
                        {{--Exam grade--}}
                        <td class="grade" style="color: darkblue">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->grade->name ?? '-') }}</td>
                        {{--Points--}}
                        <td class="points" style="color: darkviolet;">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->points ?? '-') }}</td>
                        {{--Division--}}
                        <td class="division" style="color: maroon">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->division->name ?? '-') }}</td>
                        {{--Gpa--}}
                        <td class="gpa text-center" style="color: darkslategrey">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->gpa ?? '-') }}</td>
                        {{--Position--}}
                        <td class="position" style="color: rgb(0, 112, 24);">
                            @if($total === 0)
                            &#10006; {{-- 'X' mark --}}
                            @elseif($section_id != 'all')
                            {{ $exr->where('student_id', $s->user_id)->first()->pos ?? '-' }}
                            @elseif($section_id == 'all')
                            {{ $exr->where('student_id', $s->user_id)->first()->class_pos ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="performance-summary" style="margin-top: 1em">
        {{-- Subjects Performance Summary --}}
        <strong style="color: #000; font-size: 15px;">Subjects Performance Summary</strong>
        <table class="table-styled">
            <thead>
                <tr>
                    <th>SLUG</th>
                    <th>NAME</th>
                    <th>REG</th>
                    <th>SAT</th>
                    <th>ABS</th>
                    <th>AVG</th>
                    <th style="background-color: lightblue">GRADE</th>
                    <th>GRADES COUNT</th>
                    <th>GPA</th>
                    <th>RANK</th>
                </tr>
            </thead>
            <tbody>
                {!! Mk::renderSubsPerformanceSummary($section_id, $ex->id, $my_class) !!}
            </tbody>
        </table>

        {{-- Key --}}
        <p><strong class="text-underline">KEY</strong></p>
        <p>
            <strong>SAT: </strong>Students Attended
            <strong>ABS: </strong>Absent
            <strong>REG: </strong>Registered
            <strong>AVG: </strong>Average
        </p>
    </div>

    {{-- Copyright text --}}
    <p class="copyright">{{ date('Y') . ". " }} {{ ucwords(strtolower(Qs::getSetting('system_name'))) }}. {{ __('All rights reserved.') }}</p>

    <script type="text/javascript">
        function printPage() {
            return window.print();
        }

        // Set border for tables
        var els = document.getElementsByClassName('styled');
        Array.prototype.forEach.call(els, function(el) {
            el.setAttribute('border', '1');
        });

        function hideSelected(value) {
            var els = document.getElementsByClassName(value);
            if (els.length) { // If elements with the specified class presents
                Array.prototype.forEach.call(els, function(el) {
                    el.style.display = 'none'; // Hide them all
                });
            } else
                hideElementById(value); // Else, hide element with the specified id

            disableElementById(value + '-hide'); // Disable selected option
            enableElementById(value + '-unhide'); // Enable its corresponding option in unhide select element
        }

        function unhideSelected(value) {
            var els = document.getElementsByClassName(value);
            if (els.length) {
                Array.prototype.forEach.call(els, function(el) {
                    el.style.display = 'table-cell';
                });
            } else
                unHideElementById(value);

            enableElementById(value + '-hide'); // Enable its corresponding option  in hide select element
            disableElementById(value + '-unhide'); // Disable selected option inb hide select element
        }

        function disableElementById(id) {
            document.getElementById(id).disabled = true;
        }

        function enableElementById(id) {
            document.getElementById(id).disabled = false;
        }

        function hideElementById(id) {
            document.getElementById(id).style.display = 'none';
        }

        function unHideElementById(id) {
            document.getElementById(id).style.display = 'block';
        }

    </script>
</body>

</html>