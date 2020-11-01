@extends('concrete.layouts.main')

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
                            <a href="{{url('/task/create')}}" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                            <button type="button" class="btn btn-primary btn-lg" id="edit">Edit</button>
                            <button type="button" class="btn btn-primary btn-lg" id="delete">Delete</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-striped mydatatable">
                        <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Description</th>
                            <th style="width: 40px">Action?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tasks as $t)
                        <tr>
                            <input class="view_id" type="hidden" name="row_task_id[]" value="{{$t->task_id}}"/>
                            <td class="view"> {{ $t->task_name }}</td>
                            <td class="view"> {{ $t->task_description }}</td>
                            <td><input type="checkbox" name="task" id="{{$t->task_id}}"></td>
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
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {

            $('.view').click(function(){
                var task_id = $(this).siblings('.view_id').val();

                window.location.href = "{{url('/task/view_task?task_id=')}}" + task_id;
            });

            $('#myModal').on('hidden.bs.modal', function () {
                location.reload();
            });

            $('#delete').click(function(e){
                var formData = new FormData();
                var task = [];

                $.each($("input[name='task']:checked"), function(){
                    task.push($(this).attr("id"));
                });

                if (task.length < 1){
                    $(".modal-title").text("Invalid Delete Selection!");
                    $(".modal-body").html("<p>Please check at least one Task!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                console.log(task);

                formData.append('tasks', task);
                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    type:'POST',
                    url: "{{url('/task/delete')}}",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").text("Delete Task Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").text("Delete Task Failed!");
                        $(".modal-body").html("<p>" + data.responseJSON.message + "</p>");
                        //$(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });

            $('#edit').click(function(e){
                var tasks = [];
                $.each($("input[name='task']:checked"), function(){
                    tasks.push($(this).attr("id"));
                });

                if (tasks.length != 1){
                    $(".modal-title").text("Invalid Edit Selection!");
                    $(".modal-body").html("<p>Please check one task only!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                window.location.href = "{{url('/task/edit?task_action_id=')}}" + tasks[0];
            });
        });
    </script>
@stop
