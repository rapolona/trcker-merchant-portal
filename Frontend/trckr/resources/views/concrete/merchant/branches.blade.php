@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel-title"><span class="panel-icon fa-tasks"></span> <span>Merchant Branches</span>
                    </div>
                </div>
            </div>
            <div class="row row-30">
                <div class="col-lg-2">
                    <select class="form-control" name="filter-purchases">
                        <option value="0">Filter by Business Type</option>
                        <option value="1">1-49</option>
                        <option value="2">50-499</option>
                        <option value="1">500-999</option>
                        <option value="2">1000+</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <select class="form-control" name="filter-group">
                        <option value="0">Filter by Store Type</option>
                        <option value="1">Customers</option>
                        <option value="2">Vendors</option>
                        <option value="3">Distributors</option>
                        <option value="4">Employees</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <select class="form-control" name="filter-status">
                        <option value="0">Filter by Brand</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                        <option value="3">Suspended</option>
                        <option value="4">Online</option>
                        <option value="5">Offline</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <select class="form-control" name="filter-status">
                        <option value="0">Filter by Region</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                        <option value="3">Suspended</option>
                        <option value="4">Online</option>
                        <option value="5">Offline</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <select class="form-control" name="filter-status">
                        <option value="0">Filter by Province</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                        <option value="3">Suspended</option>
                        <option value="4">Online</option>
                        <option value="5">Offline</option>
                    </select>
                </div>
                <div class="col-lg-2 text-right">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle btn-light btn-sm" data-toggle="dropdown"><span>Branch Action</span>
                        </button>
                        <div class="dropdown-menu">
                            <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="file" name="file" id="file" style="display:none">
                                <button class="btn btn-primary btn-sm dropdown-item" type="button" value="Upload CSV" id="upload_csv">
                                    <span class="fa-upload"></span>
                                    <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                                    Upload CSV
                                </button>
                            </form>
                            <a href="{{url('/merchant/branch/add')}}" type="button" class="dropdown-item btn btn-success btn-sm pull-right" id="add">
                                <span class="fa-plus"></span>
                                Add
                            </a>
                            <form method="POST" id="deleteForm" action="{{ url('merchant/branch/bulkdelete')  }}" >
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="delete_ids" id="delete_ids"  value="">
                                <button class="dropdown-item btn btn-danger btn-sm" type="button" id="delete">
                                    <span class="mdi-delete-variant"></span>
                                    <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                                    Bulk Delete
                                </button>
                            </form>
                        </div>
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
                        <th>BusinessType</th>
                        <th>StoreType</th>
                        <th>Brand</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Region</th>
                        <th></th>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $branch->address }}</td>
                            <td>{{ $branch->city }}</td>
                            <td></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle btn-light btn-sm" data-toggle="dropdown"><span>Action</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{url('/merchant/branch/edit/' . $branch->branch_id )}}"><span class="fa-edit"></span> Update</a>
                                        <a class="dropdown-item deleteBranch" href="#" target-href="{{url('/merchant/branch/delete/' . $branch->branch_id )}}"><span class="mdi-delete"></span> Delete</a>
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

            $(document).on("click", "a.deleteBranch", function() {
                if (confirm('Are you sure to delete this record ?')) {
                    window.location.href = $(this).attr('target-href');
                }
            });

            $('#delete').click(function(e){
                let products = [];

                $.each($("input[name='branch_id']:checked"), function(){
                    products.push($(this).attr("id"));
                });

                $('#delete_ids').val(JSON.stringify(products));

                if (confirm('Are you sure to delete this records ?')) {
                    $("#deleteForm").submit();
                }
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
