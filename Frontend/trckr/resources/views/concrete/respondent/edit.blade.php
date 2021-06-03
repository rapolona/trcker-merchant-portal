@extends('concrete.layouts.main')

@section('content')
<section>
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Respondent Details</div>
        </div>
        <div class="panel-body">
            <form method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row row-30">
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">First Name</span></div>
                            <input class="form-control  {{ $errors->first('first_name')? 'form-control-danger' : '' }}" type="text" value="{{ old('first_name') }}" name="first_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Last Name</span></div>
                            <input class="form-control  {{ $errors->first('last_name')? 'form-control-danger' : '' }}" type="text" value="{{ old('last_name') }}" name="last_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                            <input class="form-control  {{ $errors->first('email')? 'form-control-danger' : '' }}" type="text" value="{{ old('email') }}" name="email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Birthday</span></div>
                            <input class="form-control  {{ $errors->first('birthday')? 'form-control-danger' : '' }}" type="text" value="{{ old('birthday') }}" name="birthday">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Gender</span></div>
                            <select class="form-control {{ $errors->first('gender')? 'form-control-danger' : '' }} name="gender">
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Account Level</span></div>
                            <select class="form-control {{ $errors->first('gender')? 'form-control-danger' : '' }} name="account_level">
                                <option>basic</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row row-30">
                    <div class="col-md-12"><h3>Address</h3></div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Region</span></div>
                            <select class="form-control {{ $errors->first('regionId')? 'form-control-danger' : '' }} name="regionId" id="region">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Province</span></div>
                            <select class="form-control {{ $errors->first('provinceId')? 'form-control-danger' : '' }} name="provinceId" id="province">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">City</span></div>
                            <select class="form-control {{ $errors->first('cityId')? 'form-control-danger' : '' }} name="cityId" id="city">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Residence Type</span></div>
                            <select class="form-control {{ $errors->first('residence_type')? 'form-control-danger' : '' }} name="residence_type">
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row row-30">
                    <div class="col-md-12"><h3>Occupation Details</h3></div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Job</span></div>
                            <input class="form-control  {{ $errors->first('occupation')? 'form-control-danger' : '' }}" type="text" value="{{ old('occupation') }}" name="occupation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Industry</span></div>
                            <select class="form-control {{ $errors->first('industry')? 'form-control-danger' : '' }} name="industry" >
                                <option>BPO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Employment Status</span></div>
                            <select class="form-control {{ $errors->first('employment_status')? 'form-control-danger' : '' }} name="employment_status" >
                                <option>Probitionary</option>
                                <option>Regular</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Monthly Income</span></div>
                            <select class="form-control {{ $errors->first('monthly_income')? 'form-control-danger' : '' }} name="monthly_income">
                                <option>1,000-10,000</option>
                                <option>10,000-50,000</option>
                            </select>
                        </div>
                    </div>
                </div>
  
                <div class="row row-30">
                    <div class="col-md-12"><h3>Settlement Account</h3></div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Mobile Number</span></div>
                            <input class="form-control  {{ $errors->first('mobile_phone_number')? 'form-control-danger' : '' }}" type="text" value="{{ old('mobile_phone_number') }}" name="mobile_phone_number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Account Number</span></div>
                            <input class="form-control  {{ $errors->first('settlement_account_number')? 'form-control-danger' : '' }}" type="text" value="{{ old('settlement_account_number') }}" name="settlement_account_number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">Account Type</span></div>
                            <select class="form-control {{ $errors->first('settlement_account_type')? 'form-control-danger' : '' }} name="settlement_account_type">
                                <option>GCash</option>
                                <option>Paymaya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row row-30">
                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@stop

@section('js')
    <script type="text/javascript" src="{{url('/js/jquery.ph-locations-v1.0.0.js')}}"></script>
    <script type="text/javascript">
            var my_handlers = {

                fill_provinces:  function(){

                    var region_code = $(this).val();
                    $('#province').ph_locations('fetch_list', [{"region_code": region_code}]);
                    
                },

                fill_cities: function(){

                    var province_code = $(this).val();
                    $('#city').ph_locations( 'fetch_list', [{"province_code": province_code}]);
                }
            };

            $(function(){
                $('#region').on('change', my_handlers.fill_provinces);
                $('#province').on('change', my_handlers.fill_cities);
                $('#city').on('change', my_handlers.fill_barangays);

                $('#region').ph_locations({'location_type': 'regions'});
                $('#province').ph_locations({'location_type': 'provinces'});
                $('#city').ph_locations({'location_type': 'cities'});
                $('#barangay').ph_locations({'location_type': 'barangays'});

                $('#region').ph_locations('fetch_list');
            });
    </script>
@endsection