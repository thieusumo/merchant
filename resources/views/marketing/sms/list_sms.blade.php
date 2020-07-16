@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Management')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
<style>
     .switchery-small{width:40px;}
     .switchery-small > small{left: 40px;}
     .top_nav{height: 84px;}       
</style>
@stop
@section('content')
{{-- MODAL LIST RECEIVE --}}
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
        <div class="x_panel border-0">   
            <div class="x_title">
                <h5 class="border_bottom">SMS Management</h5>
            </div>
            <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
               <th class="text-center">ID</th>   
               <th class="text-center">Event Type</th>   
                <th>Name</th>
                <th>Start/Send Date</th>
                <th>End Date</th>
                <th class="text-center" nowrap="nowrap">Time Send</th>
                <th class="text-center">Total SMS</th>           
                {{-- <th class="text-center">Enable</th> --}}
                <th class="text-center">Last Update</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            {{-- <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">Happy Birthday</td>       
                    <td><a href="{{ route('viewSMS')}}/1" class="view-sms">HB VIP Member</a></td>
                    <td class="text-center">20/04/2019</td>
                    <td class="text-center"></td>
                    <td class="text-center">11:20 AM</td>                                      
                    <td class="text-center"><input type="checkbox" class="js-switch" checked="checked" value="1" name="sms_status"/></td>                      
                    <td class="text-center">20/04/2019 11:20 AM by Admin</td> 
                </tr>
                <tr>
                    <td class="text-center">2</td>
                     <td class="text-center">Reminder</td>   
                     <td><a href="{{ route('viewSMS')}}/2" class="view-sms">Thanksgiving Holiday</a></td>
                    <td class="text-center">20/04/2019</td>
                    <td class="text-center"></td>
                    <td class="text-center">11:20 AM</td>                                         
                    <td class="text-center"><input type="checkbox" class="js-switch" value="1" name="sms_status"/></td>                      
                    <td class="text-center">20/04/2019 11:20 AM by Admin</td>  
                </tr>
            </tbody> --}}    
        </table>   
        </div>
    </div>
</div> 
<!-- The Modal -->
<div class="modal" id="modelViewSMS">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        Modal body..
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
@stop
@section('scripts') 
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<script type="text/javascript">
// function changeSMSEventStatus(){
//    var parent = this.element.parentNode.tagName.toLowerCase()
//        ,$jswitch = this 
//        ,$isCheck = this.element.checked
//        ,$newStatus =  $isCheck?'disabled':"enabled"       
//        ,labelParent = (parent === 'label') ? false : true;
    
//     if(confirm("Are you sure to want to "+$newStatus+" this sms event ?")){   
//         $.post("{{ route('changeSMSEventStatus') }}",{id: $(this.element).val()},
//             function( data ) { 
//                 if(data.success){
//                     $jswitch.setPosition(labelParent);
//                     $jswitch.handleOnchange($isCheck);
//                     alert("Promotion has been "+$newStatus+" successfully");                    
//                 }else{
//                     alert(data.messages)
//                 }      
//             },'json');          
//     }    
// }
$(document).on('click', '.switchery', function() {

    if(window.confirm("Do you want to change this event status?")){

         var sms_event_id = $(this).siblings('input').attr('id');

         var checked = $(this).siblings('input').attr('checked');
         $.ajax({
             url: "{{ route('changeSMSEventStatus') }}",
             type: 'GET',
             dataType: 'html',
             data:"checked="+checked+"&sms_event_id="+sms_event_id,
         })
         .done(function(data) {
          console.log(data);
            toastr.success('Change Event Status Succsess!');
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Change Event Status Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }else{
            return false;
        }
     
   });

   $(document).on('click', '.switchery', function(e) {
          Table.draw();
          e.preventDefault();
      });

   $(document).on('click','.delete-event', function(e){
        var sms_event_id = $(this).attr('id');
        if(window.confirm("Are you sure you want to remove this event ?"))
        {
          $.ajax({
            url:"{{route('delete-event')}}",
            method:"get",
            data:{sms_event_id:sms_event_id},
            })
         .done(function(data) {
          console.log(data);
            Table.draw();
            e.preventDefault();
            if(data.success)
              toastr.success('Remove Event Success!');
            else
              toastr.error('Remove Event Error!');
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Remove Event  Error!');
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }
        else{
          return false;
        }
    });

$(document).ready(function() {
    Table = $('#datatable').DataTable({
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
         "aaSorting": [
             [ 7, "desc" ]
         ],
         processing: true,
        serverSide: true,
         ajax:{ url:"{{ route('smsManagement_DataTables') }}"},
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
                  "className": "text-center",
             }
             ],
        columns: [

                  { data: 'sms_send_event_id', name: 'sms_send_event_id' },
                  { data: 'sms_send_event_type', name: 'sms_send_event_type' },
                  { data: 'sms_send_event_title', name: 'sms_send_event_title' },
                  { data: 'sms_send_event_start_day' , name:'sms_send_event_start_day'},
                  { data: 'sms_send_event_end_date' , name:'sms_send_event_end_date'},
                  { data: 'sms_send_event_start_time' , name:'sms_send_event_start_time'},
                  { data: 'sms_total' , name:'sms_total'},
                  // { data: 'sms_send_event_enable' , name:'sms_send_event_enable'},
                  { data: 'last_update' , name:'last_update'},
                  { data: 'action' , name:'action'},                  
          ],
   }); 
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
    
});  
</script>
@stop

