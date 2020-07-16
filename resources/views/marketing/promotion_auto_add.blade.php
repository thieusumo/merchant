@extends('layouts.master')
@section('title', 'Marketing | Promotion | Add New Promotion')
@section('styles')
<link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">  
<style>    
    .top_nav{height: 84px;} 
    .custom-range{ margin: 10px 10px; width: 180px;}    
    .service-options .dropdown-menu{
        overflow-x:hidden;
    }
    .checkbox-inline input[type="checkbox"]{
      
    }
</style>
@stop
@section('content')
<div id="promotion" class=" col-xs-12 col-md-12 fixLHeight no-padding full-height bg-white canvas-design">
     <!-- Only show when click image design -->   
    <div class="design-on-web col-xs-2 col-md-2 no-padding full-height">
         @include('marketing.partials.template_list')         
    </div>    
    <div class="design-on-web col-xs-6 col-md-6 no-padding full-height border-right border-left border-grey-light">        
        <div class="text-center">
            {{-- <canvas id="canvasReview" width="553px" height="344px" /> --}}
            <img id="showImage" src="" alt="" width="553px" height="344px">
        </div>        
        {{-- @include('marketing.partials.canvas_text_editor')          --}}
    </div> 
   <!-- \end  -->     
   <form action="{{ route("savePromotion")}}" id="promotionForm" enctype='multipart/form-data' name="promotionForm" onsubmit="return false;">
   <!-- Only show when click image owner -->  
    <div class="owner-design col-xs-7 col-md-7 offset-md-1 no-padding full-height border-right border-left border-grey-light" style="display:none;"> 
        <div class="upload-btn-wrapper">
          <button class="btn btn-sm btn-secondary" id="btnfileUploadImageOwner" name="btnfileUploadImageOwner">Upload Image</button>
          <input type="file" name="fileUploadImageOwner" id="fileUploadImageOwner" accept="image/*">
        </div>
        <div class="text-center">
          {{-- <canvas style="max-height: 400px" id="canvasOwnerImagePreview"  />  --}}
          <img id="showImage" src="" alt="" style="max-height: 400px">
        </div>               
    </div><!-- \end  --> 

    <div class="col-xs-4 col-md-4 no-padding full-height">
        <!-- <form action="{{ route("savePromotion")}}" id="promotionForm" name="promotionForm" onsubmit="return false;"> -->
        <div class="canvas-info">            
            <h6 class="title border-bottom text">Promotion Information</h6>
            {{-- <div class="row">
                <label for="promotion_name" class="col-sm-4 col-md-3">Image</label>
                <div class="col-sm-6 col-md-5 no-padding input-group">
                    <div id="promotion_image_type" class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-sm btn-info active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default" style="width: auto">
                            <input class="show-hide-design" name="owner_image" value="1"type="radio" checked="checked">DESIGN
                        </label>
                        <label class="btn btn-sm btn-info" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default" style="min-width:120px; width: auto">
                          <input class="show-hide-design" name="owner_image" value="0" type="radio"> OWNER IMAGE
                        </label>
                      </div>
                </div>                    
           </div>  --}}
           <div class="row form-group">
                <label for="promotion_name" class="col-sm-4 col-md-3">Name</label>
                <div class="col-sm-9 col-md-8 no-padding">
                   <input readonly="" type='text' id="promotion_name" name="promotion_name" value="" class="form-control form-control-sm" />
                </div>            
           </div> 
        
           <div class="row">
                <label for="promotion_date_end" class="col-sm-4 col-md-3">Promotion Group</label>
                <div class="col-sm-6 col-md-5 no-padding">
                     <select class="form-control form-control-sm" name="promotion_group">
                          <option  value="0">Normal</option>
                          <option  value="1">Happy hours</option>
                          <option  value="2">Instant Day</option>
                      </select>
                </div>            
           </div> 

            

           {{-- <div class="row">
               <label for="promotion_date_start" class="col-sm-4 col-md-3">Date Start</label>
                <div class="col-sm-6 col-md-5 no-padding input-group-spaddon">
                   <div class='input-group date'>                    
                        <input type='text' id="promotion_date_start" name="promotion_date_start" value="{{ date('m-d-Y') }}" class="form-control form-control-sm datepicker"  required="required"/>
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>            
           </div>  --}}


           <div class="row">
                <label for="promotion_date_end" class="col-sm-4 col-md-3">Date End</label>
                <div class="col-sm-6 col-md-5 no-padding input-group-spaddon">
                     <div class='input-group date'>                    
                        <input type='text' id="promotion_date_end" name="promotion_date_end"  value="{{ date('m-d-Y', strtotime('+1 month')) }}" class="form-control form-control-sm datepicker"  required="required"/>
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>            
           </div> 
            <div class="row">
                <label for="promotion_time" class="col-sm-4 col-md-3">&nbsp;</label>
                <div class="col-sm-6 col-md-5 no-padding input-group">
                    <div id="promotion_time" class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label id="previous" class="btn btn-sm btn-info active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                            <input name="promotion_time" value="1" data-parsley-multiple="promotion_time" data-parsley-id="12" type="radio" checked="checked">PERIOD
                        </label>
                        <label id="allday" class="btn btn-sm btn-info" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                          <input name="promotion_time" value="0" data-parsley-multiple="promotion_time" type="radio"> ALL DAY
                        </label>
                      </div>
                </div>            
           </div>          
           <div class="row">
                 <label for="promotion_time_start" class="col-sm-4 col-md-3">Time Start</label>
                <div class="col-sm-6 col-md-5 no-padding input-group-spaddon">
                   <div class='input-group date'>                    
                        <input type='text' id="promotion_time_start" name="promotion_time_start" value="08:00 AM"  class="form-control form-control-sm timepicker" />
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>            
           </div> 
           <div class="row">
                <label for="promotion_time_end" class="col-sm-4 col-md-3">Time End</label>
                <div class="col-sm-6 col-md-5 no-padding input-group-spaddon">
                     <div class='input-group date'>                    
                        <input type='text' id="promotion_time_end" name="promotion_time_end" value="05:00 PM" class="form-control form-control-sm timepicker" />
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>            
           </div> 
           <div class="row">
                <label for="promotion_discount" class="col-sm-4 col-md-3">Discount <span id="promotion_discount_type"></span></label>
                <div class="col-sm-6 col-md-5 no-padding input-group">
                    <input readonly="" type='number' id="promotion_discount" name="promotion_discount" value="0" class="form-control form-control-sm"  required="required" min="1" data-bind="value:promotion_discount"/>                   
                  
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
                  <input type="hidden" id="id_template" name="id_template">
                   <input type="button" class="btn btn-sm btn-primary left" value="SAVE PROMOTION" name="btnSavePromotion" id="btnSavePromotion" />
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
          <h5 class="text-success">Your promotion saved successfully</h5>
      </div>
      <div class="modal-footer text-center" style="display:block;">
        <a href="{{ asset("marketing/promotion/add")}}" id="linkAddMore" class="btn btn-sm btn-primary">ADD MORE</a>
{{--         <a href="{{ asset("marketing/sms/send")}}" id="linkSendSMS" class="btn btn-sm btn-primary">SEND SMS</a> --}}
        <a href="{{ asset("marketing/promotions")}}" id="linkAddDone" class="btn btn-sm btn-success">DONE</a>
      </div>
    </div>
  </div>
</div>

@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>   
<script type="text/javascript" src="{{ asset('plugins/custom/fabric.min.js') }}"></script>   
<script type="text/javascript">

  function getPromotionAutoTemplates(){
    var id = $('#templateType').val();
    $.ajax({
      url:"{{ route('getPromotionAutoTemplates') }}",
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
    $("#promotion_name").val('');
    $("#promotion_discount").val('');
    $("#list_service").html('');
  }

  function clickFirst(){
    setTimeout(function(){ $("#listImageTemplate img:first").trigger("click"); }, 1000);
  }
  
$(document).ready(function(){ 
   getPromotionAutoTemplates();
   $("#templateType").on('change',function(){
    getPromotionAutoTemplates();
    clear();
    clickFirst();
   });

    $("#allday").on('click',function(){
      $("#promotion_time_start").val('');
      $("#promotion_time_end").val('');
    });
    $("#previous").on('click',function(){
      $("#promotion_time_start").val('08:00 AM');
      $("#promotion_time_end").val('05:00 PM');
    });
      //--------------
       if ($("input.datepicker")[0]) {
            $('input.datepicker').daterangepicker({            
                singleDatePicker: true, 
                isInvalidDate: true,
                minDate: moment(), // 0 days offset = today
            });
        }
        if ($("input.timepicker")[0]) {
            $('input.timepicker').datetimepicker({            
               format: 'hh:mm A',
            }).on('dp.change', function (e) {
                $(e.target).trigger('change');
            });
        }
      //--------------
    $("#btnSavePromotion").on( "click", function(event){
        // validate form
        var validatorResult = $("#promotionForm")[0].checkValidity();
        $("#promotionForm").addClass('was-validated');
        if(!validatorResult){
            event.preventDefault();
            event.stopPropagation();           
            return;
        }
        var form = $('#promotionForm')[0];
        var promotionData = new FormData(form);
        promotionData.append('promotion_discount_type',$("#promotion_discount_type").attr('type'));
        promotionData.append('list_service',$("#list_service").attr('list-service'));
        
        $.ajax({
            type: "POST",            
            url: "{{ route("savePromotion")}}",
            data: promotionData,
            processData: false,
            contentType: false,
            
        }).done(function(data) {
            if(data.success == false){
                // alert(data.message);
                toastr.error(data.messages)
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
        // console.log(discountType);
        $("#id_template").val(id);
        $("#promotion_name").val(title);
        $("#promotion_discount").val(discount);      
        $("#promotion_discount_type").text(discountType);
        $("#promotion_discount_type").attr('type',type);
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


