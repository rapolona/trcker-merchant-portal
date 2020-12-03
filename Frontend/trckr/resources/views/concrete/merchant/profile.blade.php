@extends('concrete.layouts.main')

@section('breadcrumbs_pull_right')
    <!--<a class="btn btn-light" href="#"><span class="fa-cloud-download"></span><span class="pl-2">Change Password</span></a>
    -->
@endsection

@section('content')
    <div class="panel panel-nav">
        <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
            <div class="panel-title">Merchant Profile</div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="modify_merchant" method="post" action="{{ url('m/profile')  }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="row row-30">
                    <div class="col-md-2">
                        <p>Company Logo</p>
                        <div class="tower-file mt-3">
                            @if($errors->first('profile_image'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('profile_image') }}</span></span>
                                </div>
                            @endif
                            <input class="tower-file-input" name="profile_image" id="demo1" type="file">
                            <label class="btn btn-xs btn-success" for="demo1"><span>Upload</span></label>
                                @if(isset($profile->profile_image) && !empty($profile->profile_image))
                                <div class="tower-file-details"><img class="null" src="{{ $profile->profile_image }}"></div>
                                    @endif
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-institution"></span></span></div>
                            <input type="text" class="form-control {{ $errors->first('name')? 'form-control-danger' : '' }}" id="input_name" name="name" value="{{( ! empty($profile->name) ? $profile->name : '')}}" placeholder="Company Name">
                        </div>
                        @if($errors->first('name'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('name') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-black-tie"></span></span></div>
                            <input type="text" class="form-control {{ $errors->first('address')? 'form-control-danger' : '' }}" id="input_address" name="address" value="{{( ! empty($profile->address) ? $profile->address : '')}}" placeholder="Company Address">
                        </div>
                        @if($errors->first('address'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('address') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-address-card-o"></span></span></div>
                            <input type="emtextail" class="form-control {{ $errors->first('trade_name')? 'form-control-danger' : '' }}" id="input_trade_name" name="trade_name" value="{{( ! empty($profile->trade_name) ? $profile->trade_name : '')}}" placeholder="Enter Trade Name">
                        </div>
                        @if($errors->first('trade_name'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('trade_name') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <select class="form-control {{ $errors->first('sector')? 'form-control-danger' : '' }}" id="input_sector" name="sector">
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
                        @if($errors->first('sector'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('sector') }}</span></span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-5">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                            <input type="text" class="form-control {{ $errors->first('authorized_representative')? 'form-control-danger' : '' }}" id="input_authorized_representative" name="authorized_representative" value="{{( ! empty($profile->authorized_representative) ? $profile->authorized_representative : '')}}"placeholder="Enter Authorized Representative">
                        </div>
                        @if($errors->first('authorized_representative'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('authorized_representative') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                            <input type="text" class="form-control {{ $errors->first('position')? 'form-control-danger' : '' }}" id="input_position" name="position" value="{{( ! empty($profile->position) ? $profile->position : '')}}"placeholder="Enter Position">
                        </div>
                        @if($errors->first('position'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('position') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                            <input type="emtextail" class="form-control {{ $errors->first('contact_number')? 'form-control-danger' : '' }}" id="input_contact_number" name="contact_number" value="{{( ! empty($profile->contact_number) ? $profile->contact_number : '')}}" placeholder="Enter Contact Number">
                        </div>
                        @if($errors->first('contact_number'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('contact_number') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-plus-square"></span></span></div>
                            <input type="emtextail" class="form-control {{ $errors->first('email_address')? 'form-control-danger' : '' }}" id="input_email_address" name="email_address" value="{{( ! empty($profile->email_address) ? $profile->email_address : '')}}"placeholder="Enter Email Address">
                        </div>
                        @if($errors->first('email_address'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('email_address') }}</span></span>
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary" type="submit">Save Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
