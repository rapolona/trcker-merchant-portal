@extends('adminlte::page')

@section('title', 'Trckr | Rewards Management')

@section('content_header')
    <h1>Remaining Budget</h1><span> {{ $remaining_budget }} </span>
@stop

@section('content')
    <p>View Rewards</p>
    <div class="row">
        <div class="col col-lg-9">
        </div>      
        <div class="col col-lg-3">
            <form method="POST" enctype="multipart/form-data" id="" action="javascript:void(0)" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">                    
                <button type="submit" class="btn btn-block btn-primary btn-lg pull-right">Prefund Wallet</button>  
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card" style="width:100%">
                <!-- /.card-header -->
                <table class="table table-bordered">
                    <thead>                  
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Campaign Name</th>
                        <th>Budget</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($rewards as $r)
                    <tr>
                        <td> {{ $r['no'] }}</td>
                        <td> {{ $r['budget'] }}</td>
                        <td> {{ $r['campaign_name'] }}</td>
                        <td> {{ $r['duration'] }}</td>
                        <td> {{ $r['status'] }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript">
    </script>
@stop