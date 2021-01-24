@extends('concrete.layouts.main')

@section('tableFilters')
    <div class="row">
        <div class="col-sm-7">
            <div class="input-group form-group">
                <div class="input-group-prepend"><span class="input-group-text" id="basic-addon3">Nname</span></div>
            <input class="form-control" id="name" type="text" value="{{ $filter['name'] }}" name="name">
            </div>
        </div>
        <div class="col-sm-3">
            <select class="form-control" id="status">
                <option value="">--Status--</option>
                <option {{ ($filter['status']=="PENDING")? 'selected' : '' }} value="PENDING">PENDING</option>
                <option {{ ($filter['status']=="APPROVED")? 'selected' : '' }} value="APPROVED">APPROVED</option>
            </select>
        </div>
        <div class="col-sm-2">
            <button type="button" id="searchBtn" class="btn btn-primary">Search</button>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
            <div class="panel-title">Payout Requests</div>
        </div>
        <div class="panel-menu">
            @include('concrete.layouts.filters')  
        </div>
        <div class="panel-body p-0">
                <div class="table-responsive scroller scroller-horizontal py-3">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-checkbox-light">
                                    <input class="custom-control-input" type="checkbox" id="selectAll"/>
                                    <label class="custom-control-label" for="selectAll"></label>
                                </div>
                            </td>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Payout Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payouts as $payout)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-checkbox-light">
                                    <input class="custom-control-input" type="checkbox" name="user_payout_request_id[]" id="{{ $payout->user_payout_request_id }}" value="{{ $payout->user_payout_request_id }}" />
                                    <label class="custom-control-label" for="{{ $payout->user_payout_request_id }}"></label>
                                </div>
                            </td>
                            <td>{{ $payout->user_detail->last_name }}, {{ $payout->user_detail->first_name }}</td>
                            <td>{{ $payout->user_detail->email }}</td>
                            <td>{{ $payout->user_detail->settlement_account_number }}</td>
                            <td>{{ $payout->user_detail->settlement_account_type }}</td>
                            <td>{{ $payout->amount }}</td>
                            <td class="text-{{ config('concreteadmin')['payout_status'][$payout->status] }}">
                                {{ $payout->status }}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-primary" href="{{ url('payout/' . $payout->user_payout_request_id )}}">View</a>
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
@stop

@section('js')

@stop
