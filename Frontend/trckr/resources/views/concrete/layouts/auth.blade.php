<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hustle Portal Trcker</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('components/base/base.css') }}">
    <link rel="stylesheet" href="{{ asset('components/trcker-login.css') }}">
    <script src="{{ asset('components/base/script.js') }}"></script>
</head>
<body>
<div class="page page-gradient-bg">
    <div id="particles-container"></div>
    <section class="section-lg section-one-screen">
        <div class="container">
            <div class="row justify-content-center">
                @yield('content')
            </div>
        </div>
    </section>
</div>
</body>
</html>
