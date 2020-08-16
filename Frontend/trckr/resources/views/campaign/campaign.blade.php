@extends('adminlte::page')

@section('title', 'Trckr | View Campaigns')

@section('content_header')
    <h1>Campaign</h1>
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
                    <a href="/campaign/create" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                    <button type="button" class="btn btn-primary btn-lg pull-right">Edit</button>    
                    <button type="button" class="btn btn-primary btn-lg pull-right">Delete</button>  
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
                        <th style="width: 10px">#</th>
                        <th>Campaign Name</th>
                        <th>Budget</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th style="width: 40px">Action?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($campaigns as $c)
                    <tr>
                        <td> {{ $c['no'] }}</td>
                        <td> {{ $c['campaign_name'] }}</td>
                        <td> {{ $c['budget'] }}</td>
                        <td> {{ $c['duration'] }}</td>
                        <td> {{ $c['status'] }}</td>
                        <td><input type="checkbox" name="{{ $c['no'] }}" id="{{ $c['no'] }}" {{($c['action'] === 1 ? 'checked' : '') }}> </td>
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