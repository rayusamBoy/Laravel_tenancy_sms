<head>
    <title>Students ID Card Template | {{ config('app.name') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/id_card.css') }}" />
    <style>
        .table-back-wrapper {
            min-height: 54mm;
            max-height: 54mm;
            display: table;
            border: 2px solid #115011;
            position: relative;
            max-width: 85.6mm;
            min-width: 85.6mm;
            border-radius: 5px;
        }

    </style>
</head>

<body>
    <div class="content-wrapper">
        {{-- @foreach($students as $st) --}}
        <div class="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
            <div class="cut-indicator">
                {{-- School Details--}}
                <div class="table-back-wrapper">
                    <table>
                        <tr>
                            <td>
                                This card is the property of {{ ucwords(strtolower(Qs::getSetting('system_name'))) }}, and the policies and procedures of the school govern its use.
                                The school is not responsible for any loss or expenses resulting from the loss, theft, or misuse of this card.
                                A replacement fee will be charged if this card is lost, damaged, or stolen.
                                If found, please return to the school at {{ ucwords(strtolower(Qs::getSetting('address'))) }}
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        {{-- @endforeach --}}
    </div>
    <script>
        // window.print();

    </script>
</body>

</html>
