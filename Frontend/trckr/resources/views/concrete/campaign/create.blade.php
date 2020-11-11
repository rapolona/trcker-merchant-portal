@extends('concrete.layouts.main')

@section('content')
    <div class="modal modal-3 fade modal-active" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Select Branch</h3>
                    <button class="close" data-dismiss="modal" aria-label="Close"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="row row-30">
                                    <div class="col-lg-2">
                                        @if(isset($filters->business_type))
                                            <select class="form-control hustle-filter" name="business_type">
                                                <option value="">Business Type</option>
                                                @foreach ($filters->business_type as $option)
                                                    @if(!empty($option))
                                                        <option {{ (isset($selectedFilter['business_type']) && $selectedFilter['business_type']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-2">
                                        @if(isset($filters->store_type))
                                            <select class="form-control hustle-filter" name="store_type">
                                                <option value="">Store Type</option>
                                                @foreach ($filters->store_type as $option)
                                                    @if(!empty($option))
                                                        <option {{ (isset($selectedFilter['store_type']) && $selectedFilter['store_type']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-2">
                                        @if(isset($filters->brand))
                                            <select class="form-control hustle-filter" name="brand">
                                                <option value="all">Brand</option>
                                                @foreach ($filters->brand as $option)
                                                    @if(!empty($option))
                                                        <option {{ (isset($selectedFilter['brand']) && $selectedFilter['brand']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-2">
                                        @if(isset($filters->province))
                                            <select class="form-control hustle-filter" name="province">
                                                <option value="">Province</option>
                                                @foreach ($filters->province as $option)
                                                    @if(!empty($option))
                                                        <option {{ (isset($selectedFilter['province']) && $selectedFilter['province']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-lg-2">
                                        @if(isset($filters->city))
                                            <select class="form-control hustle-filter" name="city">
                                                <option value="">City</option>
                                                @foreach ($filters->city as $option)
                                                    @if(!empty($option))
                                                        <option {{ (isset($selectedFilter['city']) && $selectedFilter['city']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body p-0">
                                <div class="table-responsive scroller scroller-horizontal py-3">
                                    <table class="table table-striped table-hover data-table" data-table-searching="true" data-table-lengthChange="true" data-page-length="5">
                                        <thead>
                                        <tr>
                                            <td style="width: 40px">
                                                <div class="custom-control custom-checkbox custom-checkbox-success">
                                                    <input class="custom-control-input" type="checkbox" id="selectAll"/>
                                                    <label class="custom-control-label" for="selectAll"></label>
                                                </div>
                                            </td>
                                            <th>Name</th>
                                            <th>BusinessType</th>
                                            <th>StoreType</th>
                                            <th>Brand</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Region</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($branches as $branch)
                                            <tr>
                                                <td style="width: 40px">
                                                    <div class="custom-control custom-checkbox custom-checkbox-success">
                                                        <input class="custom-control-input" type="checkbox" name="branch_id" id="{{ $branch->branch_id }}" />
                                                        <label class="custom-control-label" for="{{ $branch->branch_id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $branch->name }}</td>
                                                <td>{{ $branch->business_type }}</td>
                                                <td>{{ $branch->store_type }}</td>
                                                <td>{{ $branch->brand }}</td>
                                                <td>{{ $branch->address }}</td>
                                                <td>{{ $branch->city }}</td>
                                                <td>{{ $branch->region }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
                <div class="modal-footer justify-content-start">
                    <button class="btn btn-primary"  data-dismiss="modal" type="button" id="useBranch">Use Branch</button>
                </div>
            </form>
        </div>
    </div>

    <section class="section-sm">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Campaign Details</div>
                </div>
                <div class="panel-body">
                    <form method="post">
                        <div class="row row-30">
                            <div class="col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-institution"></span></span></div>
                                    <input class="form-control" type="text" name="firstName" placeholder="Campaign Name">
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-black-tie"></span></span></div>
                                    <input class="form-control" type="text" name="lastName" placeholder="Budget">
                                </div>

                                <div class="markdown padding-up" style="margin: 15px 0" data-markdown-footer="Footer placeholder">Put your campaign description here!</div>

                            </div>

                            <div class="col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                                    <select class="form-control" name="filter-purchases">
                                        <option value="0">Campaign Type</option>
                                        <option value="1">1-49</option>
                                        <option value="2">50-499</option>
                                        <option value="1">500-999</option>
                                        <option value="2">1000+</option>
                                    </select>
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                                    <select class="form-control" name="filter-purchases">
                                        <option value="0">Target Audience</option>
                                        <option value="1">1-49</option>
                                        <option value="2">50-499</option>
                                        <option value="1">500-999</option>
                                        <option value="2">1000+</option>
                                    </select>
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-calendar"></span></span></div>
                                    <input class="form-control" id="daterange1" type="text" name="daterange">
                                </div>

                                <div class="form-group col-md-5">
                                    <p>Campaign Thumbnail</p>
                                    <div class="tower-file mt-3">
                                        <input class="tower-file-input" id="demo1" type="file">
                                        <label class="btn btn-xs btn-success" for="demo1"><span>Upload</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="section-sm">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Branch Details</div>
                </div>
                <div class="panel-body">
                    <div class="row row-30">
                        <div class="col-lg-4">
                            <br />
                        </div>
                        <div class="col-lg-4">
                            <input class="form-control" type="number" name="firstName" placeholder="Default Max Submission">
                        </div>
                        <div class="col-lg-4">
                            <button class="btn btn-primary btn-block" data-modal-trigger='{"target":".modal-active","animClass":"zoom-in"}'>Select Branch</button>
                        </div>
                    </div>
                    <div class="row row-30">
                        <div class="col-lg-12">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>BusinessType</th>
                                <th>StoreType</th>
                                <th>Brand</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Region</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Branch 1</td>
                                <td>BusinessType</td>
                                <td>StoreType</td>
                                <td>Brand</td>
                                <td>Address</td>
                                <td>City</td>
                                <td>Region</td>
                                <td><input class="form-control" type="number" name="firstName" placeholder=" Max Submission"></td>
                            </tr>
                            <tr>
                                <td>Branch 2</td>
                                <td>BusinessType</td>
                                <td>StoreType</td>
                                <td>Brand</td>
                                <td>Address</td>
                                <td>City</td>
                                <td>Region</td>
                                <td><input class="form-control" type="number" name="firstName" placeholder=" Max Submission"></td>
                            </tr>
                            <tr>
                                <td>Branch 3</td>
                                <td>BusinessType</td>
                                <td>StoreType</td>
                                <td>Brand</td>
                                <td>Address</td>
                                <td>City</td>
                                <td>Region</td>
                                <td><input class="form-control" type="number" name="firstName" placeholder=" Max Submission"></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-sm">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Task Details</div>
                </div>
                <div class="panel-body">
                    <div class="row row-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="select2" name="country" data-placeholder="Country">
                                    <option label="placeholder"></option>
                                    <option>USA</option>
                                    <option>UK</option>
                                    <option>Ukraine</option>
                                    <option>Australia</option>
                                    <option>Korea</option>
                                    <option>Japan</option>
                                    <option>Germany</option>
                                    <option>Belarus</option>
                                    <option>Poland</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="select2" name="country" multiple data-placeholder="Country">
                                    <option label="placeholder"></option>
                                    <option>USA</option>
                                    <option>UK</option>
                                    <option>Ukraine</option>
                                    <option>Australia</option>
                                    <option>Korea</option>
                                    <option>Japan</option>
                                    <option>Germany</option>
                                    <option>Belarus</option>
                                    <option>Poland</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-building"></span></span></div>
                                <input class="form-control" type="text" name="city" placeholder="Reward">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-primary" type="submit">Add more</button>
                        </div>
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-light" type="submit">Save Branch</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>




















    <section class="section-sm">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Task Details</div>
                </div>
                <div class="panel-body">
                    <div class="row row-30">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2 task_type" name="task_type[]" style="width: 100%;">
                                    <option value="">Select One</option>
                                    @foreach ($task_type as $ct)
                                        <option value="{{$ct->task_classification_id}}">{{$ct->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2 task_actions" multiple="multiple" name="task_actions[]" style="width: 100%;">
                                    <option value="">Select One</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-building"></span></span></div>
                                <input class="form-control" type="text" name="reward" placeholder="Reward">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <!-- <button class="btn btn-primary" type="submit">Add more</button> -->
                            <button type="button" class="btn btn-danger btn-md pull-right remove_task" id="remove_task_0">Remove Task</button>
                            <button type="button" class="btn btn-info btn-md pull-right" id="add_task">Add more</button>
                        </div>
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-light" type="submit">Save Branch</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </form>












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
