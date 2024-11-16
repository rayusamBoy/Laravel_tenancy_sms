@if(isset($last_exam))

{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script src="{{ asset('global_assets/js/plugins/charts/chart.js') }}"></script>

<script type="text/javascript">
    // Global variables
    const options = {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
        indexAxis: getIndexAxis(),
        animation: false,
        scales: {
            x: {
                title: {
                    display: true,
                }
            },
            y: {
                title: {
                    display: true,
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: "Nunito Sans"
                    }
                }
            },
            tooltip: {
                backgroundColor: "#303030",
                titleColor: "lightgray",
                bodyColor: "lightgray",
                boxHeight: 8,
                boxPadding: 2
            },
        }
    };
    const backgroundColor = [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(201, 203, 207, 0.2)'
    ];
    const borderColor = [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
    ];

    function getIndexAxis() {
        // If the user select the horizontal bar or line chart. Update the chart type to bar or line respectively (cause we do not have a chart of type horizontal).
        // And return 'y' as indexAxis to make the chart appear horizontal by changing the axis.
        if (localStorage.chart_type == 'horizontal_bar') {
            localStorage.chart_type = 'bar';
            return 'y';
        } else if (localStorage.chart_type == 'horizontal_line') {
            localStorage.chart_type = 'line';
            return 'y';
        }
        return 'x';
    }
</script>

<span>
    {{-- CHARTS TO BE DISPLAYED FOR STUDENTS AND PARENTS --}}
    @if (Qs::userIsParent() OR Qs::userIsStudent())

    {{-- Exam performances Chart --}}
    @foreach($exams_recs->groupBy('student_id') as $exam_recs)

    {{-- For making elements and variables distinctive; differ from one another --}}
    @php $loop = $loop->iteration @endphp

    <div class="row">
        <div class="col-12">
            {{-- Charts --}}
            <div class="card card-collapsed print-this">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title"></h5>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="p-2">
                    <canvas data-x_axis_title_text="Exams" data-y_axis_title_text="Total Marks" id="exam-performances-{{ $loop }}"></canvas>
                </div>
            </div>
        </div>
    </div>

    @php
    ${"exam_names_$loop"} = $exam_recs->pluck('exam.name');
    ${"totals_$loop"} = $exam_recs->pluck('total');
    ${"student_name_$loop"} = $exam_recs->value('student.name');
    ${"year_$loop"} = $exam_recs->value('year');
    @endphp

    <script>
        var loop = @php echo $loop @endphp; // Assign loop value to js variable

        const ctx_@php echo $loop @endphp = document.getElementById('exam-performances-@php echo $loop @endphp');
        ctx_@php echo $loop @endphp.parentElement.previousElementSibling.firstChild.nextElementSibling.textContent = "Overall Exams Performance Chart of " + "@php echo ${"student_name_$loop"} @endphp " + "(@php echo ${"year_$loop"} @endphp)";

        var labels_@php echo $loop @endphp = @php echo ${"exam_names_$loop"} @endphp;
        var data_@php echo $loop @endphp = {{ ${"totals_$loop"} }};

        new Chart(ctx_@php echo $loop @endphp, {
            type: localStorage.chart_type ?? 'bar',
            data: {
                labels: labels_@php echo $loop @endphp,
                datasets: [{
                    label: 'Total Marks Obtained',
                    data: data_@php echo $loop @endphp,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: options
        });
    </script>
    @endforeach
    {{-- End Exam Performances Chart --}}

    {{-- Last Exam Subjects Peformances Chart --}}
    @if(isset($last_exm_marks))
    @foreach($last_exm_marks->groupBy('student_id') as $last_exm_marks)

    @php $loop2 = $loop->iteration @endphp

    <div class="row">
        <div class="col-12">
            {{-- Charts --}}
            <div class="card card-collapsed print-this">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title"></h5>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="p-2">
                    <canvas data-x_axis_title_text="Subjects" data-y_axis_title_text="Marks" id="subjects-performances-{{ $loop2 }}"></canvas>
                </div>
            </div>
        </div>
    </div>

    @php
    ${"subjects_names_$loop2"} = $last_exm_marks->pluck('subject.name');
    ${"marks_$loop2"} = $last_exm_marks->pluck('tex' . $last_exam->term);
    ${"student_name_$loop2"} = $last_exm_marks->value('user.name');
    @endphp

    <script>
        var loop2 = @php echo $loop2 @endphp;

        const ctx2_@php echo $loop2 @endphp = document.getElementById('subjects-performances-@php echo $loop2 @endphp');
        ctx2_@php echo $loop2 @endphp.parentElement.previousElementSibling.firstChild.nextElementSibling.textContent = "Subjects Performnance Chart of " + "@php echo ${"student_name_$loop2"} @endphp " + "(@php echo $last_exam->name . ' ' . $last_exam->year @endphp)";

        var labels2_@php echo $loop2 @endphp = @php echo ${"subjects_names_$loop2"} @endphp;
        var data2_@php echo $loop2 @endphp = {{ ${"marks_$loop2"} }};

        new Chart(ctx2_@php echo $loop2 @endphp, {
            type: localStorage.chart_type ?? 'bar',
            data: {
                labels: labels2_@php echo $loop2 @endphp,
                datasets: [{
                    label: 'Mark Obtained',
                    data: data2_@php echo $loop2 @endphp,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: options
        });
    </script>
    @endforeach
    @endif
    {{-- End Last Exam Subjects Peformances Chart --}}

    {{-- ELSE CHARTS FOR ADMINS, TEACHERS, LIBRARIANS, AND COMPANIONS --}}
    @elseif(Qs::userIsTeamSATCL())

    {{-- Classes Performance Chart --}}
    <div class="row">
        <div class="col-12">
            {{-- Charts --}}
            <div class="{{ (is_null($prev_exam_id) OR is_null($next_exam_id)) ? 'card card-collapsed print-this' : 'card print-this' }}">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Classes Performance Chart for {{ $last_exam->name . ' ('.$last_exam->year.')' }}</h5>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="p-2 display-i-on-hover">
                    <i class="display-none position-absolute left-0"><a class="{{ is_null($prev_exam_id) ? 'btn btn-sm p-1 ml-5 disabled' : 'btn btn-sm p-1 ml-5' }}" href="{{ route('statistics.index', Qs::hash($prev_exam_id)) }}"><span class="material-symbols-rounded">chevron_left</span>previous</a></i>
                    <i class="display-none position-absolute right-0"><a class="{{ is_null($next_exam_id) ? 'btn btn-sm p-1 mr-4 disabled' : 'btn btn-sm p-1 mr-4' }}" href="{{ route('statistics.index', Qs::hash($next_exam_id)) }}">next<span class="material-symbols-rounded">chevron_right</span></a></i>

                    <canvas data-x_axis_title_text="Classes" data-y_axis_title_text="Averages" id="classes-performance"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('classes-performance');

        var labels = @php echo $classes_names @endphp;
        var data = @php echo $averages @endphp;

        new Chart(ctx, {
            type: localStorage.chart_type ?? 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Class Average',
                    data: data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: options
        });
    </script>
    {{-- Classes Performance chat end --}}

    @if($exams_gpas != null || $exams_names != null)
    {{-- This year's or session's exams GPA Performance Chart --}}
    <div class="row">
        <div class="col-12">
            {{-- Charts --}}
            <div class="card card-collapsed print-this">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Exams GPA Performance Chart ({{ $year }})</h5>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="p-2">
                    <canvas data-x_axis_title_text="Exams" data-y_axis_title_text="GPA" id="exams-gpa-performance"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx3 = document.getElementById('exams-gpa-performance');

        var labels = @php echo $exams_names @endphp;
        var data = @php echo $exams_gpas @endphp;

        new Chart(ctx3, {
            type: localStorage.chart_type ?? 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Exam GPA',
                    data: data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: options
        });
    </script>
    @endif
    {{-- Exams GPA performance chart end --}}

    @endif

    <span class="dropdown bottom-0 z-100">
        <button type="button" class="btn btn-success btn-sm position-fixed right-2 bottom-0" data-toggle="dropdown" aria-expanded="false">
            <span>Change Chart Type</span><i class="material-symbols-rounded ml-1">keyboard_arrow_up</i>
        </button>

        <div class="bg-dark-alpha bg-white dropdown-menu">
            <a href="javascript:;" id="line" class="dropdown-item chart-option">Line Chart</a>
            <a href="javascript:;" id="pie" class="dropdown-item chart-option">Pie Chart</a>
            <a href="javascript:;" id="polarArea" class="dropdown-item chart-option">PolarArea</a>
            <a href="javascript:;" id="bar" class="dropdown-item chart-option">Bar Chart</a>
            <a href="javascript:;" id="radar" class="dropdown-item chart-option">Radar Chart</a>
            <a href="javascript:;" id="horizontal_bar" class="dropdown-item chart-option">Horizontal Bar Chart</a>
            <a href="javascript:;" id="horizontal_line" class="dropdown-item chart-option">Horizontal Line Chart</a>
        </div>

        <script type="text/javascript">
            let chart_type_options = document.querySelectorAll(".chart-option");
            chart_type_options.forEach(function(elem) {
                elem.addEventListener("click", function(ev) {
                    ev.preventDefault();
                    var chart_type = elem.getAttribute("id");
                    updateChartTypeInStorage(chart_type);
                });
            });

            function updateChartTypeInStorage(chart_type) {
                localStorage.chart_type = chart_type;
                window.location.reload();
            }
        </script>
    </span>
</span>
@endif

<script type="text/javascript">
    // See custom.js for more
    updateJSCharts(getPreferredTheme());
    // Resize on window size change
    $(window).on("resize", function(){
        resizeChartInstances();
    });
</script>

{{-- End if show charts is set --}}