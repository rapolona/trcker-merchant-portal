@extends('adminlte::page')

@section('title', 'Trckr | View Ticket')

@section('content_header')
    <h1>View Ticket</h1>
@stop

@section('content')
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

    <p>View Ticket</p>

    <div class="card">
        <form method="POST" id="handle_ticket" action="javascript:void(0)" >
            <div class="card-body">           
                <input type="hidden" name="_token" value="{{ csrf_token() }}">  
                <input type="hidden" name="task_ticket_id" value="{{ $tickets->task_ticket_id }}">
                <input type="hidden" name="action" id="action" value="">             
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Campaign Name</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->campaign_name) ? $tickets->campaign_name : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Ticket Id</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->task_ticket_id) ? $tickets->task_ticket_id : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Approval Status</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->approval_status) ? $tickets->approval_status : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Ticket Last Updated</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->updatedAt) ? $tickets->updatedAt : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">User Name</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->user_detail->first_name) ? $tickets->user_detail->first_name . ' ' . $tickets->user_detail->last_name : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Account Level</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->user_detail->account_level) ? $tickets->user_detail->account_level  : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->user_detail->email) ? $tickets->user_detail->email : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Device Id</label>
                    <div class="col-sm-10">
                        <span>{{ ($tickets->device_id) ? $tickets->device_id : ''}}</span>
                    </div>
                </div>
                
                @php 
                    $count = 1;
                @endphp
                @foreach ($tickets->task_details as $tix)
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Question {{$count}}</label>
                    <div class="col-sm-10">
                        <span>{{ ($tix->task_question->question) ? $tix->task_question->question : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Answer</label>
                    @if (substr($tix->response, 0, 11) == "data:image/")
                    <div class="col-sm-10">
                        <img src="{{ ($tix->response) ? $tix->response : ''}}"/>
                    </div>
                    @else
                    <div class="col-sm-10">
                        <span>{{ ($tix->response) ? $tix->response : ''}}</span>
                    </div>
                    @endif
                </div>

                @php 
                    $count +=1;
                @endphp
                @endforeach
                
            </div>
            <div class="card-footer">
                <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary btn-lg pull-right" id="approve">Approve</button> 
                    <button type="submit" class="btn btn-primary btn-lg pull-right" id="reject">Reject</button>  
                    <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                </div>
            </div>
        </form>
    </div>
    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript" src="{{url('/vendor/form-builder/form-builder.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/vendor/form-builder/form-render.min.js')}}"></script>
    <script type="text/javascript">
        
        $(document).ready(function (e) {
            $('#myModal').on('hidden.bs.modal', function () {
                window.location.href = "{{url('/ticket/view')}}";
            });

            $("#back").click(function(){
                window.location.href = "{{url('/ticket/view')}}";
            });

            $("#approve").click(function(){
                $("#action").val('approve');
            });

            $("#reject").click(function(){
                $("#action").val('reject');
            });

            $('#handle_ticket').submit(function(e){
                var formData = new FormData(this);
                var action = formData.get('action');

                post("{{url('/ticket/')}}/" + action + "_ticket", actiontext + " Ticket(s)", action, formData, "{{url('/ticket/view')}}");
            });
        });        
    </script>
@stop