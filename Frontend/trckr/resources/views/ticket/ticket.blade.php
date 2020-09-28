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
        <div class="col col-md-12">
            <form method="POST" id="handle_ticket" action="javascript:void(0)" >
                <div class="card" style="width:100%">
                    <div class="card-header">
                        
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">                
                            <div class="btn-group float-lg-right">
                        
                            <button type="button" id="export" class="btn btn-primary btn-lg">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_export" aria-hidden="true" disabled> </span>
                                Export CSV
                            </button>  
                            <button class="btn btn-primary btn-lg" type="submit" value="Approve" id="approve">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_approve" aria-hidden="true" disabled> </span>
                                Approve
                            </button>
                            <button class="btn btn-primary btn-lg" type="submit" value="Reject" id="reject">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_reject" aria-hidden="true" disabled> </span>
                                Reject
                            </button>
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
                                <input class="view_id" type="hidden" name="row_task_ticket_id[]" value="{{ $t->task_ticket_id }}"/>
                                <td class="view"> {{ $t->user_detail->first_name . " " . $t->user_detail->last_name }}</td>
                                <td class="view"> {{ $t->user_detail->email }}</td>
                                <td class="view"> No info available (not in capability/tasktickets schema yet) </td>
                                <td class="view"> {{ $t->campaign_name }}</td>
                                <td class="view"> 
                                    @foreach ($t->task_details as $d )
                                        {{ $d->task_question->question}} <br/>
                                    @endforeach    
                                </td>
                                <td class="view"> {{ $t->createdAt }}</td>
                                <td class="view"> {{ $t->device_id}} </td>
                                <td class="view"> No info avaialable (not in capability/tasktickets schema yet)</td>
                                <td class="view"> {{ $t->approval_status }}</td>
                                <td>
                                    <input type="checkbox" name="task_ticket_id" value="{{ $t->task_ticket_id }}"> 
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
    <script type="text/javascript" src="/vendor/trckr/trckr.js"></script>
    <script type="text/javascript" src="/vendor/trckr/trckr.js"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $("#export").click(function(){
                window.location.href = "/ticket/export_csv";                
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                window.location.href = "/ticket/view";
            });

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

                var task_ticket_id = [];

                console.log(task_ticket_id);
                $.each($("input[name='task_ticket_id']:checked"), function(){
                    task_ticket_id.push($(this).attr("value"));
                });

                if (task_ticket_id.length < 1){
                    $(".modal-title").text("Invalid Delete Selection!");
                    $(".modal-body").html("<p>Please check at least one product!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                console.log(task_ticket_id);

                formData.append('task_ticket_id', task_ticket_id);
                formData.append('_token', "{{ csrf_token() }}");

                var actiontext = action.charAt(0).toUpperCase() + action.slice(1);

                post("/ticket/" + action + "_ticket", actiontext + " Ticket(s)", action, formData, '/ticket/view');
            });            
        });
  </script>
@stop