
@extends('adminlte::page')

@section('title', 'Trckr | View Campaign')

@section('content_header')
    <h1>View Campaign</h1>
@stop

@section('content')
@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)
@section('plugins.JqueryUI', true)
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
    <p>Create New Campaign</p>

    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form id="create_campaign" name="create_campaign">
                    <div class="card-body">
                        <div class="row">
                            <div class="col col-lg-12">
                                <h2>Campaign Information</h2>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col col-lg-6">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                                
                                <div class="form-group row">
                                    <label for="campaign_name" class="col-sm-4 col-form-label">Campaign Name</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->campaign_name}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_description" class="col-sm-4 col-form-label">Campaign Description</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->campaign_description}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branches" class="col-sm-4 col-form-label">Branches</label>
                                    <div class="col-sm-8">
                                        @foreach ($campaign->branches as $k)
                                            <span>{{$k->name}}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="audience" class="col-sm-4 col-form-label">Allow Super Shopper</label>
                                    <div class="col-sm-8">
                                        <span>{{ ($campaign->super_shoppers) ? "Yes" : "No"}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="audience" class="col-sm-4 col-form-label">Allow Everyone</label>
                                    <div class="col-sm-8">
                                        <span>{{ ($campaign->allow_everyone) ? "Yes" : "No"}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg-6">
                            <div class="form-group row">
                                    <label for="campaign_type" class="col-sm-4 col-form-label">Campaign Status</label>
                                    <div class="col-sm-8">
                                        @if ($campaign->status == 1) 
                                            <span>Ongoing</span>
                                        @endif
                                        @if ($campaign->status == 0) 
                                            <span>Completed</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_type" class="col-sm-4 col-form-label">Campaign Type</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->campaign_type}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="reward" class="col-sm-4 col-form-label">Budget</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->budget}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="reward" class="col-sm-4 col-form-label">Reward</label>
                                    <div class="col-sm-8">
                                        <span>{{( ! empty($campaign->reward)) ? $campaign->reward : ""}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="duration" class="col-sm-4 col-form-label">Duration</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->start_date}} to {{$campaign->end_date}}</span>                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button type="edit" class="btn btn-block btn-primary btn-lg pull-right" id="submit">Edit</button>  
                            <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>
    
@stop

@section('css')
    
@stop

@section('js')
    <script type="text/javascript">
        $(document).ready(function (e) { 

            $("#back").click(function(){
                window.location.href = "/campaign/view";
            });
        });
    </script>
@stop