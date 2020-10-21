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
                                            <option value="{{$ct->campaign_type_id}}">{{$ct->name}}</option>
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
                                            @foreach ($task_type as $ct)
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
                            <button class="btn btn-primary btn-lg" type="submit" value="submit" id="submit">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_submit" aria-hidden="true" disabled> </span>
                                Create Campaign
                            </button>    
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
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">
        //input_end_date
        $('#input_start_date').datepicker({ 
            dateFormat: 'yy-mm-dd', 
            minDate: 0,
            onSelect: function(date) {
                var selectedDate = new Date(date);
                //var msecsInADay = 86400000;
                var endDate = new Date(selectedDate.getTime());
                $("#input_end_date").datepicker( "option", "minDate", endDate );
                //$("#input_end_date").datepicker( "option", "maxDate", '+y' );
            }    
        });
        $('#input_end_date').datepicker({ 
            minDate: 0,
            dateFormat: 'yy-mm-dd', 
            onSelect: function(date) {
                var selectedDate = new Date(date);
                //var msecsInADay = 86400000;
                var startDate = new Date(selectedDate.getTime());
                $("#input_start_date").datepicker( "option", "maxDate", startDate );
                //$("#input_start_date").datepicker( "option", "minDate", '-y' );
            }
        });

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
                url: "{{url('/campaign/campaign_type/task?task_id=')}}" + this.value,
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
                @foreach ($task_type as $ct)
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

            $("#create_campaign").submit(function(e){
                e.preventDefault();

                var formData = new FormData(this);

                post("{{url('/campaign/create_campaign')}}", "Create Campaign", "submit", formData, "{{url('/campaign/view')}}");
            });

            $("#back").click(function(){
                window.location.href = "{{url('/campaign/view')}}";
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