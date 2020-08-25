@extends('adminlte::page')

@section('title', 'Users Management')

@section('content_header')
    <h1>Users Management</h1>
@stop

@section('content')
    <p>Users Management - Note, no api available yet to retrieve all users belonging to same merchant.</p>
    
    <div class="row">
        <div class="col col-lg-12">
            <div class="card" style="width:100%">
                <div class="card-header">
                    <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">                

                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button type="button" id="upload_csv" class="btn btn-block btn-primary btn-lg pull-right">Upload CSV</button>  
                            <a href="#" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                            <button type="button" class="btn btn-primary btn-lg" id="edit">Edit</button>    
                            <button type="button" class="btn btn-primary btn-lg pull-right">Delete</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>                  
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Brand</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th style="width: 40px">Action?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $u)
                        <tr>
                            <td> {{ $u['no'] }}</td>
                            <td> {{ $u['name'] }}</td>
                            <td> {{ $u['email_address'] }}</td>
                            <td> {{ $u['type'] }}</td>
                            <td><input type="checkbox" name="{{ $u['no'] }}" id="{{ $u['no'] }}" {{($u['action'] === 1 ? 'checked' : '') }}> </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> 
        $(document).ready(function (e) { 
            $('#file_upload').submit(function(e) {
                //e.preventDefault();

                var formData = new FormData(this);
        
                $.ajax({
                    type:'POST',
                    url: "/#",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").text("xxx Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").text("xxx Failed!");
                        $(".modal-body").html("<p>" + data.responseText + "</p>");
                        //$(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });
        });
    </script>
@stop