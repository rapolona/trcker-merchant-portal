@extends('concrete.layouts.main')

@section('content')
<form method="post" name="create_campaign" id="create_campaign">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="section-sm campaign-section">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Campaign Details</div>
                </div>
                <div class="panel-body">
                    <div class="row row-30">
                        <div class="col-md-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-institution"></span></span></div>
                                <input class="form-control  {{ $errors->first('campaign_name')? 'form-control-danger' : '' }}" type="text" value="{{ old('campaign_name', $campaign->campaign_name) }}" name="campaign_name" placeholder="Campaign Name">
                            </div>
                            @if($errors->first('campaign_name'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('campaign_name') }}</span></span>
                                </div>
                            @endif
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-black-tie"></span></span></div>
                                <input class="form-control  {{ $errors->first('budget')? 'form-control-danger' : '' }}" type="text" value="{{ old('budget', $campaign->budget) }}" name="budget" placeholder="Budget">
                            </div>
                            @if($errors->first('budget'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('budget') }}</span></span>
                                </div>
                            @endif
                            <textarea name="campaign_description" class="markdown padding-up {{ $errors->first('campaign_description')? 'form-control-danger' : '' }}" style="" data-markdown-footer="Footer placeholder" value="">{{ old('campaign_description', ( ! empty($campaign->campaign_description) ? $campaign->campaign_description : 'Put your campaign description here!')) }}</textarea>
                            @if($errors->first('campaign_description'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('campaign_description') }}</span></span>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                                <select class="form-control" name="campaign_type">
                                    <option value="">Campaign Type</option>
                                    @foreach ($campaign_type as $ct)
                                        @if(!empty($ct))
                                            <option {{ (old('campaign_type', $campaign->campaign_type)==$ct->name)? 'selected="selected"' : '' }} value="{{$ct->name}}">{{$ct->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->first('campaign_type'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('campaign_type') }}</span></span>
                                </div>
                            @endif
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                                <select class="form-control" name="audience" id="audience">
                                    <option value="">Audience</option>
                                    <option {{ (old('audience')=="All" || $campaign->super_shoppers == 1)? 'selected="selected"' : '' }} value="All">All</option>
                                    <option {{ (old('audience')=="super_shopper" || $campaign->allow_everyone == 1)? 'selected="selected"' : '' }} value="super_shopper">Super Shopper</option>
                                </select>
                            </div>
                            @if($errors->first('audience'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('audience') }}</span></span>
                                </div>
                            @endif
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-calendar"></span></span></div>
                                <input class="form-control  {{ $errors->first('start_date') || $errors->first('end_date')? 'form-control-danger' : '' }}" id="daterange1" type="text" value="{{ old('daterange', $campaign->daterange) }} "name="daterange" placeholder="Date Range">
                            </div>
                            @if($errors->first('start_date') || $errors->first('end_date'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('start_date') }}{{ $errors->first('end_date') }}</span></span>
                                </div>
                            @endif
                            <div class="input-group form-group">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-institution"></span></span></div>
                                <input class="form-control  {{ $errors->first('thumbnail_url')? 'form-control-danger' : '' }}" type="text" value="{{ old('thumbnail_url', $campaign->thumbnail_url) }}" name="thumbnail_url" placeholder="Campaign Thumbnail">
                            </div>
                            @if($errors->first('thumbnail_url'))
                                <div class="tag-manager-container">
                                    <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('thumbnail_url') }}</span></span>
                                </div>
                            @endif

                            <div class="form-group col-md-12" style="border:1px solid #ddd; border-radius: 0.25rem; padding: 10px;">
                              <p>Campaign Thumbnail</p>
                              <div class="tower-file mt-3">
                                <input class="tower-file-input" id="demo1" type="file">
                                <label class="btn btn-xs btn-success" for="demo1"><span>Upload</span></label>
                              </div>
                            </div>

                            <div class="input-group form-group">
                                <div class="custom-control custom-switch custom-switch-success">
                                    <input class="custom-control-input" {{ (old('branch_id-nobranch')=="on")? 'checked="checked"' : '' }} type="checkbox" name="branch_id-nobranch" id="customSwitch3" />
                                    <label class="custom-control-label" for="customSwitch3">Do-It-At-Home
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-sm campaign-section">
        <div class="container-fluid">
            <div class="panel">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Branch Details</div>
                </div>
                <div class="panel-header">
                    <div class="row row-30">
                        <div class="col-lg-2">
                            @if(isset($branch_filters->business_type))
                            <select class="select2 hustle-filter" data-placeholder="Business Type" name="business_type">
                                <option label="placeholder"></option>
                                <option value="all">ALL</option>
                                @foreach ($branch_filters->business_type as $option)
                                    @if(!empty($option))
                                    <option {{ (old('busness_type')==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ (old('business_type')) }}{{ $option }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="col-lg-2">
                            @if(isset($branch_filters->store_type))
                                <select class="select2 hustle-filter" data-placeholder="Store Type" name="store_type">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
                                    @foreach ($branch_filters->store_type as $option)
                                        @if(!empty($option))
                                        <option {{ (old('store_type')==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ (old('store_type')) }}{{ $option }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-lg-2">
                            @if(isset($branch_filters->brand))
                                <select class="select2 hustle-filter" data-placeholder="Brand" name="brand">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
                                    @foreach ($branch_filters->brand as $option)
                                        @if(!empty($option))
                                        <option {{ (old('brand')==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ (old('brand')) }}{{ $option }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-lg-2">
                            @if(isset($branch_filters->province))
                                <select class="select2 hustle-filter" data-placeholder="Province" name="province">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
                                    @foreach ($branch_filters->province as $option)
                                        @if(!empty($option))
                                        <option {{ (old('province')==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ (old('province')) }}{{ $option }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-lg-2">
                            @if(isset($branch_filters->city))
                                <select class="select2 hustle-filter" data-placeholder="City" name="city">
                                    <option label="placeholder"></option>
                                    <option value="all">ALL</option>
                                    @foreach ($branch_filters->city as $option)
                                        @if(!empty($option))
                                        <option {{ (old('city')==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ (old('city')) }}{{ $option }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive scroller scroller-horizontal py-3" id="branch_table" >
                        <table class="table table-striped table-hover data-table"style="min-width: 800px">
                            <thead>
                            <tr>
                                <td style="width: 40px">
                                    <div class="custom-control custom-checkbox custom-checkbox-success">
                                        <input class="custom-control-input" type="checkbox" id="chkAll"/>
                                        <label class="custom-control-label" for="chkAll"></label>
                                    </div>
                                </td>
                                <th>Name</th>
                                <th>BusinessType</th>
                                <th>StoreType</th>
                                <th>Brand</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Region</th>
                                <th style="width:15%">Branch Submissions }</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($branches as $branch)
                                @php
                                    $branch_id = $branch->branch_id;
                                    $temp = current(array_filter($campaign->branches, function($e) use($branch_id){ return $e->branch_id == $branch_id; }));
                                    $temp_branch = ( ! empty($temp)) ? $temp->branch_id : NULL;
                                    $temp_respondent_count = ( ! empty($temp)) ? $temp->respondent_count : NULL;                 
                                @endphp
                                <tr>
                                    <td style="width: 40px">
                                        <div class="custom-control custom-checkbox custom-checkbox-success">
                                            <input class="branch-input custom-control-input" {{ (old('branch_id-' . $branch->branch_id)=="on" || $branch->branch_id == $temp_branch )? 'checked="checked"' : '' }} type="checkbox" name="branch_id-{{ $branch->branch_id }}" id="{{ $branch->branch_id }}" />
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
                                    <td class="text-right" width="15%">
                                        <input class="branch-input form-control {{ $errors->first('submissions-' . $branch->branch_id)? 'form-control-danger' : '' }}" type="text" name="submissions-{{ $branch->branch_id }}" value="{{ old('submissions-' . $branch->branch_id, $temp_respondent_count) }}" placeholder="Max Submission">
                                        @if($errors->first('submissions-' . $branch->branch_id))
                                            <div class="tag-manager-container">
                                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('submissions-' . $branch->branch_id) }}</span></span>
                                            </div>
                                        @endif
                                    </td>     
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive scroller scroller-horizontal py-3">
                        <table class="table table-striped table-hover" style="min-width: 800px">
                            <tbody>
                                <td style="width: 40px">
                                    <div class="custom-control custom-checkbox custom-checkbox-success">
                                        <input class="custom-control-input" {{ (old('branch_id-nobranch')=="on")? 'checked="checked"' : '' }} type="checkbox" name="branch_id-nobranch" id="branch_id-nobranch" />
                                        <label class="custom-control-label" for="branch_id-nobranch"></label>
                                    </div>
                                </td>
                                <td>No Branch</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right" width="15%">
                                    <input class="form-control {{ $errors->first('submissions-nobranch')? 'form-control-danger' : '' }}" type="text" name="submissions-nobranch" value="{{ old('submissions-nobranch') }}"placeholder="Max Submission">
                                    @if($errors->first('submissions-nobranch'))
                                        <div class="tag-manager-container">
                                            <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('submissions-nobranch') }}</span></span>
                                        </div>
                                    @endif
                                </td>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-sm campaign-section">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Task Details</div>
                    <button class="btn btn-sm" type="button" id="add_task"><span class="fa-plus">Add more task</span></button>
                </div>
                <div class="panel-body">
                    @php $task_count = 0;
                    @endphp
                    @foreach($campaign->tasks as $campaign_tasks)
                        
                        <div class="row task_container">
                            <div class="col col-md-3">
                                <div class="form-group row">
                                    <label for="task_type" class="col-sm-2 col-form-label">Task Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 task_type" name="task_type[]">
                                            <option value="">Select One</option>
                                            @foreach ($task_type as $ct)
                                            <option {{ (old('task_type.' . $task_count, $campaign_tasks->task_classification_id)==$ct->task_classification_id)? 'selected="selected"' : '' }} value="{{$ct->task_classification_id}}">{{$ct->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($errors->first('task_type.' . $task_count))
                                        <div class="tag-manager-container">
                                            <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('task_type.' . $task_count) }}</span></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col col-md-4">
                                <div class="form-group row">
                                    <label for="task_actions" class="col-sm-2 col-form-label">Task Actions</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 task_actions" name="task_actions[]">
                                            <option value="">Select One</option>
                                            @foreach($tasks_per_type[$campaign_tasks->task_classification_id] as $tasks)
                                                @php
                                            
                                                @endphp
                                                <option value="{{$tasks->task_id}}" {{(old('task_actions.' . $task_count, $campaign_tasks->task_id)) ? 'selected': ''}}>{{$tasks->task_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($errors->first('task_actions.' . $task_count))
                                        <div class="tag-manager-container">
                                            <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('task_actions.' . $task_count) }}</span></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-building"></span></span></div>
                                    <input class="form-control" type="text" name="reward[]" value="{{ old('reward.' . $task_count, $campaign_tasks->reward_amount) }}" placeholder="Reward" />
                                </div>
                                @if($errors->first('reward.' . $task_count))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('reward.' . $task_count) }}</span></span>
                                    </div>
                                @endif
                            </div>
                            <div class="col col-md-2">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-md btn-danger remove_task" id="remove_task_0"><span class="fa-remove"></span></button>
                                </div>
                            </div>
                        </div>
                        @php 
                        $task_count += 1;
                        @endphp
                    @endforeach
                </div>

                <div class="panel-footer">
                  <div class="group-5">
                     <button class="btn btn-success" type="submit" id="submit">Save Campaign</button>
                  </div>
                </div>
            </div>
        </div>
    </section>
</form>
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
                var endDate = new Date(selectedDate.getTime());
                $("#input_end_date").datepicker( "option", "minDate", endDate );
            }
        });
        $('#input_end_date').datepicker({
            minDate: 0,
            dateFormat: 'yy-mm-dd',
            onSelect: function(date) {
                var selectedDate = new Date(date);
                var startDate = new Date(selectedDate.getTime());
                $("#input_start_date").datepicker( "option", "maxDate", startDate );
            }
        });

        let formFilters = new Object();
        var branches = [];
        $(document).on("change", "select.hustle-filter" , function(e) {
            let selected = $("select.hustle-filter :selected").map(function(i, el) {
                formFilters[$(el).parent().attr('name')] = $(el).val();
            }).get();

            $(document).ready(function() {
                $('#chkAll').click(function() {
                    if ($(this).prop("checked") == true) 
                        $(".branch-input:checkbox").each(function(){
                            $(this).prop("checked", true);
                        });
                    else 
                        $(".branch-input:checkbox").each(function(){
                            $(this).prop("checked", false);
                        });
                });

                $('.table').DataTable( {
                    "destroy": true,
                    //"ajax": "{{url('/test.json')}}?" + $.param(formFilters)/*,
                    "ajax": "{{url('/campaign/merchant_branch')}}?" + $.param(formFilters),
                    "columnDefs": [ {
                        "targets": -1,
                        "data": 0,
                        "render": function ( data, type, row, meta ) {
                            return '<input class="branch-input form-control" type="text" name="submissions[' + data + ']" placeholder="Max Submission">';
                        }
                    },{
                        "targets": 0,
                        "data": 0,
                        "render": function ( data, type, row, meta ) {
                            return '<div class="branch-input custom-control custom-checkbox custom-checkbox-success"><input class="custom-control-input" type="checkbox" name="branch_id[' + data + ']" id="' + data + '" /><label class="custom-control-label" for="' + data +'"></label></div>';
                        }
                    }]
                } );
            
                $('#example tbody').on( 'click', 'button', function () {
                    var data = table.row( $(this).parents('tr') ).data();
                    alert( data[0] +"'s salary is: "+ data[ 5 ] );
                } );
            } );
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
                        $(task_action).append('<option value="' + this.task_classification_id + "|" + this.task_id +'">' + this.task_name + '</option>');
                    });
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        $(document).ready(function (e) {          
            $("#branch_id-nobranch").change(function(){
                if (this.checked) {
                    $(".branch-input:checkbox").each(function(){
                        console.log(this);
                        $(this).attr("id","disabledInput");
                    });
                    $(".branch-input:text").each(function(){
                        console.log(this);
                        $(this).prop("disabled", true);
                    });
                }
                else $(".branch-input ").each(function(){
                    $(".branch-input:checkbox").each(function(){
                        console.log(this);
                        $(this).removeAttr("id","disabledInput");
                    });
                    $(".branch-input:text").each(function(){
                        console.log(this);
                        $(this).prop("disabled", false);
                    });
                });
            }); 

            $("#submit").click(function(){
            });

            $("#create_campaign").submit(function(e){
                //e.preventDefault();
            });

            $("#back").click(function(){
                window.location.href = "{{url('/campaign/view')}}";
            });

            $("#add_task").click(function(){
                var index = $(".task_container").length;
                if (index >= 5) {
                    alert("The maximum limit of task classifications per campaign is 5");
                    return;
                }

                var html = '<div class="row task_container">';
                html += '<div class="col col-md-3">';
                html += '<div class="form-group row">';
                html += '<label for="task_type" class="col-sm-2 col-form-label">Task Type</label>';
                html += '<div class="col-sm-10">';
                html += '<select class="form-control select2 task_type" name="task_type[]">';
                html += '<option value="">Select One</option>';
                @foreach ($task_type as $ct)
                html += '<option value="{{$ct->task_classification_id}}">{{$ct->name}}</option>';
                @endforeach
                html += '</select>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col col-md-4">';
                html += '<div class="form-group row">';
                html += '<label for="task_actions" class="col-sm-2 col-form-label">Task Actions</label>';
                html += '<div class="col-sm-10">';
                html += '<select class="form-control select2 task_actions" name="task_actions[]">';
                html += '<option value="">Select One</option>';
                html += '</select>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col col-md-3">';
                html += '<div class="input-group">';
                html += '<div class="input-group-prepend"><span class="input-group-text"><span class="fa-building"></span></span></div>';
                html += '<input class="form-control" type="text" name="reward[]" placeholder="Reward">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col col-md-2">';
                html += '<div class="btn-group" role="group" aria-label="Basic example">';
                html += '<button type="button" class="btn btn-md btn-danger remove_task" id="remove_task_0"><span class="fa-remove"></span></button>';
                html += '</div>';
                html += '</div>';
                html += '</div>';

                $(html).insertAfter(".task_container:last");
                $(".task_actions").select2();
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