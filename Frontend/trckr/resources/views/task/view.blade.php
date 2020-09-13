@extends('adminlte::page')

@section('title', 'Trckr | View Task')

@section('content_header')
    <h1>View Task</h1>
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

    <p>View Task</p>

    <div class="card">
        <form class="form-vertical" id="create_task">
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
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Subject Level</label>
                    <div class="col-sm-10">
                        <span>{{ ($task->subject_level) ? $task->subject_level : ''}}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Classification</label>
                    <div class="col-sm-10">
                        <span>
                            @foreach ($task_classification as $ta)
                                @if  ($ta->task_classification_id == $task->task_classification_id)
                                    {{$ta->name}}
                                @endif
                            @endforeach
                        </span>
                    </div>
                </div>
                <div class="form-group row" id="image_container">
                    <label for="company_name" class="col-sm-2 col-form-label">Banner Image</label>
                    <div class="col-sm-10">
                        <input type="hidden" class="custom-file-input" id="image" name="banner_image" value="{{ ($task->banner_image) ? $task->banner_image : ''}}" >
                        @php
                            $temp = $task->banner_image;
                            echo '<img src="' . $temp . '" />';
                        @endphp
                    </div>
                </div>

                <div class="build-wrap"></div>
            </div>
            <div class="card-footer">
                <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-block btn-primary btn-lg pull-right" id="edit">Edit Details</button>  
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
    <script type="text/javascript" src="/vendor/form-builder/form-render.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function (e) { 
            /*
            var image = new Image();
            image.src = $("#image").val();
            $(image).appendTo("#image_container");
            alert(1);
            */

            let fields = [{
                    label: 'True or False',
                    className: 'true_or_false',
                    placeholder: 'True or False',
                    type: 'radio-group',
                    subType: 'true-or-false',
                    values: [{
                        label: 'True',
                        value: 'tof-' + Math.floor((Math.random() * 9999) + 1),
                        }, {
                        label: 'False',
                        value: 'tof-' + Math.floor((Math.random() * 9999) + 1),
                    }],
                    icon: '🌟',
                },{
                    label: 'Take a Photo',
                    className: 'PHOTO',
                    placeholder: 'Take a Photo',
                    type: 'file',
                    icon: '🌟',
                },{
                    label: 'Integer',
                    className: 'INTEGER',
                    placeholder: 'Integer',
                    type: 'number',
                    icon: '🌟',
                },{
                    label: 'Checkbox',
                    className: 'CHECKBOX',
                    placeholder: 'Checkbox',
                    type: 'checkbox-group',
                    icon: '🌟',
                },{
                    label: 'Single Select Dropdown',
                    className: 'SINGLE SELECT DROPDOWN',
                    placeholder: 'Single Select Dropdown',
                    type: 'select',
                    icon: '🌟',
                },{
                    label: 'Calculated Value',
                    className: 'CALCULATED VALUE',
                    placeholder: 'Calculated Value',
                    type: 'number',
                    icon: '🌟',
                },{
                    label: 'Amount',
                    className: 'CURRENCY',
                    placeholder: 'Amount',
                    type: 'number',
                    icon: '🌟',
                },{
                    label: 'Text',
                    className: 'TEXT',
                    placeholder: 'Text',
                    type: 'textarea',
                    icon: '🌟',
                },{
                    label: 'Date',
                    className: 'DATEFIELD',
                    placeholder: 'date',
                    type: 'date',
                    icon: '🌟',
                },{
                    label: 'Float',
                    className: 'FLOAT',
                    placeholder: 'float',
                    type: 'number',
                    icon: '🌟',
                }
                ];

            var addField = new Array();
            //never mix javascript with laravel data :) zzz
            @foreach($task->task_questions as $question)
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

            console.log(JSON.stringify(options));
            

            var options = {
                container,
                addField,
                dataType: 'json',
                fields: fields,
            };
            //$(container).formRender(options);

            $("#back").click(function(){
                window.location.href = "/task/view";
            });

            $("#edit").click(function(){
                window.location.href = "/task/edit?task_action_id={{$task->task_id}}";
            });
        
            var formData = JSON.stringify(addField);

            var container = $('.build-wrap');
            //var formData = '[{"label":"True or False","className":"true_or_false","placeholder":"True or False","type":"radio-group","subType":"true-or-false","values":[{"label":"True","value":"tof-4458"},{"label":"False","value":"tof-7692"}],"icon":"🌟","name":"361eeaef-38eb-4c73-8e0a-ce5266db36c7"},{"label":"Float","className":"FLOAT","placeholder":"float","type":"number","icon":"🌟","name":"4233ca1f-30b6-4840-aed9-42ae39362a37","values":[]},{"label":"Single Select Dropdown","className":"SINGLE SELECT DROPDOWN","placeholder":"Single Select Dropdown","type":"select","icon":"🌟","name":"898570d7-dd13-44eb-80c8-9eb98f570612","values":[]},{"label":"Calculated Value","className":"CALCULATED VALUE","placeholder":"Calculated Value","type":"number","icon":"🌟","name":"945b3a0c-9aed-4da5-870c-1bdb3cb53a17","values":[]},{"label":"Text","className":"TEXT","placeholder":"Text","type":"textarea","icon":"🌟","name":"aa6cdb51-1415-4a89-a3c2-fe8a25ebbf70","values":[]},{"label":"Take a Photo","className":"PHOTO","placeholder":"Take a Photo","type":"file","icon":"🌟","name":"ba7a059f-8170-45e9-a5d8-ffb9dea6be97","values":[]},{"label":"Date","className":"DATEFIELD","placeholder":"date","type":"date","icon":"🌟","name":"d84d32ba-8480-49e6-840b-5bb8ebd48622","values":[]},{"label":"Checkbox","className":"CHECKBOX","placeholder":"Checkbox","type":"checkbox-group","icon":"🌟","name":"ec1ea6f5-54e8-4340-8fe8-3c82bc4183e0","values":[]},{"label":"Amount","className":"CURRENCY","placeholder":"Amount","type":"number","icon":"🌟","name":"f1109f0b-a84a-4ed7-85b2-81fdecb21cab","values":[]}]';

            var formRenderOpts = {
                container,
                formData,
                fields: fields,
                dataType: 'json'
            };

            console.log(formRenderOpts);
            $(container).formRender(formRenderOpts);

        });


        function findObjectInArrayByProperty(array, propertyName, propertyValue) {
            return array.find((o) => { return o[propertyName] === propertyValue });
        }

        
    </script>
@stop