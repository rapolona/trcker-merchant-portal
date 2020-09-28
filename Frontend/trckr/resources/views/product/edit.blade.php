@extends('adminlte::page')

@section('title', 'Trckr | Edit Product')

@section('content_header')`
    <h1>Edit Product</h1>
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
    <p>Edit Product</p>

    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form class="form-vertical" id="edit_product">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">                
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Product Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_product_name" name="product_name" value="{{ ($product->product_name) ? $product->product_name : ''}}" placeholder="Enter Product Product Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Product Description</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_product_description" name="product_description" value="{{ ($product->product_description) ? $product->product_description : ''}}" placeholder="Enter Product Description">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="file" name="file" id="file" style="display:none">                
                            <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                                <button class="btn btn-primary btn-lg" type="submit" value="Edit Product" id="edit">
                                    <span class="spinner-border spinner-border-sm" role="status" id="loader_edit" aria-hidden="true" disabled> </span>
                                    Edit Product
                                </button>
                                <button type="button" class="btn btn-danger btn-lg pull-right" id="back">Back</button>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@stop

@section('css')
    <link rel="stylesheet" href="#">
@stop

@section('js')
    <script type="text/javascript" src="/vendor/trckr/trckr.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function (e) { 
            $('#edit_product').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append("product_id", "{{ $product_id }}");
        
                post("/merchant/product/edit", "Edit Product", "edit", formData, "/merchant/product");                
            });

            $("#back").click(function(){
                window.location.href = "/merchant/product";
            });
        });
    </script>
@stop