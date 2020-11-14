@extends('concrete.layouts.main')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Please Input Product Details</div>
        </div>
        <div class="panel-body">
            <form class="form-vertical" id="add_product" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row form-group {{ $errors->first('product_name')? 'text-danger' : '' }}">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Product Name:</label>
                    </div>

                    <div class="col-sm-10">
                        <input required type="text" class="form-control {{ $errors->first('product_name')? 'form-control-danger' : '' }}" id="input_product_name" name="product_name" value="{{ old('product_name') }}" placeholder="Enter Product Name">
                        @if($errors->first('product_name'))
                        <div class="tag-manager-container">
                            <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('product_name') }}</span></span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="row form-group {{ $errors->first('product_description')? 'text-danger' : '' }}">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">Product Description:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" required class="form-control {{ $errors->first('product_description')? 'form-control-danger' : '' }}" id="input_product_description" name="product_description" value="{{ old('product_description') }}" placeholder="Enter Product Description">
                        @if($errors->first('product_description'))
                            <div class="tag-manager-container">
                                <span class="tm-tag badge badge-danger" ><span>{{ $errors->first('product_description') }}</span></span>
                            </div>
                        @endif
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
