@extends('adminlte::page')

@section('title', 'Trckr | View Profile')

@section('content_header')`
    <h1>Create New Task</h1>
@stop

@section('content')
@section('plugins.JqueryUI', true)
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

    <p>Create Task</p>

    <div class="card">
        <form class="form-vertical" id="create_task">
            <div class="card-body">           
                <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Action Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="input_task_action_name" name="task_action_name" value="" placeholder="Enter Task Action Name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Action Description</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="input_task_action_description" name="task_action_description" value="" placeholder="Enter Task Action Description">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Subject Level</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="input_subject_level" name="subject_level" value="" placeholder="Enter Subject Level">
                    </div>
                </div>

                <div class="build-wrap"></div>
            </div>
            <div class="card-footer">
                <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-block btn-primary btn-lg pull-right" id="submit">Save Details</button>  
                    <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                </div>
            </div>
        </form>
    </div>
    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript" src="/vendor/form-builder/form-builder.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function (e) { 
            var formBuilder = $('.build-wrap').formBuilder();

            $('#create_task').submit(function(e) {
                
                var myFormBuilder = formBuilder.actions.getData('json', true);
                e.preventDefault();

                var formData = new FormData(this);

                formData.append('data_type', "custom");
                formData.append('data_source', myFormBuilder);
                
                $.ajax({
                    type:'POST',
                    url: "/task/create",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").text("Custom Task Creation Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").text("Custom Task Creation Failed!");
                        $(".modal-body").html("<p>" + data.responseText + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });
        });
    </script>
@stop