<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="authors" content="Chinedu Okemiri (4jean) & Rashidi Said (rayusam)">

    @laravelPWA

    <title>{{ config('app.name') }} &#183; @yield('page_title')</title>

    @include('partials.app.inc_top')

    @stack('css')
</head>

<body>
    @yield('content')
</body>

</html>