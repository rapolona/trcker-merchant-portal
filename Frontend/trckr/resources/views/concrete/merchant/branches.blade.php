@extends('concrete.layouts.main')

@section('breadcrumbs_pull_right')
    <div class="group-10">
        <!-- <a class="btn btn-light" href="{{ url('/merchant/branch/add') }}"><span class="fa-plus"></span><span class="pl-2">Add New Branch</span></a> -->
        <div class="btn-group">
          <a class="btn btn-outline-primary" href="{{ url('/merchant/branch/add') }}"><span class="fa-plus">Add a new branch</span></a>
<!--           <a class="btn btn-dark" href="#"><span class="fa-upload">Upload CSV</span></a> -->
        </div>

    </div>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-header d-flex flex-wrap align-items-center justify-content-between">
            <div class="panel-title">Merchant Branches</div>
            <div class="btn-group">
                <form method="POST" enctype="multipart/form-data" id="file_upload" action="{{ url('merchant/branch/upload') }}" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="file" name="file" id="file" style="display:none">
                    <button class="btn btn-light btn-sm" type="button" value="Upload CSV" id="upload_csv">
                        <span class="fa-upload"></span>
                        <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                        Upload CSV
                    </button>
                </form>
                <form method="POST" id="deleteForm" action="{{ url('merchant/branch/bulkdelete')  }}" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="delete_ids" id="delete_ids"  value="">
                    <button class="btn btn-danger btn-sm" type="button" id="delete">
                        <span class="mdi-delete-variant"></span>
                        <span class="spinner-border spinner-border-sm" role="status" id="loader_upload_csv" aria-hidden="true" disabled> </span>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="panel-menu">
            <div class="row row-30">
                <div class="col-lg-2">
                    @if(isset($filters->business_type))
                    <select class="select2 hustle-filter" data-placeholder="Business Type" name="business_type">
                        <option label="placeholder"></option>
                        <option value="all">ALL</option>
                        @foreach ($filters->business_type as $option)
                            @if(!empty($option))
                            <option {{ (isset($selectedFilter['business_type']) && $selectedFilter['business_type']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                            @endif
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="col-lg-2">
                    @if(isset($filters->store_type))
                        <select class="select2 hustle-filter" data-placeholder="Store Type" name="store_type">
                            <option label="placeholder"></option>
                            <option value="all">ALL</option>
                            @foreach ($filters->store_type as $option)
                                @if(!empty($option))
                                    <option {{ (isset($selectedFilter['store_type']) && $selectedFilter['store_type']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-lg-2">
                    @if(isset($filters->brand))
                        <select class="select2 hustle-filter" data-placeholder="Brand" name="brand">
                            <option label="placeholder"></option>
                            <option value="all">ALL</option>
                            @foreach ($filters->brand as $option)
                                @if(!empty($option))
                                    <option {{ (isset($selectedFilter['brand']) && $selectedFilter['brand']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-lg-2">
                    @if(isset($filters->province))
                        <select class="select2 hustle-filter" data-placeholder="Province" name="province">
                            <option label="placeholder"></option>
                            <option value="all">ALL</option>
                            @foreach ($filters->province as $option)
                                @if(!empty($option))
                                    <option {{ (isset($selectedFilter['province']) && $selectedFilter['province']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-lg-2">
                    @if(isset($filters->city))
                        <select class="select2 hustle-filter" data-placeholder="City" name="city">
                            <option label="placeholder"></option>
                            <option value="all">ALL</option>
                            @foreach ($filters->city as $option)
                                @if(!empty($option))
                                    <option {{ (isset($selectedFilter['city']) && $selectedFilter['city']==$option)? 'selected="selected"' : '' }} value="{{ $option }}">{{ $option }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>

        <div class="panel-body p-0">
                <div class="table-responsive scroller scroller-horizontal py-3">
                    <table class="table table-striped table-hover data-table" data-table-searching="true" data-table-lengthChange="true" data-page-length="5">
                        <thead>
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-checkbox-light">
                                    <input class="custom-control-input" type="checkbox" id="selectAll"/>
                                    <label class="custom-control-label" for="selectAll"></label>
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
                                <div class="custom-control custom-checkbox custom-checkbox-light">
                                    <input class="custom-control-input" type="checkbox" name="branch_id" id="{{ $branch->branch_id }}" />
                                    <label class="custom-control-label" for="{{ $branch->branch_id }}"></label>
                                </div>
                            </td>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->business_type }}</td>
                            <td>{{ $branch->store_type }}</td>
                            <td>{{ $branch->brand }}</td>
                            <td>{{ $branch->address }}</td>
                            <td>{{ $branch->city }}</td>
                            <td>{{ $branch->region }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle btn-light btn-sm" data-toggle="dropdown"><span>Action</span></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{url('/merchant/branch/edit/' . $branch->branch_id )}}"> Update</a>
                                        <a class="dropdown-item deleteBranch" href="#" target-href="{{url('/merchant/branch/delete/' . $branch->branch_id )}}"> Delete</a>
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

@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function (e) {

            setTimeout(function(){
                $('.table').DataTable( {
                    "destroy": true,
                    "ordering": false
                } );
            }, 2000);



            $('#selectAll').click(function(e){
                let table= $(e.target).closest('table');
                $('td input:checkbox',table).prop('checked',this.checked);
            });

            $(document).on("click", "a.deleteBranch", function() {
                if (confirm('Are you sure you want to delete this item ?')) {
                    window.location.href = $(this).attr('target-href');
                }
            });

            let formFilters = new Object();

            $('select.hustle-filter').on('change', function(e) {
                let selected = $("select.hustle-filter :selected").map(function(i, el) {
                    let oVal = $(el).val();
                    formFilters[$(el).parent().attr('name')] = (oVal=='all')? '' : oVal;
                }).get();

                window.location.href = "{{ url('merchant/branch') }}?" + $.param(formFilters);
            });


            $('#delete').click(function(e){
                let products = [];

                $.each($("input[name='branch_id']:checked"), function(){
                    products.push($(this).attr("id"));
                });

                $('#delete_ids').val(JSON.stringify(products));

                if (confirm('Are you sure you want to delete these items ?')) {
                    $("#deleteForm").submit();
                }
            });


            $('#upload_csv').click(function(e){
                $("#file").click();
            });

            $('#file').change(function(){
                $('#file_upload').submit();
            });
        });
    </script>
@stop
