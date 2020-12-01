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

            var addField = {!! json_encode($taskForm) !!};
            
            var options = {
                container,
                addField,
                dataType: 'json',
                fields: fields,
            };
            //$(container).formRender(options);


            var formData = JSON.stringify(addField);
            console.log(formData);

            var container = $('.build-wrap');

            var formRenderOpts = {
                container,
                formData,
                fields: fields,
                dataType: 'json'
            };

            $(container).formRender(formRenderOpts);

        });
        function findObjectInArrayByProperty(array, propertyName, propertyValue) {
            return array.find((o) => { return o[propertyName] === propertyValue });
        }
    </script>
@stop
