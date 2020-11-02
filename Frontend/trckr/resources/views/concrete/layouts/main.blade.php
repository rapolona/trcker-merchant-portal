<!DOCTYPE html>
<html class="rd-navbar-sidebar-active page-small-footer" lang="en" url="{{ asset('/') }}">
<head>
    <title>Hustle Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('components/base/base.css') }}">
    <link rel="stylesheet" href="{{ asset('components/trcker-login.css') }}">
    <script src="{{ asset('components/base/script.js') }}"></script>
    @yield('css')
    @yield('js')
</head>
<body>
<div class="page">
    @include('concrete.layouts.header')
    <section class="section-sm">
        <div class="container-fluid">
            @yield('content')
        </div>
    </section>
    @include('concrete.layouts.footer')
</div>
</body>
</html>
