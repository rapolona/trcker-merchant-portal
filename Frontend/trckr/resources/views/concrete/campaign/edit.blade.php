@extends('concrete.layouts.main')

@section('content')
    <form method="post" name="create_campaign" id="create_campaign" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="campaign_id" value="{{ $campaign['campaign_id'] }}">
        <section class="section-sm campaign-section">
            <div class="container-fluid">
                <div class="panel panel-nav">
                    <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                        <div class="col-md-4">
                            <div class="panel-title">Campaign Details</div>
                        </div>

                        <div class="col-md-4">
                            <div class="pull-right">
                                <strong>Status: </strong>
                                <span>{{ $campaign['status'] }}</span>
                                @if($campaign['status']=='DISABLED')
                                    <a class="btn btn-sm btn-danger" href="{{ url('campaign/status/enable/' . $campaign['campaign_id'] )}}">Activate</a>
                                @elseif($campaign['status']=='ONGOING')
                                    <a class="btn btn-sm btn-success" href="{{ url('campaign/status/disable/' . $campaign['campaign_id'] )}}">Disable</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pull-right">
                                <div class="custom-control custom-switch custom-switch-primary">
                                    <input class="custom-control-input" {{ ($campaign['permanent_campaign'])? 'checked=""' : '' }} type="checkbox" id="permanent_campaign" name="permanent_campaign" />
                                    <label class="custom-control-label" for="permanent_campaign">Permanent Campaign?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row row-30">
                            <div class="col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-institution"></span></span></div>
                                    <input required class="form-control  {{ $errors->first('campaign_name')? 'form-control-danger' : '' }}" type="text" value="{{ old('campaign_name', $campaign['campaign_name']) }}" name="campaign_name" placeholder="Campaign Name">
                                </div>
                                @if($errors->first('campaign_name'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('campaign_name') }}</span></span>
                                    </div>
                                @endif
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-black-tie"></span></span></div>
                                    <input required class="form-control  {{ $errors->first('budget')? 'form-control-danger' : '' }}" type="number" min="1" value="{{ old('budget', $campaign['budget']) }}" name="budget" id="budget" placeholder="Budget">
                                </div>
                                @if($errors->first('budget'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('budget') }}</span></span>
                                    </div>
                                @endif



                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="mdi-account-minus"></span></span></div>
                                    <input class="form-control  {{ $errors->first('audience_age_min')? 'form-control-danger' : '' }}" type="number" min="1" value="{{ old('audience_age_min', $campaign['audience_age_min']) }}" name="audience_age_min" id="audience_age_min" placeholder="Audience Minimum Age">
                                </div>
                                @if($errors->first('audience_age_min'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('audience_age_min') }}</span></span>
                                    </div>
                                @endif
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="mdi-account-plus"></span></span></div>
                                    <input class="form-control  {{ $errors->first('audience_age_max')? 'form-control-danger' : '' }}" type="number" min="1" value="{{ old('audience_age_max', $campaign['audience_age_max']) }}" name="audience_age_max" id="audience_age_max" placeholder="Audience Maximum Age">
                                </div>
                                @if($errors->first('audience_age_max'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('audience_age_max') }}</span></span>
                                    </div>
                                @endif
                                



                                <textarea name="campaign_description" required class="markdown padding-up {{ $errors->first('campaign_description')? 'form-control-danger' : '' }}" style="" data-markdown-footer="Footer placeholder">{{ old('campaign_description', $campaign['campaign_description']) }}</textarea>
                                @if($errors->first('campaign_description'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('campaign_description') }}</span></span>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-caret-square-o-right"></span></span></div>
                                    <select required class="form-control" name="campaign_type">
                                        <option value="">Campaign Type</option>
                                        @foreach ($campaign_type as $ct)
                                            @if(!empty($ct))
                                                <option {{ (old('campaign_type',$campaign['campaign_type'])==$ct->name)? 'selected="selected"' : '' }} value="{{$ct->name}}">{{$ct->name}}</option>
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
                                    <select required class="form-control" name="audience" id="audience">
                                        <option value="">Audience</option>
                                        <option {{ (old('audience', $campaign['audience'])=="All")? 'selected="selected"' : '' }} value="All">All</option>
                                        <option {{ (old('audience', $campaign['audience'])=="super_shopper")? 'selected="selected"' : '' }} value="super_shopper">Super Shopper</option>
                                    </select>
                                </div>
                                @if($errors->first('audience'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('audience') }}</span></span>
                                    </div>
                                @endif


                                
                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="mdi-gender-male-female"></span></span></div>
                                    <select class="form-control" name="audience_gender" id="audience_gender">
                                        <option value="">Target Gender</option>
                                        <option {{ (old('audience_gender', $campaign['audience_gender'])=="Male")? 'selected="selected"' : '' }} value="Male">Male</option>
                                        <option {{ (old('audience_gender', $campaign['audience_gender'])=="Female")? 'selected="selected"' : '' }} value="Female">Female</option>
                                    </select>
                                </div>
                                @if($errors->first('audience_gender'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('audience_gender') }}</span></span>
                                    </div>
                                @endif

                                @php
                                $audience_city = [];
                                foreach($campaign['audience_cities'] as $city){
                                    array_push($audience_city, $city->city_id);
                                }
                                $audience_city_old = (old('audience_city', $audience_city)) ? old('audience_city', $audience_city) : [];
                                @endphp
                                <div class="input-group form-group">
                                    <select class="form-control select2"  data-placeholder="  --Select Audience City--" id="audience_city" name="audience_city[]"  multiple="multiple">
                                        <option label="placeholder"></option>
                                        @foreach($cities as $newCity)

                                        <option {{ in_array($newCity->Id, $audience_city_old)? 'selected' : '' }} value="{{ $newCity->Id }}">{{ $newCity->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->first('audience_city'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('audience_city') }}</span></span>
                                    </div>
                                @endif




                                <div class="input-group form-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><span class="fa-calendar"></span></span></div>
                                    <input  required class="form-control  {{ $errors->first('start_date') || $errors->first('end_date')? 'form-control-danger' : '' }}" id="daterange1" type="text" value="{{ old('daterange', $campaign['daterange']) }}" name="daterange" placeholder="Date Range">
                                </div>
                                @if($errors->first('start_date'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('start_date') }}</span></span>
                                    </div>
                                @endif
                                @if($errors->first('end_date'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('end_date') }}</span></span>
                                    </div>
                                @endif
                                <div class="form-group col-md-5 {{ $errors->first('campaign_name')? 'form-control-danger' : '' }}">
                                    <p>Campaign Thumbnail</p>
                                    <div class="tower-file mt-3">
                                        <input class="tower-file-input" name="thumbnail_url" id="demo1" type="file">
                                        <label class="btn btn-xs btn-success" for="demo1"><span>Upload</span></label>
                                        @if(isset($campaign['signed_thumbnail_url']) && !empty($campaign['signed_thumbnail_url']))
                                            <div class="tower-file-details"><img class="null" src="{{ $campaign['signed_thumbnail_url'] }}"></div>
                                        @endif
                                    </div>
                                </div>
                                @if($errors->first('thumbnail_url'))
                                    <div class="tag-manager-container">
                                        <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('thumbnail_url') }}</span></span>
                                    </div>
                                @endif
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
                        <div class="col-md-6">
                            <div class="panel-title">Branch Details</div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-5 pull-right">
                                <div class="input-group form-group">
                                    <div class="custom-control custom-switch custom-switch-success">
                                        <input class="custom-control-input" type="checkbox" name="branch_id-nobranch" {{ old('branch_id-nobranch', $campaign['at_home_campaign']) > 0? 'checked=""' : '' }} id="branch_id-nobranch">
                                        <label class="custom-control-label" for="branch_id-nobranch">Do-It-At-Home</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 pull-right">
                                <input class="form-control" type="number" min="1" name="nobranch_submissions" id="nobranch_submissions" value="{{ old('nobranch_submissions', isset($campaign['branch_id-nobranch_value'])? $campaign['branch_id-nobranch_value'] : '' ) }}" placeholder="Max Submission" />
                            </div>
                        </div>
                    </div>
                    <div class="panel-header branchSection">
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
                            <div class="col-lg-2">
                                <input class="branch-input form-control" id="defaultMaxSubmission" type="number" min="1" value="" placeholder="Default Max Submission">
                            </div>
                        </div>
                    </div>
                    <div class="panel-body p-0 branchSection">
                        <div class="table-responsive scroller scroller-horizontal py-3" id="branch_table" >
                            <table class="table table-striped table-hover data-table"style="min-width: 800px">
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
                                    <th style="width:15%">Branch Submissions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($branches as $branch)
                                    @if(is_array(old('branch_id', $campaign['branch_id'])  ) && in_array($branch->branch_id, old('branch_id', $campaign['branch_id'])))
                                    <tr>
                                        <td style="width: 40px">
                                            <div class="custom-control custom-checkbox custom-checkbox-success">
                                                <input class="branch-input custom-control-input branch-id-checkbox" type="checkbox" name="branch_id[]" id="{{ $branch->branch_id }}" value="{{ $branch->branch_id }}"
                                                       @if(is_array(old('branch_id', $campaign['branch_id'])  ) && in_array($branch->branch_id, old('branch_id', $campaign['branch_id']))) checked @endif />
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
                                            @php
                                                $branchIdKey = array_search($branch->branch_id, old('branch_id', $campaign['branch_id']));
                                            @endphp
                                            <input
                                                @if($branchIdKey > -1)
                                                name="submission[]"
                                                @if(isset($campaign['submission'][$branchIdKey]))
                                                value="{{ $campaign['submission'][$branchIdKey] }}"
                                                @else
                                                value="{{ old('submission.'.$branchIdKey) }}"
                                                @endif
                                                @else
                                                disabled
                                                @endif
                                                class="branch-input form-control max-submission" type="number" min="1" placeholder="Max Submission">
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
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
                        <div id="taskBody">
                            @php 
                                $selected_task_ids = [];
                            @endphp
                            @for($x = 0; $x <= 5; $x++)
                                @if($x==0 || old('task_id.' . $x, (isset($campaign['tasks'][$x]->task_id))? $campaign['tasks'][$x]->task_id : ''))
                                    @php 
                                        $cur_task_id = old('task_id.' . $x, $campaign['tasks'][$x]->task_id );
                                        $man_value = old('man.' . $x, $x, $campaign['tasks'][$x]->mandatory );
                                        array_push($selected_task_ids, $campaign['tasks'][$x]->task_id);
                                    @endphp
                                    <div class="row row-30 task-container">
                                        <div class="col-md-2">
                                            <div class="custom-control custom-switch custom-switch-primary">
                                                <input class="custom-control-input mandatory" {{ ($man_value=='true' || $man_value=='')? 'checked' : '' }} type="checkbox" id="mandatory{{ $x }}" name="mandatory[]" />
                                                <label class="custom-control-label" for="mandatory{{ $x }}">Mandatory?</label>
                                                <input class="mandatory-buffer" type='hidden' value="{{ ($man_value=='true' || $man_value=='')? 'true' : 'false' }} " name='man[]'>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group task-action-container">
                                                <select class="form-control select2 task_actions" required name="task_id[]" style="width: 100%;">
                                                    <option value="">Select Task</option>
                                                    @foreach($tasks as $task)
                                                    <option {!! ($task->task_id==$cur_task_id)? 'selected=""' : '' !!} value="{{ $task->task_id }}">{{ $task->task_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-building"></span></span></div>
                                                <input class="form-control reward_amount" required type="number" min="1" name="reward_amount[]" value="{{old('reward_amount.' . $x, $campaign['tasks'][$x]->reward_amount)}}" placeholder="Reward">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-md remove_task" style="display: none" >
                                                <span class="fa-remove"></span>
                                            </button>
                                        </div>

                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="group-5 pull-right">
                            <button class="btn btn-primary" type="submit" id="submit">Save Campaign</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@stop


@section('js')
    <script type="text/javascript">

        let oTable = null,
            allPages = null;

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

        $(document).ready(function() {
            setTimeout(function () {
                oTable = $('.table').dataTable({
                    "destroy": true,
                    stateSave: true,
                    order: [[1, 'desc']],
                    "columnDefs": [
                        { "orderable": false, "targets": [0,2,3,4,5,6,7,8] }
                    ]
                });

                allPages = oTable.fnGetNodes();

            }, 2000);
        });


        let formFilters = new Object();
        var branches = [];
        $(document).on("change", "select.hustle-filter" , function(e) {
            let selected = $("select.hustle-filter :selected").map(function(i, el) {
                formFilters[$(el).parent().attr('name')] = $(el).val();
            }).get();

            $('.table').dataTable( {
                "destroy": true,
                "ordering" : false,
                "ajax": {
                    "type" : "GET",
                    "url" : "{{url('/campaign/merchant_branch')}}?" + $.param(formFilters),
                    "dataSrc": function ( json ) {
                        reinstateDataTable();
                        return json.data;
                    }
                },
                "columnDefs": [ {
                    "targets": -1,
                    "data": 0,
                    "render": function ( data, type, row, meta ) {
                        return '<input disabled class="branch-input form-control max-submission" type="number" min="1" name="submissions[]" placeholder="Max Submission">';
                    }
                },{
                    "targets": 0,
                    "data": 0,
                    "bSortable": false,
                    "render": function ( data, type, row, meta ) {
                        return '<div class="branch-input custom-control custom-checkbox custom-checkbox-light"><input class="custom-control-input branch-id-checkbox" type="checkbox" name="branch_id[]" id="' + data + '" value="' + data + '"  /><label class="custom-control-label" for="' + data +'"></label></div>';
                    }
                } ]
            } );

        });

        function reinstateDataTable(){
            setTimeout(function () {
                oTable = $('.table').dataTable({
                    "destroy": true,
                    stateSave: true,
                    order: [[1, 'desc']],
                    "columnDefs": [
                        { "orderable": false, "targets": [0,2,3,4,5,6,7,8] }
                    ]
                });

                allPages = oTable.fnGetNodes();
            }, 800);
        }


        $(document).on("click", "#selectAll" , function() {
            let table= $(this).closest('table');
            $('td input:checkbox',allPages).prop('checked',this.checked);
            if (this.checked) {
                $('td input.max-submission', allPages).removeAttr('disabled');
                $('td input.max-submission', allPages).attr('name', 'submission[]');
                $('td input.max-submission', allPages).attr('required', true);
                $('td input.max-submission', allPages).val($('#defaultMaxSubmission').val() || 1);
            }else{
                $('td input.max-submission', allPages).attr('disabled', true);
                $('td input.max-submission', allPages).removeAttr('name');
                $('td input.max-submission', allPages).removeAttr('required');
                $('td input.max-submission', allPages).val('');
            }
        });

        $(document).on("change", "input#defaultMaxSubmission" , function() {
            $( "input.max-submission:enabled", allPages).val( $('#defaultMaxSubmission').val() );
        });

        $(document).on("change", "input.branch-id-checkbox" , function() {
            let tableRow= $(this).closest('tr');
            if (this.checked) {
                $('td input.max-submission', tableRow).removeAttr('disabled');
                $('td input.max-submission', tableRow).attr('name', 'submission[]');
                $('td input.max-submission', tableRow).attr('required', true);
                $('td input.max-submission', tableRow).val($('#defaultMaxSubmission').val());
            }else{
                $('td input.max-submission', tableRow).attr('disabled', true);
                $('td input.max-submission', tableRow).removeAttr('name');
                $('td input.max-submission', tableRow).removeAttr('required');
                $('td input.max-submission', tableRow).val('');
            }
        });


        $(document).on("click", ".remove_task" , function() {
            $(this).closest('.task-container').remove();
        });

        $(document).on("change", ".task_actions", function(){
            var count = $(".task_actions").select2('data').length;
        });

        let selected_task_ids = {!! json_encode($selected_task_ids) !!};
        $(document).on("change", ".task_type" , function() {
            let task_action = $(this).closest('.task-container').find('.task_actions');
            let currentTaskIdKey = $(this).attr('data-taskkey');
            let selectedTaskId = selected_task_ids[parseInt(currentTaskIdKey)];
            $(task_action).empty();
            $.ajax({
                type:'GET',
                url: "{{url('/campaign/campaign_type/task?task_id=')}}" + this.value,
                cache:true,
                contentType: false,
                processData: false,
                success: (data) => {
                    $(data.file).each(function(){
                        let isSel = (this.task_id==selectedTaskId)? 'selected="selected"' : '' ;
                        $(task_action).append('<option '+isSel+' value="'+ this.task_id +'">' + this.task_name + '</option>');
                    });
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        $(document).ready(function (e) {
            $("#branch_id-nobranch").change(function(){
                if (this.checked) {
                    $('.branchSection').hide();
                    $(".branch-input:checkbox").each(function(){
                        $(this).prop("checked",false);
                        $(this).change();
                        $('#selectAll').prop("checked",false);
                    });
                    $('#nobranch_submissions').show();
                    $('#nobranch_submissions').attr('required', true);
                }
                else{
                    $('.branchSection').show();
                    $('#nobranch_submissions').hide();
                    $('#nobranch_submissions').removeAttr('required');
                    $('#nobranch_submissions').val('');
                }

            });

            $("#submit").click(function(){
            });

            $("#create_campaign").submit(function(e){
                //e.preventDefault();
            });

            $("#back").click(function(){
                window.location.href = "{{url('/campaign/view')}}";
            });

            $(document).on('click', '#add_task', function(){
                let index = $(".task-container").length;
                if (index >= 5) {
                    $.alert({
                        title: 'Hustle',
                        type: 'red',
                        content: 'The maximum limit of task classifications per campaign is 5',
                    });
                    return;
                }

                let clonedTask = $("div.task-container:first").clone();

                $('.task-action-container',clonedTask).html('<select required class="form-control select2 task_actions" name="task_id[]" style="width: 100%;">' +
                    '<option value="">Select Task</option>' +
                    '</select>');
                $('#taskBody').append(clonedTask);
                $("div.task-container .remove_task:last").show();
                $('#taskBody select.task_actions:last').select2({ //apply select2 to my element
                    placeholder: "Select One",
                    //allowClear: true
                });
            });



            $('#audience_city').tagsInput({
                placeholder:'Target City',
                'autocomplete': {
                    source: [
                        'Caloocan',
                        'Makati',
                        'Mandaluyong',
                        'Taguig'
                    ]
                }

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
        $(document).ready(function (e) {
            setTimeout(function(){ $(".task_type").change(); }, 3000);
            $("#branch_id-nobranch").change();

            $("form#create_campaign").submit(function(e){
                $('.table').DataTable().destroy();
                let isNoBranch = $("#branch_id-nobranch").is(':checked');
                let budget = parseInt($("#budget").val());
                let totalSubmission = 0;
                let totalRewards = 0;
                if(isNoBranch){
                    totalSubmission = parseInt($("#nobranch_submissions").val());
                }else{
                    $("input.max-submission").each(function(){
                        totalSubmission += parseInt($(this).val()) || 0;
                    });

                    if(totalSubmission==0)
                    {
                        reinstateDataTable();
                        $.alert({
                            title: 'Hustle',
                            type: 'red',
                            content: 'Please select at least one branch',
                        });
                        return false;
                    }
                }

                $("input.reward_amount").each(function(){
                    totalRewards += parseInt($(this).val()) || 0;
                });

                let computeBudget = totalSubmission * totalRewards;

                if(budget < computeBudget){
                    reinstateDataTable();
                    $.alert({
                        title: 'Hustle',
                        type: 'red',
                        content: 'You are over your budget please adjust your rewards or max submissions or increase your budget <br /> ' +
                            'Current Budget : ' + budget + '<br />' + 
                            'Total Budget Needed : ' + computeBudget + '<br />' + 
                            '',
                    });
                    return false;
                }



                return true;
            });

        });

    </script>
@stop
