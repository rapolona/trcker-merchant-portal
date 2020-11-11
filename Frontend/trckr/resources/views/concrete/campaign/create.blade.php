@extends('concrete.layouts.main')

@section('content')
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
                                        @foreach ($campaign_type as $ct)
                                            <option value="{{$ct->campaign_type_id}}">{{$ct->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                                    <select class="form-control" name="filter-purchases">
                                        <option value="0">Target Audience</option>
                                        <option value="super_shopper">Super Shopper</option>
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
            <div class="panel">
                <div class="panel-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel-title"><span class="panel-icon fa-tasks"></span> <span>Select Branches</span>
                            </div>
                        </div>
                    </div>
                    <div class="row row-30">
                        <div class="col-lg-2">
                            @if(isset($filters->business_type))
                                <select class="select2 hustle-filter" data-placeholder="Business Type" name="business_type">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
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
                                <select class="select2 hustle-filter" data-placeholder="Store Type" name="store_type">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
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
                                <select class="select2 hustle-filter" data-placeholder="Brand" name="brand">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
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
                                <select class="select2 hustle-filter" data-placeholder="Province" name="province">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
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
                                <select class="select2 hustle-filter" data-placeholder="City" name="city">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
                                    @foreach ($filters->city as $option)
                                        @if(!empty($option))
                                            <option {{ (isset($selectedFilter['city']) && $selectedFilter['city']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-lg-2 text-right">

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
                                            <input class="custom-control-input" type="checkbox" name="branch_id[]" value="{{ $branch->branch_id }}" />
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
    </section>

    <section class="section-sm">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Task Details</div>

                </div>
                <div class="panel-body">
                    <div id="taskBody">
                        <div class="row row-30 task-container">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control task_type" name="task_type[]" style="width: 100%;">
                                        <option value="">Select One</option>
                                        @foreach ($task_type as $ct)
                                            <option value="{{$ct->task_classification_id}}">{{$ct->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group task-action-container">
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
                                <button type="button" class="btn btn-danger btn-md pull-right remove_task" style="display: none" >Remove Task</button>
                            </div>

                        </div>
                    </div>

                    <div>
                        <button type="button" class="btn btn-info btn-md pull-right" id="add_task">Add more</button>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-light" type="submit">Save Campaign</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </form>
@stop

@section('js')
    <script type="text/javascript">

        $(document).ready(function (e) {
            $('#selectAll').click(function (e) {
                let table = $(e.target).closest('table');
                $('td input:checkbox', table).prop('checked', this.checked);
            });

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
                $(this).closest('.task-container').remove();
            });

            $(document).on("change", ".task_actions", function(){
                var count = $(".task_actions").select2('data').length;
            });

            $(document).on("change", ".task_type" , function() {

                let task_action = $(this).closest('.task-container').find('.task_actions');
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

            $(document).on('click', '#add_task', function(){
                //$("#taskBody select.task_actions").select2("destroy");
                let clonedTask = $("div.task-container:first").clone();

                $('.task-action-container',clonedTask).html('<select class="form-control select2 task_actions" multiple="multiple" name="task_actions[]" style="width: 100%;">' +
                    '<option value="">Select One</option>' +
                    '</select>');
                $('#taskBody').append(clonedTask);
                $("div.task-container .remove_task:last").show();
                $('#taskBody select.task_actions:last').select2({ //apply select2 to my element
                    placeholder: "Select One",
                    allowClear: true
                });
            })


            $("#create_campaign").submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                post("{{url('/campaign/create_campaign')}}", "Create Campaign", "submit", formData, "{{url('/campaign/view')}}");
            });

            $("#back").click(function(){
                window.location.href = "{{url('/campaign/view')}}";
            });
        });
    </script>
@stop
