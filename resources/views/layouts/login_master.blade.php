<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="authors" content="Chinedu Okemiri (4jean) & Rashidi Said (rayusam)">

    @php
    $settings = Qs::getSettings();
    $colors = $settings->where('type', 'login_and_related_pgs_txts_and_bg_colors')->value('description');
        if ($colors !== null) {
            $colors_exploaded = explode(Qs::getDelimiter(), $colors);
            $texts_color = $colors_exploaded[0];
            $bg_color = $colors_exploaded[1];
        }
    @endphp

    @if (Qs::googleAnalyticsSetUpOkAndEnabled())
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->where("type", "google_analytic_tag_id")->value("description") }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $settings->where("type", "google_analytic_tag_id")->value("description") }}');
        </script>
    @endif

    <title>{{ Qs::getStringAbbreviation(config('app.name')) }} &#183; @yield('page_title')</title>

    @laravelPWA

    @include('partials.login.inc_top')
</head>

<body>
    @include('partials.login.header')
    @include('partials.login.exam_announce')
    @include('pages.modals.admin')

    @yield('content')

    @include('partials.login.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize uniform
            $('.form-input-styled').uniform();
        }); 
    </script>
</body>

</html>
