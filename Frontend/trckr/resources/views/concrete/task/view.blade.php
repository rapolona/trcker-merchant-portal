@extends('concrete.layouts.main')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Task Details</div>
        </div>
        <div class="panel-body">

            <section class="section-sm bg-100">
                <div class="container-fluid">
                    <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                        <div class="media-item"><img src="{{ ($task->banner_image) ? $task->banner_image : ''}}" width="165" height="165" alt=""></div>
                        <div class="media-body">
                            <h2>{{  $task->task_name }}</h2>
                            <p>{{  $task->task_description }}</p>
                            <p>
                                @foreach ($task_classification as $ta)
                                    @if  ($ta->task_classification_id == $task->task_classification_id)
                                        {{$ta->name}}
                                    @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <div class="row row-30">
                <div class="col-md-12">
                    <div class="build-wrap"></div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('js')
    <script type="text/javascript" src="{{url('/vendor/form-builder/form-builder.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/vendor/form-builder/form-render.min.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {
            $('#myModal').on('hidden.bs.modal', function () {
                window.location.href = "{{url('/task/view')}}";
            });
            /*
            var image = new Image();
            image.src = $("#image").val();
            $(image).appendTo("#image_container");
            alert(1);
            */

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
                    }],
                    icon: '🌟',
                },{
                    label: 'Take a Photo',
                    className: 'IMAGE',
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
                    className: 'DATETIME',
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
                window.location.href = "{{url('/task/view')}}";
            });

            $("#edit").click(function(){
                window.location.href = "{{url('/task/edit?task_action_id=')}}{{$task->task_id}}";
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
