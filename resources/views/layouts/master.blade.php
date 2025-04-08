<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="authors" content="Chinedu Okemiri (4jean) & Rashidi Said (rayusam)">

    @php $settings = Qs::getSettings() @endphp

    @if(Qs::googleAnalyticsSetUpOkAndEnabled())
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

    @include('partials.inc_top')

    @stack('css')
</head>

<body class="{{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.manage', 'assessments.manage', 'assessments.index', 'assessments.show', 'assessments.progressive', 'assessments.bulk', 'marks.show', 'ttr.manage', 'ttr.show', 'bin', 'events.manage', 'events.edit', 'query_builder.index', 'query_builder.select', 'notices.index', 'notices.edit', 'users.index', 'books.index', 'logs.index', 'students.promotion_manage', 'tenants.index', 'settings.index', 'settings_non_tenancy.index', 'analytics.index', 'analytics.fetch_data', 'tickets.reply', 'tickets.index_central', 'tickets.answer']) || auth()->user()->sidebar_minimized ? 'sidebar-xs' : '' }}">
    {{-- Block UI -- Loading page indicator; Will be removed by js script on page load; Copied from Block UI interface implementation --}}
    <div class="blockUI blockOverlay custom" style="z-index: 1000;border: none;margin: 0px;padding: 0px;width: 100%;height: 100%;background-color: rgb(0, 0, 0);opacity: 0.6;cursor: wait;position: fixed;"></div>
    <div class="blockUI blockMsg blockPage custom">
        <div class="mr-3 mt-1 d-inline-flex dot-carousel"></div> Loading. Please wait...
    </div>

    @include('partials.top_menu')
    <div class="page-content">
        @include('partials.menu')
        <div class="content-wrapper fl-scrolls-hoverable" data-fl-scrolls>
            @include('partials.header')

            <div class="content">
                {{-- Error Alert Area --}}
                @if($errors->any())
                <div class="alert alert-danger border-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                    @foreach($errors->all() as $er)
                    <span><i class="material-symbols-rounded pb-2px">hdr_strong</i> {{ $er }}</span> <br>
                    @endforeach

                </div>
                @endif

                <div class="display-none" id="ajax-alert"></div>

                @yield('content')
            </div>
        </div>
    </div>

    {{-- Go to Bottom Button --}}
    <div class="go-to-bottom">
        <button type="button" class="material-symbols-rounded btn btn-to-bottom float-right" title="Go to Bottom">arrow_downward</button>
    </div>
    {{-- Notification Sounds --}}
    <span class="d-none" id="notification-sounds" data-allow_system_sounds="{{ auth()->user()->allow_system_sounds }}" data-base_url="{{ asset('global_assets/sounds') }}"></span>
    {{-- Offline Indicator --}}
    <div id="offline-alert">Application Offline</div>

    @include('partials.inc_bottom')

    @yield('scripts')
</body>

</html>
