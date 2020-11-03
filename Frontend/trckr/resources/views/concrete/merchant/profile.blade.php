@extends('concrete.layouts.main')

@section('content')
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" style="display:auto;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <p>View Merchant Information</p>
    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form class="form-horizontal" id="modify_merchant">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col col-lg-6">
                                <div class="form-group row">
                                    <label for="company_name" class="col-sm-2 col-form-label">Company Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_name" name="name" value="{{( ! empty($profile->name) ? $profile->name : '')}}" placeholder="Enter Company Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="company_address" class="col-sm-2 col-form-label">Company Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_address" name="address" value="{{( ! empty($profile->address) ? $profile->address : '')}}" placeholder="Enter Company Address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="trade_name" class="col-sm-2 col-form-label">Trade Name</label>
                                    <div class="col-sm-10">
                                        <input type="emtextail" class="form-control" id="input_trade_name" name="trade_name" value="{{( ! empty($profile->trade_name) ? $profile->trade_name : '')}}" placeholder="Enter Trade Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sector" class="col-sm-2 col-form-label">Business Sector</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="input_sector" name="sector">
                                            <option value="" selected disabled>Select Sector</option>
                                            <option value="FMCG" {{( ! empty($profile->sector) AND $profile->sector == "FMCG") ? "selected" : ""}}>FMCG</option>
                                            <option value="Pharma" {{( ! empty($profile->sector) AND $profile->sector == "Pharma") ? "selected" : ""}}>Pharma</option>
                                            <option value="Services" {{( ! empty($profile->sector) AND $profile->sector == "Services") ? "selected" : ""}}>Services</option>
                                            <option value="Retail" {{( ! empty($profile->sector) AND $profile->sector == "Retail") ? "selected" : ""}}>Retail</option>
                                            <option value="E-commerce" {{( ! empty($profile->sector) AND $profile->sector == "E-commerce") ? "selected" : ""}}>E-commerce</option>
                                            <option value="Food and Beverage" {{( ! empty($profile->sector) AND $profile->sector == "Food and Beverage") ? "selected" : ""}}>Food and Beverage</option>
                                            <option value="Banking and Finance" {{( ! empty($profile->sector) AND $profile->sector == "Banking and Finance") ? "selected" : ""}}>Banking and Finance</option>
                                            <option value="Healthcare" {{( ! empty($profile->sector) AND $profile->sector == "Healthcare") ? "selected" : ""}}>Healthcare</option>
                                            <option value="Advertising" {{( ! empty($profile->sector) AND $profile->sector == "Advertising") ? "selected" : ""}}>Advertising</option>
                                        </select>
                                    </div>
                                </div>
                                <!--
                                <div class="form-group row">
                                    <label for="business_structure" class="col-sm-2 col-form-label">Business Structure</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="input_business_structure" name="business_structure">
                                            <option value="" selected disabled>Select Business Structure</option>
                                            <option>option 1</option>
                                            <option>option 2</option>
                                            <option>option 3</option>
                                            <option>option 4</option>
                                            <option>option 5</option>
                                        </select>
                                    </div>
                                </div>
                                -->
                            </div>
                            <div class="col col-lg-6">
                                <div class="form-group row">
                                    <label for="representative" class="col-sm-3 col-form-label">Authorized Representative</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input_authorized_representative" name="authorized_representative" value="{{( ! empty($profile->authorized_representative) ? $profile->authorized_representative : '')}}"placeholder="Enter Authorized Representative">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="position" class="col-sm-3 col-form-label">Position</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input_position" name="position" value="{{( ! empty($profile->position) ? $profile->position : '')}}"placeholder="Enter Position">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="contact_number" class="col-sm-3 col-form-label">Contact Number</label>
                                    <div class="col-sm-9">
                                        <input type="emtextail" class="form-control" id="input_contact_number" name="contact_number" value="{{( ! empty($profile->contact_number) ? $profile->contact_number : '')}}" placeholder="Enter Contact Number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email_address" class="col-sm-3 col-form-label">Email Address</label>
                                    <div class="col-sm-9">
                                        <input type="emtextail" class="form-control" id="input_email_address" name="email_address" value="{{( ! empty($profile->email_address) ? $profile->email_address : '')}}"placeholder="Enter Email Address">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">

                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button class="btn btn-primary btn-lg" type="submit" value="Save Details" id="submit">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_submit" aria-hidden="true" disabled> </span>
                                Save Details
                            </button>
                            <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                        </div>
                    </div>
                </form>
            </div>
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
            $('#modify_merchant').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                post("{{url('/merchant/modify_profile')}}", "Update Merchant", "submit", formData, "{{url('/dashboard')}}");

            });

            $("#back").click(function(){
                window.location.href = "{{url('/dashboard')}}";
            });
        });
    </script>
@stop