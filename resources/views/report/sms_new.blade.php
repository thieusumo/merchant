@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Report | SMS')
@section('styles')
<style type="text/css">
    div.type-content:hover, div.type-content.active{
        background: #00ff98;
    }
</style>
@stop
@section('content')
<input type="hidden" id="event_id_hidden" value="">
<input type="hidden" id="type_id_hidden" value="0">
<input type="hidden" id="type_event_id_hidden" value="" name="">
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2">Total: <span style="color: red" id="total"></span></div>
            <div class="col-md-2">Send: <span style="color: red" id="success"></span> </div>
            <div class="col-md-2">Fail: <span style="color: red" id="fail"></span></div>
            <div class="col-md-2">Balance: <span style="color: red" id="balance"></span></div>
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
<div class="x_panel report-finance">
    <form action="{{route('loadReportSMS')}}" method="post" id="finance-form" name="finance-form">  
        <div class="btn-toolbar mb-3" role="toolbar">    
        <div class="input-group-spaddon no-margin">
            <div class="input-group date">                          
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                        
                <input type="text" name="view_date" class="form-control form-control-sm datepicker" id="btnDate">        
            </div>
        </div>    
        <div class="groupOptions btn-group btn-group-sm mb-2" role="group" style="margin-left:10%">
            <input type="hidden" id="group-option-hidden" value="1">
            <input type="hidden" value="btnDaily" id="today_hidden" name="">       
            <button id="btnDaily" type="button" data-type="1" name="options" onclick="eventType(this,'btnWeekly','btnMonthly','btnQuaterly','btnYearly')" class="btn btn-sm btn-light btn-primary" autocomplete="off">Daily</button>
            <button id="btnWeekly"  type="button" data-type="2" name="options" onclick="eventType(this,'btnDaily','btnMonthly','btnQuaterly','btnYearly')" class="btn btn-sm btn-light" autocomplete="off">Weekly</button>
            <button id="btnMonthly"  type="button" data-type="3"  name="options"  onclick="eventType(this,'btnDaily','btnWeekly','btnQuaterly','btnYearly')"  class="btn btn-sm btn-light" autocomplete="off">Monthly</button>
            <button id="btnQuaterly"  type="button" data-type="4"  name="options" onclick="eventType(this,'btnDaily','btnWeekly','btnMonthly','btnYearly')" class="btn btn-sm btn-light" autocomplete="off">Quarterly</button>
            <button id="btnYearly"  type="button" data-type="5"  name="options" onclick="eventType(this,'btnDaily','btnWeekly','btnMonthly','btnQuaterly')" class="btn btn-sm btn-light" autocomplete="off">Yearly</button>
             {{-- <div class="type-content text-center bg-gray-light p-2 ml-5">
                <div class="text-uppercase font-weight-bold">Balance SMS: 0</div>                
            </div> --}}
        </div>        
        </div>
    </form>
    <div class="x_content row">
        <div class="col-md-2">
            <input type="hidden" id="eventype-option-hidden" value="1">           
           
            {{-- <div class="type-content text-center bg-blue-light p-2 active" data-event-type="1">
                <div class="text-uppercase">Happy Birthday</div>
                <div class="p-sm-2">
                    Total SMS: <span class="font-weight-bold">0</span>
                </div>
            </div>
            <div class="type-content text-center bg-gray-light p-2" data-event-type="2">
                <div class="text-uppercase">Invite Review</div>
                <div class="p-sm-2">
                    Total SMS: <span class="font-weight-bold">0</span>
                </div>
            </div>
            <div class="type-content text-center bg-blue-light p-2" data-event-type="3">
                <div class="text-uppercase">Payment Giftcard</div>
                <div class="p-sm-2">
                    Total SMS: <span class="font-weight-bold">0</span>
                </div>
            </div>
            <div class="type-content text-center bg-gray-light p-2" data-event-type="4">
                <div class="text-uppercase">Coupon</div>
                <div class="p-sm-2">
                    Total SMS: <span class="font-weight-bold">0</span>
                </div>
            </div>
             <div class="type-content text-center bg-blue-light p-2" data-event-type="5">
                <div class="text-uppercase">Reminder</div>
                <div class="p-sm-2">
                    Total SMS: <span class="font-weight-bold">0</span>
                </div>
            </div> --}}
            <div id="event_type" class="col-md-12">
                
            </div>
        </div>
        
        <div class="col-md-10">
            <table id="datatable" class="table table-hover table-bordered" width="100%">
                <tr>
                    <th class="text-center">ID</th>   
                    <th class="text-center">Event Type</th>   
                    <th class="text-center">Name</th>
                    <th class="text-center">Start/Send Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </table>
        </div>
    </div>
</div>  
@stop
@section('scripts')
<script type="text/javascript">

$(document).ready(function(){    
    $('input.datepicker').daterangepicker({
        singleDatePicker: true,
        minDate: moment().subtract(10, 'years'),
        maxDate: moment(),
        showDropdowns: true
    });
    $(".datepicker").change(function(event) {
        sTable.draw();
    });
    if( $("#today_hidden").val() == "btnDaily"){
        $("#btnDaily").click();
    }
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
         ajax:{ url:"{{ route('get-data-event') }}",
          data:function(d){
            d.search_join_date = $("#search_join_date").val();
            d.today = $("#today_hidden").val();
            d.type_event_id = $("#type_event_id_hidden").val();
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
        url: '{{route('calculate-sms-report')}}',
        type: 'get',
        dataType: 'html',
        data: {event_id: event_id,type_id: type_id},
      })
      .done(function(data) {
        data = JSON.parse(data);
        $("#total").text(data['total']);
        $("#success").text(data['success']);
        $("#fail").text(data['fail']);
        $("#balance").text(data['balance']);
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
         ajax:{ url:"{{ route('event-detail-report')}}",
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
$(document).on("click",".type-event",function(){

    $('.type-event').removeClass('btn-primary');
    $(this).addClass('btn-primary'); 

    var type_event_id_hidden = $(this).attr('data-event-type');
    $("#type_event_id_hidden").val(type_event_id_hidden);
    sTable.draw();
});

function eventType(that,elemnt_1,element_2,element_3,element_4){
  

  var current_time_format = $(that).attr('id');

  var date_order = $(".datepicker").val();
  
  if(date_order != ""){

    $("#today_hidden").val(current_time_format);

  $(that).removeClass('btn-default').addClass('btn-primary');
  $('#'+elemnt_1).removeClass('btn-primary');
  $('#'+element_2).removeClass('btn-primary');
  $('#'+element_3).removeClass('btn-primary');
  $('#'+element_4).removeClass('btn-primary');

    $.ajax({
    url: '{{route('get-event-type-report')}}',
    type: 'GET',
    dataType: 'html',
    data: {current_time_format: current_time_format,date_order:date_order},
  })
  .done(function(data){
    console.log(data);
    var html_event = "";
    var type_event_id = $("#type_event_id_hidden").val();
    $.each(JSON.parse(data), function(index, val) {
        
        if(type_event_id == val['type_id']){
            html_event += `<div class="type-event type-content text-center active `+val['color']+` p-2" data-event-type="`+val['type_id']+`">
                <div class="text-uppercase">`+val['event_type_name'] +`</div>
                <div class="p-sm-2">
                    Total SMS: <span class="font-weight-bold">`+val['sms_total']+`</span>
                </div>
            </div>`
        }
        else{
            html_event += `<div class="type-event type-content text-center `+val['color']+` p-2" data-event-type="`+val['type_id']+`">
                <div class="text-uppercase">`+val['event_type_name'] +`</div>
                <div class="p-sm-2">
                    Total SMS: <span class="font-weight-bold">`+val['sms_total']+`</span>
                </div>
            </div>`
        }
      
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

