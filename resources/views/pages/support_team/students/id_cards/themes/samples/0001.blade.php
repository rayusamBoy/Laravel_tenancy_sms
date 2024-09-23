<!DOCTYPE html>
<head>
    <title>Students ID Card Template | {{ config('app.name') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/id_card.css') }}" />
    <style>
        .student:after {
            content: "{{ $settings->where('type', 'system_title')->value('description') }} Sample ID Card";
            position: absolute;
            transform: rotate(306deg);
            line-height: 0.9;
            font-size: 29px;
            /* opacity: 0.1; */
            font-family: kingstonRoman;
            -webkit-transform: rotate(306deg);
            right: 2px;
            top: -8px;
            z-index: 1;
        }

        .table-wrapper {
            background-image: url({{ tenant_asset('backgrounds/ordinary_level.svg') }});
        }

        #web-link::before {
            content: "ORDINARY LEVEL STUDENT";
            position: absolute;
            line-height: 0.9;
            font-size: 5px;
            opacity: 0.5;
            font-family: kingstonRoman;
            right: 19px;
            top: 143px;
            z-index: 1;
        }

        #web-link::after {
            content: "{{ $year_from . ' - ' . $year_to }}";
            position: absolute;
            line-height: 0.9;
            font-size: 4px;
            opacity: 0.5;
            font-family: kingstonRoman;
            right: 40px;
            top: 150px;
            z-index: 1;
        }

        .student img {
            filter: brightness({{ $brightness }});
            backdrop-filter: none;
        }

    </style>

    @laravelPWA
</head>

<body>
    <div class="content-wrapper">
        @foreach($students as $st)
        <div class="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
            <div class="cut-indicator">
                {{-- School Details--}}
                <div class="table-wrapper">
                    <table id="theme-one">
                        <tr>
                            <td><img class="logo" src="{{ tenant_asset('others/smz-logo.jpg') }}"></td>
                            <td colspan="2">
                                <span id="school-name">{{ $settings->where('type', 'system_name')->value('description') }}</span>
                                <hr id="line-divider">
                                <span id="id-title">STUDENT IDENTITY CARD</span>
                            </td>
                            <td><img class="logo" src="{{ tenant_asset($settings->where('type', 'logo')->value('description')) }}"></td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="name"><strong><span class="title">NAME:</span> {{ $st->user->name }}</strong></td>
                            <td class="student" rowspan="7"><img id="student" src="{{ Usr::getTenantAwarePhoto($st->user->photo) }}"></td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="dob"><span class="title">DOB:</span> {{ date_format(date_create($st->user->dob), 'F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="gender"><span class="title">SEX:</span> {{ $st->user->gender ?? '' }}</td>
                        </tr>
                        <tr>
                            {{--If class_from and class_to variables are not NULL (string; declared in href route link), use them, otherwise use the student specific class--}}
                            <td class="detail" colspan="3" id="class"><span class="title">CLASS:</span> {{ ($class_from !== 'NULL' OR $class_to !== 'NULL') ? ($class_from . ' - ' . $class_to) : $st->my_class->name }}</td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="issued_expire"><span class="title">ISSUED:</span> {{ $issued }} <span class="title">& EXPIRE:</span> {{ $expire }}</td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="phone"><span class="title">HEADMASTER'S MOBILE NO:</span> {{ $phone }}</td>
                        </tr>
                        <tr>
                            <td class="detail w-100" id="headmaster-sign" colspan="2"><img src="{{ tenant_asset('others/headmaster-sign.png') }}">Headmaster's sign: {{ str_repeat('_', 14) }}</td>
                            <!-- <td class="detail" id="student-sign" colspan="2"><span>Student's sign: {{ str_repeat('_', 14) }}</span></td> -->
                        </tr>
                        <tr>
                            <td class="text-center" id="motto" colspan="4">{{ $motto }}</td>
                        </tr>
                        <img src="{{ tenant_asset('others/flower.png') }}" id="flower-signature">
                        @if($web_link !== 'NULL')
                        <tr>
                            <td id="web-link" colspan="4">{{ $web_link }}</td>
                        </tr>
                        @else
                        <tr><td></td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <script>
        window.print();

    </script>
</body>

</html>
