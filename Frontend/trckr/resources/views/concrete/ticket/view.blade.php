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
                                <span><strong>{{ $tickets->user_detail->settlement_account_type }} - {{ $tickets->user_detail->settlement_account_number }}</strong></span><br />
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
                    <div class="btn-group">
                        <a href="{{ url('ticket/approve_ticket/'.$tickets->campaign_id . '/' . $tickets->task_ticket_id) }}" class="btn btn-success"><span class="fa-check"></span></a>
                        <a href="{{ url('ticket/reject_ticket/'.$tickets->campaign_id . '/' . $tickets->task_ticket_id) }}" class="btn btn-danger"><span class="fa-remove"></span></a>
                        <!-- <button class="btn btn-primary"><span class="fa-eye"></span></button> -->
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
                                    @if (substr($tix->response, 0, 11) == "data:image/")
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

        });
    </script>
@stop
