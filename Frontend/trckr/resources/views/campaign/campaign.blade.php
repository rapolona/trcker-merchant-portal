@extends('adminlte::page')

@section('title', 'Trckr | View Campaigns')

@section('content_header')
    <h1>Campaign</h1>
@stop

@section('content')
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
    <p>Insert text here</p>
    <div class="row">
        <div class="card" style="width:100%">
            <div class="col col-lg-12">
                <div class="card-header">
                    <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <a href="/campaign/create" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                            <button type="button" class="btn btn-primary btn-lg" id="edit">Edit</button>    
                            <button class="btn btn-primary btn-lg" type="button" value="button" id="delete">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_delete" aria-hidden="true" disabled> </span>
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped mydatatable">
                        <thead>                  
                        <tr>
                            <th style="width: 10px">#</th>
                            <th >Campaign Name</th>
                            <th >Budget</th>
                            <th >Duration</th>
                            <th >Status</th>
                            <th style="width: 40px">Action?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($campaigns as $c)
                        <tr>
                            <input class="view_id" type="hidden" name="row_campaign_id[]" value="{{$c->campaign_id}}"/>
                            <td class="view"> {{ $c->no }}</td>
                            <td class="view"> {{ $c->campaign_name }}</td>
                            <td class="view"> {{ $c->budget }}</td>
                            <td class="view"> {{ $c->duration }}</td>
                            <td class="view"> {{ $c->status }}</td>
                            <td><input type="checkbox" name="campaigns" id="{{$c->campaign_id}}" value="{{$c->campaign_id}}"></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
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