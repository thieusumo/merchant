@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Report | Rent Station')
@section('styles')
    
@stop
@section('content')
<div class="x_panel report-staff">
    <form action="{{route('loadReportStaff')}}" method="post" id="staff-form" name="staff-form">  
        <div class="btn-toolbar mb-3" role="toolbar">    
            <div class="input-group-spaddon no-margin">
                <div class="input-group date">                          
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                        
                    <input type="text" name="view_date" class="form-control form-control-sm datepicker" id="btnDate" style="width:180px;">        
                </div>
            </div>    
            <div class="groupOptions btn-group btn-group-sm mb-2" role="group" style="margin-left:5%">
                <input type="hidden" id="group-option-hidden" value="1" name="">            
                <button id="btnDaily" type="button" data-type="1" name="options" class="btn btn-sm btn-light btn-primary" autocomplete="off">Daily</button>
                <button id="btnWeekly"  type="button" data-type="2"  name="options" class="btn btn-sm btn-light" autocomplete="off">Weekly</button>
                <button id="btnMonthly"  type="button" data-type="3"  name="options" class="btn btn-sm btn-light" autocomplete="off">Monthly</button>
                <button id="btnQuaterly"  type="button" data-type="4"  name="options" class="btn btn-sm btn-light" autocomplete="off">Quarterly</button>
                <button id="btnYearly"  type="button" data-type="5"  name="options" class="btn btn-sm btn-light" autocomplete="off">Yearly</button>
            </div>
             <div class="input-group" style="margin-left:5%;">
                 <select id="listClientGroup" name="client_group" class="form-control form-control-sm">
                     <option value="@foreach($staff_list as $staff){{$staff->worker_id}},@endforeach"> -- All Rent Station -- </option>
                     @foreach($staff_list as $staff)
                     <option value="{{$staff->worker_id}}">{{$staff->worker_nickname}}</option>
                     @endforeach
                 </select>
            </div>
        </div>
    </form>
    <div class="x_content" id="divToUpdate">
        <table id="dtReportStaff" class="table table-bordered table-hover" style="width: 100%">
            <thead>
                <tr>                       
                    <th>Time</th>
                    <th>Total Service</th>
                    <th>Total Service Price</th>
                    <th>Total Price Hold</th>
                    <th>Total Price Agreement</th>
                    <th>Total Tip</th>
                    <th>Total</th>
                </tr>
            </thead>
        </table>
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
    }, function(start, end, label) {
        $("#btnDaily").trigger("click");        
    });
   
     //CLICK BUTTON TIME FORMAT
    $('.groupOptions button').on('click', function(){
        $('.groupOptions button').removeClass('btn-primary');
        $("#group-option-hidden").val($(this).attr('data-type'));        
        $(this).addClass('btn-primary');        
        var viewTimeHeader = dataTable.columns(0).header();
        switch($(this).attr('data-type')){
            case "1": $(viewTimeHeader).html('Time');   break;  // daily 
            case "2": $(viewTimeHeader).html('Day'); break; // weekly
            case "3": $(viewTimeHeader).html('Date'); break; // monthly
            case "4": $(viewTimeHeader).html('Month');  break; // quarterly
            case "5": $(viewTimeHeader).html('Month');  break; // yearly
        }            
        dataTable.draw();
        
    });   

    dataTable = $('#dtReportStaff').DataTable({
        dom: 'frtip',
        processing: true,
        serverSide: true,
        buttons: [],
        ordering: false,
        searching: false,
        paging: true,
        info: true,
        deferLoading: 0,
        ajax:{ url:"{{ route('loadReportStaff') }}",
            data: function (d) {
                d.view_date = $('#btnDate').val();
                d.view_type = $('#group-option-hidden').val();
                d.view_staff = $("#listClientGroup :selected").val();
            }
        },
        columns: [
            { data: 'view_time', name: "view_time", sClass: "text-center no-sort"},
            { data: 'total_service', name: 'total_service', sClass: "text-right"},
            { data: 'total_service_price', name: 'total_service_price', sClass: "text-right"},
            { data: 'total_price_hold', name: 'total_price_hold', sClass: "text-right"},
            { data: 'total_price_agreement', name: 'total_price_agreement', sClass: "text-right" },
            { data: 'total_tip', name: 'total_tip', sClass: "text-right" },
            { data: 'total', name: 'total', sClass: "text-right" },
        ],
        drawCallback: function(settings) {
               if($('#group-option-hidden').val() == "3"){ // monthy{
                   $('#dtReportStaff_paginate,#dtReportStaff_info').show();
               }else{
                   $('#dtReportStaff_paginate,#dtReportStaff_info').hide();
               }
          }                                  
     });
    $("#listClientGroup").change(function(event) {
        dataTable.draw();
    });
    $("#btnDaily").trigger("click");   
});    
</script>   
@stop

