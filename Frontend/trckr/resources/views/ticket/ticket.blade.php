@extends('adminlte::page')

@section('title', 'Ticket Management')

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
                                <th style="width: 5%">Device ID</th>
                                <th style="width: 5%">Location</th>
                                <th style="width: 5%">Status</th>
                                <th style="width: 5%">Action?</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $t)
                            <tr>
                                <input class="view_id" type="hidden" name="task_ticket_id[]" value="{{ $t->task_ticket_id }}"/>
                                <td class="view"> {{ $t->user_detail->first_name . " " . $t->user_detail->last_name }}</td>
                                <td class="view"> {{ $t->user_detail->email }}</td>
                                <td class="view"> No info available (not in capability/tasktickets schema yet) </td>
                                <td class="view"> {{ $t->campaign_name }}</td>
                                <td class="view"> 
                                    @foreach ($t->task_details as $d )
                                        {{ $d->task_question->question}} <br/>
                                    @endforeach    
                                </td>
                                <td class="view"> {{ $t->updatedAt }}</td>
                                <td class="view"> {{ $t->device_id}} </td>
                                <td class="view"> No info avaialable (not in capability/tasktickets schema yet)</td>
                                <td class="view"> {{ $t->approval_status }}</td>
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

            $('.view').click(function(){
                var ticket_id = $(this).siblings('.view_id').val();
                
                window.location.href = "/ticket/view_ticket?ticket_id=" + ticket_id;
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