@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Sales & Finances | Order History')
@section('styles') 
@stop
@section('content')
<div class="x_panel">
<form action="" method="post" id="calendar_form" name="calendar_form" class="form-inline">                      
       <div class="form-group col-sm-3">                                                                            
              <div class="input-group-sm">
                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                <input type="text" style="width: 200px" name="order_date" id="order_date" class="form-control form-control-sm" value="" />
              </div>                    
          </div>										           
      <div class="form-group col-md-2">                                     
        <select id="select_type" name="select_type" class="form-control form-control-sm">
           {!!GeneralHelper::getDropdownPaymentType()!!}  
         </select>
        </div>  
    <div class="form-group col-md-2">
           <button type="submit" class="btn btn-sm btn-primary">Search</button>
          <button class="btn btn-sm btn-default" id="order_reset" type="reset">Clear</button>
    </div>
</form>
</div>    
<div class="x_panel">    
<table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Order ID </th>
        <th>Order Date</th>
        <th>Customer</th>
        <!-- <th>Rent Station</th>   -->      
        <th>Amount($)</th>        
        <th>Tip($)</th>
        <th>Total Charge($)</th>        
        <th>Payment Type</th>        
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
    
   $('input[name=order_date]').daterangepicker({ 
       autoUpdateInput: false,

      locale: {
        cancelLabel: 'Clear'
      }
   }); 

   $('input[name=order_date]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('input[name=order_date]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });

    oTableOrder = $('#datatable').DataTable({
             dom: "lBfrtip",
             buttons: [
                 // {   
                 //     extend: 'csv', 
                 //     text: '<i class="glyphicon glyphicon-export"></i> Export',
                 //     className: "btn-sm"
                 // }
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
         ajax:{ url:"{{ route('get-order-history') }}",
         data: function (d) {
                d.order_date = $('input[name=order_date]').val();
                d.select_type = $('#select_type :selected').val();
                d.search_customer = $('input[name=search_customer]').val();
                d.search_rentstation = $('input[name=search_rentstation]').val();
              } 
          },
         columns: [

                  { data: 'order_id', name: 'order_id' },
                  { data: 'order_datetime_payment', name: 'order_datetime_payment' },
                  { data: 'customer_fullname', name: 'customer_fullname' },
                  /*{ data: 'worker_name' , name:'worker_name'},*/
                  { data: 'amount' , name:'amount'},
                  { data: 'tip' , name:'tip'},
                  { data: 'order_price' , name:'order_price'},
                  { data: 'status' , name: 'status',  orderable: false, searchable: false }
          ],
         order: [[ 0, "desc" ]],
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
                "className": "text-left"
           },
           {
                "targets": 3, 
                "className": "text-left"
           },
            {
                "targets": 4, 
                "className": "text-right"
           },
           {
                "targets": 5,
                "className": "text-right",
           },
           {
                "targets": 6,
                "className": "text-right",
           }
           ],         
       }); 

      $('#calendar_form').on('submit', function(e) {
          oTableOrder.draw();
          e.preventDefault();
      });
      $(document).on('click','#order_reset', function(e) {
          $("#calendar_form")[0].reset();
          oTableOrder.draw();
          e.preventDefault();
      });
    
}); 
</script>    
@stop

