@extends('concrete.layouts.main')

@section('breadcrumbs_pull_right')
    <div class="btn-group">
          <a class="btn btn-primary" href="{{ url('task/create') }}"><span class="fa-plus">Add a new branch</span></a>
<!--           <a class="btn btn-dark" href="#"><span class="fa-upload">Upload CSV</span></a> -->
    </div>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel-title"><span class="panel-icon fa-tasks"></span> <span>Tasks</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body p-0">
            <div class="table-responsive scroller scroller-horizontal py-3">
                <table class="table table-striped table-hover data-table" data-table-searching="true" data-table-lengthChange="true" data-page-length="5">
                    <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Task Description</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tasks as $t)
                        <tr>
                            <td> {{ $t->task_name }}</td>
                            <td> {{ $t->task_description }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle btn-light btn-sm" data-toggle="dropdown"><span>Action</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url('task/view_task/' . $t->task_id) }}">View</a>
                                        <a class="dropdown-item" href="{{ url('task/edit/' . $t->task_id) }}">Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">

        $(document).ready(function (e) {

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
