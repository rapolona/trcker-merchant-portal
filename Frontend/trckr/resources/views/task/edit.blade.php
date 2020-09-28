@extends('adminlte::page')

@section('title', 'Trckr | Edit Task')

@section('content_header')
    <h1>Edit Task</h1>
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

    <p>Edit Task</p>

    <div class="card">
        <form class="form-vertical" id="create_task">
            <div class="card-body">           
                <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="input_task_name" name="task_name" placeholder="Enter Task Name" value="{{ ($task->task_name) ? $task->task_name : ''}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Description</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="input_task_description" name="task_description" placeholder="Enter Task Description" value="{{ ($task->task_description) ? $task->task_description : ''}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Task Classification</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="task_classification_id" style="width: 100%;">
                            <option value="">Select One</option>
                            @foreach ($task_classification as $ta)
                            <option value="{{$ta->task_classification_id}}"
                            @php echo ($ta->task_classification_id == $task->task_classification_id) ? "selected" : ""
                                @endphp
                                >{{$ta->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="company_name" class="col-sm-2 col-form-label">Banner Image</label>
                    <div class="col-sm-10">
                        <input type="file" class="custom-file-input" id="exampleInputFile" name="banner_image" accept="image/*" value="{{ ($task->banner_image) ? $task->banner_image : ''}}" >
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                </div>

                <div class="build-wrap"></div>
            </div>
            <div class="card-footer">
                <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                    <button class="btn btn-primary btn-lg" type="submit" value="submit" id="submit">
                        <span class="spinner-border spinner-border-sm" role="status" id="loader_submit" aria-hidden="true" disabled> </span>
                        Edit Task
                    </button> 
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
    <script type="text/javascript" src="/vendor/trckr/trckr.js"></script>
    <script type="text/javascript" src="/vendor/form-builder/form-builder.min.js"></script>
    <script type="text/javascript" src="/vendor/form-builder/form-render.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function (e) {
            $('#myModal').on('hidden.bs.modal', function () {
                window.location.href = "/task/view";
            });
            
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
                    className: 'PHOTO',
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
                    className: 'DATETIME',
                    placeholder: 'date',
                    type: 'date'
                },{
                    label: 'Float',
                    className: 'FLOAT',
                    placeholder: 'float',
                    type: 'number'
                }
            ];
            let options = {
                disableFields: ['autocomplete', 'radio-group', 'checkbox-group','button','hidden','paragraph','header','select','text','textarea','file','date','number'],
                disabledAttrs: ['access','other','placeholder','required','value','description','inline','max','maxlength','min','multiple','rows','step','style','subtype','toggle'],
                fields: fields,
            };

            var formBuilder = $('.build-wrap').formBuilder(options);

            formBuilder.promise.then(function(fb) {
                var addField = new Array();
                //never mix javascript with laravel data :) zzz
                @foreach($task->task_questions as $question)
                    var temp = findObjectInArrayByProperty(fields, "className", "{{$question->required_inputs}}");
                    temp.name = "{{$question->task_question_id}}";
                    temp.label = "{{$question->question}}";
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
                    //formBuilder.actions.addField(temp,1);
                    addField.push(temp);
                @endforeach

                fb.actions.setData(addField);
            });
            

            //$('.build-wrap').formBuilder(options);
            

            $('#create_task').submit(function(e) {
                e.preventDefault();
 
                var formData = new FormData(this);

                var myFormBuilder = formBuilder.actions.getData('json', true);

                //formData.append('data_type', "custom");
                formData.append('form_builder', myFormBuilder);

                formData.append("task_id", "{{ $task_id }}");

                post("/task/edit", "Edit Task", "submit", formData, "/task/view");
            });

            $("#back").click(function(){
                window.location.href = "/task/view";
            });
        });
    </script>
@stop