@extends('adminlte::page')

@section('title', 'Tasks')

@section('content_header')
    <h1>Tasks</h1>
@stop

@section('content')
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" style="display:auto;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <p>Insert text here</p>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card" style="width:100%">
                <div class="card-header">
                    <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">                

                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <a href="/task/create" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                            <button type="button" class="btn btn-primary btn-lg" id="edit">Edit</button>    
                            <button type="button" class="btn btn-primary btn-lg pull-right">Delete</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                <table class="table table-bordered">
                    <thead>                  
                    <tr>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Subject Level</th>
                        <th style="width: 40px">Action?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tasks as $t)
                    <tr>
                        <td> {{ $t->task_action_name }}</td>
                        <td> {{ $t->task_action_description }}</td>
                        <td> {{ $t->subject_level }}</td>
                        <td><input type="checkbox" name="branch" id="{{$t->task_action_id}}"></td>
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