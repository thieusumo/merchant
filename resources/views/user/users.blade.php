@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Users')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
@stop
@section('content')
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">No.</th>  
        <th>Full Name</th>
        <th width="80">Nick name</th>
        <th class="text-center" width="100">Phone</th>
        <th>Email</th>
        <th class="text-center" width="120">Role Permission</th>
        <th class="text-center" width="60">Status</th>
        <th>Last Update</th>        
        <th class="text-center">Action</th>        
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
    
   $('#order_date').daterangepicker({ 
       startDate: moment().subtract(1, 'month').startOf('month'), 
       endDate: moment().subtract(1, 'month').endOf('month')
   }); 
        
        sTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,
             autoWidth: true,
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('edit-user')}}";
                    }
                },{   
                     extend: 'excel', 
                     text: '<i class="glyphicon glyphicon-export"></i> Export',
                     className: "btn-sm"
                 }
             ],
             columnDefs: [
              {
                  "targets": 0, 
                  "className": "text-center"
             },
             {
                  "targets": 1,
                  "className": "text-left",
             },
             {
                  "targets": 2,
                  "className": "text-left",
             },
             {
                  "targets": 3,
                  "className": "text-center",
             },
             {
                  "targets": 4,
                  "className": "text-left",
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
             },
             {
                  "targets": 8,
                  "className": "text-center  nowrap",
             }
             ],
             ajax:{ url:"{{route('get-users')}}"},
                columns:[
                {data:'user_id', name:'user_id'},
                {data:'user_name', name:'user_name'},
                {data:'user_nickname', name:'user_nickname'},
                {data:'user_phone', name:'user_phone'},
                {data:'user_email', name:'user_email'},
                {data:'ug_name', name:'ug_name'},
                {data:'enable_status', name:'enable_status'},
                {data:'user_login_time', name:'user_login_time'},
                {data:'action', name:'action',orderable: false, searchable: false},
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

        if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#0874e8',
                className : 'switchery switchery-small'                
            });
        });
   }
    

    $(document).on('click', '.delete_user', function() {

    if(window.confirm("Do you want to change this user?")){

         var user_id = $(this).attr('id');

         $.ajax({
             url: "{{ route('delete-user') }}",
             type: 'GET',
             dataType: 'html',
             data:"user_id="+user_id,
         })
         .done(function() {
            toastr.success('Change User Succsess!');
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Change User Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }else{
            return false;
        }
     
   });
    $(document).on('click', '.delete_user', function(e) {
          sTable.draw();
          e.preventDefault();
      });

    $(document).on('click', '.switchery', function() {

    // if(window.confirm("Do you want to change this status?")){

         var checked = $(this).siblings(`input`).attr('checked');

         var user_id = $(this).siblings('input').attr('id');

         $.ajax({
             url: "{{ route('change-enable-user') }}",
             type: 'GET',
             dataType: 'html',
             data:"checked="+checked+"&user_id="+user_id,
         })
         .done(function() {
            // alert('Change User Status Succsess!');
            toastr.success('Change User Status Succsess!',"Success!");
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            alert('Change User Status Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     // }else{
     //        return false;
     //    }
     
   });

    $(document).on('click', '.switchery', function(e) {
          sTable.draw();
          e.preventDefault();
      });
    
}); 
</script>        
@stop

