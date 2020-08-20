@extends('adminlte::page')

@section('title', 'Trckr | Product Management')

@section('content_header')
    <h1>Product Management</h1>
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

    <p>Product Management</p>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card" style="width:100%">
                <div class="card-header">
                    <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" id="file" style="display:none">                

                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <button type="button" id="upload_csv" class="btn btn-block btn-primary btn-lg pull-right">Upload CSV</button>  
                            <a href="/merchant/product/add" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                            <button type="button" class="btn btn-primary btn-lg" id="edit">Edit</button>    
                            <button type="button" class="btn btn-primary btn-lg pull-right">Delete</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <form id="products_table" action="javascript:void(0)" >
                        <table class="table table-bordered">
                            <thead>                  
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Brand</th>
                                <th>Description</th>
                                <th style="width: 40px">Action?</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $p)
                            <tr>
                                <input type="hidden" name="row_product_id[]" value="{{ $p->product_id}}"/>
                                <td> {{ $p->no }}</td>
                                <td> {{ $p->product_name }}</td>
                                <td> {{ $p->product_description }}</td>
                                <td><input type="checkbox" name="product" id="{{$p->product_id}}"></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
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
            $('#myModal').on('hidden.bs.modal', function () {
                location.reload();
            });

            $('#edit').click(function(e){
                var products = [];
                $.each($("input[name='product']:checked"), function(){
                    alert($(this).attr("id"));
                    products.push($(this).attr("id"));
                });
                if (products.length != 1){
                    $(".modal-title").text("Invalid Edit Selection!");
                    $(".modal-body").html("<p>Please check one product only!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                window.location.href = "/merchant/product/edit?product_id=" + products[0];
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
                
                $.ajax({
                    type:'POST',
                    url: "/merchant/product/upload",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(".modal-title").text("Upload Product Successful!");
                        $(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    },
                    error: function(data){
                        $(".modal-title").text("Upload Product Failed!");
                        $(".modal-body").html("<p>" + data.responseText + "</p>");
                        //$(".modal-body").html("<p>" + data.message + "</p>");
                        $("#myModal").modal('show');
                    }
                });
            });
        });
  </script>
@stop