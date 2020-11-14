@extends('concrete.layouts.auth')

@section('content')
    <div class="col-lg-5">
        <div class="row row-10 align-items-end">
            <div class="col-6 col-sm-7 logo"><a  href="#"><img src="{{ config('concreteadmin.logo_img', 'logo_img')  }}" alt=""></a></div>
        <!--
      <div class="col-6 col-sm-5 text-right"><a class="font-weight-bold" href="{{ url('forgot-password') }}">Forgot Password</a><span class="px-2">|</span><a href="register.html">Register</a></div>
        -->
        </div>
        <form class="panel" method="post" action="{{ config('concreteadmin.login_url', 'login')  }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 order-lg-1">
                        @if($errors->has('email'))
                            <div class="alert alert-dismissible alert-danger alert-sm" role="alert"><span class="alert-icon fa-remove"></span><span>{{ $errors->first('email') }}</span>
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button>
                            </div>
                        @endif

                        @if($expiredMsg)
                            <div class="alert alert-dismissible alert-{{ $expiredMsg['type'] }} alert-sm" role="alert"></span><span>{{ $expiredMsg['message'] }}</span>
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="user">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text fa fa-user"></span></div>
                                <input class="form-control" id="email" type="text" name="email" placeholder="Enter username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pass">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text fa fa-lock"></span></div>
                                <input class="form-control" id="pass" type="password" name="password" placeholder="Enter password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row row-10">
                    <!-- <div class="col-sm-6">
                      <div class="custom-control custom-switch custom-switch-sm custom-switch-primary">
                        <input class="custom-control-input" type="checkbox" id="hdmkdsef"/>
                        <label class="custom-control-label" for="hdmkdsef">Remember me
                        </label>
                      </div>
                    </div> -->
                    <div class="col-sm-12 text-sm-right">
                        <button class="btn btn-primary" type="submit">Sign In</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
