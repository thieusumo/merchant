@extends('layouts.master')
@section('title', 'Marketing | Reviews')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">
<style>
   .review_active{
   background:#efefef;
   }
   .with_column1{
   width: 400px;
   }
   .white{
   color: ;
   }
   .hv_pointer:hover{
      cursor: pointer;
   }
</style>
@stop
@section('content')
<div class="x_panel">
   <div class="x_title">
      <ul class="reviews-overview ">
          <li class="review_active">
            <a id="bad_review" class="name" href="#"> 
            <i class="fa fa-google-plus-square"></i> Bad Review
            <span class="badge bg-blue">{{$sum_totalBad}}</span>
            </a>                   
         </li>

         <li>
            <a id="allreviews" class="name " href="#"> 
            <i class="fa fa-thumbs-o-up"></i> All Reviews 
            <span class="badge bg-blue">{{$sum_total}}</span>  
            </a>                   
         </li>
         <li>
            <a id='website' class="name" href="#"> 
            <i class="fa fa-star"></i> SMS Reviews
            <span class="badge bg-blue">{{$sum_website}}</span>  
            </a>                   
         </li>
         <li>
            <a id="yelp" class="name" href="#"> 
            <i class="fa fa-yelp"></i> Yelp
            <span class="badge bg-blue">{{$sum_yelp}}</span>  
            </a>                   
         </li>
         <li>
            <a id="facebook" class="name" href="#"> 
            <i class="fa fa-facebook-square"></i> Facebook
            <span class="badge bg-blue">{{$sum_facebook}}</span>  
            </a>                   
         </li>
         <li>
            <a id="google" class="name" href="#"> 
            <i class="fa fa-google-plus-square"></i> Google
            <span class="badge bg-blue">{{$sum_google}}</span>  
            </a>                   
         </li>
      </ul>
   </div>
   <div class="x_content" id="x_content">
      <div class="review_search" hidden>
         <form action="" method="post" id="filter_form" name="calendar_form">
            <input type="hidden" name="type" id="type_review">
            <input type="hidden" class="data_length_start" name="data_length_start" value="20">
            <input type="hidden" name="show_more" value="0">
         </form>
      </div>
      <ul class="messages">
      </ul>
      <div class="text-center">
         <a href="#" class="show_more" >
            <h5><button type="button" class="btn btn-sm btn-primary">Show More</button></h5>
         </a>
      </div>
   </div>
   <div class="x_content" id="table_full_package" style="display: none">
      <div class="container">
         <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10 showtable">
            </div>
            <div class="col-sm-1"></div>
         </div>
      </div>
   </div>
   <div class="x_content" id="sms_content" style="display: none">
      <div class="container">
         <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8 text-center text-uppercase">
               <h3>
               <b> Dịch vụ SMS mời khách hàng viết đánh giá</b></h4>
            </div>
            <div class="col-sm-2">
            </div>
         </div>
         <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-9">
               <ol style="font-size: 18px;">
                  <li> Giúp hiển thị những đánh giá 5 sao lên Google, Yelp hoặc Facebook</li>
                  <li> Những đánh giá 3 sao trở xuống sẽ chỉ hiển thị trong hệ thống</li>
                  <li> Giúp tiệm có rating tốt nhất trên mạng xã hội</li>
                  <li> Đồng thời dựa trên đánh giá xấu trên hệ thống sẽ giúp cải thiện dịch vụ ngày một tốt hơn</li>
                  <li> Đăng ký gói dịch vụ SMS Review sẽ đi kèm theo SMS thông báo booking thành công. SMS thông báo
                     thông tin về Gift Card khi khách hàng mua Gift Card, SMS Coupon, Happy Birthday, ngày vắng khách,
                     sự kiện, SMS nhắc nhở khách hàng đến tiệm làm dịch vụ sau một thời gian tùy theo chủ tiệm cài đặt.
                  </li>
               </ol>
            </div>
            <div class="col-sm-2"></div>
         </div>
         <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8 text-center">
               <span style="font-size: 16px; color: #25ACDB">Để sử dụng dịch vụ trên. Vui lòng xem thông tin chi tiết</span>
               <a href="javascript:void(0)" id="taiday"><span style="font-size: 24px; color: #B40404;font-weight: bold;">  TẠI ĐÂY</span></a>
            </div>
            <div class="col-sm-2">
            </div>
         </div>
         <div class="row">
            <div class="col-sm-12 text-center" style="font-size: 18px; color: red;">tollfree : 888 840 8070</div>
         </div>
      </div>
   </div>
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript">
   $(document).ready(function() {
       if ($("input.icheckstyle")[0]) {
           $('input.icheckstyle').iCheck({
               checkboxClass: 'icheckbox_flat-green',
               radioClass: 'iradio_flat-green'
           });       
       }   
       $('#review_date').daterangepicker({
           autoUpdateInput: false,
           locale: {
               cancelLabel: 'Clear'
           }
       });
   
       $('#review_date').on('apply.daterangepicker', function(ev, picker) {
           $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
           $("input[name='start_date']").val(picker.startDate.format('DD-MM-YYYY'));
           $("input[name='end_date']").val(picker.endDate.format('DD-MM-YYYY'));
       });
   
       $('#review_date').on('cancel.daterangepicker', function(ev, picker) {
           $(this).val('');
       }); 
       
   }); 
</script> 
<script>
   $(document).ready(function(){
     
   
   
     //ajax load allreview
     review_ajax('{{ route('ajax_bad_review') }}');
     //function ajax review
     function review_ajax(url){
         $.ajax({ 
         // async:true,
         url:url,
         method:'get',
         dataType: 'json',
         success:function(data){
           if(data){
             var html = "";
             html = ajax_success(data);
             if(html != ""){
             $(".show_more").show();
             $('.messages').html(html);
             var data_length_start = $(".data_length_start").val();
             var length = parseInt(data_length_start);
             var type = $("#type_review").val();
             if(type == 1 ){
               length_reality = {{$sum_yelp=='' ? 0 : $sum_yelp}};
             }
             if(type == 2 ){
               length_reality = {{$sum_google=='' ? 0 : $sum_google}};
             }
             if(type == 3 ){
               length_reality = {{$sum_facebook=='' ? 0 : $sum_facebook}};
             }
             if(type == '' ){
               length_reality = {{$sum_total=='' ? 0 : $sum_total}};
             }
             if(type == 5 ){
               length_reality = {{$sum_website=='' ? 0 : $sum_website}} ;
             }
             if(type == 6 ){
               length_reality = {{$sum_totalBad=='' ? 0 : $sum_totalBad}};
             }
             if(length >= length_reality){
               $(".show_more").hide();
             }
             }
           }         
           
           else{
             $(".show_more").hide();
             $('.messages').html('<div class="text-center" id="no_review"><a ><h5><button type="button" class="btn btn-sm btn-danger">No review</button></h5></a></div>');     
           }
           
         },
         error:function(){
           $(".show_more").hide();
             $('.messages').html('<div class="text-center" id="no_review"><a ><h5><button type="button" class="btn btn-sm btn-danger">No review</button></h5></a></div>');     
         }
       });
         
       }
   
     //function success ajax
     function ajax_success(data){
           var images = '';
           var html ='';
           for(var i = 0; i<data.data.length; i++){
             date = new Date(data.data[i].created_date);
             var rating = '';
             for(var r = 0;r < data.data[i].rating; r++){
                     rating += '<i class="text-warning fa fa-star"></i>'
                   }
             // check type
             if(data.data[i].type){
               var type = data.data[i].type;
               if(type == 1) images = '{{asset("images/yelp.png")}}';
               else if(type == 2) images = '{{asset("images/google.png")}}';
               else if(type == 3) images = '{{asset("images/facebook.png")}}';
               else if(type == 5) images = '{{ asset('images/star.png') }}'
             }
             // if(data.data[i].type ==5 && data.data[i].customer_id >0)
             // {
             //   var customer_info = '<a href="/client/info/'+data.data[i].customer_id+'">'+data.data[i].customer+' '+'</a>' ;
             // }else{
               var customer_info = data.data[i].customer ;
             // }
   
             html += '<li>'+
             '<img src="'+images+'" class="avatar" alt="Avatar">'+
             '<div class="message_date">'+formatDate(date)+'</div>'+
             '<div class="message_wrapper">'+
                '<h4 class="heading">'+customer_info+'<span class="ratings">'
                           +rating+
                   '</span>'+
                '</h4>'+
                '<blockquote class="message">'+
                   ''+data.data[i].message+''+
                '</blockquote>'+
                '<br />'+
                // '<a href="#" class="btn btn-sm btn-outline-secondary btn-round btn-response">'+
                // '<i class="fa fa-reply"></i>'+
                // 'Reply '+
                // '</a>'+
             '</div>'+
          '</li>';
           }
   
   
         return html;
         }
       //--
     //event click .name
     $('.name').on('click',function(e){
   
       $('.reviews-overview li').removeClass('review_active');
       $(this).parent().addClass('review_active');
   
       var id = $(this).attr("id");
       
       e.preventDefault();
   
       if(id === 'yelp'){
         $("#x_content").show();
        $("#sms_content").hide();
        $("#table_full_package").hide();
         review_ajax('{{ route('ajax_yelp') }}');
         $("input[name='type']").val(1);
       }      
       else if(id === 'facebook'){
         $("#x_content").show();
        $("#sms_content").hide();
        $("#table_full_package").hide();
         review_ajax('{{ route('ajax_facebook') }}');
         $("input[name='type']").val(3);
       }
       else if(id === 'google'){
         $("#x_content").show();
        $("#sms_content").hide();
        $("#table_full_package").hide();
         review_ajax('{{ route('ajax_google') }}');
         $("input[name='type']").val(2);
       }
       else if(id ==='allreviews'){
         $("#x_content").show();
        $("#sms_content").hide();
        $("#table_full_package").hide();
         review_ajax('{{ route('ajax_allreviews') }}');
         $("input[name='type']").val('');
       }
       else if(id ==='website'){
        $.ajax({
          url:"{{ route('checkReviewWebsite') }}",
          method:"get",
          success:function(data){
            if(data == 1){
              //show review
              $("#x_content").show();
              $("#sms_content").hide();
              $("#table_full_package").hide();
              review_ajax('{{ route('ajax_website') }}');
              $("input[name='type']").val(5);
            } else {
              //show buy sms
               $.ajax({
               url:"{{ route('showtable') }}",
               method:'get',
               data:{_token:'{{csrf_token()}}'},
               success:function(data){
                 $('.showtable').html(data);
               }              
             });
   
            $("#sms_content").show();
            $("#x_content").hide();
            $(document).on('click',"#taiday",function(){
             $("#sms_content").hide();
             $("#x_content").hide();
             $("#table_full_package").show();
               $(document).on('click',"#website",function(){
               $("#sms_content").show();
               $("#x_content").hide();
               $("#table_full_package").hide();
              })
            });
            }
          }
        });

          
   
        
   
       }
       else if(id ==='bad_review'){
         $("#x_content").show();
        $("#sms_content").hide();
        $("#table_full_package").hide();
         review_ajax('{{ route('ajax_bad_review') }}');
         $("input[name='type']").val(6);
       }
       $("input[type='checkbox']").prop("checked",false);
       $(".data_length_start").val('20');
     });
     //-- form filter submit
     $('#filter_form').on('submit',function(e){
       e.preventDefault();
   
       var val_length = $("select[name='show_items_length'] :selected").val();      
       if(val_length) $(".data_length_start").val(val_length);
   
       var form = $('#filter_form')[0];
       var form_data = new FormData(form);
   
       $.ajax({        
         url:"{{ route('ajax_filter_form') }}",
         method:"post",
         data:form_data,
         cache:false,
         contentType:false,
         processData:false,
         dataType:'json',
         success:function(data){ 
           // console.log(data);
           var html = ajax_success(data);
           $('.messages').html(html);          
         }
       });
     });
     // reload review
     $(".reload").on('click',function(e){
       e.preventDefault();
       var type = $("input[name='type']").val();
       $(".data_length_start").val('20');
       $.ajax({
               url:"{{ route('ajax_filter_form') }}",
               method:"post",
               data:{type:type},             
               dataType:'json',
               success:function(data){                          
                 var html = ajax_success(data);
                 $('.messages').html(html);          
               }
       });
       $("input[type='checkbox']").prop("checked",false);
       $("select[name='show_items_length'] option:selected").prop('selected',false);
       $("select[name='rating'] option:selected").prop('selected',false);
       $("input[name='review_date']").val('');
       $("input[name='customer_name']").val('');
     });
     //show more append html
     $(".show_more").on('click',function(e){
       e.preventDefault();
       $("input[name='show_more']").val(1); //if(show_more == 1) $('.messages').append(html);
       $("select[name='show_items_length'] option:selected").prop('selected',false);
       $("select[name='show_items_length'] option:first").prop('selected',true);      
       
             var form = $('#filter_form')[0];
             var form_data = new FormData(form);
   
             $.ajax({        
               url:"{{ route('ajax_filter_form') }}",
               method:"post",
               data:form_data,
               cache:false,
               contentType:false,
               processData:false,
               dataType:'json',
               success:function(data){ 
                 // console.log(data);
                 var html = ajax_success(data);
                 $('.messages').append(html);
                 var data_length_start = $(".data_length_start").val();
                 var length = parseInt(data_length_start);
                 var type = $("#type_review").val();
                 if(type == 1 ){
                   length_reality = {{$sum_yelp == '' ? 0 : $sum_yelp}};
                 }
                 if(type == 2 ){
                   length_reality = {{$sum_google == '' ? 0 : $sum_google}};
                 }
                 if(type == 3 ){
                   length_reality = {{$sum_facebook == '' ? 0 : $sum_facebook}};
                 }
                 if(type == '' ){
                   length_reality = {{$sum_total == '' ? 0 : $sum_total}};
                 }
                 if(type == 5 ){
                   length_reality = {{$sum_website == '' ? 0 : $sum_website}} ;
                 }
                 if(type == 6 ){
                   length_reality = {{$sum_totalBad == '' ? 0 : $sum_totalBad}};
                 }
   
                 if(length >= length_reality){
                   $(".show_more").hide();
                 }
               }
             });
   
       var length = $(".data_length_start").val();
       length = parseInt(length) + 20;
       $(".data_length_start").val(length);
       $("input[name='show_more']").val(0);
     });
   
     $("input[type='checkbox']").on('change',function(){
       if(this.checked) 
         $('#filter_form').submit();
       else $(".reload").trigger('click');
     })
     
   
   });
</script>  
@stop