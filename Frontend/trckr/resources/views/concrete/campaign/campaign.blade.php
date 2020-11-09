@extends('concrete.layouts.main')

@section('breadcrumbs_pull_right')
    <div class="group-10">
        <!-- <button class="btn btn-secondary" type="button"><span class="fa-plus"></span><span class="pl-2">Add New Branch</span></button> -->
        <a class="btn btn-light" href="{{ url('campaign/create') }}"><span class="fa-plus"></span><span class="pl-2">Add New Campaign</span></a>
        <!-- <button class="btn btn-secondary" type="button"><span class="fa-user"></span><span class="pl-2">Button Link</span></button> -->
    </div>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel-title"><span class="panel-icon fa-tasks"></span> <span>Campaigns</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body p-0">
            <div class="table-responsive scroller scroller-horizontal py-3">
                <table class="table table-striped table-hover data-table" data-table-searching="true" data-table-lengthChange="true" data-page-length="5">
                    <thead>
                    <tr>
                        <th>Campaign Name</th>
                        <th>Budget</th>
                        <th>Reward</th>
                        <!--<th>No. of tasks</th>-->
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->campaign_name }}</td>
                        <td>{{ $campaign->budget }}</td>
                        <td>{{ $campaign->total_reward_amount }}</td>
                        <!--<td>N/A</td>-->
                        <td>{{ date('Y-m-d', strtotime($campaign->start_date)) }}</td>
                        <td>{{ date('Y-m-d', strtotime($campaign->end_date)) }}</td>
                        <td class="text-{{ config('concreteadmin.status')[$campaign->status] }}">{{ $campaign->status }}</td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle btn-light btn-sm" data-toggle="dropdown"><span>Action</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('campaign/view/') . $campaign->campaign_id }}">View</a>
                                    <a class="dropdown-item" href="{{ url('campaign/edit/') . $campaign->campaign_id }}">Edit</a>
                                    <a class="dropdown-item" href="{{ url('campaign/delete/') . $campaign->campaign_id }}">Delete</a>
                                    <a class="dropdown-item" href="{{ url('campaign/duplicate/') . $campaign->campaign_id }}">Duplicate</a>
                                    <a class="dropdown-item" href="{{ url('campaign/disable/') . $campaign->campaign_id }}">Disable</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {

            $('.view').click(function(){
                var campaign_id = $(this).siblings('.view_id').val();

                window.location.href = "{{url('/campaign/view_campaign?campaign_id=')}}" + campaign_id;
            });

            $('#delete').click(function(e){
                var formData = new FormData();
                var campaigns = [];

                $.each($("input[name='campaigns']:checked"), function(){
                    campaigns.push($(this).attr("id"));
                });

                if (campaigns.length < 1){
                    $(".modal-title").text("Invalid Delete Selection!");
                    $(".modal-body").html("<p>Please check at least one Task!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                formData.append('campaigns', campaigns);
                formData.append('_token', "{{ csrf_token() }}");

                post("{{url('/campaign/delete')}}", "Delete Campaign", "delete", formData, "{{url('/cmpaign/view')}}");
            });

            $('#edit').click(function(e){
                var campaigns = [];
                $.each($("input[name='campaigns']:checked"), function(){
                    campaigns.push($(this).attr("id"));
                });

                if (campaigns.length != 1){

                    $(".modal-title").text("Invalid Edit Selection!");
                    $(".modal-body").html("<p>Please check one task only!</p>");
                    $("#myModal").modal('show');
                    return;
                }
                window.location.href = "{{url('/campaign/edit?campaign_id=')}}" + campaigns[0];
            });
        });
  </script>
@stop
