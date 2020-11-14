<!DOCTYPE html>
<html class="rd-navbar-sidebar-active page-small-footer" lang="en" url="{{ asset('/') }}">
<head>
    <title>Hustle Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('components/base/base.css') }}">
    <script data-loaded="true" src="{{ asset('./components/base/jquery-3.4.1.min.js') }}"></script> <!-- IMPORTANT DONT CHANGE -->
    <script data-loaded="true" src="{{ asset('./components/base/jquery-ui.min.js') }}"></script> <!-- IMPORTANT DONT CHANGE -->
    <script src="{{ asset('components/base/script.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    @yield('css')
    @yield('js')
</head>
<body>
<div class="page">
    @include('concrete.layouts.header')
    <section class="section-sm">
        <div class="container-fluid">
            @if(isset($formMessage))
                <div class="alert alert-dismissible alert-{{ $formMessage['type'] }} mt-1" role="alert"><span class="alert-icon fa-trophy"></span><span>{{ $formMessage['message'] }}</span><button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button></div>
            @endif

            @if (session('formMessage') )
                <div class="alert alert-dismissible alert-{{ session('formMessage')['type'] }} mt-1" role="alert"><span class="alert-icon fa-trophy"></span><span>{{ session('formMessage')['message'] }}</span><button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button></div>
            @endif


            @yield('content')
        </div>
    </section>
    @include('concrete.layouts.footer')
</div>
</body>
</html>
