@extends('concrete.layouts.main')

@section('breadcrumbs_pull_right')
    <a class="btn btn-outline-primary" href="{{ url('/ticket/export_csv') }}"><span class="fa-cloud-download"></span><span class="pl-2">Download Report</span></a>
@endsection

@section('tableFilters')
    <div class="row">
        <div class="col-sm-4">
            <div class="input-group form-group">
                <div class="input-group-prepend"><span class="input-group-text" id="basic-addon3">Name</span></div>
            <input class="form-control" id="basic-url" type="text" aria-describedby="basic-addon3">
            </div>
        </div>
        <div class="col-sm-3">
            <select class="form-control" id="status">
                <option value="">--Status--</option>
                <option value-"ONGOING">ONGOING</option>
                <option value-"DONE">DONE</option>
                <option value-"INACTIVE">INACTIVE</option>
                <option value-"CANCELED">CANCELED</option>
            </select>
        </div>
        <div class="col-sm-3">
            <div class="input-group form-group">
                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-calendar"></span></span></div>
                <input class="form-control" id="daterange1" type="text" value="" name="daterange" placeholder="Date Range">
            </div>
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-primary">Search</button>
        </div>
    </div>
@endsection

@section('content')
    <form method="post" action="{{ url('ticket/bulk-action') }}" id="tableList">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="panel">
            <div class="panel-header">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel-title"><span class="panel-icon fa-ticket"></span> <span>Tickets</span></div>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" value="reject" class="btn btn-danger btn-sm pull-right approve-reject"><span class="fa-ban"></span><span class="pl-2">Reject</span></button>
                        <button type="button" value="approve" class="btn btn-light btn-sm pull-right approve-reject"><span class="fa-check"></span><span class="pl-2">Approve</span></button>
                        <input id="ticket-status" name="status" type="hidden" />
                    </div>
                </div>
            </div>
            <div class="panel-menu">
                @include('concrete.layouts.filters')  
            </div>
            <div class="panel-body p-0">
                <div class="table-responsive scroller scroller-horizontal py-3">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="no-sort">
                                <div class="custom-control custom-checkbox custom-checkbox-light">
                                    <input class="custom-control-input" type="checkbox" id="selectAll">
                                    <label class="custom-control-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Device ID</th>
                            <th>Campaign Name</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Rewards</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tickets as $t)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox custom-checkbox-light">
                                        <input class="custom-control-input" type="checkbox" name="tickets[]" id="{{ $t->task_ticket_id }}" value="{{ $t->task_ticket_id }}">
                                        <label class="custom-control-label" for="{{ $t->task_ticket_id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $t->user_detail->first_name . " " . $t->user_detail->last_name }}</td>
                                <td>{{ $t->user_detail->email }}</td>
                                <td>{{ $t->device_id}}</td>
                                <td>{{ $t->campaign->campaign_name }}</td>
                                <td>{{ date('Y-m-d', strtotime($t->createdAt)) }}</td>
                                <td>
                                    <div class="text-{{ config('concreteadmin')['ticket_status'][$t->approval_status ] }}">{{ $t->approval_status }}</div>
                                    @if($t->approval_status=="REJECTED")
                                    <div>{{ $t->rejection_reason }}</div>
                                    @endif
                                    
                                </td>
                                <td>
                                    @php $rewards = 0; @endphp
                                    @foreach($t->task_details as $task)
                                        @php $rewards = $task->task_question->reward_amount;@endphp
                                    @endforeach
                                    {{ $rewards}}
                                </td>


                                <td>
                                    <div class="btn-group">
                                        <a href="{{ url('ticket/view/' . $t->campaign_id . "/" . $t->task_ticket_id ) }}"><button class="btn btn-light" type="button"><span class="fa-eye"></span></button></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer"> 
                @include('concrete.layouts.pagination')
            </div>
        </div>
    </form>
@stop

@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {

            $('#selectAll').click(function(e){
                let table= $(e.target).closest('table');
                $('td input:checkbox',table).prop('checked',this.checked);
            });

            $('.approve-reject').click(function(e){
                let val = $(this).attr('value');
                $('#ticket-status').val(val);

                if (confirm('Are you sure you want to ' + val + ' these items ?')) {
                    $('#tableList').submit();
                }
            });

            $("#export").click(function(){
                window.location.href = "{{url('/ticket/export_csv')}}";
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                window.location.href = "{{url('/ticket/view')}}";
            });

            $('.view').click(function(){
                var ticket_id = $(this).siblings('.view_id').val();

                window.location.href = "{{url('/ticket/view_ticket?ticket_id=')}}" + ticket_id;
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

                post("{{url('/ticket/')}}/" + action + "_ticket", actiontext + " Ticket(s)", action, formData, "{{url('/ticket/view')}}");
            });
        });
    </script>
@stop
