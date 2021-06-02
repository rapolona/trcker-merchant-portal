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
                    <div class="col-md-12">

                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text">First Name</span></div>
                            <input class="form-control  {{ $errors->first('address')? 'form-control-danger' : '' }}" type="text" value="{{ old('name') }}" name="name" placeholder="Branch Name">
                        </div>
                        <div class="input-group form-group">
                            <select name="brand" data-placeholder="Brand" class="select2 {{ $errors->first('brand')? 'form-control-danger' : '' }}">
                                <option label="placeholder"></option>
                            </select>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-address-card-o"></span></span></div>
                            <input class="form-control {{ $errors->first('address')? 'form-control-danger' : '' }}" type="text" name="address" value="{{ old('address') }}" placeholder="Address">
                        </div>
                        <div class="input-group form-group">
                            <select name="region" data-placeholder="Region" class="select2 {{ $errors->first('region')? 'form-control-danger' : '' }}">
                                <option label="placeholder"></option>
                            </select>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-map-marker"></span></span></div>
                            <input class="form-control {{ $errors->first('latitude')? 'form-control-danger' : '' }}" type="text" name="latitude" value="{{ old('latitude') }}" placeholder="Latitude">
                        </div>

                                <div>
            <table>
                <tr>
                    <td>Region</td>
                    <td><select id="region"></select></td>
                </tr>
                <tr>
                    <td>Province</td>
                    <td><select id="province"></select></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td><select id="city"></select></td>
                </tr>
            </table>

        </div>
                    </div>

                    <div class="col-sm-12 text-right">
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
                },


                fill_barangays: function(){

                    var city_code = $(this).val();
                    $('#barangay').ph_locations('fetch_list', [{"city_code": city_code}]);
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