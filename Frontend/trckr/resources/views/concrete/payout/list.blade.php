@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
            <div class="panel-title">Payout Requests</div>
        </div>

        <div class="panel-body p-0">
                <div class="table-responsive scroller scroller-horizontal py-3">
                    <table class="table table-striped table-hover data-table" data-table-searching="true" data-table-lengthChange="true" data-page-length="5">
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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('js')

@stop
