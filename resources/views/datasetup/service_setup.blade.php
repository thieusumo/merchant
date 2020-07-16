@extends('layouts.master')
@section('title', 'Management | Services/Products')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">           
<style>
    .servicesetup .catalog ul.nav li a {padding: 0px 15px 0px !important;}
    
</style>    
@stop
@section('content')
@foreach ($errors->all() as $error)
    <span style="color: red">{{ $error }}<br/></span>
@endforeach
<div class="row" class="servicesetup">
    <div class="col-md-6 no-padding">
        <div class="x_panel catalog" style="padding-top: 0px;">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li><a href="#service" data-toggle="tab">Services </a></li>
                <li><a href="#product" data-toggle="tab">Products</a></li>
                <li><a href="#combo" data-toggle="tab">Combos</a></li>
                <li><a href="#drink" data-toggle="tab">Drinks</a></li>
            </ul>
            <div class="catalog-add" style="width:auto;">
                <a href="#" class="btn btn-sm btn-default" id="add_category"><i class="fa fa-plus-circle"></i> Category</a>
                <a href="#" class="btn btn-sm btn-default" id="add_service"><i class="fa fa-plus-circle"></i>  Service</a>
            </div>
        </div>
        <div class="tab-content">
            <div id="service" class="tab-pane fade active show">
              <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                @foreach($cateservice_list as $cateservice)
                <div class="panel catalog-panel">
                  <div class="panel-heading collapsed" id="cate{{$cateservice->cateservice_id}}" role="tab" id="headingGroup1" data-toggle="collapse" data-parent="#accordion" href="#{{str_replace(' ','_',$cateservice->cateservice_name)}}" aria-expanded="true" aria-controls="{{str_replace(' ','_',$cateservice->cateservice_name)}}">
                    <h4 class="panel-title">{{$cateservice->cateservice_name}}</h4>
                    <ul class="list-inline right services-act-groups">
                        <li><a href="#" name="{{$cateservice->cateservice_name}}" index="{{$cateservice->cateservice_index}}" id="{{$cateservice->cateservice_id}}"
                          class="cateservice_edit"><i class="glyphicon glyphicon-edit"></i></a></li>
                        <li><a href="javascript:void(0)" child_id="{{$cateservice->cateservice_id}}" id="cateservice{{$cateservice->cateservice_id}}"  onclick="_delete('cateservice{{$cateservice->cateservice_id}}','cate{{$cateservice->cateservice_id}}')" url_delete="{{route('delete-cate-service')}}" ><i class="glyphicon glyphicon-trash"></i></a></li>
                    </ul>  
                  </div>
                  <div id="{{str_replace(' ','_',$cateservice->cateservice_name)}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="{{str_replace(' ','_',$cateservice->cateservice_name)}}">
                    <div class="panel-body">
                      @php
                      $collection = collect($service_list);
                      $children = $collection->where('service_cate_id',$cateservice->cateservice_id);
                      @endphp
                      @foreach($children as $child)
                        <div class="catalog-item" id="parent{{$child->service_id}}">
                           <span class="column1">{{$child->service_name}}</span>                                      
                           <span class="column2">${{$child->service_price}}</span>
                            <span class="column3">
                                <a href="#" onclick="editService('{{$cateservice->cateservice_id}}','{{$cateservice->cateservice_name}}','{{$child->service_id}}','{{$child->service_name}}','{{$child->service_price}}','{{$child->service_duration}}','{{$child->service_price_hold}}','{{$child->service_tax}}')"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="#" child_id="{{$child->service_id}}" id="service{{$child->service_id}}"  onclick="_delete('service{{$child->service_id}}','parent{{$child->service_id}}')" url_delete="{{route('delete-service-mana')}}"><i class="glyphicon glyphicon-trash"></i></a>
                            </span>                 
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                @endforeach
              </div> 
            </div>
            <div id="product" class="tab-pane fade">
              <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel catalog-panel">
                  <div id="collapseGroup1" role="tabpanel" aria-labelledby="headingGroup1">
                    <div class="panel-body">
                @foreach($product_list as $product)    
                          <div class="catalog-item" id="drink{{$product->sn_id}}">
                           <span class="column1">{{$product->sn_name}}</span>                                      
                           <span class="column2">${{$product->sn_price}}</span>
                            <span class="column3">
                                <a href="#" id="{{$product->sn_id}}"onclick="add_product('{{$product->supply_name}}','{{$product->supply_id}}','{{$product->sn_name}}','{{$product->sn_image}}','{{$product->sn_id}}','{{$product->sn_price}}','{{$product->sn_unit}}','{{$product->sn_quantity}}','{{$product->sn_capacity}}','{{$product->sn_discount}}','{{$product->sn_point}}','{{$product->sn_sale_tax}}','{{$product->sn_bonus}}','{{format_date($product->sn_datetime)}}','{{format_date($product->sn_dateexpired)}}')"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="#" child_id="{{$product->sn_id}}" id="pro{{$product->sn_id}}" url_delete="{{route('delete-product')}}"  onclick="_delete('pro{{$product->sn_id}}','drink{{$product->sn_id}}')" ><i class="glyphicon glyphicon-trash"></i></a>
                            </span>                 
                        </div>
                @endforeach
                    </div>
                  </div>
                </div>
              </div> 
            </div>
            <div id="combo" class="tab-pane fade">
              <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                @foreach($combo_list as $combo)
                <div class="panel catalog-panel">
                  <div class="panel-heading collapsed" role="tab" id="combo{{$combo->package_id}}" data-toggle="collapse" data-parent="#accordion" href="#{{str_replace(' ','_',$combo->package_name)}}" aria-expanded="true" aria-controls="collapseGroup1">
                    <h4 class="panel-title">{{$combo->package_name}}</h4>
                    <ul class="list-inline right services-act-groups">
                        <li><a href="#"  id="{{$combo->package_id}}" onclick="getAddCombo(this,'{{$combo->package_name}}','{{$combo->package_id}}')" ><i class="glyphicon glyphicon-edit"></i></a></li>
                        <li><a href="#" id="com{{$combo->package_id}}" url_delete="{{route('delete-combo')}}" child_id="{{$combo->package_id}}" onclick="_delete('com{{$combo->package_id}}','combo{{$combo->package_id}}')"><i class="glyphicon glyphicon-trash"></i></a></li>
                    </ul>  
                  </div>
                  <div id="{{str_replace(' ','_',$combo->package_name)}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingGroup1">
                    <div class="panel-body">
                      @php
                      $combo_array = explode(';',$combo->package_listservice_id);
                      foreach($combo_array as  $key => $value)
                      {
                      $combo_detail_collection = collect($combo_detail_list);
                      $combo_detail = $combo_detail_collection->where('packagedetail_id',$value);
                      @endphp
                      @foreach($combo_detail as $detail)
                          <div class="catalog-item">
                           <span class="column1">{{$detail->packagedetail_name}}</span>                                      
                           <span class="column2">${{$detail->packagedetail_price}}</span>
                            <span class="column3">
                                <a href="#" id="{{$combo->package_id}}" onclick="getAddCombo(this,'{{$combo->package_name}}','{{$combo->package_id}}')" ><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="#" onclick="comboItemDelete(this,event,'{{$combo->package_id}}','{{$detail->packagedetail_id}}')" class="trash"><i class="glyphicon glyphicon-trash"></i></a>
                            </span>                 
                          </div>
                      @endforeach
                      @php
                    }
                      @endphp
                    </div>
                  </div>
                </div>
                @endforeach
              </div> 
            </div>
            <div id="drink" class="tab-pane fade">
              <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel catalog-panel">
                  <div id="collapseGroup1" role="tabpanel" aria-labelledby="headingGroup1">
                    <div class="panel-body">
                @foreach($drink_list as $drink)    
                          <div class="catalog-item" id="drink{{$drink->beverage_id}}">
                           <span class="column1">{{$drink->beverage_name}}</span>                                      
                           <span class="column2">${{$drink->beverage_price}}</span>
                            <span class="column3">
                                <a href="#" id="{{$drink->beverage_id}}" onclick="getEditDrink('{{$drink->beverage_id}}','{{$drink->beverage_name}}','{{$drink->beverage_price}}','{{$drink->beverage_description}}')"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="#" id="d{{$drink->beverage_id}}" child_id="{{$drink->beverage_id}}" url_delete="{{route('delete-drink')}}" onclick="_delete('d{{$drink->beverage_id}}','drink{{$drink->beverage_id}}')" ><i class="glyphicon glyphicon-trash"></i></a>
                            </span>                 
                        </div>
                @endforeach
                    </div>
                  </div>
                </div>
              </div> 
            </div>
        </div>
      </div>    
    </div>
    <div class="col-md-6 no-padding">
        <div class="x_panel catalog">
            @include('datasetup.partials.cat_service_form')
            @include('datasetup.partials.product_form')
            @include('datasetup.partials.combo_form')
            @include('datasetup.partials.drink_form')
            @include('datasetup.partials.service_form')
        </div>
    </div>
</div>            
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {    
     if ($("input.checkFlat")[0]) {
        $('input.checkFlat').iCheck({
            checkboxClass: 'icheckbox_flat-green'
        });       
        $('input.checkFlat').on('ifChecked', function (ev) { 
            $(ev.target).click().attr('checked', true); 
            $($(ev.target).attr('data-target')).toggle(true);
        });
        $('input.checkFlat').on('ifUnchecked', function (ev) {
            $(ev.target).removeAttr('checked'); 
            $($(ev.target).attr('data-target')).hide();
        });  
    }
    if ($("input.datepicker")[0]) {
        $('input.datepicker').daterangepicker({
            singleDatePicker: true,
            singleClasses: "picker_3"
        });
    }
    $('.date .input-group-addon').click(function(event){
        event.preventDefault();
        $(this).parent().children('input.datepicker').click();
    });
    $("#tableComboDetail a.add-item").click(function(event){
        event.preventDefault();
        $("#tableComboDetail > tbody").append($('<tr>')
                  .append($('<td>').append("<input type='hidden' class='form-control'  name='packagedetail_id[]' value='0' /><input type='text' class='form-control' required name='packagedetail_name[]' />"))
                  .append($('<td>').append("<input type='number' required class='form-control text-right iprice ' name='packagedetail_price[]' onchange='calculateComboTotal(this,\"iprice\");'/>"))
                  .append($('<td>').append("<input type='number' required class='form-control iduration text-right' name='packagedetail_duration[]'  required onchange='calculateComboTotal(this,\"iduration\");'/>"))
                  .append($('<td>').append("<input type='number' required  class='form-control  text-center' name='packagedetail_hold[]' />"))
                  .append($('<td>').append('<a href="#" class="btn" onclick="comboItemDelete(this,event);"><i class="glyphicon glyphicon-trash"></i></a>'))
                );
    });
}); 
function calculateComboTotal(el, tag){
    var total = 0;
    $( "#tableComboDetail input." + tag ).each(function() {        
        var number = parseInt($( this ).val());
        if(!isNaN(number)) total += number;
    });
    if(typeof(total) == "undefined" || isNaN(total)) total = 0;
    $( "#tableComboDetail input.total" + tag).val(total.toString());
}
function comboItemDelete(element, event,combo_id = null,id = null){
    event.preventDefault();
    if($(element).attr('class') == 'trash')
    {
      $(element).closest('div').remove();
    }
    else{
      $(element).closest("tr").remove();
      calculateComboTotal(null,'iprice');
      calculateComboTotal(null,'iduration');
    }
    if(combo_id != null && id != null)
    {
      $.ajax({
      url: '{{route('delete-combo-item')}}',
      type: 'GET',
      dataType: 'html',
      data: {id: id, combo_id:combo_id},
      })
      .done(function(response) {
        toastr.success(response);
        //console.log(response);
      })
      .fail(function() {
        toastr.error('Delete Item Combo Error!');
        //console.log("error");
      });
    }
    
}
function _delete(id,cate)
{
  if(window.confirm("Do you want to delete this and inside?")){

    var url_delete = $('#'+id).attr('url_delete');

    var id = $('#'+id).attr('child_id');

    $.ajax({
      url: url_delete,
      type: 'get',
      dataType: 'html',
      data: {id:id},
    })
    .done(function(response) {
      $('#'+cate).hide();
      toastr.success(response);
    })
    .fail(function(xhr, ajaxOptions, thrownError) {
      //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      toastr.error('Delete Error!');
      //console.log("error");
    });
  }
  else {
    return false;
  }
}
//EDIT PRODUCT
function add_product(supply_name,supply_id,sn_name,sn_image,sn_id,sn_price,sn_unit,sn_quantity,sn_capacity,sn_discount,sn_point,sn_sale_tax,sn_bonus,sn_datetime,sn_dateexpired)
{
    $('#add_edit_product').slideDown();
    $('#add_edit_service').hide();
    $('#add_edit_combo').hide();
    $('#add_edit_drink').hide();
    $('#add_edit_cateservice').hide();

    $('#product_title').text('Edit Product')

    $('#supply_id').val(supply_id);
    $('#supply_name').val(supply_name);
    $('#sn_name').val(sn_name);
    $('#sn_id').val(sn_id);
    $('#sn_image').attr('src','{{config('app.url_file_view')}}'+sn_image);
    $('#sn_image_hidden').val(sn_image);
    $('#sn_price').val(sn_price);
    $('#sn_unit').val(sn_unit);
    $('#sn_quantity').val(sn_quantity);
    $('#sn_capacity').val(sn_capacity);
    $('#sn_discount').val(sn_discount);
    $('#sn_point').val(sn_point);
    $('#sn_sale_tax').val(sn_sale_tax);
    $('#sn_bonus').val(sn_bonus);
    $('#sn_datetime').val(sn_datetime);
    $('#sn_dateexpired').val(sn_dateexpired);
}
$(document).ready(function() {
  $('#add_category').on('click',function(){
    $('#add_edit_cateservice').slideDown();
    $('#add_edit_service').hide();
    $('#add_edit_combo').hide();
    $('#add_edit_drink').hide();
    $('#add_edit_product').hide();
  });
  $('#add_service').on('click',function(){
    $('#add_edit_service').slideDown();
    $('#add_edit_cateservice').hide();
    $('#add_edit_combo').hide();
    $('#add_edit_drink').hide();
    $('#add_edit_product').hide();
  });
});
//COMBO
function getAddCombo(that,combo_name,combo_id)
{
  $('#combo_reset').text('ADD NEW');
  $('#add_edit_combo').slideDown(500);
    $('#add_edit_service').hide();
  $('#add_edit_product').hide();
  $('#add_edit_drink').hide();
  $('#add_edit_cateservice').hide();
  //empty data
  $("#tableComboDetail > tbody> tr >td").remove();
  $('#title_combo').text('Edit Combo');

  $('#package_name').val(combo_name);
  $('#package_id').val(combo_id);
  var id = $(that).attr('id');

  $.ajax({
    url: '{{route('get-combo-detail')}}',
    type: 'GET',
    dataType: 'html',
    data: {id: id},
  })
  .done(function(response) {
      jQuery.each( JSON.parse(response), function( i, val ) {
          $("#tableComboDetail > tbody").append($('<tr>')
                  .append($('<td>').append("<input type='hidden' class='form-control'  name='packagedetail_id[]' value='"+val[4]+"' /><input type='text' class='form-control' required name='packagedetail_name[]' value='"+val[0]+"' />"))
                  .append($('<td>').append("<input type='number' required class='form-control text-right iprice ' name='packagedetail_price[]' value='"+val[1]+"' onchange='calculateComboTotal(this,\"iprice\");'/>"))
                  .append($('<td>').append("<input type='number' required class='form-control iduration text-right' name='packagedetail_duration[]' value='"+val[2]+"' required onchange='calculateComboTotal(this,\"iduration\");'/>"))
                  .append($('<td>').append("<input type='number' required value='"+val[3]+"' class='form-control  text-center' name='packagedetail_hold[]' />"))
                  .append($('<td>').append('<a href="#" class="btn combo_item_delete" id="'+val[4]+'" combo_id="'+id+'" onclick="comboItemDelete(this,event,'+id+','+val[4]+');"><i class="glyphicon glyphicon-trash"></i></a>'))
                );
      });
  })
  .fail(function() {
    console.log("error");
  });
  
}
//reset combo
$(document).ready(function() {
  $('#combo_reset').on('click',function(){
    $('#package_id').val(0);
    $("#tableComboDetail > tbody> tr >td").remove();
    $("#tableComboDetail > tbody").append($('<tr>')
                  .append($('<td>').append("<input type='hidden' class='form-control' value='0' name='packagedetail_id[]' /><input type='text' class='form-control' required name='packagedetail_name[]' />"))
                  .append($('<td>').append("<input type='number' required class='form-control text-right iprice ' name='packagedetail_price[]' onchange='calculateComboTotal(this,\"iprice\");'/>"))
                  .append($('<td>').append("<input type='number' required class='form-control iduration text-right' name='packagedetail_duration[]'  required onchange='calculateComboTotal(this,\"iduration\");'/>"))
                  .append($('<td>').append("<input type='number' required  class='form-control  text-center' name='packagedetail_hold[]' />"))
                  .append($('<td>').append('<a href="#" class="btn" onclick="comboItemDelete(this,event);"><i class="glyphicon glyphicon-trash"></i></a>'))
                );
    $('#title_combo').text('Add Combo');
    $(this).text('RESET');
  });
  $('#combo_submit').on('click',function()
  {
    // validate form
    var validatorResult = $("#combo_form")[0].checkValidity();
    $("#combo_form").addClass('was-validated');
    if(!validatorResult){
        event.preventDefault();
        event.stopPropagation();           
        return;
    }
    //form = document.createElement('#customer_form');
    $('#combo_form').submit();
});
  
});
//get edit drink form
function getEditDrink(id,name,price,description)
{
  $('#add_edit_title').text('Edit Drink');
  $('#drink_reset').text('ADD NEW');
  $('#add_edit_drink').slideDown(500);
    $('#add_edit_service').hide();
  $('#add_edit_product').hide();
  $('#add_edit_combo').hide();
  $('#add_edit_cateservice').hide();

  $('#beverage_id').val(id);
  $('#beverage_name').val(name);
  $('#beverage_price').val(price);
  $('#beverage_description').val(description);
}
// validate edit drink form before submit
$('#drink_submit').on('click',function()
  {
    // validate form
    var validatorResult = $("#drink_form")[0].checkValidity();
    $("#drink_form").addClass('was-validated');
    if(!validatorResult){
        event.preventDefault();
        event.stopPropagation();           
        return;
    }
    $('#drink_form').submit();
});
//resetdrink form
$(document).ready(function() {
  $('#drink_reset').on('click',function(){
    $('#add_edit_title').text('Add Drink');
    $('#beverage_id').val('0');
    $(this).text('RESET');
  })
});


//SERVICE 
//edit button
function editService(cate_id, cate_name, service_id, service_name, service_price, service_duration, service_price_hold, service_tax)
{
  $('#service_title').text('Edit Service');
  $('#service_reset').text('ADD NEW');
  $('#add_edit_service').slideDown();
    $('#add_edit_product').hide();
    $('#add_edit_combo').hide();
    $('#add_edit_drink').hide();
    $('#add_edit_cateservice').hide();

    $("#cateservice_id option").filter(function() {
        return this.text == cate_name; 
    }).attr('selected', true);

    $('#service_name').val(service_name);
    $('#service_id').val(service_id);
    $('#service_price').val(service_price);
    $('#service_duration').val(service_duration);
    $('#service_price_hold').val(service_price_hold);
    $('#service_tax').val(service_tax);
}
$(document).ready(function() {
  $('#service_submit').on('click',function(){
    var validatorResult = $("#service_form")[0].checkValidity();
    $("#service_form").addClass('was-validated');
    if(!validatorResult){
        event.preventDefault();
        event.stopPropagation();           
        return;
    }
    //form = document.createElement('#customer_form');
    $('#service_form').submit();
  });
  $('#service_reset').on('click',function(){
    $(this).text('RESET');
    $('#service_title').text('Add New Service');
    $('#service_id').val('0');
  });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $($(input).attr("data-target")).attr('src', e.target.result);
            $($(input).attr("data-target")).hide();
            $($(input).attr("data-target")).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }    
}
$("input[type=file]").change(function() {
    console.log(this);
    readURL(this);
});
$(document).ready(function() {
  $('.cateservice_edit').on('click',function(){

    $('#add_edit_cateservice').slideDown();
    $('#add_edit_service').hide();
    $('#add_edit_combo').hide();
    $('#add_edit_drink').hide();
    $('#add_edit_product').hide();

    var name = $(this).attr('name');
    var id = $(this).attr('id');
    var index = $(this).attr('index');

    $('#cate_service_name').val(name);
    $('#cate_service_index').val(index);
    $('#cateservice_id').val(id);

    $('#cateservice_title').text('Edit Category Service');

  });
  $('#cateservice_reset').on('click',function(){
    $('#cateservice_title').text('Add Category Service');
    $('#cateservice_id').val('0');
    $(this).text('RESET');
  });
  $('#cateservice_submit').on('click',function(){
    var validatorResult = $("#cateservice_form")[0].checkValidity();
    $("#cateservice_form").addClass('was-validated');
    if(!validatorResult){
        event.preventDefault();
        event.stopPropagation();           
        return;
    }
    //form = document.createElement('#customer_form');
    $('#cateservice_form').submit();
  });
  $('#product_reset').click(function(){
    $('#product_title').text('Add Product');
  });
});

</script>         
@stop

