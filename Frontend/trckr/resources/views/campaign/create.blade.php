@extends('adminlte::page')

@section('title', 'Trckr | Create New Campaign')

@section('content_header')
    <h1>Create New Campaign</h1>
@stop

@section('content')
    <p>Create New Campaign</p>

    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form id="modify_merchant">
                    <div class="row">
                        <div class="col col-lg-6">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                            <div class="card-body">
                                <div class="form-group row">
                                    <h2>Campaign Information</h2>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_name" class="col-sm-2 col-form-label">Campaign Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_campaign_name" name="campaign_name" value="" placeholder="Enter Campaign Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_type" class="col-sm-2 col-form-label">Campaign Type</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_campaign_type" name="campaign_type" value="" placeholder="Enter Campaign Type">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_description" class="col-sm-2 col-form-label">Campaign Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_description" name="description" value="" placeholder="Enter Campaign Description">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="reward" class="col-sm-2 col-form-label">Reward</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_reward" name="reward" value="" placeholder="Enter Reward">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branches" class="col-sm-2 col-form-label">Branches</label>
                                    <div class="col-sm-10">
                                    <select id="branch_select" name="branch[]" multiple="" class="form-control">
                                        <option>option 1</option>
                                        <option>option 2</option>
                                        <option>option 3</option>
                                        <option>option 4</option>
                                        <option>option 5</option>
                                    </select>
                                    <div class="col-sm-10">
                                        <span>Select All</span>
                                        <input type="checkbox" id="input_branch_checkbox" name="branch_checkbox" value="" placeholder="">
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="audience" class="col-sm-2 col-form-label">Audience</label>
                                    <div class="col-sm-10">
                                        <span>Select All</span>
                                        <input type="checkbox" id="input_branch_checkbox" name="branch_checkbox" value="" placeholder="">
                                        <select id="branch_select" name="audience[]" multiple="" class="form-control">
                                            <option>option 1</option>
                                            <option>option 2</option>
                                            <option>option 3</option>
                                            <option>option 4</option>
                                            <option>option 5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="duration" class="col-sm-2 col-form-label">Duration</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_duration_from" name="duration_from" value="" placeholder="Enter Duration From">
                                        <input type="text" class="form-control" id="input_duration_to" name="duration_to" value="" placeholder="Enter Duration To">                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-lg-6">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col col-lg-8 ">
                                        <h2>Tasks</h2><span>(Pre-defined based on Campaign Type)</span>
                                    </div>
                                    <div class="col col-lg-4 ">
                                        <a href="/task/create" type="button" class="btn btn-primary btn-lg pull-right">Add Custom</a>
                                    </div>
                                </div>
                                <table class="table table-bordered" id="table_tasks">
                                    <thead>                  
                                    <tr>
                                        <th>Task Name</th>
                                        <th>Task</th>
                                        <th style="width:span0px">Action?</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $t)
                                        <tr>
                                            <td> {{ $t['task_name'] }}</td>
                                            <td> {{ $t['description'] }}</td>
                                            <td><input type="checkbox" name="" id=""> </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="table_selected">
                                    <thead>                  
                                    <tr>
                                        <th>Task Name</th>
                                        <th>Task</th>
                                        <th style="width: 40px">Action?</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col col-lg-9">
                        </div>      
                        <div class="col col-lg-3 ">
                            <button type="submit" class="btn btn-block btn-primary btn-lg pull-right" id="submit">Save Details</button>  
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
    <script type="text/javascript">
        
        $(document).ready(function (e) { 
            $('#modify_merchant').submit(function(e) {
                alert(1);
                e.preventDefault();

                var formData = new FormData(this);
        
                $.ajax({
                    type:'POST',
                    url: "/campaign/create_campaign",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        console.log(data);
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            });
        });

        $(document).on('click', '#table_tasks tbody tr', function(){
            
            tid = $(this).attr('id');
            html =$(this).html();
            alert(html);
            $('#table_selected tbody').append("<tr>"+html+"</tr>");
            if ($(this).length) {
                $(this).remove();
            }
        })

        $(document).on('click', '#table_selected tbody tr', function(){
            tid = $(this).attr('id');
            html = $(this).html();
            alert(html);
            $('#table_tasks tbody').append("<tr>"+html+"</tr>");
            if ($(this).length) {
                $(this).remove();
            }
        });

        $(document).on('click', '#branch_select', function(){
        });

    </script>
@stop