@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Report | Finance')
@section('styles')
<style type="text/css">
   .report-finance th.colname{
        min-width: 140px;
    }
</style>
@stop
@section('content')
<div class="x_panel report-finance">
    <form action="{{route('loadReportFinance')}}" method="post" id="finance-form" name="finance-form">  
        <div class="btn-toolbar mb-3" role="toolbar">    
        <div class="input-group-spaddon no-margin">
            <div class="input-group date">                          
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                        
                <input type="text" name="view_date" class="form-control form-control-sm datepicker" id="btnDate">        
            </div>
        </div>    
        <div class="groupOptions btn-group btn-group-sm mb-2" role="group" style="margin-left:10%">
            <input type="hidden" id="group-option-hidden" value="1" name="">            
            <button id="btnDaily" type="button" data-type="1" name="options" class="btn btn-sm btn-light btn-primary" autocomplete="off">Daily</button>
            <button id="btnWeekly"  type="button" data-type="2" name="options" class="btn btn-sm btn-light" autocomplete="off">Weekly</button>
            <button id="btnMonthly"  type="button" data-type="3"  name="options" class="btn btn-sm btn-light" autocomplete="off">Monthly</button>
            <button id="btnQuaterly"  type="button" data-type="4"  name="options" class="btn btn-sm btn-light" autocomplete="off">Quarterly</button>
            <button id="btnYearly"  type="button" data-type="5"  name="options" class="btn btn-sm btn-light" autocomplete="off">Yearly</button>
        </div>        
        </div>
    </form>
    <div class="x_content" id="divToUpdate">
        <span id="daily_monthly_weekly_table">
        <table id="datatable" class="table table-hover table-bordered">
                <thead>
                    <tr>                       
                        <th>Time</th>
                        <th>Ticket</th>
                        <th>Walkin</th>
                        <th>New customer</th>
                        <th>Services</th>
                        <th>Promo</th>
                        <th>Tips</th>
                        <th>Product</th>
                        <th>Buy Giftcard</th>
                        <th>Tax</th>
                        <th>Gross</th>
                        <th>Pay Giftcard</th>
                        <th>Pay Point</th>
                        <th>RS</th>
                        <th>Extra</th>
                        <th>Net</th>        
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
        </span>
        <span id="yearly_table" style="display: none">
        <div class="col-md-12">
            <h5>Gross Income</h5>
            <table id="datatable_gross" class="table table-hover table-bordered" width="100%">
                <thead>
                    <tr>                       
                        <th nowrap="nowrap">Payment Method</th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Aug</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>
                        <th>Percent</th>
                        <th>Total</th>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-center">TOTAL</th>
                        <th class="text-right" id="total1">0</th>
                        <th class="text-right" id="total2">0</th>
                        <th class="text-right" id="total3">0</th>
                        <th class="text-right" id="total4">0</th>
                        <th class="text-right" id="total5">0</th>
                        <th class="text-right" id="total6">0</th>
                        <th class="text-right" id="total7">0</th>
                        <th class="text-right" id="total8">0</th>
                        <th class="text-right" id="total9">0</th>
                        <th class="text-right" id="total10">0</th>
                        <th class="text-right" id="total11">0</th>
                        <th class="text-right" id="total12">0</th>
                        <th class="text-right" id="totalPercent">100%</th>
                        <th class="text-right" id="totalTotal">0</th>
                    </tr>
                </tfoot>
            </table>
        </div>
            <br>
        <div class="col-md-12">
            <h5>Expense</h5>
            <table id="expense_table" class="table table-hover table-bordered" width="100%">
                <thead>
                    <tr>                       
                        <th>Name</th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Aug</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>
                        <th class="font-weight-bold">Percent</th>
                        <th class="font-weight-bold">Total</th>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                 <tfoot>
                    <tr>
                        <th class="text-center">TOTAL</th>
                        <th class="text-right" id="totale1">0</th>
                        <th class="text-right" id="totale2">0</th>
                        <th class="text-right" id="totale3">0</th>
                        <th class="text-right" id="totale4">0</th>
                        <th class="text-right" id="totale5">0</th>
                        <th class="text-right" id="totale6">0</th>
                        <th class="text-right" id="totale7">0</th>
                        <th class="text-right" id="totale8">0</th>
                        <th class="text-right" id="totale9">0</th>
                        <th class="text-right" id="totale10">0</th>
                        <th class="text-right" id="totale11">0</th>
                        <th class="text-right" id="totale12">0</th>
                        <th class="text-right" id="totalePercent">100%</th>
                        <th class="text-right" id="totaleTotal">0</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        </span>
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
        if($(this).attr('id') == 'btnYearly'){
            $("#daily_monthly_weekly_table").hide();
            $("#yearly_table").show();            
            yGrossTable.draw();
            yExpenseTable.draw();
        }else{
            $("#daily_monthly_weekly_table").show();
            $("#yearly_table").hide();   
            var viewTimeHeader = dwmTable.columns(0).header();
            switch($(this).attr('data-type')){
                case "1": $(viewTimeHeader).html('Time');   break;  // daily 
                case "2": $(viewTimeHeader).html('Day'); break; // weekly
                case "3": $(viewTimeHeader).html('Date'); break; // monthly
                case "4": $(viewTimeHeader).html('Month');  break; // quarterly
            }            
            dwmTable.draw();
        }
    });    
    //GET DATA FOLLOW TIMW FORMAT
    dwmTable = $('#datatable').DataTable({ 
            dom: 'frtip',
            processing: true,
            serverSide: true,
            buttons: [],
            ordering: false,
            searching: false,
            paging: true,
            info: true,
            deferLoading: 0,
            ajax:{ url:"{{ route('loadReportFinance') }}",
                data: function (d) {
                    d.view_date = $('#btnDate').val();
                    d.view_type = $('#group-option-hidden').val();
                    d.command = 'get-daily-monthly-quaterly';
                }
            },
            columns: [
                { data: 'view_time', name: 'view_time', sClass: "text-center no-sort"},
                { data: 'total_ticket', name: 'total_ticket', sClass: "text-right" },
                { data: 'total_walkin', name: 'total_walkin' , sClass: "text-right" },
                { data: 'total_newcustomer', name: 'total_newcustomer' , sClass: "text-right" },
                { data: 'total_services', name: 'total_services' , sClass: "text-right" },
                { data: 'total_promo', name: 'total_promo' , sClass: "text-right" },
                { data: 'total_tips', name: 'total_tips', sClass: "text-right" },
                { data: 'total_product' , name: 'total_product', sClass: "text-right" },
                { data: 'total_buygiftcard' , name: 'total_buygiftcard', sClass: "text-right" },
                { data: 'total_tax' , name: 'total_tax', sClass: "text-right" },
                { data: 'total_gross', name: 'total_gross', sClass: "text-right" },
                { data: 'total_paygiftcard' , name: 'total_paygiftcard', sClass: "text-right" },
                { data: 'total_paypoint' , name: 'total_paypoint', sClass: "text-right" },
                { data: 'total_rs', name: 'total_rs', sClass: "text-right" },
                { data: 'total_extra', name: 'total_extra', sClass: "text-right" },
                { data: 'total_net' , name: 'total_net' , sClass: "text-right" }
           ],
           drawCallback: function(settings) {
               if($('#group-option-hidden').val() == "3"){ // monthy{
                   $('#datatable_paginate,#datatable_info').show();
               }else{
                   $('#datatable_paginate,#datatable_info').hide();
               }
          }                            
       });
     
    yGrossTable = $('#datatable_gross').DataTable({             
            processing: true,
            serverSide: true,
            buttons: [],
            ordering: false,
            searching: false,
            paging: false,
            info: false,
            deferLoading: 0,
            ajax:{ url:"{{ route('loadReportFinance') }}",
                data: function (d) {
                         d.view_date = $('#btnDate').val();
                         d.view_type = $('#group-option-hidden').val();
                         d.command = 'get-yearly-gross-income';
                }
            },
            columns: [

                     { data: 'name', name: 'name', sClass: "text-left",},
                     { data: 'Jan', name: 'Jan', sClass: "text-right"},
                     { data: 'Feb', name: 'Feb', sClass: "text-right" },
                     { data: 'Mar', name: 'Mar', sClass: "text-right" },
                     { data: 'Apr', name: 'Apr', sClass: "text-right" },
                     { data: 'May', name: 'May', sClass: "text-right" },
                     { data: 'Jun', name: 'Jun', sClass: "text-right"},
                     { data: 'Jul', name: 'Jul', sClass: "text-right"},
                     { data: 'Aug' , name: 'Aug', sClass: "text-right"},
                     { data: 'Sep' , name: 'Sep', sClass: "text-right"},
                     { data: 'Oct' , name: 'Oct', sClass: "text-right"},
                     { data: 'Nov', name: 'Nov', sClass: "text-right"},
                     { data: 'Dec' , name: 'Dec', sClass: "text-right"},
                     { data: 'percent' , name: 'percent', sClass: "text-right font-weight-bold"},
                     { data: 'total' , name: 'total', sClass: "text-right font-weight-bold"},
           ],
           footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), total;
                for(var $columnIndex =1; $columnIndex <= 12; $columnIndex++){
                    total = api.column($columnIndex).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    $( api.column($columnIndex).footer() ).html('$'+Number(total).toLocaleString());
                }
                total = api.column(14).data().reduce( function (a, b) {
                       return intVal(a) + intVal(b);                       
                }, 0 );
                $( api.column(14).footer() ).html('$'+Number(total).toLocaleString());
            }                            
       });
       yExpenseTable = $('#expense_table').DataTable({       
            processing: true,
            serverSide: true,
            buttons: [],
            ordering: false,
            searching: false,
            paging: false,
            info: false,   
            deferLoading: 0,
            ajax:{ url:"{{ route('loadReportFinance') }}",
                 data: function (d) {
                   d.view_date = $('#btnDate').val();
                   d.view_type = $('#group-option-hidden').val();
                   d.command = 'get-yearly-promotion-coupon';
                }
            },
            columns: [

               { data: 'name', name: 'name', sClass: "text-left"},
               { data: 'Jan', name: 'Jan', sClass: "text-right"},
               { data: 'Feb', name: 'Feb', sClass: "text-right"},
               { data: 'Mar', name: 'Mar', sClass: "text-right"},
               { data: 'Apr', name: 'Apr', sClass: "text-right"},
               { data: 'May', name: 'May', sClass: "text-right"},
               { data: 'Jun', name: 'Jun', sClass: "text-right"},
               { data: 'Jul', name: 'Jul', sClass: "text-right"},
               { data: 'Aug' , name: 'Aug', sClass: "text-right"},
               { data: 'Sep' , name: 'Sep', sClass: "text-right"},
               { data: 'Oct' , name: 'Oct', sClass: "text-right"},
               { data: 'Nov', name: 'Nov', sClass: "text-right"},
               { data: 'Dec' , name: 'Dec', sClass: "text-right"},
               { data: 'percent' , name: 'percent', sClass: "text-right font-weight-bold"},
                { data: 'total' , name: 'total', sClass: "text-right font-weight-bold"},
         ],
           footerCallback: function ( row, data, start, end, display ) {
                 var api = this.api(), total;
                for(var $columnIndex =1; $columnIndex <= 12; $columnIndex++){
                    total = api.column($columnIndex).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    $( api.column($columnIndex).footer() ).html('$'+Number(total).toLocaleString());
                }
                total = api.column(14).data().reduce( function (a, b) {
                       return intVal(a) + intVal(b);                       
                }, 0 );
                $( api.column(14).footer() ).html('$'+Number(total).toLocaleString());
            }  
                                       
       });
    $("#btnDaily").trigger("click");
});    
</script>
@stop

