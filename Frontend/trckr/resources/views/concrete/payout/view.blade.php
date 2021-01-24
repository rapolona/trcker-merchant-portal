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
            <form method="post" action="{{ url('payout/' . $payout->user_payout_request_id) . '/update' }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel">
                
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Payout Requests</div>
                </div>
                <div class="panel-body">
                    <div class="row row-30">
                        <div class="col-md-10">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Date Requested</span></div>
                                <input class="form-control " type="text" readonly="" value="{{ date('Y-m-d H:s', strtotime($payout->createdAt)) }}">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Amount</span></div>
                                <input class="form-control " type="text" readonly="" value="{{ $payout->amount }}">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Reference</span></div>
                                <input class="form-control " required=""  type="text" name="reference_id" value="">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Remarks</span></div>
                                <input class="form-control " required=""  type="text" name="remarks" value="">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                                <select class="form-control" name="status">
                                    <option value="APPROVE">Approve</option>
                                    <option value="REJECT">Reject</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer pull-right text-right"> 
                    <button class="btn btn-success" type="submit">Submit</button>    
                </div>
                
            </div>
        </form>
        </div>
    </div>
</section>
@stop


@section('js')
    <script type="text/javascript">

        $(document).ready(function (e) {

            $("#approve").click(function(){
                let url = "{{ url('respondent/block/'.$user->user_id ) }}";
                $.confirm({
                    title: 'Hustle',
                    content: 'Are you sure you want to BLOCK this user?',
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
