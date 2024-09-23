<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Assessment Sheet - {{ $my_class->name ?? '' }} {{ $section->name ?? '' }} {{ $exam->name.' ('.$year.')' }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/print_assessments.css') }}" />

    @laravelPWA
</head>

<body>
    <div class="container">
        @if(Mk::isTerminalExam($exam->category_id) OR Mk::isAnnualExam($exam->category_id))
        {{--Background Logo--}}
        <div class="text-center">
            <img src="{{ tenant_asset(Qs::getSetting('logo')) }}" />
        </div>
        {{--Print Area--}}
        <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
            <div class="body">
                <h3 class="text-center">ASSESSMENT RECORDS FOR STUDENTS PER SUBJECT</h3>

                <div class="row text-center">
                    <div class="col-md-3">
                        <h4 class="card-title"><strong>CLASS: </strong> <span class="border-bottom-1">{{ strtoupper($m->my_class->name) }} {{ (!isset($section_id_is_null)) ? $m->section->name : '' }}</span></h4>
                    </div>
                    <div class="col-md-3">
                        <h4 class="card-title"><strong>SUBJECT: </strong> <span class="border-bottom-1">{{ strtoupper($m->subject->name) }}</span></h4>
                    </div>

                    <div class="col-md-3">
                        <h4 class="card-title"><strong>TERM: </strong> <span class="border-bottom-1">{!! strtoupper(Mk::getSuffix($m->exam->term)) !!} TERM</span></h4>
                    </div>

                    <div class="col-md-3">
                        <h4 class="card-title"><strong>YEAR: </strong> <span class="border-bottom-1">{{ strtoupper($m->year) }}</span></h4>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <td rowspan="2" colspan="2">DATE</td>
                            <td colspan="11">CLASSWORK ({{ $exam->cw_denominator }})</td>
                            <td colspan="6">HOME WORK ({{ $exam->hw_denominator }})</td>
                            <td colspan="4">TOPIC TEST ({{ $exam->tt_denominator }})</td>
                            <td rowspan="3"><span class="rotated">TEST ({{ $exam->tdt_denominator }})</span></td>
                            <td rowspan="3"><span class="rotated">TOTAL (100)</span></td>
                            <td rowspan="3"><span class="rotated">GRADE</span></td>
                            <td rowspan="3"><span class="rotated">POSITION</span></td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                            <td rowspan="2"><span class="rotated">AVERAGE ({{ $exam->cw_denominator }})</span></td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td rowspan="2"><span class="rotated">AVERAGE ({{ $exam->hw_denominator }})</span></td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td rowspan="2"><span class="rotated">AVERAGE ({{ $exam->tt_denominator }})</span></td>
                        </tr>
                        <tr class="h-fixed">
                            <td>S/N</td>
                            <td>STUDENT'S NAME</td>
                            <td colspan="10">STUDENT'S MARKS</td>
                            <td colspan="5">STUDENT'S MARKS</td>
                            <td colspan="3">STUDENT'S MARKS</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessments->sortBy('user.name')->sortBy('user.gender') as $mk)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{--Course Work--}}
                            <td class="text-left pl-p5em">{{ $mk->user->name }}</td>
                            <td>{{ $mk->cw1 ?? '' }}</td>
                            <td>{{ $mk->cw2 ?? '' }}</td>
                            <td>{{ $mk->cw3 ?? '' }}</td>
                            <td>{{ $mk->cw4 ?? '' }}</td>
                            <td>{{ $mk->cw5 ?? '' }}</td>
                            <td>{{ $mk->cw6 ?? '' }}</td>
                            <td>{{ $mk->cw7 ?? '' }}</td>
                            <td>{{ $mk->cw8 ?? '' }}</td>
                            <td>{{ $mk->cw9 ?? '' }}</td>
                            <td>{{ $mk->cw10 ?? '' }}</td>
                            {{--CW Average (10)--}}
                            <td>@php $cw_avg = Mk::getAverage($mk->cw1 + $mk->cw2 + $mk->cw3 + $mk->cw4 + $mk->cw5 + $mk->cw6 + $mk->cw7 + $mk->cw8 + $mk->cw9 + $mk->cw10, 10) @endphp {{ $cw_avg != 0 ? $cw_avg : '' }}</td>
                            {{--Home Work--}}
                            <td>{{ $mk->hw1 ?? '' }}</td>
                            <td>{{ $mk->hw2 ?? '' }}</td>
                            <td>{{ $mk->hw3 ?? '' }}</td>
                            <td>{{ $mk->hw4 ?? '' }}</td>
                            <td>{{ $mk->hw5 ?? '' }}</td>
                            {{--HW Average (10)--}}
                            <td>@php $hw_avg = Mk::getAverage($mk->hw1 + $mk->hw2 + $mk->hw3 + $mk->hw4 +$mk->hw5, 5) @endphp {{ $hw_avg != 0 ? $hw_avg : '' }}</td>
                            {{--Topic Test--}}
                            <td>{{ $mk->tt1 ?? '' }}</td>
                            <td>{{ $mk->tt2 ?? '' }}</td>
                            <td>{{ $mk->tt3 ?? '' }}</td>
                            {{--TT Average (20)--}}
                            <td>@php $tt_avg = Mk::getAverage($mk->tt1 + $mk->tt2 + $mk->tt2, 3) @endphp {{ $tt_avg != 0 ? $tt_avg : ''  }}</td>
                            {{--Exam (60)--}}
                            <td>{{ $mk->exm ?? '' }}</td>
                            {{--Total (100)--}}
                            <td>@php $tex = 'tex'.$exam->term; $mark = $mk->$tex; @endphp {{ $mark ?? '' }}</td>
                            {{--Grade--}}
                            <td>{{ $mk->grade->name ?? '' }}</td>
                            {{--Position of each student of a particular section and subject--}}
                            <td>{{ $stds_texs->count() > 0 ? $stds_texs->search($mk->$tex) + 1 : '' }}</td>
                        </tr>
                        @endforeach
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
        @endif
    </div>

    <script>
       window.onload = (event) => {
            window.print();
        }
    </script>
</body>

</html>