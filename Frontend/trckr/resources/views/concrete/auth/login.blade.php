@extends('concrete.layouts.auth')

@section('content')
<div class="col-lg-5">
    <div class="row row-10 align-items-end">
        <div class="col-6 col-sm-7 logo"><a  href="#"><img src="{{ config('concreteadmin.logo_img', 'logo_img')  }}" alt=""></a></div>
    </div>

    <form class="panel" action="{{ config('concreteadmin.login_url', 'login')  }}" method="post">
        {{ csrf_field() }}
        <div class="panel-header">
            <h3>Sign in to start your session</h3>
        </div>
        <div class="panel-body">
            <div class="row row-30">
                <div class="col-lg-12 order-lg-1">
                    @if($errors->has('email'))
                        <div class="alert alert-dismissible alert-danger alert-sm" role="alert"><span class="alert-icon fa-remove"></span><span>{{ $errors->first('email') }}</span>
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
                <div class="col-sm-6">
                    <div class="custom-control custom-switch custom-switch-lg custom-switch-primary">
                        <input class="custom-control-input" type="checkbox" id="yooedpuq" name="remember"/>
                        <label class="custom-control-label" for="yooedpuq">Remember me
                        </label>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-right">
                    <button class="btn btn-primary" type="submit">Sign In</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
