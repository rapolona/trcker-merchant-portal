@extends('concrete.layouts.main')

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

    <p>Branch Management</p>
    </div>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card" style="width:100%">
                <div class="card-header">
                    <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">
                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button class="btn btn-primary btn-lg" type="button" value="Upload CSV" id="upload_csv">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                                Upload CSV
                            </button>
                            <a href="{{url('/merchant/branch/add')}}" type="button" class="btn btn-primary btn-lg pull-right" id="add">
                                Add
                            </a>
                            <button class="btn btn-primary btn-lg" type="button" value="Edit" id="edit">
                                Edit
                            </button>
                            <button class="btn btn-primary btn-lg" type="button" value="Delete" id="delete">
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_delete" aria-hidden="true" disabled> </span>
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <form id="branches_table" action="javascript:void(0)" >
                        <table class="table table-bordered table-striped mydatatable">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Coordinates</th>
                                <th style="width: 40px">Action?</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($branches as $b)
                            <tr>
                                <input type="hidden" name="row_branch_id[]" value="{{ $b->branch_id}}"/>
                                <td> {{ $b->no }}</td>
                                <td> {{ $b->name }}</td>
                                <td> {{ $b->address }}</td>
                                <td> {{ $b->latitude }} {{ $b->longitude }}</td>
                                <td><input type="checkbox" name="branch" id="{{$b->branch_id}}"></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
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

            $('#delete').click(function(e){
                var formData = new FormData();
                var branches = [];

                console.log(branches);
                $.each($("input[name='branch']:checked"), function(){
                    branches.push($(this).attr("id"));
                });

                if (branches.length < 1){
                    $(".modal-title").text("Invalid Delete Selection!");
                    $(".modal-body").html("<p>Please check at least one product!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                console.log(branches);

                formData.append('branches', branches);
                formData.append('_token', "{{ csrf_token() }}");

                post("{{url('/merchant/branch/delete')}}", "Delete Branch", "delete", formData, "{{url('/merchant/branch')}}");
            });

            $('#edit').click(function(e){
                var branches = [];
                $.each($("input[name='branch']:checked"), function(){
                    branches.push($(this).attr("id"));
                });

                if (branches.length != 1){
                    $(".modal-title").text("Invalid Edit Selection!");
                    $(".modal-body").html("<p>Please check one branch only!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                window.location.href = "{{url('/merchant/branch/edit?branch_id=')}}" + branches[0];
            });

            $('#upload_csv').click(function(e){
                $("#file").click();
            });

            $('#file').change(function(){
                $('#file_upload').submit();
            });

            $('#file_upload').submit(function(e) {
                //e.preventDefault();

                var formData = new FormData(this);

                post("{{url('/merchant/branch/upload')}}", "Upload Branch", "upload_csv", formData, "{{url('/merchant/branch')}}")
            });
        });
    </script>
@stop
