@extends('layouts.master')
@section('title', 'Expense')
@section('styles')
    
@stop
@section('content')
 <div class="page-title">
        <div class="title_left">
            <h3>@if(isset($id)) Edit Expense @else Add Expense @endif</h3>
        </div>
    </div>
<div class="x_panel">   
    <div class="col-xs-12 col-sm-12 col-md-12 full-height scroll-menu">
    <form class="form-horizontal form-addon-ext label-date" name="frm-expense" custom-submit="" novalidate="novalidate">
        <div class="clear">&nbsp;</div>
        <div class="row form-group">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="row form-group">
                    <label class="col-xs-6 col-sm-6 col-md-6">Expense Date</label>
                    <div class="col-xs-3 col-sm-3 col-md-5 no-padding">
                         <div class='input-group date'>
                            <input type='text' class="form-control form-control-sm" id="expense_date"/>
                            <span class="input-group-addon">
                               <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="expenses_html" class="row form-group">            
        </div>
        <div class="row form-group">  
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="row form-group">
                    <label class="col-xs-6 col-sm-6 col-md-6">
                        <input class="form-control form-control-sm" id="add_expense_name"  placeholder="Name" type="text">
                    </label>
                    <div class="col-xs-3 col-sm-3 col-md-4 no-padding">
                        <div class="input-group">
                            <input class="form-control form-control-sm" id ="add_expense_cost" valid-number="" placeholder="Cost" type="number"> <span class="input-group-addon">$</span></div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="btn btn-sm btn-success" id="expense_add" ><i class="fa fa-plus" title="Add item" role="button" tabindex="0"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label>&nbsp;</label>
                <div id="submit_expense" class="submit_expense btn btn-sm btn-primary" >SUBMIT</div>
                <div id="cancel_expense" class="cancel_expense btn btn-sm btn-default">CANCEL</div>
            </div>
        </div>
    </form>
</div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    var id = "{{$id}}";
    var gen_id ="1";

    $('#expense_date').daterangepicker({       
       singleDatePicker: true
    })
    ; 
    //CHECK NEW OR UPDATE
    if(id =="" ){
        //NEW EXPENSE
        var expense_arr = [
        {"pe_id":"", "pe_name":"SUPPLY OFFICE", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"ACCOUNT", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"GAS FOR BUSINESS", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"LICENSE", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"MERCHANT SERVICE", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"DONATION", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"BUSINESS INS", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"LEGAL SERVICE", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"TRAVEL", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"MEAL/PARTY", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"SHOW NAILS", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"BUSINESS CARD", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"HEALTH INS", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"ELECTRONIC", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"REPAIR BUSINESS", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"BUSINESS PHONE", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"SAMPLE PRODUCT", "pe_cost":""}, 
        {"pe_id":"", "pe_name":"BROCHURSE", "pe_cost":""}
        ];
        $expense_html ="" ;
        for (var k in expense_arr) {
            $expense_html +='<div class=" remove_'+gen_id+' col-xs-12 col-sm-4 col-md-4"><div class="row form-group"><input type="hidden" id="expense_id" class="expense_id" value="" ><input type="hidden" id="expense_name" class="expense_name" value="'+expense_arr[k].pe_name+'" ><label class="col-xs-6 col-sm-6 col-md-6">'+expense_arr[k].pe_name+'</label><div class="col-xs-3 col-sm-3 col-md-4 no-padding"><div class="input-group"><input id="expense_cost" class="expense_cost form-control form-control-sm" value="'+expense_arr[k].pe_cost+'" placeholder="Cost" type="number"> <span class="input-group-addon">$</span></div></div><div class="col-xs-2 col-sm-2 col-md-2"><div id="remove_'+gen_id+'" class="remove_item btn btn-sm btn-default bg-gray"><i class="fa fa-minus" title="Remove item" role="button" tabindex="0"></i></div></div></div></div>';
            gen_id++;
        }
        document.getElementById("expenses_html").innerHTML = $expense_html;
    }else{
        // EDIT EXPENSES
        $expense_html ="" ;
        var expense_request = {!! json_encode($expense_list) !!} ;
        $('#expense_date').val('{{$expense_date}}');
        for(var i in expense_request )
        {
            $expense_html +='<div class="remove_'+gen_id+' col-xs-12 col-sm-4 col-md-4"><div class="row form-group"><input type="hidden" id="expense_id" class="expense_id" value="'+expense_request[i].pe_id+'" ><input type="hidden" id="expense_name" class="expense_name" value="'+expense_request[i].pe_name+'" ><label class="col-xs-6 col-sm-6 col-md-6">'+expense_request[i].pe_name+'</label><div class="col-xs-3 col-sm-3 col-md-4 no-padding"><div class="input-group"><input id="expenseremove_'+expense_request[i].pe_id+'" class="expense_cost form-control form-control-sm" value="'+expense_request[i].pe_cost+'" placeholder="Cost" type="number"> <span class="input-group-addon">$</span></div></div></div></div></div>';
            gen_id++;
        }
         document.getElementById("expenses_html").innerHTML = $expense_html;
    }
   
   // REMOVE ITEM IN ADD NEW - BEGIN
   $(document).on('click', '.remove_item', function() {
     if(window.confirm("Are you sure you want to delete this item ?")){
            $("div."+this.id).remove();
        }
        else{
            return false;
        }
   });
    // REMOVE ITEM - END

   //ADD NEW EXPENSE - BEGIN
    $( "#expense_add" ).click(function() {
        if( $('#add_expense_name').val()=="" || $('#add_expense_cost').val()==""  )
        {
            toastr.error("Please enter name and cost.");
        }else
        {
            $add_expense_html = '<div class="remove_'+gen_id+' col-xs-12 col-sm-4 col-md-4"><div class="row form-group"><input type="hidden" id="expense_id" class="expense_id" value="" ><input type="hidden" id="expense_name" class="expense_name" value="'+$('#add_expense_name').val()+'" ><label class="col-xs-6 col-sm-6 col-md-6">'+$('#add_expense_name').val()+'</label><div class="col-xs-3 col-sm-3 col-md-4 no-padding"><div class="input-group"><input id="expense_cost" class="expense_cost form-control form-control-sm" value="'+$('#add_expense_cost').val()+'" placeholder="Cost" type="number"> <span class="input-group-addon">$</span></div></div><div class="col-xs-2 col-sm-2 col-md-2"><div id="remove_'+gen_id+'" class="remove_item btn btn-sm btn-default bg-gray"><i class="fa fa-minus" title="Remove item" role="button" tabindex="0"></i></div></div></div></div>' ;

            $("#expenses_html").append($add_expense_html);
            $('#add_expense_name').val("");
            $('#add_expense_cost').val("");
            gen_id++;
        }
        
    });
    //ADD NEW EXPENSE - END

    $( ".cancel_expense" ).click(function() {
        window.location.href = "{{route('expenses')}}";
    });

    $( ".submit_expense" ).click(function() {
        var expense_date = $("#expense_date").val();
        
        var parts = expense_date.split('/');
        var dmyDate = parts[2] + '-' + parts[0] + '-' + parts[1];

        //GET LIST EXPENSE ID
        var pe_idarray = $("#expenses_html")
             .find(".expense_id") 
             .map(function() { return this.value; }) 
             .get(); 
        //GET LIST EXPENSE COST
        var costarray = $("#expenses_html")
             .find(".expense_cost") 
             .map(function() { return this.value; }) 
             .get(); 
        //GET LIST EXPENSE NAME
        var namearray = $("#expenses_html")
             .find(".expense_name") 
             .map(function() { return this.value ; }) 
             .get();
        expense_arr=[];
        var pe_place_id = {{Session::get('current_place_id') }} ;
        for (var i = 0; i < costarray.length; ++i) {
            if(costarray[i]>0)
            {
                expense_arr.push({"pe_id":pe_idarray[i],"pe_name":namearray[i],"pe_place_id":pe_place_id ,"pe_cost":costarray[i] ,"pe_date":dmyDate}) ;
            }              
        }
        if(expense_arr.length ==0)
        {
            toastr.error('Please enter Cost value');
            return false;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
        $.ajax({  
            url:"{{route('save-expenses')}}",   
            method:"POST",  
            data:{expense_arr : expense_arr },                              
               success: function( data ) { 
                    window.location.href = "{{route('expenses')}}";
                   
            }
       });
    });
    
    
 
}); 

</script>    

@stop

