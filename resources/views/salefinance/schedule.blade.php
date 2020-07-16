@extends('layouts.master')
@section('title', 'Sales & Finances | Schedule')
@section('styles')
<meta name="bookingFromSchedule" content="">
<link href='{{ asset('plugins/fullcalendar-scheduler-1.6.2/scheduler.min.css')}}' rel='stylesheet' />
<!-- <link href="{{ asset('plugins/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">   -->
<link href="{{ asset('/css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/fullcalendar/fullcalendar.print.css') }}" rel="stylesheet" media="print">
{{-- <style type="text/css" media="screen">
/*<<<<<<< HEAD*/
    .fc-content .fc-title,.fc-content .fc-time{
        color: black;
        font-weight: 700;
        text-transform: capitalize;
        font-size: 1em;
    }
    .fc-time-grid-event{
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: auto;
    }
    .fc-time-grid-event .fc-content{
        text-align: center;
    }
    td.day{
      position:relative;  
    }
    td.day.disabled{
      text-decoration: line-through;
    }

    td.day.disabled:hover:before {
        content: 'This time is closed';
        border: 1px red solid;
        border-radius: 11px;
        color: red;
        background-color: white;
        top: -22px;
        position: absolute;
        width: 136px;
        left: -34px;
        z-index: 1000;
        text-align: center;
        padding: 2px;
    }
    .datepicker{
        border: 1px solid #319546;
    }
    .bg-primary{
        background-color: #0874e8 !important;
    }
    .fc table{
    table-layout: auto;
    }
    .fc-view > table{  
        min-width: 0;
        width: auto;
    }
    .fc-axis{
        min-width:50px; /*the width of times column*/
        width:50px; /*the width of times column*/
    }
    .fc-day,.fc-resource-cell,.fc-content-col{
        min-width:250px;
        width:250px;
    }
    .fc-view-container
    {
        overflow-x:auto;
    }

    .fc-view-container::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    .fc-view-container::-webkit-scrollbar
    {
        width: 12px;
        background-color: #F5F5F5;
    }

    .fc-view-container::-webkit-scrollbar-thumb
    {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    }
    .fc-header-toolbar{
        /*background-color: blue;*/

        margin-bottom: 0.1em !important ;
       /* color: white;*/
    }
    .tic{
        position: absolute;
        top: 0px;
        right: 0px;
        color: lightgreen;
    }
    .div-custom{
        line-height: 30px;
        color: black;
        font-weight: bold;
    }
    .new_booking{
        background-color: #edd70a;
    }
    .confirm{
        background-color: #0874e8;
    }
    .working{
        background-color: #307539;
    }
    .cancel{
        background-color: #d42423;
    }
    .paid{
        background-color: #bbbdc4;
    }
    .popover-body{
        padding: 0px;
    }
    .customer_content, .booking_content,.header{
        padding:0.2em 1em;
    }
    .title-bold{
        font-weight: bold;
    }
    .phone{
        float: right;
    }
    .note{
        border-bottom: 1px gray solid;
    }
    .note_title {
        color: #d42423;
    }
    .footer_popup{
        text-align: center;
        padding: auto;
    }
    .btn{
        width: 80px;
    }
    .green{
        background-color: green;
    }
    .fc-unthemed tbody{
        background-color: white;
    }
    .display_none{
        display: none;
    }
    .display_show{
        display: inline;
    }
    .content_popper{
        padding-bottom: 10px;
    }
    /*.fc-bg{
        background-color: #0874E8 !important;
    }*/

    /*show vertical scrollbar Schedule*/
    .fc-view-container .fc-view {
      height:530px;
    }
    .fc-scroller {
       overflow-y: hidden !important;
    }
    /*end show*/
    .working-confirm{
        color: #fff;
    }
 </style>  --}}
 <style>
{{-- ======= --}}
   .fc-content .fc-title,.fc-content .fc-time{
   color: black;
   font-weight: 700;
   text-transform: capitalize;
   font-size: 1em;
   }
   .fc-time-grid-event{
   display: flex;
   justify-content: center;
   align-items: center;
   text-align: center;
   padding: auto;
   }
   .fc-time-grid-event .fc-content{
   text-align: center;
   }
   td.day{
   position:relative;  
   }
   td.day.disabled{
   text-decoration: line-through;
   }
   td.day.disabled:hover:before {
   content: 'This time is closed';
   border: 1px red solid;
   border-radius: 11px;
   color: red;
   background-color: white;
   top: -22px;
   position: absolute;
   width: 136px;
   left: -34px;
   z-index: 1000;
   text-align: center;
   padding: 2px;
   }
   .datepicker{
   border: 1px solid #319546;
   }
   .bg-primary{
   background-color: #0874e8 !important;
   }
   .fc table{
   table-layout: auto;
   }
   .fc-view > table{  
   min-width: 0;
   width: auto;
   }
   .fc-axis{
   min-width:50px; /*the width of times column*/
   width:50px; /*the width of times column*/
   }
   .fc-day,.fc-resource-cell,.fc-content-col{
   min-width:250px;
   width:250px;
   }
   .fc-view-container
   {
   overflow-x:auto;
   }
   .fc-view-container::-webkit-scrollbar-track
   {
   -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
   border-radius: 10px;
   background-color: #F5F5F5;
   }
   .fc-view-container::-webkit-scrollbar
   {
   width: 12px;
   background-color: #F5F5F5;
   }
   .fc-view-container::-webkit-scrollbar-thumb
   {
   border-radius: 10px;
   -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
   }
   .fc-header-toolbar{
   /*background-color: blue;*/
   margin-bottom: 0.1em !important ;
   /* color: white;*/
   }
   .tic{
   position: absolute;
   top: 0px;
   right: 0px;
   color: lightgreen;
   }
   .div-custom{
   line-height: 30px;
   color: black;
   font-weight: bold;
   }
   .new_booking{
   background-color: #edd70a;
   }
   .confirm{
   background-color: #0874e8;
   }
   .working{
   background-color: #307539;
   }
   .cancel{
   background-color: #d42423;
   }
   .paid{
   background-color: #bbbdc4;
   }
   .popover-body{
   padding: 0px;
   }
   .customer_content, .booking_content,.header{
   padding:0.2em 1em;
   }
   .title-bold{
   font-weight: bold;
   }
   .phone{
   float: right;
   }
   .note{
   border-bottom: 1px gray solid;
   }
   .note_title {
   color: #d42423;
   }
   .footer_popup{
   text-align: center;
   padding: auto;
   }
   .btn{
   width: 80px;
   }
   .green{
   background-color: green;
   }
   .fc-unthemed tbody{
   background-color: white;
   }
   .display_none{
   display: none;
   }
   .display_show{
   display: inline;
   color: #fff;
   }
   .content_popper{
   padding-bottom: 10px;
   }
   /*.fc-bg{
   background-color: #0874E8 !important;
   }*/
   /*show vertical scrollbar Schedule*/
  /* #calendar{
    height: 65em;
   }*/
   .fc-view-container .fc-view{
   /*height:530px;*/
   /*height: 53rem;*/
   }
   /*.fc-scroller {
    overflow-y: hidden !important;
   }
*/   .fc-view-container .fc-view {
      overflow-x: scroll;
      /*overflow-y: scroll;*/
    }
    .fc-day, .fc-resource-cell, .fc-content-col{
      min-width: 13em;
      width:auto;
    }

       

</style>
{{-- >>>>>>> origin --}}
@stop
@section('content')
@php
Session::put('worker_array_session',[]);
Session::put('schedule_arr',[]);
@endphp
@foreach($date_action_list as $key => $date)
<input type="hidden" id="{{$key}}" name="{{$key}}" value="{{$date['closed']}}">
@endforeach
{{-- modal get information booking --}}
{{-- <div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header">
            <span class="modal-title">Modal Header</span>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <span class="booking-content">- SERVICE: </span>
            <span id="service_list" style="text-indent: 20px;"></span><br>
            <span class="total_price"></span><br>
            <span class="worker"></span>
         </div>
         <div class="modal-footer">
            <a class="booking-edit" href="" title=""><button type="button" class="btn btn-primary btn-sm">EDIT</button></a>
            <a class="payment-checkout" href="" title=""><button type="button" class="btn btn-danger btn-sm">PAYMENT</button></a>
         </div>
      </div>
   </div>
</div> --}}
<div class="x_panel_form col-xs-10 col-sm-10 col-md-10" id="calendar-box">
   <div id='calendar' style="display:{{($open_close == "closed")?"none":""}};color: black;background-color: white">
</div>
</div>
<div class="col-md-2">
   <input type="hidden" id="expense_date" name="">
   <div id="datepicker" >
   </div>
   <div class="col-md-12" style="padding-left: 0px;">
      <h6 style="color: black;font-weight: bold">Rent Station</h6>
      {{-- {{dd($booking_status_list)}} --}}
      <div class="col-md-6 text-center bg-danger div-custom worker_all worker_id" style="padding: 1px;border: 2px white solid;color: white" id="@foreach($worker_id as $worker){{$worker}},@endforeach">All</div>
      @foreach($resource as $key => $staff)
      <div class="col-md-6 text-center bg-primary div-custom worker_id" style="padding: 1px;border: 2px white solid;color: white" id="{{$staff['id']}}">{{ucfirst($staff['title'])}}<span class="glyphicon glyphicon-ok tic tic-element" style="display: none;"></span></div>
      @endforeach
      <div class="clearfix"></div>
      <div class="row"  style="margin-top: 30px">
         @foreach($booking_status_list as $key => $booking)
         <div class="col-md-12" style="border: 2px white solid">
            <div class="col-md-3" style="background: {{$key}};height: 15px"></div>
            <div class="col-md-9" style="line-height: 15px;font-weight: 700" >{{ucfirst($booking)}}</div>
         </div>
         @endforeach
      </div>
   </div >
</div>
</div>
@stop
@section('scripts')
<script src="{{ asset('plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalendar-scheduler-1.6.2/fullcalendar.min.js')}}"></script>
<script src="{{ asset('plugins/fullcalendar-scheduler-1.6.2/scheduler.min.js')}}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/js/plugins/bootstrap.min.js')}}"></script>
<script type="text/javascript">
   var worker_list_arr = [];
   //NOTIFI OPEN OR CLOSE SHOP
   var open_close = '{{$open_close}}';
   if(open_close == 'closed')
       toastr.warning('Today, the salon has closed. Choose another date to view and book. Thanks!');
   //END NOTIFI
   // var event = [];
   var url_file_view  = '<?php echo config('app.url_file_view') ?>';

   function getListBooking(date =''){
    var result ='';
    $.ajax({
        url:"{{ route('getListBooking') }}",
        async: false,  
        method:"get",
        data:{date},
        dataType:"json",
        success:function(data){
            if(data.success){
                result = data;
            }
        },
        error:function(){
            toastr.error("Failed to load list booking!");
        }
    });
    return result;
   }

   function loadDetailBooking(event,element){
    // $(element.target).popover('hide');   
    $.ajax({
                   // async: true,
                   url: '{{route("get-services-by-bookingid")}}',
                   data: { id: event.id, worker_id:event.worker_id, booking_time:event.booking_time },
                   method:"get",
                   success: function (data) {  
                   if(data){  
                           popup_item = data;
                           var status_cancel = '';
                           var status_payment = '';
                           var status_new_booking = '';
                           var status_confirm = '';
                           var status_paid ='';
                           var status_working = '';
           
                           if(popup_item['status_number'] == 0){
                               status_new_booking = "display_none";
                               status_payment = 'display_none';
                               status_paid = 'display_none';
                               status_working = 'display_none';
                           }
                           if(popup_item['status_number'] == 1){
                               status_new_booking = "display_show";
                               status_payment = 'display_none';
                               status_paid = 'display_none';
                               status_working = 'display_none';
                           }
                           if(popup_item['status_number'] == 2){
                               status_confirm = 'display_show';
                               status_working = 'display_show';
                               status_payment = 'display_none';
                               status_cancel = 'display_none';
                               status_paid = 'display_none';
                           }
                           if(popup_item['status_number'] == 3){
                               status_new_booking = "display_none";
                               status_payment = "display_show";
                               status_paid = 'display_none';
                               status_working = 'display_none';
                           }
                           if(popup_item['status_number'] == 4){
                               status_paid = 'display_show';
                               status_new_booking = "display_none";
                               status_payment = "display_none";
                               status_working = 'display_none';
                           }
                           if(popup_item['booking_note'] == null)
                               popup_item['booking_note'] = "";
                           if(popup_item['customer_description'] == null)
                               popup_item['customer_description'] = "";
                                      
                       $(element.target).popover({
                       trigger: 'click',
                       header: 'Detail',
                       //title: '<div class=""></div>',
                       content: `<div class="content_popper">
                       <div class="header `+popup_item['class_color']+`" style="background-color: #edd70a">
                           <span class="title-bold">`+popup_item['customer_fullname']+`</span><span  class="title-bold phone">`+popup_item['customer_phone']+`</span>
                           <span>`+popup_item['customer_email']+`</span>
                       </div>
                       <div class="customer_content">
                          <span class="title-bold">Membership level</span>: Dimont<br>
                          <span class="title-bold">First visit</span>: `+popup_item['first_visit']+`<br>
                          <span class="title-bold">Last visit</span>: `+popup_item['last_visit']+`<br>
                          <span class="title-bold">Last review</span>:<br>
                          <span class="title-bold">Last staff</span>: `+popup_item['worker_name_last']+`<br>
                          <span class="title-bold">Visit count</span>: `+popup_item['visit_count']+`<br>
                          <span class="title-bold">Total spend</span>: `+popup_item['total_spend']+`<br>
                          <span class="title-bold">Current reward point</span>:<br>
                          <span class="title-bold">Reward earned point</span>:<br>
                          <span class="title-bold ">Customer Note</span>: <span class="note_title">`+popup_item['customer_description']+`</span><br>
           
                       </div>
                       <div class="booking_content">
                           <div class="note title-bold"></div>
                           <span class="title-bold">Time</span>: `+popup_item['booking_datetime']+`<br>
                           <span class="title-bold">Service</span>: `+popup_item['service_html']+`
                           <span class="title-bold">Staff request</span>: `+popup_item['staff_request']+`<br>
                           <span class="title-bold">Total</span>: $`+popup_item['total_price']+`<br>
                           <span class="title-bold ">Booking Note</span>: <span class="note_title">`+popup_item['booking_note']+`<span><br>
                       </div>
                       <div class="text-center links">
                               <a class="btn btn-sm  btn-danger booking-cancel booking title-bold `+status_confirm+' '+status_new_booking+`" id="`+event.id+`" href="#">Cancel</a>
                               <a class="btn btn-sm btn-warning booking title-bold `+status_confirm+' '+status_new_booking+`" href="`+'{{route('edit-booking')}}'+'/'+event.id+`">Edit</a>
                               <a class="btn btn-sm  booking title-bold btn-primary `+status_cancel+' '+status_new_booking+` booking-confirm" id="`+event.id+`" href="#">Confirm</a>
                               <a class="btn btn-sm  booking title-bold working `+status_cancel+' '+status_working+` working-confirm" id="`+event.id+`" href="#">Working</a>
                               <a class="btn btn-sm btn-danger booking title-bold `+status_payment+`" href="`+'{{route('payment-checkout')}}'+'/'+event.id+`">Payment</a>
                               <a class="btn btn-sm btn-danger booking title-bold `+status_paid+`" href="`+'{{route('booking-clone')}}'+'/'+event.id+`">Clone</a>
                       </div>
                       </div>`,
                           html: true,
                           animation: true,
                           container: 'body',
                           placement: 'auto',
                       }).popover('toggle');
                    }
                   },
                   error: function () {
                       toastr.error('could not get the data');
                   },
        });
   }
   
      function loadfullCalendar(event, resource, start_time, end_time) { 
        // console.log(resource);
       $('#calendar').fullCalendar({
            height: $(window).height()*0.83,
           eventColor: '#fff',
           aspectRatio: 2,
           // dragScroll: true,
           slotDuration: '00:15',
           defaultView: 'agendaDay',
           eventLimit: true, // allow "more" link when too many events
           header:{
               left:   '',
               center: 'title',
               right:  ''
           },
           minTime: start_time+":00",
           maxTime: end_time+":00",
           scrollTime:  moment().format('H:m'),
           timeZone: 'UTC',
           schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
           allDaySlot: false,
           resourceRender: function(resourceObj, labelTds, bodyTds) {
               var src_url ="";
               $.each(resourceObj, function(index, val) {
                    if(resourceObj.id == val) {
                       labelTds.last().empty();
                       if(resourceObj.image != null)
                           src_url = url_file_view+"/"+resourceObj.image;
                       else
                           src_url = "{{asset('images/user.png')}}";
                   labelTds.prepend(
                   '<span style="text-align:">' +
                   '<img class="image_'+resourceObj.id+'" src="'+src_url+'" style="border-radius:50%" width="40" height="40">' +
                   '</span>'
                    );
                   labelTds.append(resourceObj.title);
               }
           });
           },
           resources: resource,
           events: event,
   
           select: function(start, end, jsEvent, view, resource) {
               // console.log(
               //     'select',
               //     start.format(),
               //     end.format(),
               //     resource ? resource.id : '(no resource)'
               // );
           },
           dayClick: function(date, jsEvent, view, resource) {
   
               var date_selected = date.format();
   
               var worker_id = resource.id;
   
               window.location.href='{{route('booking-form-schedule')}}/'+worker_id+'/'+date_selected;
           },           
            eventClick: function(event, element) {
                loadDetailBooking(event,element);                    
                },
       });
    
   };

   function reloadCalendar() {
        var date =  $('#expense_date').val();
        $('#calendar').fullCalendar('destroy');
        data = getListBooking(date);

        if(data.success){
            loadfullCalendar(data.event,data.resource,data.start_time,data.end_time);
        }
        if(date){
           $('#calendar').fullCalendar('gotoDate',date); 
        }        
        return;
    }
   
   //=====================================================================================
   $(document).ready(function() {
    // load FullCalendar
    try {
    reloadCalendar();
  }
  catch(err) {
    console.log('ff');
  }
    

   var arr = [];
   
   var arr_date = ['sun','mon','tue','wed','thur','fri','sat'];
   
   $.each(arr_date, function(index, val) {
   
   if($('input[name='+val+']').val() == 1){
   
     arr.push(index);
   }
   });
   var date = new Date();
   
   $("#datepicker").datepicker({   
       daysOfWeekDisabled: arr,
       todayHighlight: true,
       autoclose: false,
       startDate: date,
   });
   $('#datepicker').on('changeDate', function() {
      $("#calendar").show(); 
      $('#expense_date').val($('#datepicker').datepicker('getFormattedDate'));
        reloadCalendar();  

        return;
        // $('#calendar').fullCalendar('rerenderEvents');
        // return;
      
   
      // $.ajax({
      //     url: '{{route('get-schedule')}}',
      //     type: 'GET',
      //     dataType: 'html',
      //     data: {date: date},
      // })
      // .done(function(data) {
      //  // console.log(data);
      //  var data = JSON.parse(data);
      //  //set min time again
      //  $('#calendar').fullCalendar('option', 'minTime',data['start_time']+":00");
   
      //  $('#calendar').fullCalendar('removeEvents');
      //  $('#calendar').fullCalendar('addEventSource',data['event']);
      //  $('#calendar').fullCalendar('gotoDate',date);     
      //  $('#calendar').fullCalendar('rerenderEvents');
   
      //  // console.log(worker_list_arr);
      //  $(".fc-view-container").addClass('scroll-style-1');
      //  if(worker_list_arr != "")
      //      $.each(worker_list_arr, function(index, val) {
      //          if(val['image'] != null)
      //              $(".image_"+val['id']).attr("src",url_file_view+"/"+val['image']);
      //          else
      //              $(".image_"+val['id']).attr("src","{{asset('images/user.png')}}");
      //      });
      // })
      // .fail(function() {
      //      alert('Processing Error!');
      // });
   });
   $("#worker_submit").click(function(event) {
   
       var worker_all = $("#worker_all").val();
   
       var worker_id = $(this).val();
   
       $('#calendar').fullCalendar( 'removeResource', worker_all );
       $('#calendar').fullCalendar( 'addResource', worker_id );
   });
   
   // BOOKING CANCEL
   $(document).on('click','.booking-cancel',function(e){
       e.preventDefault();
       if(window.confirm("Are you sure you want to delete this ticket?")){
           var booking_id = $(this).attr('id');
           $.ajax({
               url: '{{route('booking-delete')}}',
               type: 'GET',
               dataType: 'html',
               data: {booking_id: booking_id},
           })
           .done(function(data) {
               $(this).closest('fc-time-grid-event').css('background-color', 'green');
               $(".booking").addClass('disabled');
               reloadCalendar();
               
           })
           .fail(function() {
               console.log("error");
           });
           
       }else
          return e.preventDefault();
   });
   $(document).on('click','.popover',function(){
       $(this).popover('hide');
   });
   
   $(".worker_id").click(function(){
   
       var worker_id_all = <?php echo json_encode($worker_id) ?>;
       var worker_list = $(".worker_all").attr('id');
       var id = $(this).attr('id');
       $(this).children('span').toggle();
       if($(this).hasClass('worker_all'))
           $(".tic-element").hide();
       //alert(worker_list);
   
       $.ajax({
           url: '{{route('get-resource')}}',
           type: 'GET',
           dataType: 'html',
           data: {worker_list: worker_list,id: id},
       })
       .done(function(data) {
           // console.log(data);
           if(JSON.parse(data).length == worker_id_all.length){
               $(".tic-element").show();
           }
           // console.log(JSON.parse(data).length);
           // console.log(worker_id_all.length);
   
           worker_list_arr = JSON.parse(data);
   
       //REMOVE WORKER LIST
           $.each(worker_id_all, function(index, val) {
   
             $('#calendar').fullCalendar('removeResource',val);
           });
       //END REMOVE
   
       //REWRITE WORKER LIST
            $.each(JSON.parse(data), function(index, val) {
   
               $('#calendar').fullCalendar('addResource', {
                   id: val['id'],
                   title: val['title'],
               });
           });
   
            $.each(JSON.parse(data), function(index, val) {
               if(val['image'] != null )
                   $(".image_"+val['id']).attr("src",url_file_view+"/"+val['image']);
               else
                   $(".image_"+val['id']).attr("src","{{asset('images/user.png')}}");
            });
   
       //END REWRITE
       })
       .fail(function() {
           console.log("error");
       });
   });
   // CONFIRM BOOKING
   $(document).on('click','.booking-confirm',function(e){
       e.preventDefault();
       var booking_id = $(this).attr('id');
           $.ajax({
               url: '{{route('booking-confirm')}}',
               type: 'GET',
               dataType: 'html',
               data: {booking_id: booking_id},
           })
           .done(function(data) {
               //$(this).closest('fc-time-grid-event').css('background-color', 'green');
               //$(".booking").addClass('disabled');
               reloadCalendar();
               //console.log(data);
           })
           .fail(function() {
               console.log("error");
           });
   });
   //END CONFIRM
   // CONFIRM WORKING
   $(document).on('click','.working-confirm',function(e){
       e.preventDefault();
       var booking_id = $(this).attr('id');
           $.ajax({
               url: '{{route('working-confirm')}}',
               type: 'GET',
               dataType: 'html',
               data: {booking_id: booking_id},
           })
           .done(function(data) {
               //$(this).closest('fc-time-grid-event').css('background-color', 'green');
               //$(".booking").addClass('disabled');
               reloadCalendar();
               //console.log(data);
           })
           .fail(function() {
               console.log("error");
           });
   });
   // END CONFIRM WORKING
   
    
    
    $(".fc-time,.fc-title").on('click',function(e){
        $(this).parent().parent().trigger('click');
        return false;
    });
    $(".fc-bg,.fc-content").on('click',function(e){
        $(this).parent().trigger('click');
        return false;
    });

   });
</script>
{{-- add css full calander--}}
<script>
  $(document).ready(function(){
    $("#calendar .fc-view-container .fc-view table tbody tr td .fc-scroller").css("overflow","unset");
  });
  // $(".time-grid-container").css("overflow","unset");
  // $(".time-grid-container").css("display","none");
</script>
@stop