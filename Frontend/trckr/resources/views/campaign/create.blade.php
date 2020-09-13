@extends('adminlte::page')

@section('title', 'Trckr | Create New Campaign')

@section('content_header')
    <h1>Create New Campaign</h1>
@stop

@section('content')
@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)
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
    <p>Create New Campaign</p>

    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form id="create_campaign" name="create_campaign">
                    <div class="card-body">
                        <div class="row">
                            <div class="col col-lg-12">
                                <h2>Campaign Information</h2>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col col-lg-6">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                                
                                <div class="form-group row">
                                    <label for="campaign_name" class="col-sm-2 col-form-label">Campaign Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_campaign_name" name="campaign_name" value="" placeholder="Enter Campaign Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_description" class="col-sm-2 col-form-label">Campaign Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_campaign_description" name="campaign_description" value="" placeholder="Enter Campaign Description">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branches" class="col-sm-2 col-form-label">Branches</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" multiple="multiple" name="branches[]" style="width: 100%;">
                                            <option value="">Select One</option>
                                            @foreach ($branches as $b)
                                            <option value="{{$b->branch_id}}">{{$b->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="audience" class="col-sm-2 col-form-label">Audience</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" name="audience[]" id="audience" style="width: 100%;">
                                            <option value="All">All</option>
                                            <option value="super_shopper">Super Shopper</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg-6">
                                <div class="form-group row">
                                    <label for="campaign_type" class="col-sm-2 col-form-label">Campaign Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" name="campaign_type" id="campaign_type" style="width: 100%;">
                                            <option value="">Select One</option>
                                            @foreach ($campaign_type as $ct)
                                            <option value="{{$ct->task_classification_id}}">{{$ct->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="reward" class="col-sm-2 col-form-label">Budget</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_budget" name="budget" value="" placeholder="Enter Budget">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="reward" class="col-sm-2 col-form-label">Reward</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_reward" name="reward" value="" placeholder="Enter Reward">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="duration" class="col-sm-2 col-form-label">Duration</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control date" id="input_start_date" name="start_date" value="" placeholder="Enter Duration From">
                                        <input type="text" class="form-control date" id="input_end_date" name="end_date" value="" placeholder="Enter Duration To">                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col col-lg-6">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col col-lg-8 ">
                                            <h2>Tasks</h2><span>(Pre-defined based on Campaign Type)</span>
                                        </div>
                                        <div class="col col-lg-4 ">
                                            <a href="/task/create" type="button" class="btn btn-primary btn-lg pull-right">Add Custom</a>
                                        </div>
                                    </div>
                                    <table class="table table-bordered" id="table_tasks">
                                        <thead>                  
                                        <tr>
                                            <th style="width: 40%">Task Name</th>
                                            <th style="width: 50%">Task</th>
                                            <th>Action?</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tasks as $t)
                                            <tr>
                                                <td> {{ $t->task_action_name }}</td>
                                                <td> {{ $t->task_action_description }}</td>
                                                <td>
                                                    <input type="hidden" name="task_action_id[]" value="{{$t->task_action_id}}"/>
                                                    <input type='hidden' name='task_action_name[]' value="{{$t->task_action_name}}"/>
                                                    <input type='hidden' name='task_action_description[]' value="{{$t->task_action_description}}"/>
                                                    <input type="checkbox" name="" id=""/>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered" id="table_selected">
                                        <thead>                  
                                        <tr>
                                            <th style="width: 40%">Task Name</th>
                                            <th style="width: 50%">Task</th>
                                            <th>Action?</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        -->

                        <div class="row">
                            <div class="col col-lg-12">
                                <h2>Tasks</h2>
                            </div>    
                        </div>
                        <div class="row task_container">
                            <div class="col col-lg-5">
                                <div class="form-group row">
                                    <label for="task_type" class="col-sm-2 col-form-label">Task Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 task_type" name="task_type[]" style="width: 100%;">
                                            <option value="">Select One</option>
                                            @foreach ($campaign_type as $ct)
                                            <option value="{{$ct->task_classification_id}}">{{$ct->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg-5">
                                <div class="form-group row">
                                    <label for="task_actions" class="col-sm-2 col-form-label">Task Actions</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 task_actions" multiple="multiple" name="task_actions[]" style="width: 100%;">
                                            <option value="">Select One</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg-2">
                                <div class="btn-group" role="group" aria-label="Basic example">  
                                    <button type="button" class="btn btn-danger btn-md pull-right remove_task" id="remove_task_0">Remove Task</button>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group" role="group" aria-label="Basic example">  
                            <button type="button" class="btn btn-info btn-md pull-right" id="add_task">Add New Task</button>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-block btn-primary btn-lg pull-right" id="submit">Save Details</button>  
                            <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@stop

@section('css')
    
@stop

@section('js')
    <script type="text/javascript">
        $('.date').datepicker({ dateFormat: 'yy-mm-dd' });

        $(document).on("click", ".remove_task" , function() {
            $(this).parent().parent().parent().remove();
        });

        $(document).on("change", ".task_actions", function(){
            var count = $(".task_actions").select2('data').length;
        });

        $(document).on("change", ".task_type" , function() {

            var task_action = $(this).parent().parent().parent().parent().find($(".task_actions"));
            $(task_action).empty();
        
            $.ajax({
                type:'GET',
                url: "/campaign/campaign_type/task?task_id=" + this.value,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    

                    $(data.file).each(function(){

                        $(task_action).append('<option value="' + this.task_id +'">' + this.task_name + '</option>');
                    });
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        $(document).ready(function (e) { 
            $('.select2').select2();

            /*
            $("#input_audience_checkbox").click(function(){
                var isChecked= $(this).is(':checked');

                $("#branch_select option").each(function(){
                    if ( ! isChecked) 
                        $(this).removeAttr('selected');
                    else
                        $(this).attr('selected', 'selected');
                });
            });
            */

            /*
            $("[name*='task_type']").map(function() {          
                alert($(this).attr('id')); 
            });
            */

            $("#add_task").click(function(){
                var index = $(".task_container").length;
                if (index >= 5) {
                    $(".modal-title").text("Cannot Add more tasks!");
                    $(".modal-body").html("<p>The maximum limit of task classifications per campaign is 5</p>");
                    $("#myModal").modal('show');
                    return;
                }

                //messy way to create the dynamic object
                var html = '<div class="row task_container">';
                html += '<div class="col col-lg-5">';
                html += '<div class="form-group row">';
                html += '<label for="task_type" class="col-sm-2 col-form-label">Task Type</label>';
                html += '<div class="col-sm-10">';
                html += '<select class="form-control select2 task_type" name="task_type[]" style="width: 100%;">';
                html += '<option value="">Select One</option>';
                @foreach ($campaign_type as $ct)
                    html += '<option value="{{$ct->task_classification_id}}">{{$ct->name}}</option>'
                @endforeach
                html += '</select>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col col-lg-5">';
                html += '<div class="form-group row">';
                html += '<label for="task_actions" class="col-sm-2 col-form-label">Task Actions</label>';
                html += '<div class="col-sm-10">';
                html += '<select class="form-control select2 task_actions" multiple="multiple" name="task_actions[]" style="width: 100%;">';
                html += '<option value="">Select One</option>';
                html += '</select>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col col-lg-2">';
                html += '<div class="btn-group" role="group" aria-label="Basic example">';
                html += '<button type="button" class="btn btn-danger btn-md pull-right remove_task">Remove Task</button>';
                html += '</div>';
                html += '</div>';

                $(html).insertAfter(".task_container:last");

                $(".task_actions").select2();
            });

            /*
            $("#campaign_type").change(function(){
                //alert(this.value);
                $.ajax({
                    type:'GET',
                    url: "/campaign/campaign_type/task?task_id=" + this.value,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".temp_tasks").each(function(){
                            $(this).remove();
                        });

                        $(data.file).each(function(){
                            console.log(data);
                            var html = "<tr class='temp_tasks'><td>" + this.task_action_name + "</td>";
                            html += "<td>" + this.task_action_description + "</td>";
                            html += "<td><input type='checkbox' name='' id=''/>";
                            html += "<input type='hidden' name='task_action_id[]' value='" + this.task_action_id + "'/>";
                            html += "<input type='hidden' name='task_action_name[]' value='" + this.task_action_name + "'/>";
                            html += "<input type='hidden' name='task_action_description[]' value='" + this.task_action_description + "'/>";
                            html += "<input type='hidden' name='data_type[]' value='" + this.data_type + "'/>";
                            html += "<input type='hidden' name='data_source[]' value='" + this.data_source + "'/>";
                            
                            $('#table_tasks tbody').append(html);
                        });
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            });
            */

            $("#create_campaign").submit(function(e){
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    type:'POST',
                    url: "/campaign/create_campaign",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").text("Campaign Creation Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").text("Campaign Creation Failed!");
                        //$(".modal-body").html("<p>" + data.responseText + "</p>");
                        $(".modal-body").html("<p>" + data.responseJSON.message + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });

            $("#back").click(function(){
                window.location.href = "/campaign/view";
            });
        });

        $(document).on('click', '#table_tasks tbody tr', function(){
            var html = $(this).clone().wrap('<p/>').parent().html();
            $('#table_selected tbody').append(html);
            if ($(this).length) {
                $(this).remove();
            }
        })

        $(document).on('click', '#table_selected tbody tr', function(){
            var html = $(this).clone().wrap('<p/>').parent().html();
            $('#table_tasks tbody').append(html);
            if ($(this).length) {
                $(this).remove();
            }
        });

        

    </script>
@stop