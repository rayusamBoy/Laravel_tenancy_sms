<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMS') }} - @yield('title')</title>
    <!-- Styles -->
    <style>
        svg {
            width: 25vh;
            height: 5vw;
            margin: auto
        }
    </style>

    @stack('css')

    @laravelPWA

    @include('errors.inc_top')
</head>

<body>   
    <div class="navbar navbar-dark login">
        @if(session('tenant_account_not_active'))
        <h6 class="text-bold text-info text-color-custom mt-auto mb-auto">{{ session('tenant_account_not_active') }}</h6>
        @else
        <h6 class="mt-auto mb-auto text-color-custom">@yield('title')</h6>
        @endif
    
        <a href="{{ route('home') }}" class="navbar-nav-link d-flex text-color-custom float-right">
            <span class="mr-1">{{ ("Home") }}</span>
            <i class="material-symbols-rounded">home</i>
        </a>
    </div>

    @yield('content')

    {{-- Put error code into session for other uses ie., to activate other actions like account recover for 2fa 'see verify.blade.php in view/auth/...' --}}
    @php session()->put('error_code', $error_code ?? null) @endphp

    <script type="module">
        anime({
            targets: 'svg path',
            strokeDashoffset: [anime.setDashoffset, 0],
            easing: 'easeInOutSine',
            duration: 1500,
            delay: function(el, i) {
                return i * 250
            },
            direction: 'alternate',
            loop: true
        });
    </script>

    <script src="{{ asset('global_assets/js/plugins/animejs/anime.min.js') }} "></script>
</body>

</html>
