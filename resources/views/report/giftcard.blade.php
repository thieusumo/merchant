@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Report | Gift card')
@section('styles')
@stop
@section('content')
<div class="x_panel report-finance">
    <form action="{{route('loadReportGiftCard')}}" method="post" id="giftcart-form" name="finance-form">  
        <div class="btn-toolbar mb-3" role="toolbar">    
        <div class="input-group-spaddon no-margin">
            <div class="input-group date">                          
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                        
                <input type="text" name="view_date" class="form-control form-control-sm datepicker" id="btnDate">  
                <input type="hidden" name="search_join_date" value="">      
            </div>
        </div>    
        <div class="groupOptions btn-group btn-group-sm mb-2" role="group" style="margin-left:10%">
            <input type="hidden" id="group-option-hidden" value="1">            
            <button id="btnDaily" type="button" data-type="1" name="options" class="btn btn-sm btn-light btn-primary" autocomplete="off">Daily</button>
            <button id="btnWeekly"  type="button" data-type="2" name="options" class="btn btn-sm btn-light" autocomplete="off">Weekly</button>
            <button id="btnMonthly"  type="button" data-type="3"  name="options" class="btn btn-sm btn-light" autocomplete="off">Monthly</button>
            <button id="btnQuaterly"  type="button" data-type="4"  name="options" class="btn btn-sm btn-light" autocomplete="off">Quarterly</button>
            <button id="btnYearly"  type="button" data-type="5"  name="options" class="btn btn-sm btn-light" autocomplete="off">Yearly</button>            
        </div>        
        </div>
    </form>
    <div class="x_content">
       <table id="datatable" class="table table-hover table-bordered" width="100%">
            <thead>
                <tr>                       
                    <th>Gift Code</th>
                    <th>Client Name</th>
                    <th>Client Phone</th>
                    <th>Client Email</th>
                    <th>Amount</th>
                    <th>Balance</th>
                    <th>Redemption</th>
                    <th>Purchase Date</th>                                       
                </tr>
            </thead>
           <tbody>
                <tr>                    
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>  
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/function-report/function-report.js') }}"></script>
<script>
    $(document).ready(function(){

        $(".groupOptions button[name='options']").on('click',function(e){
            e.preventDefault();
            var btn = $(this).attr('id');
            var view_date = $('input[name="view_date"]').val();
            var search_join_date = '';
            switch(btn){
                case 'btnDaily': search_join_date = btnDaily(view_date); break;
                case 'btnWeekly': search_join_date = btnWeekly(view_date); break;
                case 'btnMonthly': search_join_date = btnMonthly(view_date); break;
                case 'btnQuaterly': search_join_date = btnQuaterly(view_date); break;
                case 'btnYearly': search_join_date = btnYearly(view_date); break;
            };
            $("input[name='search_join_date']").val(search_join_date);            
        }); 

    });       


</script>
<script type="text/javascript">
$(document).ready(function(){    

    $('input.datepicker').daterangepicker({
        singleDatePicker: true,
        minDate: moment().subtract(10, 'years'),
        maxDate: moment(),
        showDropdowns: true
    });
    var dataTable = $('#datatable').DataTable({            
            processing: true,
            serverSide: true,
            buttons: [],
            ajax:{ url:"{{ route('loadReportGiftCard') }}",
                data: function (d) {
                    d.view_date = $('#btnDate').val();
                    d.view_type = $('#group-option-hidden').val();
                    d.command = 'get-daily-monthly-quaterly';
                    d.search_join_date = $('input[name="search_join_date"]').val();
                }
            },
            columns: [
                { data: 'gift_code', name: 'gift_code', sClass: "text-center"},
                { data: 'client_name', name: 'client_name', sClass: "text-left" },
                { data: 'client_phone', name: 'client_phone' , sClass: "text-left" },
                { data: 'client_email', name: 'client_email' , sClass: "text-left" },
                { data: 'amount', name: 'amount' , sClass: "text-right" },
                { data: 'balance', name: 'balance' , sClass: "text-right" },
                { data: 'redemption', name: 'redemption', sClass: "text-right" },
                { data: 'purchase_date' , name: 'purchase_date', sClass: "text-center" },
           ],
                                       
       });
    //CLICK BUTTON TIME FORMAT
    $('.groupOptions button').on('click', function(){
        $('.groupOptions button').removeClass('btn-primary');
        $("#group-option-hidden").val($(this).attr('data-type'));        
        $(this).addClass('btn-primary');
        dataTable.draw();
    });
    $(".type-content").on('click', function(){
        $('.type-content').removeClass('btn-primary');
        $(this).addClass('btn-primary');    
        $("#eventype-option-hidden").val($(this).attr('data-event-type')); 
        dataTable.draw();
    });
    $('#btnDaily').trigger('click');  
});
</script>

@stop

