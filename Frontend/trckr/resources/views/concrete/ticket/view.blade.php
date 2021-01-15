@extends('concrete.layouts.main')

@section('content')
<section>
    <div class="row no-gutters">
        <div class="col-md-4 col-lg-3 col-xxl-2 bg-100">
            <div class="px-3 py-4">
                <div class="panel">
                    <div class="panel-body">
                        <div class="list-block-container">
                            <div class="list-block-title">Campaign Details</div>
                            <div class="list-block">
                                <p><strong>{{ $tickets->campaign->campaign_name }}</strong></p>
                                <span>{{ $tickets->user_detail->first_name }} {{ $tickets->user_detail->last_name }}</span>
                                <span>{{ $tickets->user_detail->email }}</span><br />
                                @if(isset($tickets->user_detail->settlement_account_type))
                                <span><strong>{{ $tickets->user_detail->settlement_account_type }} - {{ $tickets->user_detail->settlement_account_number }}</strong></span><br />
                                @endif
                                <span>{{ $tickets->user_detail->account_level }}</span><br />
                            </div>
                            <br />
                            <div class="list-block-title">Ticket Details</div>
                            <div class="list-block">
                                <span>Ticket ID: {{ $tickets->task_ticket_id }}</span><br />
                                <span>Ticket Last Update: {{ date('M d, Y H:i:s', strtotime($tickets->updatedAt)) }}</span><br />
                                <!-- <span class="text-warning">Pending</span><br /> -->
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-9 col-xxl-10 border-md-left">
            <div class="bg-white">
                <div class="group-15 p-3 d-flex flex-wrap justify-content-lg-between">
                    <div class="row">
                        <div class="col-md-4">


                            <a id="approve" class="btn btn-success"><span class="fa-check"></span></a>
                                <a id="reject" class="btn btn-danger"><span class="fa-remove"></span></a>
                        </div>
                        <div class="col-md-8">
                            <div class="text-right">
                            @if($tickets->approval_status=="REJECTED")
                                <span><strong>Reason: </strong>{{ $tickets->rejection_reason }}</span>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-align-1 table-vertical-align">
                        <thead>
                        <tr>
                            <!-- <th scope="col">Select</th> -->
                            <th scope="col">Timestamp</th>
                            <!--<th scope="col">Coordinates</th>-->
                            <th scope="col">Task Question</th>
                            <th scope="col">Task Answer</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tickets->task_details as $tix)
                            <tr>
                                <td>{{ date('M d, Y H:i:s', strtotime($tix->updatedAt)) }}</td>
                                <!--<td></td>-->
                                <td>{{ ($tix->task_question->question) ? $tix->task_question->question : ''}}</td>
                                <td>
                                    @if (substr($tix->response, 0, 11) == "data:image/" || is_array(@getimagesize($tix->response)))
                                        <div class="image-details">
                                            @if($tix->image_source)
                                            <span><strong>Source: </strong> {{ $tix->image_source }}</span>
                                            @endif
                                            @if($tix->file_name)
                                            <span><strong>Original Filename: </strong> {{ $tix->file_name }}</span>
                                            @endif

                                        </div>
                                        <span class="list-inline-item">
                                            <img src="{{ ($tix->response) ? $tix->response : ''}}"/>
                                        </span>
                                    @else
                                        {{ ($tix->response) ? $tix->response : ''}}
                                    @endif
                                </td>
                                <td class="text-{{ config('concreteadmin')['ticket_status'][$tickets->approval_status ] }}">{{ $tickets->approval_status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop


@section('js')
    <script type="text/javascript">

        $(document).ready(function (e) {

            $("#approve").click(function(){
                let url = "{{ url('ticket/approve_ticket/'.$tickets->campaign_id . '/' . $tickets->task_ticket_id) }}";
                $.confirm({
                    title: 'Hustle',
                    content: 'Are you sure you want to APPROVE this ticket?',
                    buttons: {
                    confirm: function () {
                        $.alert('Confirmed!');
                    },
                    cancel: function () {
                        //$.alert('Canceled!');
                    } }
                })
            
            });
            

            $("#reject").click(function(){
                let url = "{{ url('ticket/reject_ticket/'.$tickets->campaign_id . '/' . $tickets->task_ticket_id) }}";
                $.confirm({
                    title: 'Hustle',
                    content: '' +
                    '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<label>Reason for Rejecting this ticket:</label>' +
                    '<input type="text" placeholder="Reason" class="reason form-control" required />' +
                    '</div>' +
                    '</form>',
                    buttons: {
                        formSubmit: {
                            text: 'Submit',
                            btnClass: 'btn-blue',
                            action: function () {
                                var reason = this.$content.find('.reason').val();
                                if(!reason){
                                    $.alert('provide a valid reason');
                                    return false;
                                }
                                window.location = url;
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {
                        // DO NOTHING
                    }
                });
            });

        });
    </script>
@stop
