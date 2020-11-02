@extends('concrete.layouts.main')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Please Input Product Details</div>
        </div>
        <div class="panel-body">
            <form class="form-vertical" id="add_product" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Product Name:</label>
                    </div>
                    <div class="col-sm-10">
                        <input required type="text" class="form-control" id="input_product_name" name="product_name" value="" placeholder="Enter Product Name">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Product Description:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" required class="form-control" id="input_product_description" name="product_description" value="" placeholder="Enter Product Description">
                    </div>
                </div>

                <div class="row pull-right">
                    <div class="col-sm-12">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true" disabled> </span>
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
