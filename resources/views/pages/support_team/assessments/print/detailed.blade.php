<html>

<head>
    <title>Student Assessmentsheet (Detailed) - {{ $sr->user->name }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/my_print.css') }}" />

    @laravelPWA
</head>

<body>
    <div class="container">
        <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
            {{-- Logo N School Details--}}
            <table width="100%">
                <tr>
                    <td><img src="{{ tenant_asset($settings->where('type', 'logo')->value('description')) }}" style="max-height : 100px;"></td>

                    <td style="text-align: center; ">
                        <strong><span style="color: #1b0c80; font-size: 25px;">{{ strtoupper(Qs::getSetting('system_name')) }}</span></strong><br />
                        {{-- <strong><span style="color: #1b0c80; font-size: 20px;">MINNA, NIGER STATE</span></strong><br/>--}}
                        <strong><span style="color: #000; font-size: 15px;"><i>{{ ucwords($settings->where('type', 'address')->value('description')) }}</i></span></strong>
                        <br />
                        <strong><span style="color: #000; font-size: 15px;"> ASSESSMENT SHEET REPORT {{ '('.strtoupper($class_type->name).')' }}</span></strong>
                    </td>
                    <td style="width: 100px; height: 100px; float: left;">
                        <img src="{{ tenant_asset($sr->user->photo) }}" alt="..." width="100" height="100">
                    </td>
                </tr>
            </table>
            <br />

            {{--Background Logo--}}
            <div style="position: relative;  text-align: center;">
                <img src="{{ tenant_asset($settings->where('type', 'logo')->value('description')) }}" style="max-width: 500px; max-height:600px; margin-top: 60px; position:absolute; opacity: 0.1; margin-left: auto;margin-right: auto; left: 0; right: 0;" />
            </div>

            {{-- SHEET BEGINS HERE--}}
            @include('pages.support_team.assessments.print.sheet')

            {{--Key to Grading--}}
            @include('pages.support_team.assessments.print.grading')

            {{-- TRAITS - PSCHOMOTOR & AFFECTIVE --}}
            @include('pages.support_team.assessments.print.skills')

            <div style="margin-top: 25px; clear: both;"></div>

            {{-- COMMENTS & SIGNATURE    --}}
            @include('pages.support_team.assessments.print.comments')

        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });

    </script>
</body>

</html>
