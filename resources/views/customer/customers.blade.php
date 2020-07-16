@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Clients')
@section('styles')  
  <style>
      ./*x_panel>div>.active, .toggle_group>div>.active{
        background: #319546;
      }*/      
  </style>
@stop
@section('content')
@if (session('status'))
<div class="alert alert-info">{{session('status')}}</div>
@endif
<div class="x_panel">
   {{-- 
   <form action="" method="get" action="clients/get-customers" id="calendar_form" name="calendar_form" class="form-inline">
      --}}
      <div class="form-group form-group-sm active_group" style="padding-right: 10px">
         {{-- 
         <select id="customertag_dropdown" class="form-control form-control-sm">
            <option value="">-/- Client Group -/- </option>
            @foreach($list_customertag as $customertag)
            <option value ="{{$customertag->customertag_id}}">{{$customertag->customertag_name}}</option>
            @endforeach
         </select>
         --}}
         <button class="btn btn-sm btn-primary reset active" type="reset">ALL CLIENT</button> 
         <button class="btn btn-sm btn-default basic_group">BACSIC </button> 
         <button class="btn btn-sm btn-default membership_group">MEMBERSHIP GROUP</button> 
         <button class="btn btn-sm btn-default birthday_group">BIRTHDAY GROUP</button> 
         <button class="btn btn-sm btn-default visited_time_group">VISITED TIME GROUP</button> 
      </div>
      <hr>
      <div class="input-group-sm">
         {{-- <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
         <input type="text" style="width: 240px" name="search_customer_date" id="search_customer_date" class="form-control form-control-sm" value="" />
      </div>
      --}}                      
      <div class="form-group col-md-12 toggle_group">
         <div id="basic_group" style="display: none">
            <button class="btn btn-sm btn-default " data='new'>NEW</button> 
            <button class="btn btn-sm btn-default " data='royal'>ROYAL</button> 
            <button class="btn btn-sm btn-default " data='vip'>VIP</button> 
         </div>
         <div id="membership_group" style="display: none">
            <button class="btn btn-sm btn-default " data='normal membership'>NORMAL MEMBERSHIP</button> 
            <button class="btn btn-sm btn-default " data='silver membership'>SILVER MEMBERSHIP</button> 
            <button class="btn btn-sm btn-default " data='golden membership'>GOLDEN MEMBERSHIP</button> 
            <button class="btn btn-sm btn-default " data='diamond membership'>DIAMOND MEMBERSHIP</button> 
         </div>
         <div id="birthday_group" style="display: none">
            <button class="btn btn-sm btn-default " data='1'>JAN</button> 
            <button class="btn btn-sm btn-default " data='2'>FEB</button> 
            <button class="btn btn-sm btn-default " data='3'>MAR</button> 
            <button class="btn btn-sm btn-default " data='4'>APR</button> 
            <button class="btn btn-sm btn-default " data='5'>MAY</button> 
            <button class="btn btn-sm btn-default " data='6'>JUN</button> 
            <button class="btn btn-sm btn-default " data='7'>JULY</button> 
            <button class="btn btn-sm btn-default " data='8'>AUG</button> 
            <button class="btn btn-sm btn-default " data='9'>SEP</button> 
            <button class="btn btn-sm btn-default " data='10'>OCT</button> 
            <button class="btn btn-sm btn-default " data='11'>NOV</button> 
            <button class="btn btn-sm btn-default " data='12'>DEC</button> 
         </div>
         <div id="visited_time_group" style="display: none">
            <button class="btn btn-sm btn-default " data='7_DAYS'>7 DAYS</button> 
            <button class="btn btn-sm btn-default " data='14_DAYS'>14 DAYS</button> 
            <button class="btn btn-sm btn-default " data='21_DAYS'>21 DAYS</button> 
            <button class="btn btn-sm btn-default " data='30_DAYS'>30 DAYS</button> 
            <button class="btn btn-sm btn-default " data='60_DAYS'>60 DAYS</button> 
            <button class="btn btn-sm btn-default " data='90_DAYS'>90 DAYS</button> 
            <button class="btn btn-sm btn-default " data='180_DAYS'>180 DAYS</button> 
            <button class="btn btn-sm btn-default " data='365_DAYS'>365 DAYS</button> 
         </div>
         {{-- <button type="submit" class="btn btn-sm btn-primary">Search</button> --}}
         {{-- <button class="btn btn-sm btn-default reset"  type="reset">Clear</button> --}}
      </div>
      {{-- 
   </form>
   --}}
</div>
<div class="x_panel">
   <table id="datatable" class="table table-striped table-bordered">
      <thead>
         <tr>
            <th class="text-center" width="10">ID</th>
            <th width="150" class="text-left">Name </th>
            <th width="25" class="text-center" >Gender</th>
            <th width="90" class="text-left">Cellphone </th>
            <th width="100" class="text-left">Email</th>
            <th class="text-center" width="30" >DOB</th>
            <th class="text-center customertag_name" width="30" >Group</th>
            <th class="text-center" width="60" >Join Date</th>
            <th class="text-center search_membership" width="10">Membership</th>
            <th class="text-center" width="90">Action</th>
            <th style="display: none" class="text-center search_birthday" width="10">search_birthday</th>
            <th style="display: none" class="text-center search_visited_time" width="10">visited_time_group</th>
            
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
      $('input[name=search_customer_date]').daterangepicker({ 
          autoUpdateInput: false,
   
         locale: {
           cancelLabel: 'Clear'
         }
      }); 
   
     $('input[name=search_customer_date]').on('apply.daterangepicker', function(ev, picker) {
       $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
     });
   
     $('input[name=search_customer_date]').on('cancel.daterangepicker', function(ev, picker) {
       $(this).val('');
     });
           oTable = $('#datatable').DataTable({
                dom: "lBfrtip",
                processing: true,
                serverSide: true,
                autoWidth: true,
                buttons: [
                    {
                       text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
                       className: "btn-sm btn-add",
                       action: function ( e, dt, node, config ) {
                           document.location.href = '{{route("client")}}';
                       }
                   },{
                       text: '<i class="glyphicon glyphicon-import"></i> Import',
                       className: 'btn-sm',
                       action: function ( e, dt, node, config ) {
                           document.location.href = "{{route('import')}}";
                       }
                   },
                   {   
                        extend: 'excel', 
                        text: '<i class="glyphicon glyphicon-export"></i> Export',
                        className: "btn-sm",
                        action: function ( e, dt, node, config ) {
                           document.location.href = "{{route('export-clients')}}";
                       }
                    }
                ],
                order: [[ 0, "desc" ]],
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
                     "className": "text-center",
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
                     // "visible": false,
                     "className": "text-center",
                },
                {
                     "targets": 9,
                     "className": "text-center nowrap",
                },
                {
                     "targets": 10,
                      "visible": false,
                },
                {
                     "targets": 11,
                     "visible": false,
                },                
                ],
   
                ajax:{ url:"{{ route('get-customers') }}",
                    data: function (d) {
                           // d.search_customer_date = $('input[name=search_customer_date]').val();
                           // d.group_select = $('#customertag_dropdown :selected').val();
                       }
                     },
                    columns: [
                             { data: 'customer_id', name: 'customer_id' },
                             { data: 'customer_fullname', name: 'customer_fullname' },
                             { data: 'customer_gender', name: 'customer_gender' },
                             { data: 'customer_phone', name: 'customer_phone' },
                             { data: 'customer_email', name: 'customer_email' },
                             { data: 'customer_birthdate', name: 'customer_birthdate' },
                             { data: 'customertag_name', name:'customertag_name'},
                             { data: 'created_at', name:'created_at'},
                             { data: 'membership_name', name:'membership_name'},
                             { data: 'action' , name: 'action',  orderable: false, searchable: false },
                             { data: 'search_birthday', name:'search_birthday'},
                             { data: 'visited_time_group', name:'visited_time_group'},
                             
                          ],   
          }); 
   
         // $('#calendar_form').on('submit', function(e) {
         //     oTable.draw();
         //     e.preventDefault();
         // });
   
   
       //DELETE GROUP
       $(document).on('click','.delete-customer', function(){
           var id = $(this).attr('id');  
           if(window.confirm("Are you sure you want to delete this customer ?"))
           {
             $.ajax({
               url:"{{route('delete-customer')}}",
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

       function refresh_search(){
         oTable
            .columns([".customertag_name",".search_birthday",".search_visited_time",".search_membership"])           
            .search('')
            .draw();
       }

        $(document).on('click','.reset', function(e){
            toggle_group();            
            refresh_search();
        });

        //search 
        $("#basic_group button").on('click',function(e){
          e.preventDefault();
          var search = $(this).attr('data');
          refresh_search();
          oTable
          .columns(".customertag_name")
          .search(search)
          .draw();
        });
        $("#birthday_group button").on('click',function(e){
          e.preventDefault();
          var search = $(this).text();
          refresh_search();
          oTable
          .columns(".search_birthday")
          .search(search)
          .draw();
        });
        $("#visited_time_group button").on('click',function(e){
          e.preventDefault();
          var search = $(this).attr("data");
          refresh_search();
          oTable
          .columns(".search_visited_time")
          .search(search)
          .draw();
        });
        $("#membership_group button").on('click',function(e){
          e.preventDefault();
          var search = $(this).attr("data");
          refresh_search();
          oTable
          .columns(".search_membership")
          .search(search)
          .draw();
        });
             
   });
  
</script>       
<script>
   function toggle_group(){
     $('.toggle_group').find("div").hide(200);
   }
   
   $(document).ready(function(){
     $('.basic_group').on('click',function(e){
       toggle_group();
       $("#basic_group").show(200);
     });
     $('.membership_group').on('click',function(e){
       toggle_group();
       $("#membership_group").show(200);
     });
     $('.birthday_group').on('click',function(e){
       toggle_group();
       $("#birthday_group").show(200);
     });
     $('.visited_time_group').on('click',function(e){
       toggle_group();
       $("#visited_time_group").show(200);
     });
     //add toggle class active
     $(".active_group button").on('click',function(e){
      $(this).parent().find('.active').removeClass("active").removeClass('btn-primary').addClass('btn-default');
      $(this).removeClass("btn-default").addClass('active').addClass('btn-primary');
     });
     $(".toggle_group div button").on('click',function(e){
      $(this).parent().parent().find('div .active').removeClass("active").removeClass('btn-primary').addClass('btn-default');
      $(this).removeClass("btn-default").addClass('active').addClass('btn-primary');
     });
   });
</script>     
@stop