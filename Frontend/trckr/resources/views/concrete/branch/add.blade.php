@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Please Input Branch Details</div>
        </div>
        <div class="panel-body">
            <form class="form-vertical" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Branch Name:</label>
                    </div>
                    <div class="col-sm-10">
                        <input required type="text" class="form-control" id="input_name" name="name" value="" placeholder="Enter Branch Name">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Address:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" required class="form-control" id="input_address" name="address" value="" placeholder="Enter Address">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">City:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" required class="form-control" id="input_city" name="city" value="" placeholder="Enter City">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Coordinates:</label>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" required class="form-control" id="input_longitude" name="longitude" value="" placeholder="Enter Longitude">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" required class="form-control" id="input_latitude" name="latitude" value="" placeholder="Enter Latitude">
                    </div>
                </div>

                <div class="row pull-right">
                    <div class="col-sm-12">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true" disabled> </span>
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {
            $('#add_branch').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                post("{{url('/merchant/branch/add')}}", "Add Branch", "submit", formData, "{{url('/merchant/branch')}}");
            });

            $("#back").click(function(){
                window.location.href = "{{url('/merchant/branch')}}";
            });
        });
    </script>
@stop
