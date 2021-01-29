@extends('concrete.layouts.custom')

@section('content')
    <form class="login100-form validate-form" method="post" action="{{ url('forgot-password-pin') }}"  id="pwd-container">
        <input type="hidden" name="user_id" value="{{ $user->admin_id }}">
        <span class="login100-form-title">
            <img src="{{ config('concreteadmin.logo_img', 'logo_img')  }}" alt="Hustle Logo">
        </span>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

            @if(isset($message))
            <div class="alert alert-danger alert-border-left" role="alert"><span class="alert-icon fa-info"></span><span>{{ $message }}</span>
            </div>
            @else
            <div class="alert alert-success alert-border-left" role="alert"><span class="alert-icon fa-info"></span><span>Please use the CODE we sent you at your email: {{ $user->email_address }}!</span>
            </div>
            @endif


            <div class="wrap-input100 validate-input m-b-16">
                <input class="input100" type="text" name="passwordToken" id="passwordToken" placeholder="CODE">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-input100 validate-input m-b-16">
                <input class="input100" type="password" name="password" id="password" placeholder="New Password">
                <span class="focus-input100"></span>
            </div>

            <div>
                <div class="pwstrength_viewport_progress"></div>
            </div>

            <div class="wrap-input100 validate-input m-b-16">
                <input class="input100" type="password" name="password_confirmation" id="password_confirmation" placeholder="Retype Password">
                <span class="focus-input100"></span>
            </div>

            <div class="container-login100-form-btn">
                <button class="login100-form-btn" type="submit">Submit</button>
            </div>
    </form>
@endsection

@section('js')
    <script src="{{ asset('js/pwstrength-bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            "use strict";
            var options = {};
            options.ui = {
                container: "#pwd-container",
                viewports: {
                    progress: ".pwstrength_viewport_progress"
                },
                showVerdictsInsideProgressBar: true
            };
            options.common = {
                debug: true,
                onLoad: function () {
                    $('#messages').text('Start typing password');
                }
            };
            $('#password').pwstrength(options);
        });
    </script>
@stop
