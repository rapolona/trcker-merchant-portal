@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Ticket Management</h1>
@stop

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
    <p>Insert text here</p>
    <div class="row">
        <div class="col col-lg-12">
            <form method="POST" id="handle_ticket" action="javascript:void(0)" >
                <div class="card" style="width:100%">
                    <div class="card-header">
                        
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">                

                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button type="button" id="upload_csv" class="btn btn-block btn-primary btn-lg pull-right">Export List</button>  
                            <input type="submit" class="btn btn-primary btn-lg" id="approve" value="Approve">
                            <input type="submit" class="btn btn-primary btn-lg" id="reject" value="Reject">
                            <input type="hidden" name="action" id="action" value="">
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>                  
                            <tr>
                                <th>Trckr Username</th>
                                <th>Email</th>
                                <th>Mobile number</th>
                                <th>Campaign Name</th>
                                <th>Task</th>
                                <th>Date Submitted</th>
                                <th>Device ID</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th style="width: 40px">Action?</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $t)
                            <tr>
                                <td> {{ $t->trckr_username }}</td>
                                <td> {{ $t->email }}</td>
                                <td> {{ $t->mobile_number }}</td>
                                <td> {{ $t->campaign_name }}</td>
                                <td> {{ $t->tasks }}</td>
                                <td> {{ $t->date_submitted }}</td>
                                <td> {{ $t->device_id }}</td>
                                <td> {{ $t->location }}</td>
                                <td> {{ $t->status }}</td>
                                <td>
                                    <input type="checkbox" name="task_ticket_id[]" value="{{ $t->task_ticket_id }}"> 
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
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
    <script type="text/javascript">
        $(document).ready(function (e) { 
            $("#approve").click(function(){
                $("#action").val('approve');
            });

            $("#reject").click(function(){
                $("#action").val('reject');
            });

            $('#handle_ticket').submit(function(e){
                var formData = new FormData(this);
                var action = formData.get('action');

                $.ajax({
                    type:'POST',
                    url: "/ticket/" + action + "_ticket",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").val(action + "Ticket Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").val(action + "Ticket Failed!");
                        $(".modal-body").html("<p>" + data.responseText + "</p>");
                        //$(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });

            $('#reject').click(function(e){
                var formData = new FormData(this);

                $.ajax({
                    type:'POST',
                    url: "/ticket/reject_ticket",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").val("Approve Ticket Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").val("Approve Ticket Failed!");
                        $(".modal-body").html("<p>" + data.responseText + "</p>");
                        //$(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });
            
        });
  </script>
@stop