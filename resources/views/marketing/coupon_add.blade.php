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
        <div>
            <canvas id="canvasReview" width="553px" height="344px" />
        </div>        
        @include('marketing.partials.canvas_text_editor')         
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
                   <input type='text' id="coupon_title" name="coupon_title" value="Coupon Title" class="form-control form-control-sm"  required="required"/>
                </div>            
           </div> 
           <div class="row form-group">
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
           <div class="row form-group">
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
                <label class="col-sm-4 col-md-3">Discount</label>
                <div class="col-sm-6 col-md-5 no-padding input-group">
                    <input type='number' id="coupon_discount" name="coupon_discount" value="0" class="form-control form-control-sm"  required="required" min="1" data-bind="value:coupon_discount"/>                   
                    <div id="coupon_discount_type" class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-sm btn-info active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                            <input name="coupon_discount_type" value="%" data-parsley-multiple="coupon_discount_type" data-parsley-id="12" type="radio" checked="checked"> &nbsp; % &nbsp;
                        </label>
                        <label class="btn btn-sm btn-info" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                          <input name="coupon_discount_type" value="$" data-parsley-multiple="coupon_discount_type" type="radio"> &nbsp; $ &nbsp;
                        </label>
                      </div>
                </div>            
           </div> 
            <div class="row form-group">
                <label class="col-sm-4 col-md-3">Quantity</label>
                <div class="col-sm-6 col-md-5 no-padding">
                   <input type='number' id="coupon_quantity" name="coupon_quantity" value="1" class="form-control form-control-sm" type="number" min="1" max="100" data-bind="value:coupon_quantity"/>
                </div>            
           </div> 
         <!--    <div class="row form-group">
                <label class="col-sm-4 col-md-3">Coupon for</label>
                <div class="col-sm-8 col-md-8 no-padding">
                    <select id="coupon_for" name="coupon_list_service" class="form-control form-control-sm">
                        <option value=""> -- All Services -- </option>
                         @foreach ($listServices as $serviceId => $serviceName)
                            <option value="{{ $serviceId }}">{{ $serviceName }}</option>
                        @endforeach
                    </select>
                </div>            
           </div>  -->
           <!-- <div class="row form-group">
                <label class="col-sm-4 col-md-3">Coupon for</label>
                <div style="padding-left: 0px;" class="dropdown service-options col-sm-8 col-md-8">
                    <button type="button" class="btn btn-sm btn-block dropdown-toggle" id="toggleOptions" data-toggle="dropdown">
                          Service Options
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="toggleOptions" role="menu" style="padding-left: 10px;">
                            <h5 class="title border-bottom">List of services</h5>    
                              <label for="check_all" class="checkbox-inline " id="checkAll" style="z-index: 12121" >
                                  <input type="checkbox" id="check_all"  name="cateservice_check_all" value="">
                                  <span>Select All</span>
                              </label> 
                              @foreach ($listServices as $serviceId => $serviceName)   
                              <li class="col-sm-12" style="list-style-type: none;padding-left: 40px,">                            
                                  <label class="checkbox-inline">
                                      <input id="{{ $serviceId }}" type="checkbox" data="" class="" name="coupon_list_service[]" value="{{ $serviceId }}">
                                      <span>{{$serviceName}}</span>
                                   </label>
                              </li>   
                              @endforeach                                                                            
                        </ul>
                </div>            
           </div>  -->
            <div class="row form-group">                
                     <div class="dropdown service-options col-md-12">
                        <button type="button" class="btn btn-sm btn-block dropdown-toggle" id="toggleOptions" data-toggle="dropdown">
                          Service Options
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="toggleOptions" role="menu">
                            <h5 class="title border-bottom">List of services</h5>    
                              <label for="check_all" class="checkbox-inline " id="checkAll" style="z-index: 12121" >
                                      <input type="checkbox" id="check_all"  name="cateservice_check_all" value="">
                                      
                                      <span>Select All</span>
                                   </label>                       
                             @foreach ($ar  as  $services)
                             @foreach($services as $key => $service)
                             @php
                             $service_class = strtolower(preg_replace("![^a-z0-9]+!i", "",$key ));
                             @endphp
                             <ul>
                                <label class="checkbox-inline " id="{{$service_class}}" style="z-index: 12121" >
                                      <input id="{{$key}}" cate="{{$key}}" type="checkbox"  name="cateservice_check" value="">
                                      
                                      <span>{{$key}}</span>
                                   </label>
                               
                              @foreach($service as $idService => $nameService)
                              <li class="col-sm-12" style="list-style-type: none;padding-left: 40px,">                            
                                  <label class="checkbox-inline">
                                      <input id="{{$nameService}}{{$idService}}" type="checkbox" data="{{$nameService}}" class="{{$service_class}}" name="coupon_list_service[]" value="{{ $idService }}">
                                      <span>{{ $nameService }}</span>
                                   </label>
                              </li>
                              @endforeach
                          </ul>
                            @endforeach
                            @endforeach                                                                                
                        </ul>
                     </div>  
                   
           </div> 
            <div class="ln_solid"></div>
           <div class="row">
                 <label class="col-sm-4 col-md-3">&nbsp;</label>
                <div class="col-sm-8 col-md-8 no-padding input-group">
                   <input type="button" class="btn btn-sm btn-primary left" value="SAVE COUPON" name="btnSaveCoupon" id="btnSaveCoupon" />
                   <!--
                   <input type="button" class="btn btn-sm btn-primary left" value="RESET" name="btnReset" id="btnReset" />
                   -->
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
        <a href="{{ asset("marketing/coupon/add")}}" id="linkAddMore" class="btn btn-sm btn-primary">ADD MORE</a>
        <a href="{{route('send-sms-coupon')}}" id="linkSendSMS" class="btn btn-sm btn-primary">SEND SMS</a>
        <a href="{{ asset("marketing/coupons")}}" id="linkAddDone" class="btn btn-sm btn-success">DONE</a>
      </div>
    </div>
  </div>
</div>

@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/custom/fabric.min.js') }}"></script>   

<script type="text/javascript">
   $(document).on('click', '.checkbox-inline', function() {
    var cate_class = $(this).attr('id');
    var checkBoxes = $('.'+cate_class);
     // checkBoxes.prop("checked", !checkBoxes.prop("checked")).iCheck('update');
     // $("input[name=promotion_list_service]").prop("checked","");
     checkBoxes.prop("checked","checked");
      });

  function checkCate(service_class){
    
  }
function eventSelectTemplate($canvasReview, element, event){
    var imageUrl = $(element).children(":first").attr("src");    
   
    $canvasReview.setBackgroundImage(imageUrl, $canvasReview.renderAll.bind($canvasReview), {
        crossOrigin: 'Anonymous'
    });     
    
    $("ul#listImageTemplate a").removeClass("active");
    $(element).addClass("active");
    event.preventDefault(); 
}
$(document).ready(function(){

    $(document).on('click','#check_all',function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    })
      
    if ($("input.datepicker")[0]) {
        $('input.datepicker').daterangepicker({            
            singleDatePicker: true, 
            isInvalidDate: true,
            minDate: moment(), // 0 days offset = today
        });
    }
    
    var $canvasReview = initCanvasCoupon();       
   
    $.getJSON("{{ route('getCouponTemplates') }}",function(jsonData){
       if(jsonData.success){
          var $publicImagePath = "{{ config('app.url_file_view') }}/"; 
          $.each( jsonData.data, function( i, item ) {
             var $link = $("<a href=''><img src='"+ $publicImagePath + item.image +"' /></a>").on('click',
             function(event){
                 eventSelectTemplate($canvasReview, this, event);
             });
             $("<li />").append($link).appendTo($("ul#listImageTemplate"));             
          });
          $("ul#listImageTemplate a:first").trigger("click");
       }
   });
    
    $("#fileUploadImageTemplate").on( "change", function(e){
        var file = e.target.files[0];
        var reader = new FileReader();
        reader.onload = function (f) {
            var imageUrl= f.target.result;                    
            $canvasReview.setBackgroundImage(imageUrl, $canvasReview.renderAll.bind($canvasReview), {
                crossOrigin: 'Anonymous'
            });     
        $("ul#listImageTemplate a").removeClass("active");
        };
        reader.readAsDataURL(file);
     });
    var canvasOwnerReview = new fabric.Canvas('canvasOwnerImagePreview', {
            hoverCursor: 'pointer',
            preserveObjectStacking: true, 
            selection: true
        });
function handleImage(e){
        var reader = new FileReader();
        reader.onload = function(event){
            var img = new Image();
            img.onload = function(){
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img,0,0);
            }
            img.src = event.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);     
    }
    var fileimage ="";
        function getBase64(file) {
           var reader = new FileReader();
           reader.readAsDataURL(file);
           reader.onload = function () {
             //console.log(reader.result);
             fileimage = reader.result;
           };
           reader.onerror = function (error) {
             console.log('Error: ', error);
           };
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
        
        var form = $('#couponForm')[0];
        var couponData = new FormData(form);
        couponData.append('couponImageBase64',$canvasReview.toDataURL({format: 'png'}));
        
        $.ajax({
            type: "POST",            
            url: $("#couponForm").attr("action"),
            data: couponData,
            context: $('#btnSaveCoupon'),
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(data) {
            if(data.success == false){
                toastr.success(data.message);
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
@stop

