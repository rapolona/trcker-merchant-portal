
@extends('adminlte::page')

@section('title', 'Trckr | View Campaign')

@section('content_header')
    <h1>View Campaign</h1>
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
    <p>View Campaign</p>

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
                                    <label for="campaign_name" class="col-sm-4 col-form-label">Campaign Name</label>
                                    <div class="col-sm-8">
                                        <span>{{! empty($campaign->campaign_name) ? $campaign->campaign_name : ""}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_description" class="col-sm-4 col-form-label">Campaign Description</label>
                                    <div class="col-sm-8">
                                        <span>{{! empty($campaign->campaign_description) ? $campaign->campaign_description : ""}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branches" class="col-sm-4 col-form-label">Branches</label>
                                    <div class="col-sm-8">
                                        @foreach ($campaign->branches as $k)
                                            <span>{{$k->name}}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="audience" class="col-sm-4 col-form-label">Allow Super Shopper</label>
                                    <div class="col-sm-8">
                                        <span>{{ ($campaign->super_shoppers) ? "Yes" : "No"}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="audience" class="col-sm-4 col-form-label">Allow Everyone</label>
                                    <div class="col-sm-8">
                                        <span>{{ ($campaign->allow_everyone) ? "Yes" : "No"}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg-6">
                            <div class="form-group row">
                                    <label for="campaign_type" class="col-sm-4 col-form-label">Campaign Status</label>
                                    <div class="col-sm-8">
                                        @if ($campaign->status == 1) 
                                            <span>Ongoing</span>
                                        @endif
                                        @if ($campaign->status == 0) 
                                            <span>Completed</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_type" class="col-sm-4 col-form-label">Campaign Type</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->campaign_type}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="reward" class="col-sm-4 col-form-label">Budget</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->budget}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="reward" class="col-sm-4 col-form-label">Reward</label>
                                    <div class="col-sm-8">
                                        <span>{{( ! empty($campaign->reward)) ? $campaign->reward : ""}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="duration" class="col-sm-4 col-form-label">Duration</label>
                                    <div class="col-sm-8">
                                        <span>{{$campaign->start_date}} to {{$campaign->end_date}}</span>                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                      
                </form>
            </div>

            @foreach ($campaign_detail->campaign_tasks as $campaign_task)
            <div class="card">
                <div class="card-body">  
                    <div class="row">
                        <label for="company_name" class="col-sm-2 col-form-label">Task Classification Name</label>
                        <span class="col-sm-10  col-form-label">{{ ($campaign_task->task_classification_name) ? $campaign_task->task_classification_name : ''}}</span>
                    </div>
                </div>
            </div>
            @foreach ($campaign_task->tasks as $task)
            <div class="card">
                <div class="card-body">           
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                    
                    <div class="form-group row">
                        <label for="company_name" class="col-sm-2 col-form-label">Task Name</label>
                        <div class="col-sm-10">
                            <span>{{ ($task->task_name) ? $task->task_name : ''}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_name" class="col-sm-2 col-form-label">Task Description</label>
                        <div class="col-sm-10">
                            <span>{{ ($task->task_description) ? $task->task_description : ''}}</span>
                        </div>
                    </div>
                    <div class="form-group row" id="image_container">
                        <label for="company_name" class="col-sm-2 col-form-label">Banner Image</label>
                        <div class="col-sm-10">
                            <img src="{{ ! empty($task->task_banner_image) ? $task->task_banner_image : ''}}"/>
                        </div>
                    </div> 
                    <div id="{{$task->task_id}}"></div>
                </div>
            </div>
            @endforeach
            @endforeach
            <div class="card-footer">
                <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                    <button type="edit" class="btn btn-block btn-primary btn-lg pull-right" id="edit">Edit</button>  
                    <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                </div>
            </div>  
        </div>
    </div>
    
@stop

@section('css')
    
@stop

@section('js')
    <script type="text/javascript" src="/vendor/trckr/trckr.js"></script>
    <script type="text/javascript">
        $(document).ready(function (e) { 

            $("#edit").click(function(){
                window.location.href = "/campaign/edit?campaign_id={{$campaign->campaign_id}}";
            });
            $("#back").click(function(){
                window.location.href = "/campaign/view";
            });
        });
    </script>
    <script type="text/javascript" src="/vendor/form-builder/form-builder.min.js"></script>
    <script type="text/javascript" src="/vendor/form-builder/form-render.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function (e) { 
            let fields = [{
                    label: 'True or False',
                    className: 'TRUE OR FALSE',
                    placeholder: 'True or False',
                    type: 'radio-group',
                    subType: 'true-or-false',
                    values: [{
                        label: 'True',
                        value: 'tof-' + Math.floor((Math.random() * 9999) + 1),
                        }, {
                        label: 'False',
                        value: 'tof-' + Math.floor((Math.random() * 9999) + 1),
                    }]
                },{
                    label: 'Take a Photo',
                    className: 'IMAGE',
                    placeholder: 'Take a Photo',
                    type: 'file'
                },{
                    label: 'Integer',
                    className: 'INTEGER',
                    placeholder: 'Integer',
                    type: 'number'
                },{
                    label: 'Checkbox',
                    className: 'CHECKBOX',
                    placeholder: 'Checkbox',
                    type: 'checkbox-group'
                },{
                    label: 'Single Select Dropdown',
                    className: 'SINGLE SELECT DROPDOWN',
                    placeholder: 'Single Select Dropdown',
                    type: 'select'
                },{
                    label: 'Calculated Value',
                    className: 'CALCULATED VALUE',
                    placeholder: 'Calculated Value',
                    type: 'number'
                },{
                    label: 'Amount',
                    className: 'CURRENCY',
                    placeholder: 'Amount',
                    type: 'number'
                },{
                    label: 'Text',
                    className: 'TEXT',
                    placeholder: 'Text',
                    type: 'textarea'
                },{
                    label: 'Date',
                    className: 'DATEFIELD',
                    placeholder: 'date',
                    type: 'date'
                },{
                    label: 'Float',
                    className: 'FLOAT',
                    placeholder: 'float',
                    type: 'number'
                }
            ];

            var addField = new Array();
            //never mix javascript with laravel data :) zzz

            @foreach ($campaign_detail->campaign_tasks as $campaign_task)
                @foreach ($campaign_task->tasks as $task)
                    @foreach ($task->task_questions as $question)

                        var temp = findObjectInArrayByProperty(fields, "className", "{{$question->required_inputs}}");
                        
                        temp.name = "{{$question->task_question_id}}";
                        temp.label = "{{$question->question}}";
                        console.log("{{json_encode($question->task_question_choices)}}");
                        @if (count($question->task_question_choices) > 0)
                            tempvalues = new Array();
                            //console.log("{{count($question->task_question_choices)}}");
                            @foreach($question->task_question_choices as $values)
                                tempvalues.push(
                                    {
                                        "label": "{{$values->choices_value}}",
                                        "value": "{{$values->choices_id}}"
                                    }
                                );
                            @endforeach
                            temp.values = tempvalues;
                        @endif
                        console.log(temp);
                        
                        addField.push(temp);
                    @endforeach
                
                    var options = {
                        container,
                        addField,
                        dataType: 'json',
                        fields: fields,
                    };
                    //$(container).formRender(options);
                
                    var formData = JSON.stringify(addField);

                    var container = $('#{{$task->task_id}}');

                    var formRenderOpts = {
                        container,
                        formData,
                        fields: fields,
                        dataType: 'json'
                    };

                    console.log(formRenderOpts);
                    $(container).formRender(formRenderOpts);
                @endforeach
            @endforeach
        });
    </script>
@stop