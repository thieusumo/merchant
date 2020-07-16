@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Report | Client')
@section('styles')
<style type="text/css">
    .modal-header{ padding: 5px 10px;}
</style>
@stop
@section('content')
<div class="x_panel report-client">
    <form action="{{route('loadReportClient')}}" method="post" id="client-form" name="client-form">  
        <div class="btn-toolbar mb-3" role="toolbar">    
            <div class="input-group-spaddon no-margin">
                <div class="input-group date">                          
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                        
                    <input type="text" name="view_date" class="form-control form-control-sm datepicker" id="btnDate" style="width:180px;">        
                </div>
            </div>    
            <div class="groupOptions btn-group btn-group-sm mb-2" role="group" style="margin-left:5%">
                <input type="hidden" id="time_format_hidden" value="" name="">
                <button id="day" type="button" data-type="1" name="options" class="btn btn-sm btn-light btn-primary" autocomplete="off">Day</button>
                <button id="week"  type="button" data-type="2"  name="options" class="btn btn-sm btn-light" autocomplete="off">Week</button>
                <button id="month"  type="button" data-type="3"  name="options" class="btn btn-sm btn-light" autocomplete="off">Month</button>
                <button id="year"  type="button" data-type="5"  name="options" class="btn btn-sm btn-light" autocomplete="off">Year</button>
            </div>
            <div class="input-group" style="margin-left:5%;">
                <!-- load list from database -->
                <div id="client-group" class="btn-group btn-group-toggle" data-toggle="buttons">
                    @foreach($group_list as $key => $group)

                    @if($key == 0)
                        <input type="hidden" id="group_hidden" value="{{$group->customertag_id}}" name="{{$group->customertag_id}}">
                    @endif
                        <label class="btn btn-sm btn-info {{$key==0?"active":""}} group_radio"  data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                            <input class="show-hide-design" name="client_group" value="{{$group->customertag_id}}" type="radio" {{$key==0?"checked":""}}>{{$group->customertag_name}}
                        </label>
                    @endforeach
                </div>
            </div>
           {{--  <div class="input-group" style="margin-left:5%;">
                <h6>TOTAL CLIENT: 5</h6>
            </div> --}}
        </div>
    </form>
    <div class="x_content" id="divToUpdate">
        <table id="dtReportClient" class="table table-striped table-bordered">
            <thead>
                <tr>                     
                    <th>Client Name</th>
                    <th>Phone</th>
                    <th>Birthday</th>
                    <th>Ticket</th>
                    <th>Date</th>
                    <th>Point</th>
                    <th>Discount/Coupon</th>
                    <th>Booking Type</th>
                    <th>Client Group</th>
                    <th>View</th>
                </tr>
            </thead>
        </table>
    </div>
</div>  
<!-- Modal -->
 <div id="myModalTicketHis" class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog" style="max-width:80%; max-height: 90%; width: auto; height: auto;">       
         <div class="modal-content">
             <div class="modal-header">
                <h5 class="modal-title">Ticket History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body" style="padding-top:0px;">
                @include('report.partials.client_ticket_history')
            </div>
         </div>
         <!-- /.modal-content -->
     </div>
     <!-- /.modal-dialog -->
 </div>
 <!-- /.modal -->
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
    
    var order_id = "";
    $(document).on('click', 'a.view-ticket-his', function (event) {
        order_id = $(this).attr('id');
        $('#myModalTicketHis').modal('show');
        hTable.draw();
        gTable.draw();
    });

    hTable = $('#client-history-table').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,

             buttons: [
             ],
             columnDefs: [
             {
                "targets": 0,
                "className": "text-center",
             },
             {
                "targets": 1, 
                "className": "text-center"
             },
             {
                "targets": 2,
                "className": "text-left",
             },
             {
                "targets": 3,
                "className": "text-left",
             },
             {
                "targets": 4,
                "className": "text-left",
             },
             {
                "targets": 5,
                "className": "text-right",
             },
             {
                "targets": 6,
                "className": "text-right",
             },
             {
                "targets": 7,
                "className": "text-right",
             },
             {
                "targets" : 8,
                "className" : "text-right"
             },
             ],
             ajax:{ url:"{{ route('get-detail-order') }}",
                 data: function (d) {
                         d.order_id = order_id;
                    }
                  },
                 columns: [

                          { data: 'orderdetail_order_id', name: 'orderdetail_order_id' },
                          { data: 'updated_at', name: 'updated_at' },
                          { data: 'service_name', name: 'service_name' },
                          { data: 'combo_detail', name: 'combo_detail' },
                          { data: 'worker_nickname', name: 'worker_nickname' },
                          { data: 'orderdetail_price', name: 'orderdetail_price' },
                          { data: 'orderdetail_extra', name: 'orderdetail_extra'},
                          { data: 'orderdetail_tip' , name: 'orderdetail_tip'},
                          { data: 'orderdetail_tax' , name: 'orderdetail_tax'},
                ],
                                       
       });
    sTable = $('#dtReportClient').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,

             buttons: [
             ],
             columnDefs: [
             {
                "targets": 0,
                "className": "text-center",
             },
             {
                "targets": 1, 
                "className": "text-center"
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
                "className": "text-center",
             },
             {
                "targets": 5,
                "className": "text-right",
             },
             {
                "targets": 6,
                "className": "text-right",
             },
             {
                "targets": 7,
                "className": "text-center",
             },
             {
                "targets" : 8,
                "className" : "text-center"
             },
             {
                "targets" : 9,
                "className" : "text-center"
             },
             ],
             ajax:{ url:"{{ route('get-client-report') }}",
                 data: function (d) {
                         d.date_order = $('#btnDate').val();
                         d.client_group = $('#group_hidden').val();
                         d.time_format = $('#time_format_hidden').val();
                    }
                  },
                 columns: [
                          { data: 'customer_fullname', name: 'customer_fullname' },
                          { data: 'customer_phone', name: 'customer_phone' },
                          { data: 'customer_birthdate', name: 'customer_birthdate' },
                          { data: 'order_bill', name: 'order_bill' },
                          { data: 'updated_at', name: 'updated_at' },
                          { data: 'customer_point_total', name: 'customer_point_total' },
                          { data: 'order_coupon_discount', name: 'order_coupon_discount'},
                          { data: 'booking_type' , name: 'booking_type'},
                          { data: 'client_group' , name: 'client_group'},
                          { data: 'action', name: 'action'}
                ],
                                       
       });
    gTable = $('#giftcard_table').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,

             buttons: [
             ],
             columnDefs: [
             {
                "targets": 0,
                "className": "text-center",
             },
             {
                "targets": 1, 
                "className": "text-center"
             },
             {
                "targets": 2,
                "className": "text-right",
             },
             ],
             ajax:{ url:"{{ route('get-giftcard-report') }}",
                 data: function (d) {
                        d.order_id = order_id;
                    }
                  },
                 columns: [
                          { data: 'order_bill', name: 'order_bill' },
                          { data: 'order_giftcard_code', name: 'order_giftcard_code' },
                          { data: 'order_giftcard_amount', name: 'order_giftcard_amount' },
                ],
                                       
       });

    //CLICK BUTTON TIME FORMAT
    $('.groupOptions button').on('click', function(){
        $('.groupOptions button').removeClass('btn-primary');
        $(this).addClass('btn-primary');
        var time_format =  $(this).attr('id');
        $("#time_format_hidden").val(time_format);
          sTable.draw();
    });
    $('.group_radio').click(function(){

        $("#group_hidden").val($(this).children('input').val());

        sTable.draw();
    });
});    
</script>
@stop

