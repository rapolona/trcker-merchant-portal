@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Please Input Task Details</div>
        </div>
        <div class="panel-body">
            <form method="post" id="create_task" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row row-30">
                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-institution"></span></span></div>
                            <input class="form-control  {{ $errors->first('task_name')? 'form-control-danger' : '' }}" type="text" value="{{ old('task_name') }}" name="task_name" placeholder="Task Name">
                        </div>
                        @if($errors->first('task_name'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('task_name') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-black-tie"></span></span></div>
                            <input class="form-control {{ $errors->first('task_description')? 'form-control-danger' : '' }}" type="text" value="{{ old('task_description') }}" name="task_description" placeholder="Task Description">
                        </div>
                        @if($errors->first('task_description'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('task_description') }}</span></span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="input-group form-group">
                            <select class="form-control {{ $errors->first('$task_classification')? 'form-control-danger' : '' }}" name="task_classification_id" id="task_classification_id">
                                <option value="">Select Task Classification</option>
                                @foreach ($task_classification as $ta)
                                    <option {{ ($ta->task_classification_id==old('task_classification_id'))? 'selected="selected"' : '' }} value="{{$ta->task_classification_id}}">{{$ta->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($errors->first('task_classification_id'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('task_classification_id') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                            <input type="file" class="form-control {{ $errors->first('banner_image')? 'form-control-danger' : '' }}" id="exampleInputFile" name="banner_image" accept="image/*">
                        </div>
                        @if($errors->first('banner_image'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('banner_image') }}</span></span>
                            </div>
                        @endif
                        <div class="input-group form-group">
                        </div>
                    </div>
                </div>
                <div class="row row-30">
                    <div class="col-md-12">
                        <div class="build-wrap"></div>
                    </div>
                    <input type="hidden" id="form_builder_input"  name="form_builder" value="" />
                </div>
                <div class="row row-30">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary"  type="submit">Save Task</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@stop

@section('js')

    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {
            let options = {
                disableFields: ['autocomplete', 'radio-group', 'checkbox-group','button','hidden','paragraph','header','select','text','textarea','file','date','number'],
                disabledAttrs: ['access','other','placeholder','required','value','description','inline','max','maxlength','min','multiple','rows','step','style','subtype','toggle'],
                fields:[{
                    label: 'Checkbox',
                    className: 'CHECKBOX',
                    placeholder: 'Checkbox',
                    type: 'checkbox-group',
                },{
                    label: 'Date',
                    className: 'DATETIME',
                    placeholder: 'date',
                    type: 'date',
                },{
                    label: 'Float',
                    className: 'FLOAT',
                    placeholder: 'float',
                    type: 'number',
                },{
                    label: 'Ranking',
                    className: 'RANKING',
                    placeholder: 'Ranking',
                    type: 'checkbox-group',
                    subType: 'ranking'
                },{
                    label: 'Integer',
                    className: 'INTEGER',
                    placeholder: 'Integer',
                    type: 'number',
                },{
                    label: 'Take a Photo',
                    className: 'IMAGE',
                    placeholder: 'Take a Photo',
                    type: 'file',
                },{
                    label: 'Single Select Dropdown',
                    className: 'SINGLE SELECT DROPDOWN',
                    placeholder: 'Single Select Dropdown',
                    type: 'select',
                },{
                    label: 'Text',
                    className: 'TEXT',
                    placeholder: 'Text',
                    type: 'textarea',
                },{
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
                }
                ],
            };

            let formBuilder = jQuery('.build-wrap').formBuilder(options);
            $('#create_task').submit(function(e) {
                let myFormBuilder = formBuilder.actions.getData('json', true);
                jQuery('#form_builder_input').val(myFormBuilder);
                if(myFormBuilder.length == 2){ //[]
                    e.preventDefault();
                    return false;
                }
            });

            @if(old('form_builder'))
            let oldValFb = {!! old('form_builder') !!};
            console.log(oldValFb);
            setTimeout(function(){ formBuilder.actions.setData(oldValFb); }, 500);
            @endif

        });

    </script>
@stop
