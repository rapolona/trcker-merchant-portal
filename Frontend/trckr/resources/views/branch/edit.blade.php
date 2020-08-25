@extends('adminlte::page')

@section('title', 'Trckr | Edit Branch')

@section('content_header')`
    <h1>Edit Branch</h1>
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
    <p>Edit Branch</p>

    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form class="form-vertical" id="edit_branch">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Branch Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_name" name="name" value="{{ ($branch->name) ? $branch->name : ''}}" placeholder="Enter Branch Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_address" name="address" value="{{ ($branch->address) ? $branch->address : ''}}" placeholder="Enter Address">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_city" name="city" value="{{ ($branch->city) ? $branch->city : ''}}" placeholder="Enter City">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Coordinates</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_longitude" name="longitude" value="{{ ($branch->longitude) ? $branch->longitude : ''}}" placeholder="Enter Longitude">
                                <input type="text" class="form-control" id="input_lattitude" name="latitude" value="{{ ($branch->latitude) ? $branch->latitude : ''}}" placeholder="Enter Lattitude">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">                

                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-block btn-primary btn-lg pull-right" id="submit">Save Details</button>  
                            <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript">
        
        $(document).ready(function (e) { 
            $('#edit_branch').submit(function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                formData.append("id", "{{ $branch_id }}");
        
                $.ajax({
                    type:'POST',
                    url: "/merchant/branch/edit?{{ $branch_id }}",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").text("Edit Branch Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").text("Edit Branch Failed!");
                        //$(".modal-body").html("<p>" + data.responseText + "</p>");
                        $(".modal-body").html("<p>" + data.responseJSON.message + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });

            $("#back").click(function(){
                window.location.href = "/merchant/branch";
            });
        });
    </script>
@stop