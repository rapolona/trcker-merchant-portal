@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Ticket Management</h1>
@stop

@section('content')
    <p>Insert text here</p>
    <div class="row">
        <div class="col col-lg-9">
        </div>      
        <div class="col col-lg-3">
            <div class="row">
            <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="/ticket/export" type="button" class="btn btn-primary btn-lg pull-right">Export List</a>
                    <button type="button" class="btn btn-primary btn-lg pull-right">Approve</button>    
                    <button type="button" class="btn btn-primary btn-lg pull-right">Reject</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card" style="width:100%">
                <!-- /.card-header -->
                <table class="table table-bordered">
                    <thead>                  
                    <tr>
                        <th>Trckr Username</th>
                        <th>Email</th>
                        <th>Mobile number</th>
                        <th>Campaign Name</th>
                        <th>Task</th>
                        <th>Date Submitted</th>
                        <th>Device ID</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th style="width: 40px">Action?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tickets as $t)
                    <tr>
                        <td> {{ $t['trckr_username'] }}</td>
                        <td> {{ $t['email'] }}</td>
                        <td> {{ $t['mobile_number'] }}</td>
                        <td> {{ $t['campaign_name'] }}</td>
                        <td> {{ $t['tasks'] }}</td>
                        <td> {{ $t['date_submitted'] }}</td>
                        <td> {{ $t['device_id'] }}</td>
                        <td> {{ $t['location'] }}</td>
                        <td> {{ $t['status'] }}</td>
                        <td><input type="checkbox" name="{{ $t['action'] }}" id="{{ $t['action'] }}" {{($t['action'] === 1 ? 'checked' : '') }}> </td>
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
      
        $(document).ready(function (e) { 
            $('#file_upload').submit(function(e) {
                //e.preventDefault();

                var formData = new FormData(this);
        
                $.ajax({
                    type:'POST',
                    url: "/merchant/products/upload",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        alert('File has been uploaded successfully');
                        console.log(data);
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            });
        });
  </script>
@stop