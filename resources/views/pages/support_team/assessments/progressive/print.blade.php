<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Progressive Sheet - {{ $my_class->name . ' ' }} @if (isset($section)) {{ $section->name }} @endif {{ ' - ' . 'of ' . $ex->name . ' (' . $year . ')' }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/print_assessments.css') }}" />

    @laravelPWA
</head>

<body>
    <div class="container position-relative">
        {{--Background Logo--}}
        <div class="text-center">
            <img src="{{ tenant_asset(Qs::getSetting('logo')) }}" />
        </div>
        {{--Header--}}
        @php $term = ($asr->first()->exam->term == 1) ? "FIRST" : "SECOND" @endphp
        <h2 class="text-center">{{ Qs::getSetting('system_name') }}</h2>
        <h3 class="text-center">PROGRESSIVE SHEET FOR {{ strtoupper($my_class->name) . ' ' }} @if (isset($section)) {{ strtoupper($section->name) }} @endif {{ ' - ' . strtoupper($ex->name) . ' (' . $term . ' TERM, ' . $year . ')' }}</h3>
        {{--Print Area--}}
        <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">

            <table class="table">
                <thead class="bg-light">
                    <tr>
                        <th class="font-weight-bold">#</th>
                        <th class="font-weight-bold">STUDENT NAME</th>
                        <th class="font-weight-bold text-vertical">SEX</th>

                        @foreach ($subjects as $sub)
                        <th class="font-weight-bold text-vertical" title="{{ $sub->name }}" rowspan="2">{{ ucwords($sub->slug ?: $sub->name) }}</th>
                        <th class="font-weight-bold text-vertical" title="Grade">Grade</th>
                        <th class="font-weight-bold text-vertical" title="Grade">Position</th>
                        @endforeach

                        <th class="font-weight-bold text-vertical text-darkred">Total</th>
                        <th class="font-weight-bold text-vertical text-darkblue">Average</th>
                        <th class="font-weight-bold text-vertical text-orangered" title="Average Grade">Grade</th>
                        <th class="font-weight-bold text-vertical text-darkgreen">Position</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($students->sortBy('user.gender') as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left pl-p5em">{{ $s->user->name }}</td>
                        <td>{{ substr($s->user->gender, 0, 1) }}</td>

                        @foreach ($subjects as $sub)
                        @php $as_recs = $asr->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first() @endphp
                        <td>{{ $as_recs->$tex ?? '' }}</td>
                        <td>{{ $as_recs->grade->name ?? '' }}</td>
                        <td>{{ $as_recs->sub_pos ?? '' }}</td>
                        @endforeach

                        <td class="text-darkred">{{ $asr->where('student_id', $s->user_id)->first()->total ?: '' }}</td>
                        <td>{{ $average = $asr->where('student_id', $s->user_id)->first()->ave ?: '' }}</td>
                        <td class="text-orangered">{{ ($average != NULL) ? Mk::getGrade($average) : "" }}</td>
                        <td class="text-darkgreen">{{ $asr->where('student_id', $s->user_id)->first()->pos ?: '' }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">SUBJECT TOTAL</td>
                        @foreach ($subjects as $sub)
                        <td colspan="3">{{ $asr->where('subject_id', $sub->id)->sum($tex) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="3">SUBJECT AVERAGE</td>
                        @foreach ($subjects as $sub)
                        <td colspan="3">{{ round($asr->where('subject_id', $sub->id)->avg($tex), 2) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="3">SUBJECT GRADE</td>
                        @foreach ($subjects as $sub)
                        @php $avg = round($asr->where('subject_id', $sub->id)->avg($tex), 2) @endphp
                        <td colspan="3">{{ ($avg != NULL) ? Mk::getGrade($avg) : "" }}</td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="display-none">
                        <td class="border-0">
                            @if(Qs::authUserGenderIsMale())
                            <small class="position-fixed bottom-0 right-0 text-right w-100">Retrieved by, Mr. {{ Auth::user()->name }} ({{ date('m/d/Y h:i:s a', time()) }})</small>
                            @endif

                            @if(Qs::authUserGenderIsFemale())
                            <small class="position-fixed bottom-0 text-right w-100">Retrieved by, Madam. {{ Auth::user()->name }} ({{ date('m/d/Y h:i:s a', time()) }})</small>
                            @endif
                            {{-- <small class="position-fixed bottom-0 text-right w-90">{{ date('Y') . ". " }} {{ ucwords(strtolower(Qs::getSetting('system_name'))) }}. {{ __('All rights reserved.') }}</small> --}}
                        </td>
                    </tr>
                </tfoot>
            </table>
            
        </div>
    </div>

    <script type="text/javascript">
        window.onload = (event) => {
            window.print();
        }
    </script>
</body>

</html>
