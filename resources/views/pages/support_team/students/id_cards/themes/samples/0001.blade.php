<!DOCTYPE html>
<head>
    <title>Students ID Card Template | {{ config('app.name') }}</title>
    <style>
        body {
            margin: auto;
        }

        .content-wrapper {
            display: flex;
            flex-wrap: wrap;
            place-content: space-evenly;
        }

        td {
            text-align: center;
        }

        table.td-left td {
            text-align: left !important;
            padding: 5px;
        }

        .arial {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        .clear {
            clear: both;
        }

        @page {
            width: 53.98mm;
            height: 85.6mm;
            margin: 25px 20px;
        }

        .print {
            display: contents;
        }

        .table-wrapper {
            min-height: 54mm;
            max-height: 54mm;
            display: table;
            border: 2px solid #115011;
            position: relative;
            border-radius: 5px;
        }

        .signature {
            float: left;
            width: 200px;
            border-bottom: 1px solid;
        }

       .user {
            padding-bottom: 2em;
        }

        table tr td {
            width: 1%;
        }

        #school-name {
            font-size: 9px;
            color: #43449b;
            font-weight: 900;
            font-family: pertibd;
        }

        #id-title {
            font-size: 8px;
            font-weight: 900;
            color: #00501a;
            font-family: pertbd;
        }

        .logo {
            width: 45px;
        }

        .detail {
            font-size: 7px;
            font-weight: 700;
            text-align: start;
            color: #03004a;
            font-family: Helvetica, Arial, sans-serif;
            padding-left: 1em;
            text-transform: uppercase;
        }

        #headmaster-sign,
        #student-sign {
            width: 1%;
        }

        #headmaster-sign {
            position: relative;
            padding-top: 1em;
        }

        #headmaster-sign img {
            position: absolute;
            height: 20px;
            left: 98px;
            top: -3px;
        }

        #student-sign {
            width: 100%;
            padding: 0 5px 0 0;
            position: relative;
        }

        #student-sign span {
            position: absolute;
            top: 0;
            left: -35px;
        }

        .text-right {
            text-align: right;
        }

        table#theme-one {
            border: 5px solid #40449b;
        }

        table#theme-two {
            border: 5px solid #444497;
        }

        table {
            min-height: inherit;
            height: 100%;
            max-width: 85.6mm;
            min-width: 85.6mm;
            border-radius: 10px;
        }

        .student {
            top: 70px;
            right: 97px;
            position: absolute;
        }

        img#student {
            box-shadow: -6px 10px 14px -10px #43449b70 inset;
            border-radius: 0.5em;
            min-height: 65px;
            max-height: 65px;
            z-index: 999;
            position: inherit;
            min-width: 85px;
            max-width: 85px;
        }

        #motto {
            font-size: 7px;
            padding: 0px;
            font-family: pristina;
            font-weight: 600;
            letter-spacing: 1;
        }

        #line-divider {
            margin: 1px;
        }

        #web-link {
            font-size: 5px;
            font-family: Arial, Helvetica, sans-serif;
            letter-spacing: 1;
        }

        #theme-one .detail .title {
            color: darkred;
        }

        #theme-two .detail .title {
            color: #00501a;
        }

        .w-100 {
            width: 100%;
        }

        .cut-indicator {
            padding: 0;
            margin-top: 20px;
        }

        img#flower-signature {
            position: absolute;
            right: 15px;
            width: 18px;
            bottom: 11px;
        }

        img#graduate-signature {
            position: absolute;
            right: 15px;
            width: 30px;
            bottom: 11px;
        }

        @font-face {
            font-family: bradhitc;
            src: url("/global_assets/fonts/bradhitc.ttf");
        }

        @font-face {
            font-family: pertibd;
            src: url("/global_assets/fonts/pertibd.ttf");
        }

        @font-face {
            font-family: pertbd;
            src: url("/global_assets/fonts/Perpetua Bold font.ttf");
        }

        @font-face {
            font-family: pristina;
            src: url("/global_assets/fonts/PRISTINA.TTF");
        }

        @font-face {
            font-family: kingstonRoman;
            src: url("/global_assets/fonts/KingstonRoman-PKxpE.otf");
        }

    </style>

    @laravelPWA
</head>

<body style="margin: auto;">
    <div class="content-wrapper" style="display: flex; flex-wrap: wrap; place-content: space-evenly;">
        @foreach($students as $st)
        <div class="print" style="display: contents;" xmlns:margin-top="http://www.w3.org/1999/xhtml">
            <div class="cut-indicator" style="padding: 0; margin-top: 20px;">
                {{-- School Details--}}
                <div class="table-wrapper" style="min-height: 54mm; max-height: 54mm; display: table; border: 2px solid #115011; position: relative; border-radius: 5px; background-image: url({{ tenant_asset('backgrounds/ordinary_level.svg') }});">
                    <table id="theme-one" style="border: 5px solid #40449b; min-height: inherit; height: 100%; max-width: 85.6mm; min-width: 85.6mm; border-radius: 10px;">
                        <tr>
                            <td style="text-align: center;">
                                <img class="logo" src="{{ tenant_asset('others/left-logo.png') }}" style="width: 45px;">
                            </td>
                            <td colspan="2" style="text-align: center;">
                                <span id="school-name" style="font-size: 9px; color: #43449b; font-weight: 900; font-family: pertibd;">{{ $settings->where('type', 'system_name')->value('description') }}</span>
                                <hr id="line-divider" style="margin: 1px;">
                                <span id="id-title" style="font-size: 8px; font-weight: 900; color: #00501a; font-family: pertbd;">STUDENT IDENTITY CARD</span>
                            </td>
                            <td style="text-align: center;">
                                <img class="logo" src="{{ tenant_asset($settings->where('type', 'logo')->value('description')) }}" style="width: 45px;">
                            </td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="name" style="font-size: 7px; font-weight: 700; text-align: start; color: #03004a; font-family: Helvetica, Arial, sans-serif; padding-left: 1em; text-transform: uppercase;">
                                <strong><span class="title" style="color: darkred;">NAME:</span> {{ $st->user->name }}</strong>
                            </td>
                            <td class="student" rowspan="7" style="top: 70px; right: 97px; position: absolute;">
                                <img id="student" src="{{ Usr::getTenantAwarePhoto($st->user->photo) }}" style="box-shadow: -6px 10px 14px -10px #43449b70 inset; border-radius: 0.5em; min-height: 65px; max-height: 65px; z-index: 999; position: inherit; min-width: 85px; max-width: 85px;">
                            </td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="dob" style="font-size: 7px; font-weight: 700; text-align: start; color: #03004a; font-family: Helvetica, Arial, sans-serif; padding-left: 1em; text-transform: uppercase;">
                                <span class="title" style="color: darkred;">DOB:</span> {{ date_format(date_create($st->user->dob), 'F j, Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="gender" style="font-size: 7px; font-weight: 700; text-align: start; color: #03004a; font-family: Helvetica, Arial, sans-serif; padding-left: 1em; text-transform: uppercase;">
                                <span class="title" style="color: darkred;">SEX:</span> {{ $st->user->gender ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="class" style="font-size: 7px; font-weight: 700; text-align: start; color: #03004a; font-family: Helvetica, Arial, sans-serif; padding-left: 1em; text-transform: uppercase;">
                                <span class="title" style="color: darkred;">CLASS:</span> {{ ($class_from !== 'NULL' OR $class_to !== 'NULL') ? ("$class_from - $class_to") : $st->my_class->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="issued_expire" style="font-size: 7px; font-weight: 700; text-align: start; color: #03004a; font-family: Helvetica, Arial, sans-serif; padding-left: 1em; text-transform: uppercase;">
                                <span class="title" style="color: darkred;">ISSUED:</span> {{ $issued }} <span class="title" style="color: darkred;">& EXPIRE:</span> {{ $expire }}
                            </td>
                        </tr>
                        <tr>
                            <td class="detail" colspan="3" id="phone" style="font-size: 7px; font-weight: 700; text-align: start; color: #03004a; font-family: Helvetica, Arial, sans-serif; padding-left: 1em; text-transform: uppercase;">
                                <span class="title" style="color: darkred;">HEADMASTER'S MOBILE NO:</span> {{ $phone }}
                            </td>
                        </tr>
                        <tr>
                            <td class="detail w-100" id="headmaster-sign" colspan="2" style="width: 100%; padding: 0 5px 0 0; position: relative;">
                                <img src="{{ tenant_asset('others/headmaster-sign.png') }}" style="position: absolute; height: 20px; left: 98px; top: -3px;">
                                Headmaster's sign: {{ str_repeat('_', 14) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" id="motto" colspan="4" style="font-size: 7px; padding: 0px; font-family: pristina; font-weight: 600; letter-spacing: 1;">{{ $motto }}</td>
                        </tr>
                        <img src="{{ tenant_asset('others/flower.png') }}" id="flower-signature" style="position: absolute; right: 15px; width: 18px; bottom: 11px;">
                        @if($web_link !== 'NULL')
                        <tr>
                            <td id="web-link" colspan="4" style="font-size: 5px; font-family: Arial, Helvetica, sans-serif; letter-spacing: 1; opacity: 0.5;">
                                <span style="position: absolute; line-height: 0.9; font-size: 5px; opacity: 0.5; font-family: kingstonRoman; right: 19px; top: 143px; z-index: 1;">ORDINARY LEVEL STUDENT</span>
                                <span style="position: absolute; line-height: 0.9; font-size: 4px; opacity: 0.5; font-family: kingstonRoman; right: 40px; top: 150px; z-index: 1;">{{ "$year_from - $year_to" }}</span>
                                {{ $web_link }}
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <script>
        window.addEventListener('load', function() {
            window.print();
        });

    </script>
</body>

</html>
