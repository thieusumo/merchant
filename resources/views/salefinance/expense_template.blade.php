@extends('layouts.master')
@section('title', 'Expense')
@section('styles')
    
@stop
@section('content')
 <div class="page-title">
        <div class="title_left">
            <h3>@if(isset($id)) Edit Expense @else Add Expense Template @endif</h3>
        </div>
    </div>
<div class="x_panel">   
    <div class="col-xs-12 col-sm-12 col-md-12 full-height scroll-menu">
    <form class="form-horizontal form-addon-ext label-date" name="frm-expense" custom-submit="" novalidate="novalidate">
        <div class="clear">&nbsp;</div>
        <div id="expenses_html" class="row form-group"> 
            @foreach($expTemplate as $key=>$value) 
            <div class=" remove_{{$value->ex_template_id}} col-xs-12 col-sm-4 col-md-4">
                <div class="row form-group">
                    <!-- <input type="hidden" id="{{$value->id_ex_template}}" class="expense_id" value="">
                    <input type="hidden" id="expense_name" class="expense_name" value="SUPPLY OFFICE"> -->
                    <label class="col-xs-6 col-sm-6 col-md-6">{!!$value->ex_template_name!!}</label>
                    <div class="col-xs-3 col-sm-3 col-md-4 no-padding">
                        <div class="input-group">
                            <input id="expense_cost" class="expense_cost form-control form-control-sm" value="{{$value->ex_template_cost}}" placeholder="Cost" type="number">
                            <span class="input-group-addon">$</span>
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div id="{{$value->ex_template_id}}" class="remove_item btn btn-sm btn-default bg-gray">
                            <i class="fa fa-minus" title="Remove item" role="button" tabindex="0"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach   
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
                <a href="{{route('expenses')}}"><div id="cancel_expense" class="cancel_expense btn btn-sm btn-default">CANCEL</div></a>
            </div>
        </div>
    </form>
</div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $(document).on('click','.remove_item', function(){
        var id_ex_template=$(this).attr('id');
        // alert(id_ex_template);
        if(window.confirm("Are you sure you want to delete this item ?")){
            $.ajax({
              url:'{{route("delete-expense-template")}}',
              type:"GET",
              cache:false,
              data:{
                  "id_ex_template":id_ex_template,
              },
              success:function(data){
                if(data!=null)
                {
                    $('.remove_'+id_ex_template).remove();
                }
              }
            });
        }
        else{
            return false;
        }
    });

    //add item
    $(document).on('click','#expense_add', function(){
        var name_ex_template=$('#add_expense_name').val();
        var cost_ex_template=$('#add_expense_cost').val();
        if(name_ex_template=="")
        {
            toastr.error('Please enter name!');
        }
        else{
            $.ajax({
              url:'{{route("add-expense-template")}}',
              type:"GET",
              cache:false,
              data:{
                  "name_ex_template":name_ex_template,
                  "cost_ex_template":cost_ex_template,
              },
              success:function(data){
                if(data!=null)
                {
                    var exp_json=JSON.parse(data);
                    var html="";
                    for (var key in exp_json) {
                      html='<div class=" remove_'+exp_json['ex_template_id']+' col-xs-12 col-sm-4 col-md-4"><div class="row form-group"><label class="col-xs-6 col-sm-6 col-md-6">'+exp_json['ex_template_name']+'</label><div class="col-xs-3 col-sm-3 col-md-4 no-padding"><div class="input-group"><input id="expense_cost" class="expense_cost form-control form-control-sm" value="'+exp_json['ex_template_cost']+'" placeholder="Cost" type="number"><span class="input-group-addon">$</span></div></div><div class="col-xs-2 col-sm-2 col-md-2"><div id="'+exp_json['ex_template_id']+'" class="remove_item btn btn-sm btn-default bg-gray"><i class="fa fa-minus" title="Remove item" role="button" tabindex="0"></i></div></div></div></div>';
                    }
                    $('#expenses_html').append(html);
                    $('#add_expense_name').val('');
                    $('#add_expense_cost').val('');
                }
              }
            });
        }
    });
}); 

</script>    

@stop

