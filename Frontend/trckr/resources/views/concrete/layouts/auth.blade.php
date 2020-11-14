<!DOCTYPE html>
<html class="page-small-footer" lang="en">
<head>
    <title>Hustle Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="og:title" content="Template Monster Admin Template">
    <meta property="og:description" content="brevis, barbatus clabulares aliquando convertam de dexter, peritus capio. devatio clemens habitio est.">
    <meta property="og:image" content="http://digipunk.netii.net/images/radar.gif">
    <meta property="og:url" content="http://digipunk.netii.net">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('components/base/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('components/base/style.css') }}">
    <link rel="stylesheet" href="{{ asset('components/trcker-login.css') }}">
    <script src="{{ asset('components/base/script.js') }}"></script>
</head>
<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="row justify-content-center">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
