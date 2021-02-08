@extends('concrete.layouts.main')

@section('breadcrumbs_pull_right')
    <div class="group-10">
        <!-- <button class="btn btn-secondary" type="button"><span class="fa-plus"></span><span class="pl-2">Add New Branch</span></button> -->
        <a class="btn btn-outline-primary" href="{{ url('campaign/create') }}"><span class="fa-plus"></span><span class="pl-2"> Add New Campaign</span></a>
        <!-- <button class="btn btn-secondary" type="button"><span class="fa-user"></span><span class="pl-2">Button Link</span></button> -->
    </div>
@endsection

@section('tableFilters')
    <div class="row">
        <div class="col-sm-4">
            <div class="input-group form-group">
                <div class="input-group-prepend"><span class="input-group-text" id="basic-addon3">Name</span></div>
            <input class="form-control" id="name" type="text" value="{{ $filter['name'] }}">
            </div>
        </div>
        <div class="col-sm-3">
            <select class="form-control" id="status">
                <option value="">--Status--</option>
                <option {{ ($filter['status']=="ONGOING")? 'selected' : '' }} value="ONGOING">ONGOING</option>
                <option {{ ($filter['status']=="DONE")? 'selected' : '' }} value="DONE">DONE</option>
                <option {{ ($filter['status']=="INACTIVE")? 'selected' : '' }} value="INACTIVE">INACTIVE</option>
                <option {{ ($filter['status']=="CANCELED")? 'selected' : '' }} value="CANCELED">CANCELED</option>
            </select>
        </div>
        <div class="col-sm-3">
            <div class="input-group form-group">
                <div class="input-group-prepend"><span class="input-group-text"><span class="fa-calendar"></span></span></div>
                <input class="form-control" id="daterange" type="text" value="{{ $filter['daterange'] }}" placeholder="Date Range">
                <input type="text" name="daterange" style="display: none">
            </div>
        </div>
        <div class="col-sm-2">
            <button type="button" id="searchBtn" class="btn btn-primary">Search</button>
        </div>
    </div>
@endsection

@section('content')
    <form method="post" action="{{ url('campaign/bulk-action') }}" id="tableList">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="panel">
            <div class="panel-header">
                <div class="row">
                    <div class="col-sm-7">
                        <div class="panel-title"><span class="panel-icon fa-tasks"></span> <span>Campaigns</span></div>
                    </div>
                    <div class="col-sm-5 text-right">
                        <div class="btn-group">
                            <button class="btn btn-light btn-sm enable-disable" type="button" value="enable"><span class="fa-check"></span><span class="pl-2">Enable</span></button>
                            <button class="btn btn-danger btn-sm enable-disable" type="button" value="disable"><span class="fa-ban"></span><span class="pl-2">Disable</span></button>
                        </div>
                        <input id="campaign-status" name="status" type="hidden" />
                    </div>
                </div>
            </div>
            <div class="panel-menu">
                @include('concrete.layouts.filters')  
            </div>
            <div class="panel-body p-0">
                <div class="table-responsive scroller scroller-horizontal py-3">
                    <table class="table table-striped table-hover" id="hustleTableList">
                        <thead>
                        <tr>
                            <td style="width: 40px" data-orderable="false" data-targets="0" >
                                <div class="custom-control custom-checkbox custom-checkbox-light">
                                    <input class="custom-control-input" type="checkbox" id="selectAll"/>
                                    <label class="custom-control-label" for="selectAll"></label>
                                </div>
                            </td>
                            <th>Campaign Name</th>
                            <th>Budget</th>
                            <th>Reward</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th data-orderable="false" ></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($campaigns as $campaign)
                            <tr>
                                <td style="width: 40px">
                                    <div class="custom-control custom-checkbox custom-checkbox-light">
                                        <input class="custom-control-input" type="checkbox" name="campaign_id[]" id="{{ $campaign->campaign_id }}" value="{{ $campaign->campaign_id }}" />
                                        <label class="custom-control-label" for="{{ $campaign->campaign_id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $campaign->campaign_name }}</td>
                                <td>{{ $campaign->budget }}</td>
                                <td>{{ $campaign->total_reward_amount }}</td>
                                <td>{{ date('Y-m-d', strtotime($campaign->start_date)) }}</td>
                                <td>{{ date('Y-m-d', strtotime($campaign->end_date)) }}</td>
                                <td class="text-{{ (isset(config('concreteadmin.status')[$campaign->status] ))? config('concreteadmin.status')[$campaign->status] : 'light' }}">{{ $campaign->status }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-light btn-sm" data-toggle="dropdown"><span>Action</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ url('campaign/view/' . $campaign->campaign_id )}}">View</a>
                                            <a class="dropdown-item" href="{{ url('campaign/edit/' . $campaign->campaign_id )}}">Edit</a>
                                            <a class="dropdown-item" href="{{ url('campaign/duplicate/' . $campaign->campaign_id )}}">Duplicate</a>
                                            @if($campaign->status=='DISABLED')
                                                <a class="dropdown-item" href="{{ url('campaign/status/enable/' . $campaign->campaign_id )}}">Enable</a>
                                            @else
                                                <a class="dropdown-item" href="{{ url('campaign/status/disable/' . $campaign->campaign_id )}}">Disable</a>
                                            @endif

                                            <a  class="dropdown-item"  href="{{ url('gallery/' . $campaign->campaign_id) }}" target="new"> View Gallery</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer"> 
                @include('concrete.layouts.pagination')        
            </div>
        </div>
    </form>
@stop


@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {

setTimeout(function () {
  $('input[id="daterange"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      }
  });

  $('input[id="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('input[id="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });
}, 2000);   

            $('select.pagination_current_page').click(function(e){
                let url = "{{ url('campaign/view') }}?";
                let params = { 
                    daterange : $('#daterange').val(),
                    status : $('#status').val(),
                    name : $('#name').val(),
                    page : $(this).val()
                };
                let str = jQuery.param( params );
                window.location = url + str;
            });

            
            $('#searchBtn').click(function(e){
                let url = "{{ url('campaign/view') }}?";
                let params = { 
                    daterange : $('#daterange').val(),
                    status : $('#status').val(),
                    name : $('#name').val() 
                };
                let str = jQuery.param( params );
                window.location = url + str;
            });


            $('#selectAll').click(function(e){
                let table= $(e.target).closest('table');
                $('td input:checkbox',table).prop('checked',this.checked);
            });

            $('.enable-disable').click(function(e){
                let val = $(this).attr('value');
                $('#campaign-status').val(val);

                if (confirm('Are you sure you want to ' + val + ' these items ?')) {
                    $('#tableList').submit();
                }
            });


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


            // TABLE LIST WITH PAGINATION
            $(document).on("change", "select.hustle-filter" , function(e) {
            let selected = $("select.hustle-filter :selected").map(function(i, el) {
                formFilters[$(el).parent().attr('name')] = $(el).val();
            }).get();

            });

            function requestTableAjax(page=1, per_page=1){
                $('#hustleTableList').dataTable( {
                    "ordering" : false,
                    "ajax": {
                        "type" : "GET",
                        "url" : "{{url('api/campaign/list')}}?" + $.param(formFilters),
                        "dataSrc": function ( json ) {
                            reinstateDataTable();
                            return json.data;
                        }
                    },
                    "columnDefs": [ {
                        "targets": -1,
                        "data": 0,
                        "render": function ( data, type, row, meta ) {
                        return '<input disabled class="branch-input form-control max-submission" type="number" min="1" name="submissions[]" placeholder="Max Submission">';
                        }
                    },{
                        "targets": 0,
                        "data": 0,
                        "bSortable": false,
                        "render": function ( data, type, row, meta ) {
                            return '<div class="branch-input custom-control custom-checkbox custom-checkbox-light"><input class="custom-control-input branch-id-checkbox" type="checkbox" name="branch_id[]" id="' + data + '" value="' + data + '"  /><label class="custom-control-label" for="' + data +'"></label></div>';
                        }
                    } ]
                });
            }

        });
    </script>
@stop
