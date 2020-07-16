@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Management | Rent Stations')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
@stop
@section('content')
<div class="x_panel">
<form name="service_form" class="form-inline">   
    <div class="form-group">  
        <button status="1" id='active' type="button" class="btn btn-sm btn-primary" style="margin-bottom:0px;">Active</button>
    </div>
    <div class="form-group">  
        <button status="0" id="inactive" type="button" class="btn btn-sm btn-default" style="margin-bottom:0px;">Inactive</button>
    </div>
</form>
</div>    
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">ID</th>          
        <th width="120">Full Name </th>
        <th width="80">Nick Name </th>
        <th width="80">Phone</th>        
        <th width="80">Avatar</th>                
        <th class="text-center" width="50">Status</th>                   
        <th width="50" class="text-center">Start Date</th>            
        <th width="80" >Last Update</th>
        <th class="text-center" width="50">Action</th>        
      </tr>
    </thead>
      
</table>   
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<!-- excelHTML5 tri-->
<script type="text/javascript" src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<!-- end excel tri-->
<script type="text/javascript">
$(document).ready(function() {

  var search_worker_status="1";
  $("#active").click(function(e){
      $("#inactive").removeClass("btn btn-sm btn-primary").addClass("btn btn-sm btn-default");
       $("#active").removeClass("btn btn-sm btn-default").addClass("btn btn-sm btn-primary");
       search_worker_status=$(this).attr('status');
       oTable.draw();
        e.preventDefault();
  });
  $("#inactive").click(function(e){
    $("#active").removeClass("btn btn-sm btn-primary").addClass("btn btn-sm btn-default");
     $("#inactive").removeClass("btn btn-sm btn-default").addClass("btn btn-sm btn-primary");
     search_worker_status=$(this).attr('status');
     oTable.draw();
      e.preventDefault();
  });


    $.fn.dataTable.ext.errMode = 'none';
   $('#search_join_date').daterangepicker({ 
       autoUpdateInput: false,

      locale: {
        cancelLabel: 'Clear'
      }
   }); 

  $('#search_join_date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('#search_join_date').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });

   if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#0874e8',
                className : 'switchery switchery-small'                
            });
        });
   }
          
        oTable= $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,
             autoWidth: true,
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> ADD RENT STATION',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "staff";
                    }
                },
                // {   
                //      extend: 'csv', 
                //      text: '<i class="glyphicon glyphicon-export"></i> Export',
                //      className: "btn-sm"
                //  }
                { extend:'excel',
                  text: '<i class="glyphicon glyphicon-export"></i> Export',
                  className: "btn-sm",
                  exportOptions: {
                    modifier: {
                      page: 'all', 
                    }
                  },
                 //the remaining buttons here 
                }
             ],
             columnDefs: [
              
             {
                  "targets": 0,
                  "className": "text-center",
             },
             {
                  "targets": 3,
                  "className": "text-center",
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
             ,
             {
                  "targets": 8,
                  "className": "text-center nowrap",
             }
             ],
             ajax:{ url:"{{ route('get-workers') }}",
                 data: function (d) {
                        // d.search_join_date = $('#search_join_date').val();
                        d.search_worker_status = search_worker_status;
                    }
                  },
                 columns: [

                          { data: 'worker_id', name: 'worker_id' },
                          { data: 'worker_fullname', name: 'worker_fullname'},
                          { data: 'worker_nickname', name: 'worker_nickname' },
                          { data: 'worker_phone', name: 'worker_phone' },
                          { data: 'avatar', name: 'avatar' },
                          { data: 'status', name: 'worker_status',  orderable: false, searchable: false },
                          { data: 'worker_date_join', name: 'worker_date_join'},
                          { data: 'updated_at', name: 'updated_at'},
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                ],
                fnDrawCallback:function (oSettings) {
                    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                    elems.forEach(function (html) {
                        var switchery = new Switchery(html, {
                            color: '#0874e8',
                            className : 'switchery switchery-small'                
                        });
                    });
                }

       }); 

      // $('#service_form').on('submit', function(e) {
      //     oTable.draw();
      //     e.preventDefault();
      // });

      //DELETE GROUP
      $(document).on('click','.delete-worker', function(){
          var id = $(this).attr('id');  

          if(window.confirm("Are you sure you want to delete this woker ?"))
          {
              $.ajax({
                url:"{{route('delete-staff')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    oTable.draw();
                    toastr.success(data);
                }
              })
            }
            else{
              return false;
          }
      });

      
      $(document).on('click', '.switchery', function(e) {
          var worker_id = $(this).siblings('input').attr('id');
          
          var checked = $(this).siblings('input').attr('check');
          // debugger;
           $.ajax({
              url: "{{ route('change-staff-status') }}",
              type: 'GET',
              dataType: 'html',
              data:{
                "checked":checked,
                "worker_id":worker_id
              },
           })
           .done(function(data) {
            console.log(data);
              toastr.success('Change Menu Status Succsess!');
           })
           .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.success('Change Service  Error!');
             //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
           });
         
       });

      $(document).on('click', '.switchery', function(e) {
          oTable.draw();
          e.preventDefault();
      });
       $(document).on('click','#staff_reset', function(e) {
          $("#service_form")[0].reset();
          oTable.draw();
          e.preventDefault();
      });
    
}); 
</script>            
@stop

