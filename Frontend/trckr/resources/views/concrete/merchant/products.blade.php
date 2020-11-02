@extends('concrete.layouts.main')

@section('content')
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <span class="panel-icon fa-tasks"></span> <span>Merchant Products</span>
                </div>
            </div>
            <div class="panel-body p-0">
                <div class="btn-group" style="margin-top: 10px">
                    <button class="btn btn-primary btn-sm" type="button" value="Upload CSV" id="upload_csv">
                        <span class="fa-upload"></span>
                        <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                        Upload CSV
                    </button>
                    <a href="{{url('/merchant/product/add')}}" type="button" class="btn btn-primary btn-sm pull-right" id="add">
                        <span class="fa-plus"></span>
                        Add
                    </a>
                </div>
                <div class="table-responsive scroller scroller-horizontal py-3">
                    <table class="table table-striped table-hover" style="min-width: 800px">
                        <thead>
                        <tr>
                            <th style="width: 40px">
                                <div class="custom-control custom-checkbox custom-checkbox-success">
                                    <input class="custom-control-input" type="checkbox" id="chkAll"/>
                                    <label class="custom-control-label" for="chkAll"></label>
                                </div>
                            </th>
                            <th>Brand</th>
                            <th>Description</th>
                            <th style="with:100px">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($products as $product)
                            <tr>
                                <td style="width: 40px">
                                    <div class="custom-control custom-checkbox custom-checkbox-success">
                                        <input class="custom-control-input" type="checkbox" id="{{ $product->product_id }}" />
                                        <label class="custom-control-label" for="{{ $product->product_id }}"></label>
                                    </div>
                                </td>
                                <td> {{ $product->product_name }}</td>
                                <td> {{ $product->product_description }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-warning btn-sm" type="button" href=""><span class="fa-edit"></span></a>
                                        <a class="btn btn-danger btn-sm" type="button" href=""><span class="mdi-delete"></span></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop


@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {
            $('#delete').click(function(e){
                var formData = new FormData();
                var products = [];

                $.each($("input[name='product']:checked"), function(){
                    products.push($(this).attr("id"));
                });

                if (products.length < 1){
                    $(".modal-title").text("Invalid Delete Selection!");
                    $(".modal-body").html("<p>Please check at least one product!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                formData.append('products', products);
                formData.append('_token', "{{ csrf_token() }}");

                post("{{url('/merchant/product/delete')}}", "Delete Product", "delete", formData, "{{url('/product')}}");
            });

            $('#edit').click(function(e){
                var products = [];
                $.each($("input[name='product']:checked"), function(){
                    products.push($(this).attr("id"));
                });
                if (products.length != 1){
                    $(".modal-title").text("Invalid Edit Selection!");
                    $(".modal-body").html("<p>Please check one product only!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                window.location.href = "{{url('/merchant/product/edit?product_id=')}}" + products[0];
            });

            $('#upload_csv').click(function(e){
                $("#file").click();
            });

            $('#file').change(function(){
                $('#file_upload').submit();
            });

            $('#file_upload').submit(function(e) {
                var formData = new FormData(this);

                post("{{url('/merchant/product/upload')}}", "Upload CSV", "upload_csv", formData, "{{url('/merchant/product')}}");
            });
        });
  </script>
@stop
