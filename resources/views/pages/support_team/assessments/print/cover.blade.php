<html>

<head>
    <title>Assessment Cover for - {{ $student->user->name }}</title>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .hide .btn {
            position: fixed;
            cursor: pointer;
        }

        table:not(.photo) tr {
            font-size: 23px;
            display: table;
            padding-left: 1e;
            margin: 0px;
            border-spacing: 0 !important
        }

        table:not(.photo) tr td:last-child {
            padding-left: 1rem;
        }

        .hide p {
            border: 1px solid #00800026;
            padding: 0.3em;
            color: #00626e;
            background-color: #d6f4f8;
        }

        .student-info h5 {
            font-weight: 400;
            border-bottom: 1px solid;
            width: fit-content;
        }

        @media print {
            .hide {
                visibility: hidden;
                display: none;
            }

            @page {
                margin: 0 !important;
            }
        }

    </style>
</head>

<body>
    <div class="hide">
        <p>Set background color you like
            <select onchange="setBgColor(this.value);">
                <option selected disabled>here</option>
                <option value="white">white</option>
                <option value="seagreen">seagreen</option>
                <option value="yellowgreen">yellowgreen</option>
                <option value="green">green</option>
                <option value="darkgreen">darkgreen</option>
                <option value="darkolivegreen">darkolivegreen</option>
                <option value="darkseagreen">darkseagreen</option>
                <option value="brown">brown</option>
                <option value="cadetblue">cadetblue</option>
                <option value="darkgray">darkgray</option>
                <option value="dimgray">dimgray</option>
                <option value="gray">gray</option>
                <option value="lightblue">lightblue</option>
                <option value="lightcyan">lightcyan</option>
                <option value="rosybrown">rosybrown</option>
                <option value="sienna">sienna</option>
                <option value="khaki">khaki</option>
                <option value="darkkhaki">darkkhaki</option>
                <option value="pink">pink</option>
            </select>
            . Once finished click the <strong>Print</strong> button to print.
        </p>
        <button onclick="printPage();" class="btn"><strong>Print</strong></button>
    </div>

    <div id="print" style="margin: 3em;" xmlns:margin-top="http://www.w3.org/1999/xhtml">
        {{-- Logo N School Details--}}
        <table class="photo" width="100%">
            <tr>
                <td style="width: 120px; height: 120px; float: right;">
                    <img src="{{ tenant_asset($student->user->photo) }}" alt="{{ $student->user->name }}" width="120" height="120">
                </td>
            </tr>
        </table>

        <br />
        <h1 style="text-align: center; font-size: 50px"><strong>SECONDARY SCHOOL</strong></h1>
        <h2 style="text-align: center; font-size: 40px"><strong>ASSESSMENT RECORDS</strong></h2>
        <h3 style="text-align: center; font-size: 33px"><strong>FORM I - IV</strong></h3>
        <br /><br />
        <table class="info" width="100%">
            <tr>
                <td>
                    <h4><strong>Name of School:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ ucwords(strtolower($settings->where('type', 'system_name')->value('description'))) }}</h5>
                </td>
            </tr>

            <tr>
                <td>
                    <h4><strong>Name of Student:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ ucwords(strtolower($student->user->name)) }}</h5>
                </td>
            </tr>

            <tr>
                <td>
                    <h4><strong>Race and Religion:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ Usr::getNationality($student->user->nal_id)->value('subregion') . ' - ' . $student->user->religion }}</h5>
                </td>
            </tr>

            <tr>
                <td>
                    <h4><strong>House Number:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ $student->house_no }}</h5>
                </td>
            </tr>

            <tr>
                <td>
                    <h4><strong>Date Admitted:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ $student->date_admitted }}</h5>
                </td>
            </tr>

            <tr>
                <td>
                    <h4><strong>Date Graduated:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ $student->grad_date }}</h5>
                </td>
            </tr>

            <tr>
                <td>
                    <h4><strong>School Admission Number:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ $student->adm_no }}</h5>
                </td>
            </tr>

            <tr>
                <td>
                    <h4><strong>Date of Birth:</strong></h4>
                </td>
                <td class="student-info">
                    <h5>{{ $student->user->dob }}</h5>
                </td>
            </tr>
        </table>
    </div>

    <script>
        function setBgColor(color) {
            document.body.style.backgroundColor = color;
        }

        function printPage() {
            return window.print();
        }

    </script>
</body>
