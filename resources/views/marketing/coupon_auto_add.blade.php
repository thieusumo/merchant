@extends('layouts.master') 
@section('title', 'Marketing | Coupon | Setup Coupon')
@section('styles')
<style>    
    .top_nav{height: 84px;} 
    .custom-range{ margin: 10px 10px; width: 180px;}
</style>
@stop
@section('content')
<div id="setupCoupon" class="col-xs-12 col-md-12 fixLHeight no-padding full-height bg-white canvas-design">
    <div class="col-xs-2 col-md-2 no-padding full-height">
         @include('marketing.partials.template_list')         
    </div>    
    <div class="col-xs-6 col-md-6 no-padding full-height border-right border-left border-grey-light">        
        <div class="text-center">
            {{-- <canvas id="canvasReview" width="553px" height="344px" /> --}}
            <img id="showImage" src="" alt="" width="553px" height="344px">
        </div>        
        {{-- @include('marketing.partials.canvas_text_editor')          --}}
    </div>    
    <div class="col-xs-4 col-md-4 no-padding full-height">
        <form action="{{ route("saveCoupon")}}" id="couponForm" name="couponForm" onsubmit="return false;">
        <div class="canvas-info">            
            <h6 class="title border-bottom text">Coupon Information</h6>
            <div class="clearfix" style="height: 10px;"></div>
            <div class="row form-group">
                <label class="col-sm-4 col-md-3">Coupon Code</label>
                <div class="col-sm-6 col-md-5 no-padding">
                   <input type='text' id="coupon_code" name="coupon_code" value="{{ $couponCode }}" class="form-control form-control-sm disabled" readonly="readonly" />
                </div>            
           </div>    
           <div class="row form-group" style="display: none;">
                <label for="coupon_name" class="col-sm-4 col-md-3">Name</label>
                <div class="col-sm-9 col-md-8 no-padding">
                   <input type='text' id="coupon_name" name="coupon_name" value="." class="form-control form-control-sm" required="required"/>
                </div>            
           </div>     
            <div class="row form-group">
                <label for="coupon_title" class="col-sm-4 col-md-3">Title</label>
                <div class="col-sm-9 col-md-8 no-padding">
                   <input readonly="" type='text' id="coupon_title" name="coupon_title" value="" class="form-control form-control-sm"  required="required"/>
                </div>            
           </div> 
           <div class="row form-group" style="display: none">
                <label class="col-sm-4 col-md-3">Date Start</label>
                <div class="col-sm-6 col-md-5 no-padding input-group-spaddon">
                   <div class='input-group date'>                    
                        <input type='text' id="coupon_date_start" name="coupon_date_start" value="{{ date('m-d-Y') }}" class="form-control form-control-sm datepicker"  required="required"/>
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>            
           </div> 
           <div class="row form-group" >
                <label class="col-sm-4 col-md-3">Date End</label>
                <div class="col-sm-6 col-md-5 no-padding input-group-spaddon">
                     <div class='input-group date'>                    
                        <input type='text' id="coupon_date_end" name="coupon_date_end" value="{{ date('m-d-Y', strtotime('+1 month')) }}" class="form-control form-control-sm datepicker"  required="required"/>
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>            
           </div> 
           <div class="row">
                <label class="col-sm-4 col-md-3">Discount <span id="coupon_discount_type"></span></label>
                <div class="col-sm-6 col-md-5 no-padding input-group">
                    <input readonly="" type='number' id="coupon_discount" name="coupon_discount" value="" class="form-control form-control-sm"  required="required" min="1" data-bind="value:coupon_discount"/>                   
                    
                </div>            
           </div> 
            <div class="row form-group" style="display: none">
                <label class="col-sm-4 col-md-3">Quantity</label>
                <div class="col-sm-6 col-md-5 no-padding">
                   <input type='number' id="coupon_quantity" name="coupon_quantity" value="1" class="form-control form-control-sm" type="number" min="1" max="100" data-bind="value:coupon_quantity"/>
                </div>            
           </div> 
  
            <div class="row form-group">                
                     <div class="dropdown service-options col-md-12">
                      <div>Services</div>
                      <hr>
                      <div id="list_service"></div>
                       
                     </div>  
                   
           </div> 
            <div class="ln_solid"></div>
           <div class="row">
                 <label class="col-sm-4 col-md-3">&nbsp;</label>
                <div class="col-sm-8 col-md-8 no-padding input-group">
                  <input type="hidden" id="id_coupon_template">
                   <input type="button" class="btn btn-sm btn-primary left" value="SAVE COUPON" name="btnSaveCoupon" id="btnSaveCoupon" />

                </div>            
           </div> 
        </div>
        </form>
    </div>
</div>  
<!-- Modal Confrm After Submit-->
<div class="modal fade" id="saveConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
          <h5 class="text-success">Your coupon saved successfully</h5>
      </div>
      <div class="modal-footer text-center" style="display:block;">
        <a href="{{ route('autoAddCoupon') }}" id="linkAddMore" class="btn btn-sm btn-primary">ADD MORE</a>
        <a href="{{route('send-sms-coupon')}}" id="linkSendSMS" class="btn btn-sm btn-primary">SEND SMS</a>
        <a href="{{ route('coupons') }}" id="linkAddDone" class="btn btn-sm btn-success">DONE</a>
      </div>
    </div>
  </div>
</div>

@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/custom/fabric.min.js') }}"></script>   

<script type="text/javascript">

  function getCouponAutoTemplates(){
    var id = $('#templateType').val();
    $.ajax({
      url:"{{ route('getCouponAutoTemplates') }}",
      data:{id:id},
      method:"get",
      dataType:"json",
      success:function(data){
        // console.log(data.data);
        var $publicImagePath = "{{ config('app.url_file_view') }}/"; 
        if(data.success == true){
          var html = '';
          for(var i = 0; i < data.data.length; i++){
          html += "<li><a href=''><img data='"+data.data[i].template_id+"' template-title='"+data.data[i].template_title+"' discount='"+data.data[i].template_discount+"' list-service='"+data.data[i].template_list_service+"' type='"+data.data[i].template_type+"' src='"+ $publicImagePath + data.data[i].template_linkimage +"' /></a></li>";
          }
          $("#listImageTemplate").html(html);
        }
      }
    });
  }

  function clear(){
    $("#coupon_title").val('');
    $("#coupon_discount").val('');
    $("#list_service").html('');
  }

  function clickFirst(){
    setTimeout(function(){ $("#listImageTemplate img:first").trigger("click"); }, 1000);
  }
  
$(document).ready(function(){ 
   getCouponAutoTemplates();
   $("#templateType").on('change',function(){
    getCouponAutoTemplates();
    clear();
    clickFirst();
   });

   if ($("input.datepicker")[0]) {
        $('input.datepicker').daterangepicker({            
            singleDatePicker: true, 
            isInvalidDate: true,
            minDate: moment(), // 0 days offset = today
        });
    }

    $("#btnSaveCoupon").on( "click", function(event){
        // validate form
        var validatorResult = $("#couponForm")[0].checkValidity();
        $("#couponForm").addClass('was-validated');
        if(!validatorResult){
            event.preventDefault();
            event.stopPropagation();           
            return;
        }
        var id_template = $("#id_coupon_template").val();
        var coupon_code = $("#coupon_code").val();
        var coupon_title = $("#coupon_title").val();
        var coupon_discount = $("#coupon_discount").val();
        var coupon_date_end = $("#coupon_date_end").val();
        var coupon_discount_type = $("#coupon_discount_type").attr('type');
        var list_service = $("#list_service").attr('list-service');
        var img = $("#showImage").attr('src');
        
        $.ajax({
            type: "POST",            
            url: $("#couponForm").attr("action"),
            data: {
              _token:"{{csrf_token()}}",
              id_template:id_template,
              coupon_code:coupon_code,
              coupon_title:coupon_title,
              coupon_date_end:coupon_date_end,
              coupon_discount:coupon_discount,
              coupon_discount_type:coupon_discount_type,
              list_service:list_service,
            },
            
        }).done(function(data) {
          // console.log(data);
            if(data.success == false){
                // alert(data.message);
                toastr.error("Add Coupon Error!");
                if(typeof(data.coupon_code) != 'undefined'){
                    $("#coupon_code").val(data.coupon_code);
                }
            }else{
              var linksms="{{route('send-sms-coupon')}}";
              // console.log(data);
              $("#linkSendSMS").attr("href",linksms+"/"+data.id);
                $('#saveConfirmModal').modal('show');

            }
        });      
        
    });
    
}); 
</script>
<script>
  $(document).ready(function(){
    $("#listImageTemplate").on('click','img',function(e){
        $("#listImageTemplate li a").removeClass('active');
        $(this).parent().addClass('active');

        e.preventDefault();
        var id = $(this).attr('data');
        var srcImg = $(this).attr('src');
        var listId = $(this).attr('list-service');
        var title = $(this).attr('template-title');
        var discount = $(this).attr('discount');
        var type = $(this).attr('type');

        var discountType = '';
        if(type == 1){
          discountType = "($)";
        } else if(type == 0){
          discountType = "(%)";
        }
        $("#id_coupon_template").val(id);
        $("#coupon_title").val(title);
        $("#coupon_discount").val(discount);      
        $("#coupon_discount_type").text(discountType);
        $("#coupon_discount_type").attr('type',type);
        $("#list_service").attr("list-service",listId);

        $("#showImage").attr('src',srcImg);
        $.ajax({
          url:"{{ route('getServicesByListId') }}",
          data:{listId:listId},
          method:"get",
          dataType:"json",
          success:function(data){
            if(data.success == true){
              var html = '';
              for(var i = 0; i < data.data.length; i++){
                html += "- "+data.data[i].service_name+"<br>";
              }
              $("#list_service").html(html);
            }            
          }
        });
      });

    
    
    clickFirst();
  });
</script>
@stop

