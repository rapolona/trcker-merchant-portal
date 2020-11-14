@extends('concrete.layouts.auth')

@section('content')
    <form class="login100-form validate-form" method="post" action="{{ config('concreteadmin.login_url', 'login')  }}">
    <span class="login100-form-title">
        <img src="{{ config('concreteadmin.logo_img', 'logo_img')  }}" alt="Hustle Logo">
    </span>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

    @if($errors->has('email'))
        <!--NOTE: To fix inline style-->
            <div class="alert alert-dismissible alert-danger alert-sm" role="alert" style="width: 100%;"><span class="alert-icon fa-remove"></span><span>{{ $errors->first('email') }}</span>
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button>
            </div>
        @endif

        @if($expiredMsg)
            <div class="alert alert-dismissible alert-{{ $expiredMsg['type'] }} alert-sm" role="alert" style="width: 100%;"></span><span>{{ $expiredMsg['message'] }}</span>
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button>
            </div>
        @endif

        <div class="wrap-input100 validate-input m-b-16">
            <input class="input100" type="text" name="email" id="email" placeholder="Username">
            <span class="focus-input100"></span>

        </div>

        <div class="wrap-input100 validate-input m-b-16">
            <input class="input100" type="password" name="password" id="pass" placeholder="Password">
            <span class="focus-input100"></span>

        </div>

        <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
                Login
            </button>
        </div>
    </form>
@endsection
