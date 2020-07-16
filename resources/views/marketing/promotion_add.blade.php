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
        <div>
            <canvas id="canvasReview" width="553px" height="344px" />
        </div>        
        @include('marketing.partials.canvas_text_editor')         
    </div> 
   <!-- \end  -->     
   <form action="{{ route("savePromotion")}}" id="promotionForm" enctype='multipart/form-data' name="promotionForm" onsubmit="return false;">
   <!-- Only show when click image owner -->  
    <div class="owner-design col-xs-7 col-md-7 offset-md-1 no-padding full-height border-right border-left border-grey-light" style="display:none;"> 
        <div class="upload-btn-wrapper">
          <button class="btn btn-sm btn-secondary" id="btnfileUploadImageOwner" name="btnfileUploadImageOwner">Upload Image</button>
          <input type="file" name="fileUploadImageOwner" id="fileUploadImageOwner" accept="image/*">
        </div>
        <div>
          <canvas style="max-height: 400px" id="canvasOwnerImagePreview"  /> 
        </div>               
    </div><!-- \end  --> 

    <div class="col-xs-4 col-md-4 no-padding full-height">
        <!-- <form action="{{ route("savePromotion")}}" id="promotionForm" name="promotionForm" onsubmit="return false;"> -->
        <div class="canvas-info">            
            <h6 class="title border-bottom text">Promotion Information</h6>
            <div class="row">
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
           </div> 
           <div class="row form-group">
                <label for="promotion_name" class="col-sm-4 col-md-3">Name</label>
                <div class="col-sm-9 col-md-8 no-padding">
                   <input type='text' id="promotion_name" name="promotion_name" value="Promotion Name" class="form-control form-control-sm" required="required"/>
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
            

           <div class="row">
               <label for="promotion_date_start" class="col-sm-4 col-md-3">Date Start</label>
                <div class="col-sm-6 col-md-5 no-padding input-group-spaddon">
                   <div class='input-group date'>                    
                        <input type='text' id="promotion_date_start" name="promotion_date_start" value="{{ date('m-d-Y') }}" class="form-control form-control-sm datepicker"  required="required"/>
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>            
           </div> 


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
                        <input type='text' id="promotion_time_start" name="promotion_time_start" value="08:00 AM"  class="form-control form-control-sm timepicker" required="required"/>
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
                        <input type='text' id="promotion_time_end" name="promotion_time_end" value="05:00 PM" class="form-control form-control-sm timepicker" required="required"/>
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>            
           </div> 
           <div class="row">
                <label for="promotion_discount" class="col-sm-4 col-md-3">Discount</label>
                <div class="col-sm-6 col-md-5 no-padding input-group">
                    <input type='number' id="promotion_discount" name="promotion_discount" value="0" class="form-control form-control-sm"  required="required" min="1" data-bind="value:promotion_discount"/>                   
                    <div id="promotion_discount_type" class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-sm btn-info active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                            <input name="promotion_discount_type" value="%" data-parsley-multiple="promotion_discount_type" data-parsley-id="12" type="radio" checked="checked"> &nbsp; % &nbsp;
                        </label>
                        <label class="btn btn-sm btn-info" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                          <input name="promotion_discount_type" value="$" data-parsley-multiple="promotion_discount_type" type="radio"> &nbsp; $ &nbsp;
                        </label>
                      </div>
                </div>            
           </div>            
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
                                      <input id="{{$nameService}}{{$idService}}" type="checkbox" data="{{$nameService}}" class="{{$service_class}}" name="promotion_list_service[]" value="{{ $idService }}">
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
    //  $('input[name^=promotion_list_service]').on( "change", function(e){
    //     var arr=[];
    //     var elm=$(this).attr('data');
    //     debugger;
    //     arr.push(elm);
    //     console.log(arr);

    // });
    $(".service-options .dropdown-menu").css('max-height', $(window).height()-20);

    
    var $canvasReview = initCanvasPromotion();    
    
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
    $('input[name=promotion_time]').on( "change", function(e){
        if($(e.target).val() == 1) // PERIOD
        {
            $("#promotion_time_end").val('05:00 PM');
            $("#promotion_time_start").val('08:00 AM')
            $("input.timepicker").attr('required','required');
        }else{
            // ALL DAY 
            $("input.timepicker").removeAttr('required');
            $("input.timepicker").val('');
        }        
    });
    $.getJSON("{{ route('getPromotionTemplates') }}",function(jsonData){
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
    /*function previewFile() {
      var preview = document.querySelector('img[class=owner_image]');
      var file    = document.querySelector('input[id=fileUploadImageOwner]').files[0];
      var reader  = new FileReader();

      reader.addEventListener("load", function () {
        preview.src = reader.result;
      }, false);

      if (file) {
        reader.readAsDataURL(file);
      }
    }*/
    var canvasOwnerReview = new fabric.Canvas('canvasOwnerImagePreview', {
            hoverCursor: 'pointer',
            preserveObjectStacking: true, 
            selection: true
        });
   
    $("#fileUploadImageOwner").on( "change", function(e){
        var file = e.target.files[0];
        var reader = new FileReader();

        reader.onload = function (f) {
            var imageUrl= f.target.result;                    
            fabric.Image.fromURL(imageUrl, (img) => {
                canvasOwnerReview.setBackgroundImage(img, canvasOwnerReview.renderAll.bind(canvasOwnerReview), {
                    scaleX: canvasOwnerReview.width / img.width,
                    scaleY: canvasOwnerReview.height / img.height
                 });
            }, null, {crossOrigin: 'Anonymous'});
        };
        reader.readAsDataURL(file);
        var canvas = document.getElementById("canvasOwnerImagePreview");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
     });

    var imageLoader = document.getElementById('fileUploadImageOwner');
    imageLoader.addEventListener('change', handleImage, false);
    var canvas = document.getElementById('canvasOwnerImagePreview');
    var ctx = canvas.getContext('2d');


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
    
   
    $("#btnSavePromotion").on( "click", function(event){
        // validate form
        var validatorResult = $("#promotionForm")[0].checkValidity();
        $("#promotionForm").addClass('was-validated');
        if(!validatorResult){
            event.preventDefault();
            event.stopPropagation();           
            return;
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
        var image = $('#fileUploadImageTemplate')[0].files[0];
        //getBase64(image);


        function handleFileSelect(evt) {
          var f = evt.target.files[0]; // FileList object
          var reader = new FileReader();
          // Closure to capture the file information.
          reader.onload = (function(theFile) {
            return function(e) {
              var binaryData = e.target.result;
              //Converting Binary Data to base 64
              var base64String = window.btoa(binaryData);
              //showing file converted to base64
              document.getElementById('base64').value = base64String;
              toastr.error('File converted to base64 successfuly!\nCheck in Textarea');
            };
          })(f);
          // Read in the image file as a data URL.
          reader.readAsBinaryString(f);
        }

        var form = $('#promotionForm')[0];
        var promotionData = new FormData(form);
        promotionData.append('fileUploadImageTemplate',image);
        promotionData.append('promotionImageBase64',$canvasReview.toDataURL({format: 'png'}));
        // console.log(promotionData.get('promotionImageBase64'));
        // $.ajax({
        //     type: "POST",            
        //     url: $("#promotionForm").attr("action"),
        //     data: 1,  
        //     context: $('#btnSavePromotion'),
        //     cache: false,
        //     contentType: false,
        //     processData: false,
        // })
        // .done(function(data) {
        //  if(data.success == false){
        //         alert(data.messages);  
        //         console.log(data.request); 
        //     }else{
        //       console.log(data);
        //         $('#saveConfirmModal').modal('show');         
        //     }

        //     })
        // .fail(function(xhr, ajaxOptions, thrownError) {
        //     alert('Upload Promotion Error!');
        //     //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        //  });
        $.ajax({
          url: "{{route('savePromotion')}}",
          data: promotionData,
          processData: false,
          contentType: false,
          type: 'POST',
        })
        .done(function(data) {
         if(data.success == false){
                toastr.success(data.messages);  
                // console.log(data.request); 
            }else{
              console.log(data);
                $('#saveConfirmModal').modal('show');         
            }

            })
        .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Upload Promotion Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
      
        
    });

    $('input[class=show-hide-design]').on( "change", function(e){
        if($(e.target).val() == 0) // PERIOD
        { 
            $('.design-on-web').css("display", "none");
            $('.owner-design').removeAttr("style");
        }else{
            $('.owner-design').css("display", "none");
            $('.design-on-web').removeAttr("style");
        }        
    });
    

    $(document).on('click','#check_all',function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
      // if($('input[name="checkBoxName"]').is(':checked'))
      // {
      //   alert('check');
      // }else
      // {
      //  alert('no');
      // }
    })

    $(document).on("change",'input[name=promotion_list_service]',function(){
      var check=$(this).prop('checked');
      console.log(check);
    });
}); 
</script>
@stop


