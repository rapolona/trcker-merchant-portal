@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="row">
                <div class="col-sm-7">
                    <div class="panel-title"><span class="panel-icon fa-tasks"></span> <span>Merchant Branches</span>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="btn-group pull-right" style="margin-top: 10px">
                        <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="file" name="file" id="file" style="display:none">
                            <button class="btn btn-primary btn-sm" type="button" value="Upload CSV" id="upload_csv">
                                <span class="fa-upload"></span>
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                                Upload CSV
                            </button>
                        </form>
                        <a href="{{url('/merchant/branch/add')}}" type="button" class="btn btn-success btn-sm pull-right" id="add">
                            <span class="fa-plus"></span>
                            Add
                        </a>
                        <form method="POST" id="deleteForm" action="{{ url('merchant/branch/bulkdelete')  }}" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="delete_ids" id="delete_ids"  value="">
                            <button class="btn btn-danger btn-sm" type="button" id="delete">
                                <span class="mdi-delete-variant"></span>
                                <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                                Bulk Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="panel-body p-0">
            <div class="table-responsive scroller scroller-horizontal py-3">
                <table class="table table-striped table-hover data-table" style="min-width: 800px">
                    <thead>
                    <tr>
                        <td style="width: 40px">
                            <div class="custom-control custom-checkbox custom-checkbox-success">
                                <input class="custom-control-input" type="checkbox" id="chkAll"/>
                                <label class="custom-control-label" for="chkAll"></label>
                            </div>
                        </td>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Coordinates</th>
                        <th style="with:100px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($branches as $branch)
                        <tr>
                            <td style="width: 40px">
                                <div class="custom-control custom-checkbox custom-checkbox-success">
                                    <input class="custom-control-input" type="checkbox" name="branch_id" id="{{ $branch->branch_id }}" />
                                    <label class="custom-control-label" for="{{ $branch->branch_id }}"></label>
                                </div>
                            </td>
                            <td> {{ $branch->name }}</td>
                            <td> {{ $branch->address }}</td>
                            <td> {{ $branch->latitude }} {{ $branch->longitude }}</td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-warning btn-sm" type="button" href="{{url('/merchant/branch/edit/' . $branch->branch_id )}}"><span class="fa-edit"></span></a>
                                    <a class="btn btn-danger btn-sm deleteProduct" type="button" target-href="{{url('/merchant/branch/delete/' . $branch->branch_id )}}"><span class="mdi-delete"></span></a>
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
