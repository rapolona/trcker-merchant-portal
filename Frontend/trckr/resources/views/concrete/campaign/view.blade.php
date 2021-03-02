@extends('concrete.layouts.main')

@section('content')

    <section class="section-sm bg-100">
        <div class="container-fluid">
            <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                @if(isset($campaign['signed_thumbnail_url']))
                <div class="media-item"><img src="{{ $campaign['signed_thumbnail_url'] }}" width="165" height="165" alt=""></div>
                @endif
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
                        @php 
                            $selected_task_ids = [];
                        @endphp
                        @for($x = 0; $x <= 5; $x++)
                            @if($x==0 || old('task_type.' . $x, (isset($campaign['tasks'][$x]->task_id))? $campaign['tasks'][$x]->task_id : ''))
                            @php 
                                array_push($selected_task_ids, $campaign['tasks'][$x]->task_id);
                            @endphp
                                <div class="row row-30 task-container">
                                    <div class="col-md-3">
                                        <strong>Mandatory? </strong>
                                        {{ $campaign['tasks'][$x]->mandatory == 0 ? 'No' : 'Yes' }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Task: </strong>
                                        {{ $campaign['tasks'][$x]->task_name }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Reward: </strong>
                                        {{ $campaign['tasks'][$x]->reward_amount }}
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
