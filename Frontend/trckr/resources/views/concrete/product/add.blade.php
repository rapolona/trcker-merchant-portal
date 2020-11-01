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
    <p>Add Product</p>

    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form class="form-vertical" id="add_product">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Product Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_product_name" name="product_name" value="" placeholder="Enter Product Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Product Description</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_product_description" name="product_description" value="" placeholder="Enter Product Description">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="file" name="file" id="file" style="display:none">

                            <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                                <button class="btn btn-primary btn-lg" type="submit" value="Add Product" id="add">
                                    <span class="spinner-border spinner-border-sm" role="status" id="loader_add" aria-hidden="true" disabled> </span>
                                    Add Product
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {
            $('#add_product').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                post("{{url('/merchant/product/add')}}", "Add Product", "add", formData, "{{url('/merchant/product')}}");
            });

            $("#back").click(function(){
                window.location.href = "{{url('/merchant/product')}}";
            });
        });
    </script>
@stop
