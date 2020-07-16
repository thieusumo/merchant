@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Report | SMS')
@section('styles')
<style type="text/css">
    
    div.type-content:hover, div.type-content.active{
        background: #00ff98;
    } 
    .bg-blue-light{
        background-color: #bed6ee !important;
    } 
    .bg-blue-light:hover{
        background-color: #70b6ef!important;
    } 
    .bg-gray-light:hover{
        background-color: #e3e3e2!important;
    }
    .bg-gray-light{
        background-color: #efefea!important;
    }
</style>
@stop
@section('content')
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
            <button id="btnDaily" type="button" data-type="1" name="options" class="btn btn-sm btn-light btn-primary" autocomplete="off">Daily</button>
            <button id="btnWeekly"  type="button" data-type="2" name="options" class="btn btn-sm btn-light" autocomplete="off">Weekly</button>
            <button id="btnMonthly"  type="button" data-type="3"  name="options" class="btn btn-sm btn-light" autocomplete="off">Monthly</button>
            <button id="btnQuaterly"  type="button" data-type="4"  name="options" class="btn btn-sm btn-light" autocomplete="off">Quarterly</button>
            <button id="btnYearly"  type="button" data-type="5"  name="options" class="btn btn-sm btn-light" autocomplete="off">Yearly</button>
             <div class="type-content text-center bg-gray-light p-2 ml-5">
                <div class="text-uppercase font-weight-bold">Balance SMS: 0</div>                
            </div>
        </div>        
        </div>
    </form>
    <div class="x_content row">
        <div class="col-md-2">
            <input type="hidden" id="eventype-option-hidden" value="1">           
           
            <div class="type-content text-center bg-blue-light p-2 active" data-event-type="1">
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
            </div>
        </div>
        
        <div class="col-md-10">
            <table id="dataTable" class="table table-hover table-bordered" width="100%">
                
            </table>
        </div>
    </div>
</div>  
@stop
@section('scripts')
<script type="text/javascript">
function initDataTable(isDraw){   
   var viewType = $("#group-option-hidden").val();
   var eventType = $("#eventype-option-hidden").val();
   var columns = [{title: 'Name'},{title: 'Phone'},{title: 'Content'},{title: 'Reply Client'},{title: 'Date'}]
//   switch(eventType){
//       case "1": // Happy Birthday
//           columns = [{title: 'Name'},{title: 'Phone'},{title: 'Content'},{title: 'Reply Client'},{title: 'Date'}]
//       break;
//       case "2": // Happy Birthday
//           columns = [{title: 'Name'},{title: 'Phone'},{title: 'Content'},{title: 'Reply Client'},{title: 'Date'}]
//       break;
//       case "3": // Happy Birthday
//           columns = [{title: 'Name'},{title: 'Phone'},{title: 'Content'},{title: 'Reply Client'},{title: 'Date'}]
//       break;
//       case "4": // Happy Birthday
//           columns = [{title: 'Name'},{title: 'Phone'},{title: 'Content'},{title: 'Reply Client'},{title: 'Date'}]
//       break;
//       case "5": // Happy Birthday
//           columns = [{title: 'Name'},{title: 'Phone'},{title: 'Content'},{title: 'Reply Client'},{title: 'Date'}]
//       break;
//   }
   if ( ! $.fn.DataTable.isDataTable( '#dataTable' ) ) {
      
    $('#dataTable').dataTable({
        "columns": columns,
        "data": []
    });  
   }
}
$(document).ready(function(){    
    $('input.datepicker').daterangepicker({
        singleDatePicker: true,
        minDate: moment().subtract(10, 'years'),
        maxDate: moment(),
        showDropdowns: true
    });
    initDataTable(false);
    //CLICK BUTTON TIME FORMAT
    $('.groupOptions button').on('click', function(){
        $('.groupOptions button').removeClass('btn-primary');
        $("#group-option-hidden").val($(this).attr('data-type'));        
        $(this).addClass('btn-primary');
        initDataTable(true);
    });
    $(".type-content").on('click', function(){
        $('.type-content').removeClass('btn-primary');
        $(this).addClass('btn-primary');    
        $("#eventype-option-hidden").val($(this).attr('data-event-type')); 
        initDataTable(true);
    });
});
</script>
@stop

