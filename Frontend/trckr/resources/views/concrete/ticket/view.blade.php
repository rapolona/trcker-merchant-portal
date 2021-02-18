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
                                <span>Ticket Created: {{ date('M d, Y H:i:s', strtotime($tickets->createdAt)) }}</span><br />
                                <!-- <span class="text-warning">Pending</span><br /> -->
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-9 col-xxl-10 border-md-left">
            <div class="bg-white">
                <div class="row" style="padding: 10px">
                  
                        <div class="col-md-3">
                            @if(!$tickets->awarded)
                                <a id="approve" class="btn btn-success"><span class="fa-check"></span></a>
                                <a id="reject" class="btn btn-danger"><span class="fa-remove"></span></a>
                                @if($tickets->approval_status=="APPROVED")
                                    <a id="award" class="btn btn-warning">Award</a>
                                @endif
                            @else
                                <div class="badge badge-secondary">AWARDED</div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            
                        </div>
                        <div class="col-md-2 pull-right text-right">
                            @if(isset($pagination->prev))
                            <a href="{{ url('ticket/view/' . $pagination->prev->campaign_id . '/' . $pagination->prev->task_ticket_id ) }}?page={{ isset($pagination->prev->page)? $pagination->prev->page : ($pagination->next->page - 1) }}" class="btn btn-primary"><span class="fa-arrow-circle-left"></span></a>
                            @endif

                            @if($pagination->next)
                            <a href="{{ url('ticket/view/' . $pagination->next->campaign_id . '/' . $pagination->next->task_ticket_id ) }}?page={{ isset($pagination->next->page)? $pagination->next->page : ($pagination->prev->page + 1) }}" class="btn btn-primary"><span class="fa-arrow-circle-right"></span></a>
                            @endif
                        </div>
                    
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-align-1 table-vertical-align">
                        <thead>
                            <tr>
                                <td><strong>Reward:</strong> {{ $tickets->reward_amount }}</td>
                                <td colspan="3">
                                    @if($tickets->approval_status=="REJECTED" && isset($tickets->rejection_reason))
                                        <span><strong> Reason: </strong>{{ $tickets->rejection_reason }}</span>
                                    @endif 
                                </td>
                            </tr>
                            <tr>
                                <td>Latitude:</td>
                                <td>{{ $tickets->latitude }}</td>
                                <td>Longtitude:</td>
                                <td>{{ $tickets->longitude }}</td>
                            </tr>
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
                                <td>{!! ($tix->task_question->question) ? $tix->task_question->question : '' !!}</td>
                                <td>
                                    @if (substr($tix->response, 0, 11) == "data:image/" || is_array(@getimagesize($tix->response_url)))
                                        <div class="image-details">
                                            @if(isset($tix->image_source))
                                            <span><strong>Source: </strong> {{ $tix->image_source }}</span>
                                            @endif
                                            @if(isset($tix->file_name))
                                            <span><strong>Original Filename: </strong> {{ $tix->file_name }}</span>
                                            @endif

                                        </div>
                                        <span class="list-inline-item">
                                            <img src="{{ ($tix->response_url) ? $tix->response_url : ''}}"/>
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
                        window.location = url;
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
                                let params = { rejection_reason : reason };
                                let str = jQuery.param( params );
                                window.location = url + '?' + str;
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

             $("#award").click(function(){
                let url = "{{ url('ticket/award_ticket/'.$tickets->campaign_id . '/' . $tickets->task_ticket_id) }}";
                $.confirm({
                    title: 'Hustle',
                    content: 'Are you sure you want to AWARD the reward? (you can no longer change its status upon awarding)',
                    buttons: {
                    confirm: function () {
                        window.location = url;
                    },
                    cancel: function () {
                        //$.alert('Canceled!');
                    } }
                })
            
            });

        });
    </script>
@stop
