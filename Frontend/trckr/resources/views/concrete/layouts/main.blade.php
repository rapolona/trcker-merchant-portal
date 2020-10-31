<!DOCTYPE html>
<html class="rd-navbar-sidebar-active page-small-footer" lang="en" style="--navbar-color:rgba( 255, 255, 255, 255 ); --navbar-color-r:255; --navbar-color-g:255; --navbar-color-b:255; --navbar-color-h:0deg; --navbar-color-s:0%; --navbar-color-l:100%; --navbar-bg:rgba( 26, 31, 33, 255 ); --navbar-bg-r:26; --navbar-bg-g:31; --navbar-bg-b:33; --navbar-bg-h:197.143deg; --navbar-bg-s:11.8644%; --navbar-bg-l:11.5686%; --navbar-hover-color:rgba( 255, 255, 255, 255 ); --navbar-hover-color-r:255; --navbar-hover-color-g:255; --navbar-hover-color-b:255; --navbar-hover-color-h:0deg; --navbar-hover-color-s:0%; --navbar-hover-color-l:100%; --navbar-hover-bg:rgba( 237, 237, 237, 255 ); --navbar-hover-bg-r:237; --navbar-hover-bg-g:237; --navbar-hover-bg-b:237; --navbar-hover-bg-h:0deg; --navbar-hover-bg-s:0%; --navbar-hover-bg-l:92.9412%; --navbar-title-color:rgba( 173, 181, 189, 255 ); --navbar-title-color-r:173; --navbar-title-color-g:181; --navbar-title-color-b:189; --navbar-title-color-h:210deg; --navbar-title-color-s:10.8108%; --navbar-title-color-l:70.9804%; --navbar-panel-bg:rgba( 41, 217, 153, 255 ); --navbar-panel-bg-r:41; --navbar-panel-bg-g:217; --navbar-panel-bg-b:153; --navbar-panel-bg-h:158.182deg; --navbar-panel-bg-s:69.8413%; --navbar-panel-bg-l:50.5882%;
--navbar-brand-invert:0%;">
<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('components/base/base.css') }}">
    <link rel="stylesheet" href="{{ url('components/trcker-login.css') }}">
    <script src="{{ url('components/base/script.js') }}"></script>
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
