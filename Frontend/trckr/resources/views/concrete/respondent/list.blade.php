@extends('concrete.layouts.main')


@section('tableFilters')
    <div class="row">
        <div class="col-sm-2">
            <input class="form-control" id="last_name" type="text" value="{{ $filter['last_name'] }}" placeholder="Last name">
        </div>
        <div class="col-sm-2">
            <input class="form-control" id="first_name" type="text" value="{{ $filter['first_name'] }}" placeholder="First Name">
        </div>
        <div class="col-sm-2">
            <select class="form-control" id="status">
                <option value="">--Status--</option>
                <option {{ ($filter['status']=="PENDING")? 'selected' : '' }} value="PENDING">PENDING</option>
                <option {{ ($filter['status']=="ACTIVATED")? 'selected' : '' }} value="ACTIVATED">ACTIVATED</option>
                <option {{ ($filter['status']=="BLOCKED")? 'selected' : '' }} value="BLOCKED">BLOCKED</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input class="form-control" id="email" type="text" value="{{ $filter['email'] }}" placeholder="Email">
        </div>
        <div class="col-sm-2">
            <input class="form-control" id="mobile" type="text" value="{{ $filter['mobile'] }}" placeholder="Mobile">
        </div>
        <div class="col-sm-2">
            <button type="button" id="searchBtn" class="btn btn-primary"><span class="fa-search"></span></button>
            <button type="button" id="downloadBtn" class="btn btn-primary"><span class="fa-cloud-download"></span></button>
        </div>
    </div>
@endsection


@section('content')
    <div class="panel">
        <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
            <div class="panel-title">Respondents</div>
        </div>
        <div class="panel-menu">
                @include('concrete.layouts.filters')  
        </div>
        <div class="panel-body p-0">
                <div class="table-responsive scroller scroller-horizontal py-3">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Settlement</th>
                        <th></th>
                    </tr>
                    </thead>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->last_name }} {{ $user->first_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->account_level }}</td>
                            <td>{{ $user->status }}</td>
                            <td>{{ $user->settlement_account_type }}: {{ $user->settlement_account_number }}</td>
                            <td>
                                <a href="{{ url('respondent/'. $user->user_id  ) }}" class="btn btn-primary">view</a>
                            </td>
                        </tr>
                        @endforeach
                    <tbody>
                    
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
<script type="text/javascript">
    $(document).ready(function (e) {
        $('#searchBtn').click(function(e){
            let url = "{{ url('respondent') }}?";
            let params = { 
                    last_name : $('#last_name').val(),
                    first_name : $('#first_name').val(),
                    status : $('#status').val(),
                    email : $('#email').val(),
                    mobile : $('#mobile').val(),
            };
            let str = jQuery.param( params );
            window.location = url + str;
        });    

        $('#downloadBtn').click(function(e){
            let url = "{{ url('respondent/export_csv/download') }}?";
            let params = { 
                    last_name : $('#last_name').val(),
                    first_name : $('#first_name').val(),
                    status : $('#status').val(),
                    email : $('#email').val(),
                    mobile : $('#mobile').val(),
            };
            let str = jQuery.param( params );
            window.location = url + str;
        }); 
        
    })
</script>    
@stop
