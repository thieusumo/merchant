@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Management | Tax Forms')
@section('styles')
@stop
@section('content')
<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

<div class="x_panel">
<form action="" method="post" id="tax_form" name="tax_form" class="form-inline"> 
    <div class="form-group" style="margin-right:10px;">                                                                            
        <div class="input-group-sm">
          <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
          <input type="text" style="width: 200px" id="get_date" class="form-control form-control-sm" value=""/>
          <input type="hidden" style="width: 200px" name="join_date" id="join_date" class="form-control form-control-sm" value=""/>
        </div>                    
    </div>
    <div class="form-group">  
        {{-- <button type="submit" class="btn btn-sm btn-primary" style="margin-bottom:0px;">Search</button> --}}
        <div class="form-group form-group-sm active_group">
        {{-- <button class="btn btn-sm btn-default reset"  style="margin-bottom:0px;">Clear</button> --}}
         <button id="today" class="btn btn-sm btn-default search" search="today" style="margin-bottom:0px;">TODAY </button> 
         <button class="btn btn-sm btn-default search" search="daily" style="margin-bottom:0px;">DAILY</button> 
         <button class="btn btn-sm btn-default search" search="weekly" style="margin-bottom:0px;">WEEKLY</button> 
         <button class="btn btn-sm btn-default search" search="monthly" style="margin-bottom:0px;">MONTHLY</button> 
      </div>
    </div>
</form>
</div>    
<div class="x_panel">   
    <table width="100%" id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">ID</th>          
        <th>Full Name </th>
        <th>Nick Name </th>
        <th>Birthday </th>
        <th class="text-center">Phone</th>              
        <th class="text-center">Email</th>              
        <th class="text-center">Start Date</th>     
        <th class="text-center" width="400">Action</th>        
      </tr>
    </thead>
</table>   
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('input.datepicker').daterangepicker({
        singleDatePicker: true,
        // minDate: moment().subtract(10, 'years'),
        // maxDate: moment(),
        showDropdowns: true
    });

    $('.active_group button').on('click', function(){
        $('.active_group button').removeClass('active').removeClass('btn-primary').addClass("btn-default");
        $(this).removeClass('btn-default').addClass('active').addClass('btn-primary');
        var time_format =  $(this).attr('id');
        $("#time_format_hidden").val(time_format);
        // oTable.draw();
    });


   $('#join_date').daterangepicker({ 
       autoUpdateInput: false,

      locale: {
        cancelLabel: 'Clear'
      }
   }); 
   $('#get_date').daterangepicker({ 
      // autoUpdateInput: false,
      singleDatePicker: true,
      showDropdowns: true,      
   }); 

  $('#join_date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('#join_date').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });

        oTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,
             buttons: [
                {
                    text: 'Form Tax 1096',                    
                    className: "btn-sm",
                    action: function ( e, dt, node, config ) {
                        var date = $("#get_date").val();
                        var d = new Date(date);       
                        var year = d.getFullYear();
                        document.location.href = "{{ route('tax-form-1096') }}?search="+year+"";
                    }
                },
                {
                    text: '<i class="glyphicon glyphicon-export"></i> Export Time Sheet',                    
                    className: "btn-sm",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{ route('all-time-sheet') }}";
                    }
                }
             ],
             columnDefs: [
             {
                  "targets": 0,
                  "className": "text-center",
             },
             {
                  "targets": 1,
                  "className": "text-center",
             },
             {
                  "targets": 2,
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
             },
             {
                  "targets": 7,
                  "className": "text-center",
             }
             ],               
             ajax:{ url:"{{ route('get-worker-taxform') }}",
                 data: function (d) {
                          d.date_order = $('#btnDate').val();
                          d.time_format = $('#time_format_hidden').val();
                          d.search_join_date = $('#join_date').val();
                          d.get_date = $("#get_date").val();
                    }
                  },
                 columns: [
                          { data: 'worker_id', name: 'worker_id' },
                          { data: 'worker_fullname', name: 'worker_fullname', orderable: false, searchable: false  },
                          { data: 'worker_nickname', name: 'worker_nickname' },
                          { data: 'worker_birthday', name: 'worker_birthday' },
                          { data: 'worker_phone', name: 'worker_phone' },
                          { data: 'worker_email', name: 'worker_email' },
                          { data: 'worker_date_join', name: 'worker_date_join'},
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                ]   
       });
       $('#tax_form').on('submit', function(e) {
          var getDate = $("#get_date").val();
          if(getDate == ''){
            toastr.warning('Please Enter Date');
            return false;

          }
          oTable.draw();
          e.preventDefault();
          
      });

});
 $(document).on('click','.reset', function(e) {
  $("#tax_form")[0].reset();
  $("#join_date").val('');
        var d = new Date();          
        var Month = d.getMonth() + 1;
        var startDay = d.getDate();      
        var Year = d.getFullYear();          
        var endDay = startDay;
        var startMonth = Month;
        var endMonth = Month;

        $("#get_date").val(Month+'/'+startDay+'/'+Year);
  oTable.draw();
  e.preventDefault();
      });
</script> 

<script>
  $(document).ready(function(){

    $(".search").on('click',function(){
      var search = $(this).attr('search');

      if(search == 'today'){
        var d = new Date();          
        var Month = d.getMonth() + 1;
        var startDay = d.getDate();      
        var Year = d.getFullYear();          
        var endDay = startDay;
        var startMonth = Month;
        var endMonth = Month;

        $("#get_date").val(Month+'/'+startDay+'/'+Year);
      } else if(search == 'daily'){
        var date = $("#get_date").val();
        var d = new Date(date);   
       
        var Month = d.getMonth() + 1;
        var startDay = d.getDate();      
        var Year = d.getFullYear();         
        var endDay = startDay;
        var startMonth = Month;
        var endMonth = Month;
       
      } else if(search == 'weekly'){
        var date = $("#get_date").val();
        var d = new Date(date);
        var firstday = new Date(d.setDate(d.getDate() - d.getDay()));
        var lastday = new Date(d.setDate(d.getDate() - d.getDay()+6));
        
        var startDay = firstday.getDate();      
        var Year = d.getFullYear();
        var endDay = lastday.getDate();
        if(startDay > endDay){
          var startMonth = d.getMonth();
          var endMonth = d.getMonth() + 1;
        } else {
          var startMonth = d.getMonth() + 1;
          var endMonth = startMonth;
        }

      } else if(search == 'monthly'){
        var date = $("#get_date").val();
        var d = new Date(date);  

        var Month = d.getMonth() + 1;
        var startDay = 1;      
        var Year = d.getFullYear();
        var startMonth = Month;
        var endMonth = Month;
        //get max date In Month
        var dateInMonth = new Date(Year, Month, 0).getDate();     
        var endDay = dateInMonth;
      }

      var startDate = startMonth+'/'+startDay+'/'+Year;
      var endDate = endMonth+'/'+endDay+'/'+Year;
      $("#join_date").val(startDate + ' - ' + endDate);

    });

  });
</script>      
<script>
  $(document).ready(function(){ 
    $("#today").trigger('click');
  });
</script>

@stop

