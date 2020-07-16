@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Sales & Finances | Tickets')
@section('styles')
@stop
@section('content')
@include('salefinance.partials.message')
   <div class="x_panel">
<form action="" method="post" id="calendar_form" name="calendar_form" class="form-inline">                      
       <div class="form-group col-sm-3">                                                                            
              <div class="input-group-sm">
                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                <input type="text" style="width: 200px" name="order_date" placeholder="from - to" id="order_date" class="form-control form-control-sm" value="" />
              </div>                    
          </div>					
        <div class="form-group col-md-3">                                     
             <input type="text" id="ex4" id="search_namephone" name="search_namephone" class="form-control form-control-sm" placeholder="Customer Name or Phone" style="width: 300px">            
        </div>	
      <div class="form-group col-md-2">                                     
        <select id="select_status" name="select_status" class="form-control form-control-sm">
           {!!GeneralHelper::getDropdownBookingStatus() !!}
         </select>
        </div>  
    <div class="form-group form-actions col-md-2">
        <button type="submit" class="btn btn-sm btn-primary" style="margin-bottom: 0px;">Search</button>
          <button class="btn btn-sm btn-default" id="ticket_reset" type="reset" style="margin-bottom: 0px;">Clear</button>
    </div>
</form>
</div>    
<div class="x_panel">    
<table id="datatable" class="table table-striped table-bordered" >
    <thead>
      <tr>
        <th class="text-center" width="80" nowrap="nowrap">Booking ID </th>
        <th class="text-center" width="80" >Date</th>
        <th class="text-center" width="40" >Time</th>
        <th width="60" >Duration</th>
        <th width="160" >Customer</th>        
        <th>Rent Station & Service</th>       
        <th>Booking Type</th>
        <th class="text-center" width="120">Status</th>
        <th class="text-center">Action</th>          
      </tr>
    </thead>

</table>      
</div>
@stop
@section('scripts')
<!-- excelHTML5 tri-->
<script type="text/javascript" src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<!-- end excel tri-->

<script type="text/javascript">
   $(document).ready(function() {
   $('#order_date').daterangepicker({ 
       autoUpdateInput: false,

      locale: {
        cancelLabel: 'Clear'
      }
   }); 

  $('#order_date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('#order_date').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });
    
        oTableTicket = $('#datatable').DataTable({
             dom: "lBfrtip",
             buttons: [
                 {   
                     extend: 'csv', 
                     text: '<i class="glyphicon glyphicon-plus"></i> Add New',
                     className: "btn-sm btn-add",
                     action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('edit-booking')}}";
                    }
                 },
                 // {   
                 //     extend: 'csv', 
                 //     text: '<i class="glyphicon glyphicon-export"></i> Export',
                 //     className: "btn-sm"
                 // },
                 { 
                    extend:'excel',
                    text: '<i class="glyphicon glyphicon-export"></i> Export',
                    className: "btn-sm",
                    exportOptions: {
                      modifier: {
                        page: 'all', 
                      }
                    }
                  },
             ],  
             processing: true,
             serverSide: true,
         ajax:{ url:"{{ route('get-booking-place') }}",
         data: function (d) {
                d.order_date = $('input[name=order_date]').val();
                d.select_status = $('#select_status :selected').val();
                d.search_namephone = $('input[name=search_namephone]').val();
              } 
          },
         columns: [

                  { data: 'booking_id', name: 'booking_id' },
                  { data: 'booking_date', name: 'booking_date' },
                  { data: 'booking_time', name: 'booking_time' },
                  { data: 'duration' , name:'duration'},
                  { data: 'customer_name_phone' , name:'customer_name_phone'},
                  { data: 'rentstation_service' , name:'rentstation_service'},
                  { data: 'booking_type' , name:'booking_type'},
                  { data: 'status' , name: 'status',  orderable: false, searchable: false },
                  { data: 'action' , name: 'action',  orderable: false, searchable: false }
          ],
         columnDefs: [
            {
                "targets": 0, 
                "className": "text-center"
           },
           {
                "targets": 1, 
                "className": "text-center"
           },
           {
                "targets": 2, 
                "className": "text-center"
           },
           {
                "targets": 3, 
                "className": "text-right"
           },
            {
                "targets": 4, 
                "className": "text-left"
           },
           {
                "targets": 5,
                "className": "text-left",
           },
           {
                "targets": 6,
                "className": "text-center",
           },
           {
                "targets": 7,
                "className": "text-center",
           },
           {
                "targets": 8,
                "className": "text-center",
           }
           ],         
       }); 

      $('#calendar_form').on('submit', function(e) {
          oTableTicket.draw();
          e.preventDefault();
      });
       $(document).on('click','#ticket_reset', function(e) {
          $("#calendar_form")[0].reset();
          oTableTicket.draw();
          e.preventDefault();
      });
      @if(session()->has('message_booking'))
        $('#message_booking').modal('show');
      @endif
    
}); 
</script>    
@stop

