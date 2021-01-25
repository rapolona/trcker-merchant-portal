@extends('concrete.layouts.main')

@section('content')
<section>
    <div class="row no-gutters">
        <div class="col-md-12 col-lg-12 col-xxl-12 border-md-left">
            <form method="post" action="{{ url('m/change-password') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel">
                
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Change Password</div>
                </div>
                <div class="panel-body">
                    <div class="row row-30" id="pwd-container">
                        <div class="col-md-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">New Password</span></div>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>
                            <div>
                                <div class="pwstrength_viewport_progress"></div>
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Retype Password</span></div>
                                <input class="form-control " required=""  type="password" name="password_confirmation" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer pull-right text-right"> 
                    <button class="btn btn-success" type="submit">Submit</button>    
                </div>
                
            </div>
        </form>
        </div>
    </div>
</section>
@stop


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
