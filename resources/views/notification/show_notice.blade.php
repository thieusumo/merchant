@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Notification | show')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
@stop
@section('content')
<div class="x_panel">
<form action="" method="get" action="{{ route('getNotification') }}" id="service_form" name="service_form" class="form-inline">   
    <div class="form-group" style="margin-right:10px;">                                     
        <select class="form-control form-control-sm" name="worker_status" id="search_worker_status">
           <option value="">-- All Status-- </option>
           <option value="1" >Readed</option>
           <option value="0">Unread</option>
         </select>
    </div> 
    <div class="form-group">  
        <button type="submit" class="btn btn-sm btn-primary" style="margin-bottom:0px;">Search</button>
          <button class="btn btn-sm btn-default" id="staff_reset" type="reset" style="margin-bottom:0px;">Clear</button>
    </div>
</form>
</div>    
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">ID</th>          
        <th width="120">Type</th>
        <th width="80">Notification </th>
        <th width="80">link</th>
        <th width="80">read</th>        
        <th width="80">created</th>              
        <th class="text-center" width="50">Action</th>    
      </tr>
    </thead>
      
</table>   
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';          
        oTable= $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,
             autoWidth: true,
             buttons: [
             ],
             columnDefs: [
             {
                  "targets": 0,
                  "className": "text-center",
             },
             {
                  "targets": 3,
                  "className": "text-left",
             },
             {
                  "targets": 4,
                  "className": "text-center",
             },
             {
                  "targets": 5,
                  "className": "text-center",
             },
             {
                  "targets": 6,
                  "className": "text-center",
             }
             ],
             ajax:{ 
                 url:"{{ route('getNotification') }}",
                 data: function (d) {
                    // debugger;
                        d.search_worker_status = $('#search_worker_status :selected').val();
                    }
                  },
                 columns: [
                          { data: 'id', name: 'id' },
                          { data: 'notification_type', name: 'notification_type' },
                          { data: 'notification_message', name: 'notification_message' },
                          { data: 'notification_link', name: 'notification_link' },
                          { data: 'notification_readed', name: 'notification_readed' },
                          { data: 'created_at', name: 'created_at' },
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                ]

       }); 

      $('#service_form').on('submit', function(e) {
          oTable.draw();
          e.preventDefault();
      });
}); 
</script>  
@stop

