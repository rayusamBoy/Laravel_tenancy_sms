<html>

<head>
    <title>Staff Informations &#183; {{ Qs::getAppCode() }}</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        table tr td,
        table tr th {
            padding: 0 0.3em;
        }

        @media print {
            @page {
                size: landscape;
                margin-top: 50px;
                margin-bottom: 50px
                /* this affects the margin in the printer settings */
            }

            html {
                background-color: #FFFFFF;
                margin: 10px;
                /* this affects the margin on the html before sending to printer */
            }
        }

    </style>
</head>

<body>
    <div class="container">
        {!! $staff !!}
    </div>

    <script type="text/javascript">
        window.addEventListener('load', function() {
            window.print();
        });

    </script>
</body>

</html>
