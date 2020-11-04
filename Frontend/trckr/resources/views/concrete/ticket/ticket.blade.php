@extends('concrete.layouts.main')

@section('breadcrumbs_pull_right')
    <button class="btn btn-light" type="button"><span class="fa-cloud-download"></span><span class="pl-2">Download Report</span></button>
@endsection

@section('content')

    <section class="section-sm">
    <div class="col-12">
      <div class="panel">
        <div class="panel-body p-0">
          <div class="table-responsive scroller scroller-horizontal py-3">
            <table class="table table-striped table-hover data-table" data-table-searching="true" data-table-lengthChange="true" data-page-length="5" >
              <thead>
                <tr>
                  <th class="no-sort"></th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Device ID</th>
                  <th>Campaign Name</th>
                  <th>Date Submitted</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($tickets as $t)
                <tr>
                    <input class="view_id" type="hidden" name="row_task_ticket_id[]" value="{{ $t->task_ticket_id }}"/>
                    <td>
                        <div class="custom-control custom-checkbox custom-checkbox-light">
                          <input class="custom-control-input" type="checkbox" id="lightCheck">
                          <label class="custom-control-label" for="lightCheck"></label>
                        </div>
                    </td>
                    <td>{{ $t->user_detail->first_name . " " . $t->user_detail->last_name }}</td>
                    <td>{{ $t->user_detail->email }}</td>
                    <td>09178478820 -- add in the API</td>
                    <td>{{ $t->device_id}}</td>
                    <td>{{ $t->campaign_name }}</td>
                    <td>{{ $t->createdAt }}</td>
                    <td class="text-warning">{{ $t->approval_status }}</td>
                    <td>
                        <div class="btn-group">
                          <a href="{{ url('ticket/view_ticket/' . $t->task_ticket_id ) }}"><button class="btn btn-light" type="button"><span class="fa-eye"></span></button></a>
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
</section>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript" src="{{url('/vendor/trckr/trckr.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $("#export").click(function(){
                window.location.href = "{{url('/ticket/export_csv')}}";
            });

            $('#myModal').on('hidden.bs.modal', function (e) {
                window.location.href = "{{url('/ticket/view')}}";
            });

            $('.view').click(function(){
                var ticket_id = $(this).siblings('.view_id').val();

                window.location.href = "{{url('/ticket/view_ticket?ticket_id=')}}" + ticket_id;
            });

            $("#approve").click(function(){
                $("#action").val('approve');
            });

            $("#reject").click(function(){
                $("#action").val('reject');
            });

            $('#handle_ticket').submit(function(e){
                var formData = new FormData(this);

                var action = formData.get('action');

                var task_ticket_id = [];

                console.log(task_ticket_id);
                $.each($("input[name='task_ticket_id']:checked"), function(){
                    task_ticket_id.push($(this).attr("value"));
                });

                if (task_ticket_id.length < 1){
                    $(".modal-title").text("Invalid Delete Selection!");
                    $(".modal-body").html("<p>Please check at least one product!</p>");
                    $("#myModal").modal('show');
                    return;
                }

                console.log(task_ticket_id);

                formData.append('task_ticket_id', task_ticket_id);
                formData.append('_token', "{{ csrf_token() }}");

                var actiontext = action.charAt(0).toUpperCase() + action.slice(1);

                post("{{url('/ticket/')}}/" + action + "_ticket", actiontext + " Ticket(s)", action, formData, "{{url('/ticket/view')}}");
            });
        });
  </script>
@stop
