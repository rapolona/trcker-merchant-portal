@extends('concrete.layouts.main')

@section('content')

    <section class="section-sm bg-100">
        <div class="container-fluid">
            <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                <div class="media-item"><img src="{{ $campaign['thumbnail_url'] }}" width="165" height="165" alt=""></div>
                <div class="media-body">
                    <h2>{{ $campaign['campaign_name'] }}</h2>
                    <p>
                        <span><strong>Duration: </strong>  {{ $campaign['daterange'] }}</span>
                        <span><strong>Status: </strong> {{ $campaign['status'] }} </span>
                        <span><strong>Type: </strong> {{ $campaign['campaign_type'] }}</span>
                        <span><strong>Audience: </strong> {{ $campaign['audience']  }}</span>
                    </p>
                    <p>{!! $campaign['campaign_description'] !!}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-sm bg-100">
        <div class="container-fluid">
            <div class="panel">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="col-md-12">
                        <div class="panel-title">Branch Details</div>
                    </div>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive scroller scroller-horizontal py-3" id="branch_table" >
                        <table class="table table-striped table-hover {{ ($campaign['branch_id-nobranch_value'] > 0)? '' : 'data-table' }}"style="min-width: 800px">
                            <thead>
                            <tr>
                                @if($campaign['branch_id-nobranch_value'] > 0)
                                    <th>Name</th>
                                    <th>Branch Submissions</th>
                                @else
                                    <th>Name</th>
                                    <th>BusinessType</th>
                                    <th>StoreType</th>
                                    <th>Brand</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Region</th>
                                    <th>Branch Submissions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @if($campaign['branch_id-nobranch_value'] > 0)
                                <tr>
                                    <td>Do it at home</td>
                                    <td>{{ $campaign['branch_id-nobranch_value'] }}</td>
                                </tr>
                            @else
                                @foreach ($branches as $branch)
                                    @if(is_array(old('branch_id', $campaign['branch_id'])  ) && in_array($branch->branch_id, old('branch_id', $campaign['branch_id'])))
                                        <tr>
                                            <td>{{ $branch->name }}</td>
                                            <td>{{ $branch->business_type }}</td>
                                            <td>{{ $branch->store_type }}</td>
                                            <td>{{ $branch->brand }}</td>
                                            <td>{{ $branch->address }}</td>
                                            <td>{{ $branch->city }}</td>
                                            <td>{{ $branch->region }}</td>
                                            <td class="text-right" width="15%">
                                                @php
                                                    if(old('branch_id', $campaign['branch_id'])) {
                                                        $branchIdKey = array_search($branch->branch_id, old('branch_id', $campaign['branch_id']));
                                                    }
                                                @endphp
                                                {{ $campaign['submission'][$branchIdKey] }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section-sm bg-100">
        <div class="container-fluid">
            <div class="panel panel-nav">
                <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="panel-title">Task Details</div>
                </div>

                <div class="panel-body">
                    <div id="taskBody">
                        @for($x = 0; $x <= 5; $x++)
                            @if($x==0 || old('task_type.' . $x, (isset($campaign['tasks'][$x]->task_id))? $campaign['tasks'][$x]->task_id : ''))
                                <div class="row row-30 task-container">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control task_type" readonly="" name="task_type[]" style="width: 100%;">
                                                <option value="">Select Task Type</option>
                                                @foreach ($task_type as $ct)
                                                    <option {{ (old('task_type.' . $x, $campaign['tasks'][$x]->task_type) == $ct->task_classification_id)? 'selected="selected"' : '' }} value="{{$ct->task_classification_id}}">{{$ct->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group task-action-container">
                                            <select readonly="" class="form-control select2 task_actions" required name="task_id[]" style="width: 100%;">
                                                <option value="">Select Task</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><span class="fa-building"></span></span></div>
                                            <input class="form-control reward_amount" required type="number" min="1" name="reward_amount[]" value="{{old('reward_amount.' . $x, $campaign['tasks'][$x]->reward_amount)}}" placeholder="Reward">
                                        </div>
                                    </div>


                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
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

            } );
        });


        $(document).on("click", "#selectAll" , function() {
            let table= $(this).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
            $('input.branch-id-checkbox').change();
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

        $(document).on("change", ".task_type" , function() {
            console.log('called task_type.change');
            let task_action = $(this).closest('.task-container').find('.task_actions');
            $(task_action).empty();
            console.log('VAL:: ' + this.value);
            $.ajax({
                type:'GET',
                url: "{{url('/campaign/campaign_type/task?task_id=')}}" + this.value,
                cache:true,
                contentType: false,
                processData: false,
                success: (data) => {
                    $(data.file).each(function(){
                        $(task_action).append('<option value="'+ this.task_id +'">' + this.task_name + '</option>');
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
            })

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
                    $.alert({
                        title: 'Hustle',
                        type: 'red',
                        content: 'You are over your budget please adjust your rewards or max submissions or increase your budget',
                    });
                    return false;
                }

                return true;
            });

        });

    </script>
@stop
