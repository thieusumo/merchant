@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Account Summary')
@section('styles')
<link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">  
<style type="text/css">
    .top_nav{height: 84px;}   
    .type-content{
      height: 70px;
      margin-bottom: 20px;
      color: white;
      font-weight: 600;
      padding-top: 5px;
    }    
</style>
@stop
@section('content')
<input type="hidden" id="event_id_hidden" value="">
<input type="hidden" id="type_id_hidden" value="0">
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2">Total: <span style="color: red" id="total"></span></div>
            <div class="col-md-2">Send: <span style="color: red" id="success"></span> </div>
            <div class="col-md-2">Fail: <span style="color: red" id="fail"></span></div>
          </div>
            <table id="datatable_receive" width="100%" class="table table-bordered table-hover">
                <caption>Receive List</caption>
                <thead>
                    <tr>
                        <th>Phone</th>
                        <th>Date & Time</th>
                        <th>Content</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<div id="sms" class="col-xs-12 col-md-12 fixLHeight no-padding full-height bg-white">
     <div class="col-xs-2 col-md-2 no-padding full-height scroll-view scroll-style-1">
        @include('marketing.sms.partials.menu') 
    </div>   
    <div class="col-xs-10 col-md-10 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
         <div class="x_panel border-0 row">               
                    <div class="form-group col-md-6 row" >
                      <form action="" method="get" action="" width="" id="receiver_form" name="receiver_form" class="form-inline">
                        <div class="form-group">
                          <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                          <input type="text"  name="search_join_date" id="search_join_date" class="form-control form-control-sm" value=""/>
                          
                        </div>
                        {{-- <div class="col-md-5 row"> --}}
                          
                        {{-- </div> --}}
                      </form>
                    </div>
                    <div class="form-group col-md-6 text-center">
                      <input type="hidden" value="today" id="today_hidden" name="">
                        <button class="btn btn-sm btn-primary" id="today" onclick="eventType(this,'daily','weekly','monthly')">Today</button>
                        <button class="btn btn-sm btn-default" id="daily"  onclick="eventType(this,'weekly','weekly','today')" >Daily</button>
                        <button class="btn btn-sm btn-default" id="weekly"  onclick="eventType(this,'daily','today','monthly')" >Weekly</button>
                        <button class="btn btn-sm btn-default" id="monthly"  onclick="eventType(this,'daily','weekly','today')">Monthly</button>
                    </div>
                
        </div>
        <div class="x_panel border-0">               
            <div class="x_title">
                <h5>Tracking History</h5>
            </div>
            <div class="x_content">    
                <div class="col-md-9">
                  <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                         <th class="text-center">ID</th>   
                         <th class="text-center">Event Type</th>   
                          <th>Name</th>
                          <th>Start/Send Date</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead> 
                  </table>
                </div>
                <div class="col-md-3" id="event_type">

                  @foreach($event_sms_list as $key => $event)
                  <div class="type-content text-center" style="background-color: {{$event['color']}}">
                    <p>{{$event['event_type_name']}}</p>
                    <div class="row">
                      <div class="col-md-6">
                        Total SMS: {{$event['sms_total']}}
                      </div>
                      <div class="col-md-6">
                        Price: $0.000
                      </div>
                    </div>
                  </div>
                  @endforeach

                </div>
                  
            </div> 
        </div>
       
    </div>
</div>
@stop
@section('scripts') 
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>   
<script type="text/javascript">
$(document).ready(function() {   
   
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
   //GET LIST EVENT SEND SMS
     sTable = $('#datatable').DataTable({
         dom: "lBfrtip",
         buttons: [],
         fnDrawCallback:function (oSettings) {            
             var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
             elems.forEach(function (html) {
                 var switchery = new Switchery(html, {
                     color: '#26B99A',
                     className : 'switchery switchery-small',                        
                 });
             });
      },
         // "aaSorting": [
         //     [ 7, "desc" ]
         // ],
         processing: true,
        serverSide: true,
         ajax:{ url:"{{ route('smsManagement_DataTables') }}",
          data:function(d){
            d.search_join_date = $("#search_join_date").val();
            d.today = $("#today_hidden").val();
              }
            },
         columnDefs: [
             {
                  "targets": 0,
                  "className": "text-center",
             },
             {
                  "targets": 1, 
                  "className": "text-left"
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
                  "className": "text-center",
             }
             ],
        columns: [

                  { data: 'sms_send_event_id', name: 'sms_send_event_id' },
                  { data: 'sms_send_event_type', name: 'sms_send_event_type',searchable:false, orderable:false },
                  { data: 'sms_send_event_title', name: 'sms_send_event_title' },
                  { data: 'sms_send_event_start_day' , name:'sms_send_event_start_day'},
                  { data: 'action' , name:'action',searchable:false, orderable:false},                  
          ],
   });
     //END GET LIST

     //GET LIST REEIVERS
  $("#datatable").on('click', 'a.view-sms', function (event) {
        $.get($(this).attr('href'), function(result){
           $('#modelViewSMS .modal-body').html(result);
           $('#modelViewSMS').modal('show'); 
        });        
        event.preventDefault();
    });
  
  $(document).on('click','.detail-event',function(){

      var event_id = $(this).attr('id');
      var type_id = $(this).attr('type');
      $("#event_id_hidden").val(event_id);
      $("#type_id_hidden").val(type_id);
      receiveTable.draw();
      $.ajax({
        url: '{{route('calculate-sms')}}',
        type: 'get',
        dataType: 'html',
        data: {event_id: event_id,type_id: type_id},
      })
      .done(function(data) {
        data = JSON.parse(data);
        $("#total").text(data['total']);
        $("#success").text(data['success']);
        $("#fail").text(data['fail']);
        console.log(data);
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
      $('#myModal').modal("show");
    });


    receiveTable = $('#datatable_receive').DataTable({
         dom: "ftip",
         processing: true,
         serverSide: true,
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
         }
         ],
         ajax:{ url:"{{ route('event-detail')}}",
              data:function(d){
                d.event_id = $("#event_id_hidden").val();
                d.type_id = $("#type_id_hidden").val();
              }
            },
         columns: [
                  { data: 'phone', name: 'phone' },
                  { data: 'date_time', name: 'date_time' },
                  { data: 'content', name: 'content' }
               ]    
    });
    //END GET LIST RECEIVERS

    
    
});
function eventType(that,elemnt_1,element_2,element_3){
  

  var current_time_format = $(that).attr('id');

  var date_order = $("#search_join_date").val();
  
  if(date_order != ""){

    if(current_time_format == 'today'){ 
      $("#today_hidden").val('today');
      $("#search_join_date").val("");
    }
    else {$("#today_hidden").val("")};

  $(that).removeClass('btn-default').addClass('btn-primary');
  $('#'+elemnt_1).removeClass('btn-primary').addClass('btn-default');
  $('#'+element_2).removeClass('btn-primary').addClass('btn-default');
  $('#'+element_3).removeClass('btn-primary').addClass('btn-default');

    $.ajax({
    url: '{{route('get-event-type')}}',
    type: 'GET',
    dataType: 'html',
    data: {current_time_format: current_time_format,date_order:date_order},
  })
  .done(function(data){
    console.log(data);
    var html_event = "";
    $.each(JSON.parse(data), function(index, val) {
      html_event += `<div class="type-content text-center" style="background-color:`+val['color']+`">
                    <p>`+val['event_type_name']+`</p>
                    <div class="row">
                      <div class="col-md-6">
                        Total SMS: `+val['sms_total']+`
                      </div>
                      <div class="col-md-6">
                        Price: $0.000
                      </div>
                    </div>
                  </div>`
    });
    $("#event_type").html(html_event);
    sTable.draw();

    //console.log(data);
  })
  .fail(function() {
    console.log("error");
  });
  }
  
  
}
</script>      
@stop

