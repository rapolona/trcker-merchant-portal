@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Update Branch Details</div>
        </div>
        <div class="panel-body">
            <form method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="branch_id" value="{{ $branch->branch_id }}">
                <div class="row row-30">
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-institution"></span></span></div>
                            <input class="form-control  {{ $errors->first('address')? 'form-control-danger' : '' }}" type="text" value="{{ old('name', $branch->name) }}" name="name" placeholder="Branch Name">
                        </div>
                        @if($errors->first('name'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('name') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-black-tie"></span></span></div>
                            <input class="form-control {{ $errors->first('brand')? 'form-control-danger' : '' }}" type="text" value="{{ old('brand') }}" name="brand" placeholder="Brand">
                        </div>
                        @if($errors->first('brand'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('brand') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-address-card-o"></span></span></div>
                            <input class="form-control {{ $errors->first('address')? 'form-control-danger' : '' }}" type="text" name="address" value="{{ old('address', $branch->address) }}" placeholder="Address">
                        </div>
                        @if($errors->first('address'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('address') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-map-signs"></span></span></div>
                            <input class="form-control {{ $errors->first('region')? 'form-control-danger' : '' }}" type="text" name="region" value="{{ old('region') }}" placeholder="Region">
                        </div>
                        @if($errors->first('region'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('region') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-map-marker"></span></span></div>
                            <input class="form-control {{ $errors->first('latitude')? 'form-control-danger' : '' }}" type="text" name="latitude" value="{{ old('latitude', $branch->latitude) }}" placeholder="Latitude">
                        </div>
                        @if($errors->first('latitude'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('latitude') }}</span></span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <select name="business_type" class="form-control {{ $errors->first('business_type')? 'form-control-danger' : '' }}">
                                <option value="">Business Type</option>
                            </select>
                        </div>
                        @if($errors->first('business_type'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('business_type') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <select name="store_type" class="form-control  {{ $errors->first('store_type')? 'form-control-danger' : '' }}">
                                <option value="">Store Type</option>
                            </select>
                        </div>
                        @if($errors->first('store_type'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('store_type') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                            <input class="form-control {{ $errors->first('city')? 'form-control-danger' : '' }}" type="text" name="city" value="{{ old('city', $branch->city) }}" placeholder="City">
                        </div>
                        @if($errors->first('city'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('city') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-plus-square"></span></span></div>
                            <input class="form-control {{ $errors->first('province')? 'form-control-danger' : '' }}" type="text" name="province" value="{{ old('province') }}" placeholder="Province">
                        </div>
                        @if($errors->first('province'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('province') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-map-marker"></span></span></div>
                            <input class="form-control {{ $errors->first('longitude')? 'form-control-danger' : '' }}" type="text" name="longitude" value="{{ old('longitude', $branch->longitude) }}" placeholder="Longitude">
                        </div>
                        @if($errors->first('longitude'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('longitude') }}</span></span>
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary" type="submit">Save Branch</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
