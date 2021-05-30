@extends('concrete.layouts.main')

@section('content')
<section>
    <div class="row no-gutters">
        <div class="col-md-4 col-lg-3 col-xxl-2 bg-100">
            <div class="px-3 py-4">
                <div class="panel">
                    <div class="panel-body">
                        <div class="list-block-container">
                            <div class="list-block-title">User Details</div>
                            <table class="table table-hover table-align-1 table-vertical-align">
                            	<tbody>
                            		<tr>
                            			<td align="right"><strong>Name: </strong></td>
                            			<td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            		</tr>
                            		<tr>
                            			<td><strong>Level: </strong></td>
                            			<td>{{ $user->account_level }}</td>
                            		</tr>
                            		<tr>
                            			<td><strong>Age: </strong></td>
                            			<td>{{ $user->age }}</td>
                            		</tr>
                            		<tr>
                            			<td><strong>Birthday: </strong></td>
                            			<td>{{ date('Y-m-d', strtotime($user->birthday)) }}</td>
                            		</tr>
                            		<tr>
                            			<td><strong>Gender: </strong></td>
                            			<td>{{ $user->gender }}</td>
                            		</tr>
                            		<tr>
                            			<td><strong>Email: </strong></td>
                            			<td>{{ $user->email }}</td>
                            		</tr>
                            		<tr>
                            			<td><strong>Settlement: </strong></td>
                            			<td>{{ $user->settlement_account_type }}: {{ $user->settlement_account_number }}</td>
                            		</tr>
                            		<tr>
                            			<td><strong>Account Creation: </strong></td>
                            			<td>{{ date('Y-m-d', strtotime($user->createdAt)) }}</td>
                            		</tr>
                            	</tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-9 col-xxl-10 border-md-left">
            <div class="bg-white">
                <div class="group-15 p-3 d-flex flex-wrap justify-content-lg-between">
                    <div class="row" >
                        <div style="float: left" >
                            <a id="block" class="btn btn-danger"><span class="fa-remove"></span> Block</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="padding-left: 10px; padding-right: 10px">
                	<div><strong>Payout Requests</strong></div>
                    <table class="table table-hover table-align-1 table-vertical-align data-table" data-table-lengthChange="true" data-page-length="5">
                        <thead>
                        <tr>
                            <th scope="col">Request Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($user->user_payout_requests as $payout)
                            <tr>
                                <td>{{ date('Y-m-d', strtotime($payout->createdAt)) }}</td>
                                <td>{{ $payout->amount }}</td>
                                <td>{{ $payout->reference_id }}</td>
                                <td>{{ $payout->status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr />
                <div class="table-responsive" style="padding-left: 10px; padding-right: 10px">
                	<div><strong>Tickets</strong></div>
                    <table class="table table-hover table-align-1 table-vertical-align data-table" data-table-lengthChange="true" data-page-length="10">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($user->task_tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->task_ticket_id }}</td>
                                <td>{{ $ticket->approval_status }}</td>
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

            $("#block").click(function(){
                let url = "{{ url('respondent/block/'.$user->user_id ) }}";
                $.confirm({
                    title: 'Hustle',
                    content: '' +
                    '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<label>Reason for Blocking this user:</label>' +
                    '<input type="text" placeholder="Reason" class="reason form-control" required />' +
                    '</div>' +
                    '</form>',
                    buttons: {
                        formSubmit: {
                            text: 'Submit',
                            btnClass: 'btn-blue',
                            action: function () {
                                var reasons = this.$content.find('.reason').val();
                                if(!reasons){
                                    $.alert('provide a valid reason');
                                    return false;
                                }
                                let params = { reason : reasons };
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

        });
    </script>
@stop
