@extends('concrete.layouts.custom')

@section('content')
    <form class="login100-form validate-form" method="post" action="{{ url('forgot-password') }}">
        <span class="login100-form-title">
            <img src="{{ config('concreteadmin.logo_img', 'logo_img')  }}" alt="Hustle Logo">
        </span>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

            @if(isset($message))
            <div class="alert alert-danger alert-border-left" role="alert"><span class="alert-icon fa-info"></span><span>{{ $message }}</span>
            </div>
            @else
            <div class="alert alert-success alert-border-left" role="alert"><span class="alert-icon fa-info"></span><span>Change password success, pls login!</span>
            </div>
            @endif


            <div class="wrap-input100 validate-input m-b-16">
                <input class="input100" type="text" name="email_address" id="email" placeholder="email">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-input100 validate-input m-b-16">
                <input class="input100" type="text" name="email_address" id="email" placeholder="email">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-input100 validate-input m-b-16">
                <input class="input100" type="text" name="email_address" id="email" placeholder="email">
                <span class="focus-input100"></span>
            </div>

            <div class="container-login100-form-btn">
                <button class="login100-form-btn" type="submit">Submit</button>
            </div>
    </form>
@endsection
