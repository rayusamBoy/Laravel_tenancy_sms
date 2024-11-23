{{-- Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">

<!-- Global stylesheets -->
{{-- <link href="{{ asset('global_assets/css/icons/icomoon/styles-min.css') }}" rel="stylesheet" type="text/css"> --}}
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
{{-- <link href="{{ asset('global_assets/css/icons/material/icons.css') }}" rel="stylesheet" type="text/css"> --}}
<link href="{{ asset('assets/css/bootstrap-min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/bootstrap_limitless-min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/layout-min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/components-min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/colors-min.css') }}" rel="stylesheet" type="text/css">

<!-- Core JS files -->
<script src="{{ asset('global_assets/js/main/jquery.min.js') }} "></script>
<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }} "></script>

<!-- Theme JS files -->
<script src="{{ asset('assets/js/app-min.js') }} "></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }} "></script>
<script src="{{ asset('global_assets/js/plugins/scrollers/jquery.marquee.min.js') }}"></script>
<script src="{{ asset('global_assets/js/main/color-modes.js') }}"></script>

@php
    use Database\Seeders\NonTenancySettingsTableSeeder;
    $settings_table_seeder = new NonTenancySettingsTableSeeder();
    $color = (isset($colors) && !is_null($colors)) ? $texts_color . ' !important' : 'white !important';
    $bg_color = (isset($colors) && !is_null($colors)) ? $bg_color : 'rgb(35 39 53)';
    $bg = $settings->where('type', 'login_and_related_pages_bg')->value('description');
@endphp

<style>
    html, body {
        color-scheme: dark;
    }

    div.page-content.login-cover {
        background-image: url({{ Usr::tenancyInitilized() ? (is_null($bg) ? asset($settings_table_seeder->getLoginAndRelatedPagesBgDescription()) : tenant_asset($bg)) : asset($bg) }});
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        background-color: {{ $bg_color }} !important;
        background-blend-mode: soft-light;
        animation-name: sides-move;
        animation-duration: 60s;
        animation-iteration-count: infinite;
    }

    div.exam-announce-wrapper {
        box-shadow: -4px 5px 10px inset #333333, 4px -5px 10px inset {{ $bg_color }};
    }

    .text-color-custom {
        color: {{ $color }};
    }

    .navbar {
        background-color: {{ $bg_color }} !important;    
    }
</style>