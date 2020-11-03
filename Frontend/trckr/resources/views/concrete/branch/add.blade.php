@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Please Input Branch Details</div>
        </div>
        <div class="panel-body">
            <form class="form-vertical" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row form-group {{ $errors->first('name')? 'text-danger' : '' }}">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Branch Name:</label>
                    </div>

                    <div class="col-sm-10">
                        <input required type="text" class="form-control {{ $errors->first('name')? 'form-control-danger' : '' }}" id="input_name" name="name" value="{{ old('name') }}" placeholder="Enter Branch Name">
                        @if($errors->first('name'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('name') }}</span></span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row form-group {{ $errors->first('address')? 'text-danger' : '' }}">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Address:</label>
                    </div>

                    <div class="col-sm-10">
                        <input required type="text" class="form-control {{ $errors->first('address')? 'form-control-danger' : '' }}" id="input_address" name="address" value="{{ old('address') }}" placeholder="Enter Address">
                        @if($errors->first('address'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('address') }}</span></span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row form-group {{ $errors->first('city')? 'text-danger' : '' }}">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">City:</label>
                    </div>

                    <div class="col-sm-10">
                        <input required type="text" class="form-control {{ $errors->first('city')? 'form-control-danger' : '' }}" id="input_city" name="city" value="{{ old('city') }}" placeholder="Enter City">
                        @if($errors->first('city'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('city') }}</span></span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Coordinates:</label>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" required class="form-control {{ $errors->first('longitude')? 'form-control-danger' : '' }}" id="input_longitude" name="longitude" value="{{ old('longitude') }}" placeholder="Enter Longitude">
                        @if($errors->first('longitude'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('longitude') }}</span></span>
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-5">
                        <input type="text" required class="form-control {{ $errors->first('latitude')? 'form-control-danger' : '' }}" id="input_latitude" name="latitude" value="{{ old('latitude') }}" placeholder="Enter Latitude">
                        @if($errors->first('latitude'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('latitude') }}</span></span>
                            </div>
                        @endif
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
