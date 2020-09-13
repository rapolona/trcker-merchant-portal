@extends('adminlte::page')

@section('title', 'Trckr | View Campaigns')

@section('content_header')
    <h1>Campaign</h1>
@stop

@section('content')
    <p>Insert text here</p>
    <div class="row">
        <div class="card" style="width:100%">
            <div class="col col-lg-12">
                <div class="card-header">
                    <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                        <div class="btn-group float-lg-right" role="group" aria-label="Basic example">
                            <a href="/campaign/create" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                            <button type="button" class="btn btn-primary btn-lg" id="edit">Edit</button>    
                            <button type="button" class="btn btn-primary btn-lg" id="delete">Delete</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>                  
                        <tr>
                            <th style="width: 10px">#</th>
                            <th >Campaign Name</th>
                            <th >Budget</th>
                            <th >Duration</th>
                            <th >Status</th>
                            <th style="width: 40px">Action?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($campaigns as $c)
                        <tr>
                            <input class="view_id" type="hidden" name="row_campaign_id[]" value="{{$c->campaign_id}}"/>
                            <td class="view"> {{ $c->no }}</td>
                            <td class="view"> {{ $c->campaign_name }}</td>
                            <td class="view"> {{ $c->budget }}</td>
                            <td class="view"> {{ $c->duration }}</td>
                            <td class="view"> {{ $c->status }}</td>
                            <td><input type="checkbox" name="campaigns[]" id="campaigns[]" value="{{$c->campaign_id}}"></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
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
            $('.view').click(function(){
                var campaign_id = $(this).siblings('.view_id').val();
                
                window.location.href = "/campaign/view_campaign?campaign_id=" + campaign_id;
            });

            $('#file_upload').submit(function(e) {
                //e.preventDefault();

                var formData = new FormData(this);
        
                $.ajax({
                    type:'POST',
                    url: "/campaign/create_campaign",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        alert('File has been uploaded successfully');
                        console.log(data);
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            });
        });
  </script>
@stop