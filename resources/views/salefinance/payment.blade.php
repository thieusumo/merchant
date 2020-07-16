@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Sales & Finances | Booking & Payment Services')
@section('styles')
<link href="{{ asset('plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">    
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">
<style type="text/css">
    .yellow{
      color: #ffdf00!important;
    }
    .btn_active{
      background-color: #ffdf00!important;
    }
    #payservice .scroll-view {
      overflow-y: auto;
    }
    .full-height {
      border-right: 1px #dee2e6 solid;
    }
    .balance-box{
      display: none;
    }
    .card-footer {
     padding: .2rem .5rem; 
    }
    .calculate-div, .backleft-div, .enter-div, .cancel_next{
      line-height: 60px;
      color: #fff;
      border: .5px #959a9e  solid;
      text-align: center;
      font-weight: bold;
    }
    .calculate-div:hover, .backleft-div:hover, .enter-div:hover, .cancel_next:hover{
      background-color: #959a9e;
    }
    #pass_for_delete{
      width: 100%;
      line-height: 40px;
      padding: 0px;
      background-color: #333333;
      color: #fff;
      font-size: 22px;
      text-align: center;
      border:none;

    }
    .card-body p{
      line-height: 15px;
      margin-bottom: 8px;
    }
    .div-giftcard .form-control, .div-referral .form-control{
      border: none;
      background-color: #efefef;
    }
    .reason-div{
      line-height: 40px;
      background-color: #efefef;
      margin: 2px;
      border: .5px solid #dee2e6;
    }
    .discount_div_right .discount-box{
      line-height: 40px;
      margin: 2px;
    }
    .discount_div_left .discount-box{
      line-height: 69px;
      margin: 2px;
    }
    .discount_div_choose{
      background-color: #ffdf00;
    }
    .discount_div_origin{
      background-color: #959a9e;
    }
    .discount-box:hover{
      background-color: #ffdf00;
    }
    .reason-div:hover{
      color: #fff;
      background-color: #0874e8;
    }
    .btn-product:hover{
      color: red;
      font-weight: bold;
    }
    .btn_payment{
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: auto;
      color: #fff;
      height: 50px;
      margin-top: 10px;
    }
    .form_control{
      margin-bottom: 3px;
    }
    .btn-tip{
      width: 100px;
    }
    .top_nav{height: 84px;}    
    table#client-datatable.dataTable tbody tr:hover {
      background-color: #0874e8;
      color: #fff;
      cursor: -webkit-grab; 
      cursor: grab;
    }
    .modal-full {
    min-width: 100%;
    margin: 0;
    padding-left: 70px;
    }

    .modal-full .modal-content {
        min-height: 100vh;
    }
    .btn-custom{
        width:100px;
        margin-right:2px;
    }
    .btn-active{
        background-color: #ffdf00 !important;
        border: solid 1px #ffdf00 !important;
    }
    /*custom form_payticket*/
    .card{
        border-radius: 0px;
    }
    .btn_primary{
        background: #1268c5;
    }
    .btn_default{
        background: #959a9e;
    }
    .button_show .custom_btn_payticket{
      font-size: 18px;
      width: 16.34%;
    }
    .custom_btn_payticket{        
        border: 2px white solid;
        color: white;
        font-weight: bold;
        cursor: pointer;
        height: 80px;
        vertical-align:middle;
        display: flex;
    }
    .custom_btn_payticket>span{
        margin:auto;
    }
    .custom_card_header{
        border-top-left-radius:19px;
        border-top-right-radius:19px;
        padding: 8px;
    }
    .custom_card_body{
        border-bottom-left-radius:19px;
        border-bottom-right-radius:19px;
    }
    .font_size_18px{
        font-size: 18px;
    }
    .customer_info{
      font-size: 15px;
    }
    .button_show>.active{
        background: #ffdf00;
    }
    .ticket-combine-box:hover{
      background-color: #0874e8;
    }
</style>
@stop
@section('content')
{{-- SET IMAGE DEFAULT FOR STAFF IF NULL --}}
<input type="hidden" value="{{asset('images/user.png')}}" id="user_avatar_default" name="">
<input type="hidden" id="customer_id" value="">
<input type="hidden" id="total_charge_hidden" value="">
{{-- <div class="col-md-4 offset-md-4"style="position: fixed;top:30%;z-index: 1000444;background-color: #274360;border-radius: 10px;padding: 20px;color: #fff;display: none">
  
</div> --}}
{{-- MODAL LIST SERVICE --}}
<div class="modal fade" id="service-list-membership" role="dialog" style="z-index: 33333333333;top: 15%">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background-color: #fff;color: #000">
        <div class="modal-body col-md-12" id="service-list-box" style="padding:10px;">
        </div>
      </div>
    </div>
</div>
{{-- MODAL FOR COMBINE --}}
<div class="modal fade" id="combine-check-box" role="dialog" style="z-index: 33333333333;top: 15%">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background-color: #274360;color: #fff">
        <div class="modal-body col-md-12" id="combine-box" style="padding:10px;">

        </div>
      </div>
    </div>
</div>

{{-- MODAL FOR SHOW NOTIFICATION BUY GIFTCARD --}}
<div class="modal fade" id="buy-giftcard-notifi" role="dialog" style="z-index: 3333333333355;top: 15%">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background-color: #274360;color: #fff">
        <div class="modal-body col-md-12" id="giftcard-notifi-box" style="padding:10px;">
        </div>
      </div>
    </div>
</div>

{{-- MODAL FOR CHECK PASS DELETE TICKET --}}
<div class="modal fade" id="check-pass-box" role="dialog" style="z-index: 33333333333;top: 15%">
    <div class="modal-dialog modal-sm">
      <div class="modal-content check-content" style="background-color: #274360;color: #fff">
        <div class="modal-body col-md-12"style="background-color: #333333;padding:0px;border: 4px #959a9e solid;">
            <input type="password" class="col-md-12" id="pass_for_delete" name="" placeholder="ENTER PASS">
            <div class="col-md-12 notifi_after_check_pass text-center" style="height:20px;color:red"></div>
            @for($i=1;$i<10;$i++)
            <div class="col-md-4 text-center calculate-div">
              <b>{{$i}}</b>
            </div>
            @endfor
            <div class="col-md-4 text-center backleft-div" style="font-size: 25px">
              <b class="glyphicon glyphicon-arrow-left"></b>
            </div>
            <div class="col-md-4 text-center calculate-div">
              <b>0</b>
            </div>
            <div class="col-md-4 text-center enter-div delete_next" onclick="nextButton('void_ticket')" >
              <b>ENTER</b>
            </div>
            <div class="col-md-6 bg-primary enter-div delete_next" onclick="nextButton('void_ticket')" >
              OK
            </div>
            <div class="col-md-6 bg-danger cancel_next" data-dismiss="modal">
              CANCEL
            </div>
            
        </div>
      </div>
    </div>
</div>

<div id="payservice" class="col-xs-12 col-md-12 no-padding">
    <div class="col-xs-2 col-md-2 no-padding full-height">
      <div class="scroll-view list-services scroll-style-1" style="height: 80%">
         <ul id="listcateservices" class="list-unstyled nav nav-list tab-cateservice ">
        </ul>
      </div>
        <div id="booking" class="list-unstyled nav nav-list tab-cateservice text-center">
            <button class="btn btn-primary booking_list " >Booking</button>     
        </div>
    </div>    
    <div class="col-xs-6 col-md-6 no-padding full-height padding-top-5">
        <div id="list-services" class="height-50p scroll-view border-bottom list-services scroll-style-1">   
           @include('salefinance.partials.list_services')
        </div>        
        <div id="list-staff" class="height-50p scroll-view list-staff scroll-style-1">
            <div class="text-center" style="display:none;">No rent station available</div>
            <ul class="list-inline col-md-12 col-sm-12 row liststaffs text-center" style="width: 100%;margin: 0px;padding-left: 20px">  
            </ul>  
            
        </div>
    </div>    
    <div class="col-xs-4 col-md-4 no-padding full-height padding-top-5">
        <div class="height-40p scroll-view border-bottom section-order scroll-style-1">
            <table id="tableOrders"  class="table table-striped table-bordered" style="width:100%;">
            </table>
        </div>
        <div class="height-25p scroll-view section-client scroll-style-1">             
            <div class="row client-actions border-bottom"> 
                <div class="col-6 title">
                   Client Information
                </div>
                <div class="col-6 text-center">
                    <a href="#" data-toggle="modal" data-target="#addClientModal" >New Client</a> |   
                    <a href="#" class="select_client" >Select Client</a>
                </div>                
            </div>
            <div class="client-info">
                   <div class="col-sm-5 col-md-6">
                       <div class="row">             
                           <label class="col-sm-3 col-form-label">Name: </label>          
                           <span id="customer_fullname" class="col-sm-9 form-control-plaintext">{{isset($customer_info->customer_fullname)?$customer_info->customer_fullname:""  }}</span>                    
                       </div>
                       <div class="row">             
                           <label class="col-sm-3 col-form-label">Phone: </label>          
                           <span id="customer_phone" class="col-sm-9 form-control-plaintext">{{isset($customer_info->customer_phone)?$customer_info->customer_phone:"" }}</span>
                       </div>
                       <div class="row">             
                           <label class="col-sm-3 col-form-label">Email: </label>          
                           <span id="customer_email" class="col-sm-9 form-control-plaintext">{{isset($customer_info->customer_email)?$customer_info->customer_email:"" }}</span>
                       </div>    
                   </div>
                   <div class="col-sm-5 col-md-6">
                       <div class="row">             
                           <label class="col-sm-3 col-form-label">Group: </label>          
                           <span id="customertag_name" class="col-sm-9 form-control-plaintext">{{isset($customer_info->customertag_name)?$customer_info->customertag_name:"" }}</span>
                       </div> 
                       <div class="row">             
                           <label class="col-sm-3 col-form-label">Gender: </label>          
                           <span id="customer_gender" class="col-sm-9 form-control-plaintext">{{isset($customer_info->customer_gender)?$customer_info->customer_gender:"" }}</span>
                       </div>
                       <div class="row">             
                           <label class="col-sm-3 col-form-label">DOB: </label>          
                           <span id="customer_birthdate" class="col-sm-9 form-control-plaintext">{{isset($customer_info->customer_birthdate)?$customer_info->customer_birthdate:"" }}</span>
                       </div> 
                   </div>
            </div>           
        </div>    
        <div class="height-35p scroll-view section-summary scroll-style-1">
            <div class="extra">
                <ul class="list-inline col-sm-12 no-margin">
                    <li class="list-inline-item"><span class="text">EXTRA</span></li>
                    <li class="list-inline-item"><a class="btn extra-value" id="1" href="#">$1</a></li>
                    <li class="list-inline-item"><a class="btn extra-value" id="2" href="#">$2</a></li>
                    <li class="list-inline-item"><a class="btn extra-value" id="3" href="#">$3</a></li>
                    <li class="list-inline-item"><a class="btn extra-value" id="5" href="#">$5</a></li>
                    <li class="list-inline-item">
                        <input type="text" name="extra_number" id="extra_number" class="form-control" maxlength="3" value="{{$extra_payment}}">
                    </li>
                    <li class="list-inline-item plus_minus">
                        <a href="#" class="plus-minus-action btn plus"><i class="fa fa-plus"></i></a>
                        <a href="#" class="plus-minus-action btn minus"><i class="fa fa-minus"></i></a>
                    </li>
                   
                </ul>
            </div>
            <div class="total">
                <label class="text"> TOTAL: </label>
                <span class="price total-price"></span>
            </div>
            <div class="action">
                <div>
                    <a class="btn btn-primary pay_ticket"  href="#">Pay Ticket</a>               
                    <a class="btn btn-primary" href="#">Split</a>                     
                    <a class="btn btn-primary save_ticket" href="#">Save Ticket</a>
                    <a class="btn btn-primary new_ticket" href="#">New Ticket</a>
                    <a id="btPro" class="btn btn-primary btn-promotions" >Promotions</a>
                </div>
            </div>
        </div>
    </div>
</div> 
@include('salefinance.partials.add_client') 
{{-- @include('salefinance.partials.form_payticket')  --}}
@include('salefinance.partials.form_payticket(1)')
<input type="hidden" id="order_list" value='@if( isset($order_list) && !empty($order_list)) 1 @endif'>
<input type="hidden" id="ticket_list" value='@if( isset($ticket_list) && !empty($ticket_list)) 1 @endif'>
@stop


@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/custom/combodate.js') }}"></script>  
<script type="text/javascript">
  var ticket_list= Array();
  var payment_list = Array();
  var staff_list_payment = Array();
  var service_list_payment = [];
  var tip_list = Array();
  var discount_station = " ";
  var discount = " ";
  var discount_type = " ";
  var discount_amount = 0;
  var booking_id = 0;
  var ticket_no = "";
  var stt_staff = -1;
  var correct_ticket = "";
  var ticket_edit = "";
  var product_list = Array();
  // var staff_begin_list = Array();
$(document).ready(function(){ 
  $('input.datepicker').daterangepicker({
        singleDatePicker: true,
        minDate: moment(),
        showDropdowns: true
    });
  $('input.datepicker_expire').daterangepicker({
        singleDatePicker: true,
        minDate: moment().startOf('hour').add(24, 'hour'),
        showDropdowns: true
    });

    //DEFIND VAR - BEGIN
    var ratio_tip = [];
    var number_of_ticket = "";
    var ticket_current = "";
    var cateservice ="";
    var service_list="";
    var staff_list="";
    var order_list= Array();
    
    var staffs ="";
    var staff_payment ="";
    var selected_cateservice_id = "";
    var selected_service_id;
    var current_customer = {{$current_customer}} ;
    var order_list_old = <?php echo json_encode($list_service) ?>;
    var cateservice_click = "";

    if(order_list_old != ""){
        order_list = order_list_old;
        drawOrderList();
    }
    if($('#order_list').val()!="")
    {
        order_list = <?php echo json_encode($order_list); ?> ;
        drawOrderList();
    }
    if($('#ticket_list').val()!="")
    {
        ticket_list = <?php echo json_encode($ticket_list); ?> ;
        let number_ticket = 0;
        if(ticket_list.length !== 0){
          for( var i in ticket_list){
            if(ticket_list[i].reason_delete === "empty" && ticket_list[i].payment == 0 )
              number_ticket++;
          }
        }
        // $('.pay_ticket').text('Pay Ticket ('+number_ticket+')');
    }
    var select_row_order= -1;
    var link_view_image = '{{config('app.url_file_view')}}' ;
    //DEFIND VAR - END

    //document.getElementById('cateservice-a').click();
    window.onload=function(){
      //document.getElementById("linkid").click();
      cateservice_click = document.getElementsByClassName("cateservice")[0];
      cateservice_click.click();
      cateservice_click.getElementsByTagName("a")[0].classList.add('active');
    };
    
    // LOAD CATE SERVICE LIST - BEGIN
    $.ajax({
      url: "{{route('get-cateservices')}}",
      type: "get",
      datatype: 'json',
      success: function(data){
            for(var i in data){
                cateservice+='<li class="cateservice" value="'+data[i].cateservice_id+'" ><a  href="#" data-toggle="tab">'+data[i].cateservice_name+'</a></li>';
                //alert(data[i].cateservice_name);
            }
            $('#listcateservices').html(cateservice);    
      },
      error:function(){
          
      }   
    }); 

    // LOAD SERVICE LIST - END
    loafStaffs();
    // setOrderListBegin();
    setInterval(function(){  
        loafStaffs()
    }, 10000);   
    // LOAD STAFF LIST - BEGIN
    function loafStaffs() {

      staff_begin_list = Array();
      var staff_row = [];
        staffs="";
        staff_payment = "";
        $.ajax({
          url: "{{route('get-staffs-payment')}}",
          type: "get",
          data:{selected_cateservice_id:selected_cateservice_id},
          datatype: 'json',
          success: function(data){
            // alert(data);
            // return;
                staff_list = data;
                // for(var i in data){
                //   var worker_avatar = "";
                //   if( i > stt_staff ){
                //     if(data[i].worker_avatar == null) worker_avatar = $("#user_avatar_default").val();
                //     if(data[i].worker_avatar != null) worker_avatar = '{{config('app.url_file_view')}}/'+data[i].worker_avatar

                //     staffs+=`
                //           <div class="card staff-div staff" value="`+data[i].worker_id+`" style="border: 2px solid #277a88;width: 24%;height: 140px;border-radius: 20px;padding:0px;margin:1px">
                //               <div class="custom_card_header bg-primary" id="`+data[i].worker_id+`">
                //               <div class="text-center " style="color:#fff;"><b>`+data[i].worker_nickname+`</b></div>
                //               </div>
                //               <div class="card-body custom_card_body">
                //                     <img style="width: 85px;height: 80px" src="`+worker_avatar+`" alt="">                     
                //               </div>
                //           </div>`;
                    
                //     staff_row = new Object();
                //     staff_row.staff_name = data[i].worker_nickname;
                //     staff_row.staff_id = data[i].worker_id;
                //     staff_begin_list.push(staff_row);
                //   }
                // }
                for(var i in data){
                  // if( i <= stt_staff ){
                    if(data[i].worker_avatar == null) data[i].worker_avatar = $("#user_avatar_default").val();

                    staffs+=`
                          <div class="card staff-div staff" value="`+data[i].worker_id+`" style="border: 2px solid #277a88;width: 24%;height: 140px;border-radius: 20px;padding:0px;margin:1px">
                              <div class="custom_card_header bg-primary" id="`+data[i].worker_id+`">
                              <div class="text-center " style="color:#fff;"><b>`+data[i].worker_nickname+`</b></div>
                              </div>
                              <div class="card-body custom_card_body text-center" style="line-height:80px">
                                <div class="col-md-8">
                                  <img style="width: 85px;height: 80px" src="`+data[i].worker_avatar+`" alt=""> 
                                </div>
                                <div class="col-md-4">
                                   <span class="glyphicon glyphicon-refresh">`+data[i].worker_turn+`</span>
                                </div>
                                                        
                              </div>
                          </div>`;
                    staff_row = new Object();
                    staff_row.staff_name = data[i].worker_nickname;
                    staff_row.staff_id = data[i].worker_id;
                    staff_begin_list.push(staff_row);
                  // }
                }
                $('.liststaffs').html(staffs);
                if( order_list.length === 0 && staff_begin_list.length !== 0){
                  var order_row = new Object();
                  order_row.staff_id=staff_begin_list[0].staff_id;
                  order_row.staff_name=staff_begin_list[0].staff_name;
                  order_row.service_id="";
                  order_row.service_name="";
                  order_row.service_price="";
                  order_list.push(order_row);
                }
                drawOrderList();
            }   
        }); 
    }
   
    // SELECT CATESERVICE LOAD SERVICE LIST - BEGIN
    $(document).on('click', '.cateservice', function(e) {
        var services ="";
        selected_cateservice_id = $(this).val();
        $.ajax({
          url: "{{route('get-services-payment')}}",
          type: "get",
          data: {id:selected_cateservice_id},
          datatype: 'json',
          success: function(dta){
                service_list = dta;
                console.log(service_list);

                for(var i in dta){
                    promotion_class='';
                    if(dta[i].promotion_discount!=''){
                      promotion_class = "promotion";
                    }
                    services+='<li class="list-inline-item block-service service '+promotion_class+'" value="'+dta[i].service_id+'"  ><a href="#">'+dta[i].service_name+'</a><span class="sprice">$'+dta[i].service_price+'</span></li>';
                }
                $('.listservices').html(services);     
          },
          error:function(){   
          }   
        }); 
    }); 
    // SELECT CATESERVICE LOAD SERVICE LIST - END

    // SET EXTRA VALUE - BEGIN
    $(document).on('click', '.extra-value', function(e) {
        extraValue = $(this).attr('id');
        $('#extra_number').val(extraValue);
        drawOrderList();
    }); 
    // SET EXTRA VALUE - END

    // PLUS MINUS EXTRA VALUE - BEGIN
    $(document).on('click', '.plus-minus-action', function(e) {
        extra_number = $('#extra_number').val()
        if($(this).hasClass("plus")){
            extra_number = parseInt(extra_number)+1;
        }
        else{
            extra_number-=1;
        }
        $('#extra_number').val(extra_number);
        drawOrderList();
    }); 
    // PLUS MINUS EXTRA VALUE - END

    // SELECT SERVICE ADD TO LIST - BEGIN
    $(document).on('click', '.service', function(e) {
        var selected_service_id = $(this).val();
        for(var i in service_list){
            if(service_list[i].service_id == selected_service_id ){
                selected_service_name = service_list[i].service_name;
                selected_service_price = service_list[i].service_price;
                selected_promotion_discount = service_list[i].promotion_discount;
                selected_promotion_type = service_list[i].promotion_type;
                break;
            }
        }
        if(select_row_order>=0){
            
            order_list[select_row_order].service_id = selected_service_id;
            order_list[select_row_order].service_name = selected_service_name;
            order_list[select_row_order].service_price = selected_service_price;
            order_list[select_row_order].promotion_discount = selected_promotion_discount;
            order_list[select_row_order].promotion_type = selected_promotion_type;
        }
        else{
            var order_row = new Object();
            order_row.staff_id="";
            order_row.staff_name="";
            order_row.service_id=selected_service_id;
            order_row.service_name=selected_service_name;
            order_row.service_price=selected_service_price;
            order_row.promotion_discount=selected_promotion_discount;
            order_row.promotion_type=selected_promotion_type;
        
            order_list.push(order_row);
        }
        drawOrderList();
    }); 
    // SELECT SERVICE ADD TO LIST - END

    // SELECT STAFF ADD TO LIST - BEGIN
    $(document).on('click', '.staff', function(e) {

      var selected_staff_id = $(this).attr('value');
      
        selected_staff_name ="" ;
        for(var i in staff_list){
            if(staff_list[i].worker_id == selected_staff_id ){
                selected_staff_name = staff_list[i].worker_nickname;
                break;
            }
        }

        if(select_row_order>=0){
            order_list[select_row_order].staff_id = selected_staff_id;
            order_list[select_row_order].staff_name = selected_staff_name;
        }
        else{
            var order_row = new Object();
            order_row.staff_id=selected_staff_id;
            order_row.staff_name=selected_staff_name;
            order_row.service_id="";
            order_row.service_name="";
            order_row.service_price="";
            order_list.push(order_row);
        }
        drawOrderList();
        // loafStaffs();
    });
    //CHECK FOR BUY GIFTCARD
    var check_buy_giftcard = '{{$check}}';
    if(check_buy_giftcard !== ""){
      payTicket();
      clearGiftcard();
      $("#check-pass-box").modal('show');
      $(".delete_next").attr('onclick',"nextButton('buy_giftcard')");
      showCheckBox();
    }
    //HIDE MODAL CHECK PASS WHEN CLICK OUTSIDE
    $(document).mouseup(function(e) 
    {
        var container = $(".check-content");
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            $(".left_col").css('opacity', '1');
            $("#payTicketModal").css('opacity', '1');
        }
    });

    //SET AUTO CLICK CHOOSE LIST SERVICE STAFF
    function clickStaffService(class_tr){
      if (!$("."+class_tr).hasClass('selected') || order_list.length ==1) {
            $('tr').removeClass('selected');
            $("."+class_tr).addClass('selected');
            select_row_order = $("."+class_tr).attr('id');
        }
        else{
            $("."+class_tr).removeClass('selected');
            select_row_order = -1;
        }
    }
    // SELECT STAFF ADD TO LIST - END

    $(document).on('click', '.tr_order', function() {
        if (!$(this).parent().hasClass('selected')) {
            $('tr').removeClass('selected');
            $(this).parent().addClass('selected');
            select_row_order = $(this).parent().attr('id');
        }
        else{
            $(this).parent().removeClass('selected');
            select_row_order = -1;
        }
    });

    $(document).on('click', '.delete_order', function() {
        select_row_order = -1 ;
        delete_order_id = $(this).parent().attr('id');
        order_list.splice(delete_order_id,1);
        drawOrderList();
    });
    //PAY TICKET
    function payTicket(){
      $('#payTicketModal').modal('show');
        returnBegin();
        $("#type_tip_hidden").val("1");
        loadTicketBegin();
    }

    function drawOrderList(){

        order_list_html="";

        total_price = 0;

        for(var i in order_list){
            var class_tr = "";

            service_price = order_list[i].service_price;

            if(order_list[i].promotion_discount > 0)
            {
                service_price = order_list[i].service_price -
                      ( (order_list[i].service_price * order_list[i].promotion_discount)/100 );
            }
            total_price = parseFloat(total_price) + parseFloat(service_price) ;

            if(select_row_order == i){ selected ="selected";}else{ selected ="";}
            if( i == 0) class_tr = "tr_selected"; else class_tr = "";

            order_list_html+= '<tr class="'+selected+" "+class_tr+'" id="'+i+'" ><td class="tr_order col-name"><i class="fa fa-user-circle-o"></i>  '+order_list[i].staff_name+'</td><td class="tr_order col-item"><i class="fa fa-check-circle-o"></i>  '+order_list[i].service_name+'</td><td class="tr_order col-price text-right">$'+service_price+'</td><td class="delete_order col-action text-center"><a href="#"><i class="fa fa-trash"></i></a></td></tr>';
          
        }
        total_price = parseFloat(total_price) + parseFloat($('#extra_number').val());

        document.getElementById("tableOrders").innerHTML = order_list_html;

        $('.total-price').text('$'+total_price);
        //SET AUTO CHOOSE 
        if(order_list.length ==1 )
          clickStaffService('tr_selected');
        saveExtra();
        saveOrderList();
    }

    function saveOrderList(){
        $.ajax({
          url: "{{route('set-session-payment')}}",
          type: "post",
          data:{data:order_list , action:"order_list_payment"},
          success: function(data){
          },
          error:function(){   
          }   
        });
    }

    function saveCurrentCustomer(){
        $.ajax({
          url: "{{route('set-session-payment')}}",
          type: "post",
          data:{data:current_customer , action:"current_customer_payment"},
          success: function(data){
          },
          error:function(){   
          }   
        });
    }

    function saveExtra(){
        $.ajax({
          url: "{{route('set-session-payment')}}",
          type: "post",
          data:{data:$('#extra_number').val() , action:"extra_payment"},
          success: function(data){
          },
          error:function(){   
          }   
        });
    }

    function clearSessionPayment(){
        $.ajax({
          url: "{{route('clear-session-payment')}}",
          type: "post",
          success: function(data){
          },
          error:function(){   
          }   
        });
    }
    //CLEAR VAR AND INPUT IN VIEW - BEGIN
    function clearView(){
        cateservice ="";
        service_list="";
        staff_list="";
        order_list= Array();
        staffs ="";
        selected_cateservice_id;
        selected_service_id;
        current_customer = 0 ;
        select_row_order= -1 ;
        total_price = 0;

        $('#customer_fullname').text("") ;
        $('#customer_phone').text("") ;
        $('#customer_email').text("") ;
        $('#customertag_name').text("") ;
        $('#customer_gender').text("") ;
        $('#customer_birthdate').text("") ;

        document.getElementById("tableOrders").innerHTML = "";
        $('#extra_number').val(0);

        clearSessionPayment();
        
        drawOrderList();  
    }
    //CLEAR VAR AND INPUT IN VIEW - END

    // ADD NEW TICKET
    $('.new_ticket').on('click', function(e) {
        clearView();
    });

    // Modal Add Client - BEGIN

    $('#dropdown_country a').on('click', function(e) {
      e.preventDefault();

      $('#current_country_selected').text($(this).attr('value')+" ") ;
      $('#country_code').val($(this).attr('value'));
    });

     $('input.dateofbirth').combodate({
        smartDays: true,
        customClass: "form-control form-control-sm"
     }); 

     if ($("input.checkGender")[0]) {
        $('input.checkGender').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });       
    } 

    $('#submit-add-client').on('click', function(e) {
        var validatorResult = $("#customer_form")[0].checkValidity();
        $("#customer_form").addClass('was-validated');
        if(!validatorResult){
            event.preventDefault();
            event.stopPropagation();           
            return;
        }
        
        $.ajax({
            type: 'post',
            url: '{{route("save-customer-payment")}}',
            data: $('#customer_form').serialize(),
            success: function (data){
                resetErrors();
                //Check ERROR ADD NEW FORM
                if(data['status'] == 'errors'){
                    $.each(data['errors'], function(i, v) {
                      var msg = '<label class="error invalid-feedback" for="'+i+'">'+v+'</label>';
                      $('input[name="' + i + '"], select[name="' + i + '"]').addClass('is-invalid').after(msg);
                    });
                    var keys = Object.keys(data['errors']);
                    $('input[name="'+keys[0]+'"]').focus();
                }else{
                    //ADD NEW SUCCESS
                    
                    //SET DATA TO CUSTOMER
                    $('#customer_fullname').text($("input#customer_fullname").val()) ;
                    $('#customer_phone').text($("input#customer_phone").val()) ;
                    $('#customer_email').text($("input#customer_email").val()) ;
                    $('#customertag_name').text($("input#customertag_name").val()) ;
                    $('#customer_gender').text($("input#customer_gender").val()) ;
                    $('#customer_birthdate').text($("input#customer_birthdate").val()) ;
                    
                    //SET CURRENT CUSTOMER
                    current_customer = data['customer_id'];
                    //PUT TO SESSION CURRENT CUSTOMER
                    saveCurrentCustomer();

                    toastr.success('Add new Client Success!');
                    $('#addClientModal').modal('hide');
                    document.getElementById("customer_form").reset();
                }
                 
            },
            error: function(data){
               toastr.error("Error");
            }
        });

    });

    function resetErrors() {
        $('form input, form select').removeClass('is-invalid');
        $('label.error').remove();
    }
    $('.select_client').click(function(){
        $('#selectClientModal').modal('show');
        cTable.draw();
    });
    
    cTable = $('#client-datatable').DataTable({
         dom: "ftip",
         processing: true,
         serverSide: true,
         columnDefs: [
          {
              "targets": 0, 
              "className": "text-left"
         },
         {
              "targets": 1,
              "className": "text-right",
         },
         {
              "targets": 2,
              "className": "text-left",
         }
         ],
         ajax:{ url:"{{ route('get-customers-payment')}}"},
             columns: [
                      { data: 'customer_fullname', name: 'customer_fullname' },
                      { data: 'customer_phone', name: 'customer_phone' },
                      { data: 'customer_email', name: 'customer_email' }
                   ]    
        }); 


    $('#client-datatable tbody').on('click', 'tr', function () {
        var data = cTable.row( this ).data();

        $('#customer_fullname').text(data['customer_fullname']) ;
        $('#customer_phone').text(data['customer_phone']) ;
        $('#customer_email').text(data['customer_email']) ;
        $('#customertag_name').text(data['customertag_name']) ;
        $('#customer_gender').text(data['customer_gender']) ;
        $('#customer_birthdate').text(data['customer_birthdate']) ;

        current_customer = data['customer_id'];
        saveCurrentCustomer();
        $('#selectClientModal').modal('hide');
    } );
    // Modal Add Client - END

    //Show Promotion List - BEGIN
    $('#btPro').click(function() {

          $('#promotionsModal').modal('show');

          var promotionList = "";

          $.ajax({
                url: '{{route('get-promotion-payment')}}',
                type: 'get',
                dataType: 'json',
                data: {
                },
                success:function(responses) {

                  $.each(responses, function(k, v) {

                    promotionList+='<div class="col-md-4"><div class="thumbnail" style="height:300px"><img src="{{config('app.url_file_view')}}/'+v.promotion_image+'" alt="Lights" style="width:100%"><div class="caption"><p>Name: '+v.promotion_name+'</p><p>Discount: '+v.promotion_discount+'%</p><p><span class="glyphicon glyphicon-calendar">: '+v.promotion_date_start+' To: '+v.promotion_date_end+'</p><p><span class="glyphicon glyphicon-time"></span>: '+v.promotion_time_start+' to '+v.promotion_time_end+'</p></div></div></div>';
                  });
                  $('.promotion-list').html(promotionList);
                }
          });
    });
    //Show Promotion List - END

    $('.booking_list').click(function(){

        $('#bookingModal').modal('show');

        bookingTable.draw();
    });

    bookingTable = $('#booking-datatable').DataTable({
         dom: "ftip",
         processing: true,
         serverSide: true,
         columnDefs: [
          {
              "targets": 0, 
              "className": "text-center"
         },
         {
              "targets": 1,
              "className": "text-left",
         },
         {
              "targets": 2,
              "className": "text-left",
         },
         {
              "targets": 3,
              "className": "text-center",
         }
         ],
         ajax:{ url:"{{ route('get-booking-list-payment')}}"},
             columns: [
                      { data: 'booking_id', name: 'booking_id' },
                      { data: 'booking_time_selected', name: 'booking_time_selected' },
                      { data: 'customer_fullname', name: 'customer_fullname' },
                      { data: 'booking_status' , name: 'booking_status'}
                   ]    
    });
    //CHECK PAY A TICKET
    var click_pay = '{{$click_pay}}';
    if(click_pay != ''){
      number_of_ticket = {{$number_of_ticket}};
      current_customer = '{{$booking_customer_id}}';
      ticket_no = '{{$booking_code}}';
      order_list = '<?php echo $service_arr ?>';
      payTicket();
      $.ajax({
        url: '{{route('get-ticket-today')}}',
        type: 'GET',
        dataType: 'html',
      })
      .done(function(data) {
        data = JSON.parse(data);
        ticket_list = data;
        ticket_list_html="";
        
        for(var i in data){
          order_list_in_ticket="";
          var style_background = '';
          var service_price = 0;

          $.each(data[i].order_list, function(index, val) {
            var service_name_html = "";
            $.each(val, function(index_service, val_service) {
              service_name_html += '-'+val_service['service_name']+'<br>';
              service_price += parseInt(val_service['service_price']);
            });
            order_list_in_ticket+='<p><b>'+index+'</b><br>'+service_name_html+'</p>';
          });

          if( ticket_no === data[i].booking_code){
            style_background = '#ffdf00';
            number_of_ticket = i;
          }
          if( ticket_no !== data[i].booking_code) style_background = '#959a9e';

          ticket_list_html+=`
          <div class="card ml-2 mb-1 " style="width: 12rem;" id="`+data[i].booking_code+`">
            <div class="card-header card-footer" style="background-color:`+style_background+`"  ticket_no_footer="`+data[i].booking_code+`" id="`+i+`">
              <div class="float-left"><b>#`+data[i].booking_code+ `</b></div>
              <div class="float-right">`+data[i].time+`</div>
            </div>
            <div class="card-body scrollbar scroll-style-1 card-footer" ticket_no_footer="`+data[i].booking_code+`" id="`+i+`" style="height:5.5rem;overflow-y: auto">
            `+order_list_in_ticket+`
            </div>
            <div class="col-md-12 text-danger text-right card-footer" style="position:absolute;bottom:0px;border:none"  ticket_no_footer="`+data[i].boooking_code+`" id="`+i+`">$`+service_price+`</div>
          </div>`;
        }
        $('.ticket_list').html(ticket_list_html);

        //GET MEMBERSHIP DISCOUNT
         $.ajax({
          url: '{{route('get-membership-point')}}',
          type: 'GET',
          dataType: 'html',
          data: {
            customer_id: current_customer,
            order_list: order_list
          },
        })
        .done(function(data) {
          membership_point = data;
          drawPaymentList();
          $(".membership_point").addClass('yellow');
        })
        .fail(function() {
          console.log("error");
        });
        loadInfoCustomerFooter();
        getCustomer(number_of_ticket);
      })
      .fail(function() {
        toastr.error('Error!');
      });
    }

    $('.pay_ticket').click(function(event) {
      $(".custom_btn_payticket").removeClass('btn_active');
        $('#payTicketModal').modal('show');
        $("#type_tip_hidden").val("1");
        $(".correct_list").css('display', 'none');
        $(".ticket_list").css('display', '');
        number_of_ticket = "";
        returnBegin();
        loadTicketBegin();
        if(number_of_ticket != ""){
          drawPaymentList();
        }
    });

    function loadTicketBegin(){
      $.ajax({
        url: '{{route('get-ticket-today')}}',
        type: 'GET',
        dataType: 'html',
      })
      .done(function(data) {
        data = JSON.parse(data);
        ticket_list = data;
        ticket_list_html="";
        
        for(var i in data){
          order_list_in_ticket="";
          var style_background = '';
          var service_price = 0;

          $.each(data[i].order_list, function(index, val) {
            var service_name_html = "";
            $.each(val, function(index_service, val_service) {
              service_name_html += '-'+val_service['service_name']+'<br>';
              service_price += parseInt(val_service['service_price']);
            });
            order_list_in_ticket+='<p><b>'+index+'</b><br>'+service_name_html+'</p>';
          });

          if( ticket_no === data[i].booking_code){
            style_background = '#ffdf00';
            number_of_ticket = i;
          }
          if( ticket_no !== data[i].booking_code) style_background = '#959a9e';

          ticket_list_html+=`
          <div class="card ml-2 mb-1 " style="width: 12rem;" id="`+data[i].booking_code+`">
            <div class="card-header card-footer" style="background-color:`+style_background+`"  ticket_no_footer="`+data[i].booking_code+`" id="`+i+`">
              <div class="float-left"><b>#`+data[i].booking_code+ `</b></div>
              <div class="float-right">`+data[i].time+`</div>
            </div>
            <div class="card-body scrollbar scroll-style-1 card-footer" ticket_no_footer="`+data[i].booking_code+`" id="`+i+`" style="height:5.5rem;overflow-y: auto">
            `+order_list_in_ticket+`
            </div>
            <div class="col-md-12 text-danger text-right card-footer" style="position:absolute;bottom:0px;border:none"  ticket_no_footer="`+data[i].boooking_code+`" id="`+i+`">$`+service_price+`</div>
          </div>`;
        }
        $('.ticket_list').html(ticket_list_html);
      })
      .fail(function() {
        toastr.error('Error!');
      });     
    }

    $('.save_ticket').click(function(e) {
        //VALIDATE SAVE TICKET - BEGIN
        if(current_customer==0){
          toastr.error("You dont't select client");
          return;
        }
        for(var i in order_list){
            //console.log(order_list);
            if(order_list[i].service_id =="" || order_list[i].service_id == null ){
              toastr.error("You dont't select Service");
              return;
            }
            if(order_list[i].staff_id =="" || order_list[i].staff_id == null){
              toastr.error("You don't select Staff");
              return;
            }
        }
        //UPDATE TICKET
        if(ticket_edit == 1){
          $.ajax({
            url: '{{route('update-ticket-payment')}}',
            type: 'GET',
            dataType: 'html',
            data: {
              booking_code: ticket_list[number_of_ticket].booking_code,
              order_list: order_list
            },
          })
          .done(function(data) {
            console.log(data);
            if(data == 0){
              toastr.error("Can't book this staff at this time!");
              return;
            }else{
              booking_id = 0;
              number_of_ticket = "";
              ticket_edit = "";
              //SAVE TO SESSION TICKET - BEGIN
              // saveTicketListToSession();
              addTurnStaff();
              loafStaffs();
              clearView();
            }
          })
          .fail(function() {
            // console.log("error");
          });
        }
        else{
          //CHECK TIME BOOKING TICKET
          $.ajax({
            url: '{{route('check-ticket')}}',
            type: 'GET',
            dataType: 'html',
            data: {
              order_list: order_list,
              customer_id: current_customer,
            },
          })
          .done(function(data) {
            console.log(data);
            if(data == 0){
              toastr.error("Can't book this staff at this time!");
              return;
            }else{
              booking_id = 0;
              number_of_ticket = "";
              ticket_edit = "";
              //SAVE TO SESSION TICKET - BEGIN
              // saveTicketListToSession();
              addTurnStaff();
              loafStaffs();
              clearView();
            }
          })
          .fail(function(xhr, ajaxOptions, thrownError){   
              toastr.error("Error save ticket");
              console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          });
        }
        console.log(ticket_list);
    });

  //ADD TURN WITH SERVICE
  function addTurnStaff(){
     $.ajax({
            url: '{{route('add-turn-with-cateservice')}}',
            type: 'GET',
            dataType: 'html',
            data: {order_list: order_list},
          })
          .done(function(data) {
            console.log(data);
          })
          .fail(function() {
            console.log("error");
          });
  }

   $('#booking-datatable tbody').on( 'click','tr', function () {
       var id =  bookingTable.row(this).data()['booking_id'] ;
       booking_id = id;
       $.ajax({
           url: '{{route('get-booking-form-payment')}}',
           type: 'GET',
           dataType: 'html',
           data: {id: id},
       })
       .done(function(data) {
        var data = JSON.parse(data);
        $("#customer_fullname").text(data['customer_fullname']);
        $("#customer_phone").text(data['customer_phone']);
        $("#customer_email").text(data['customer_email']);
        $("#customer_email").text(data['customer_email']);
        $("#customer_birthdate").text(data['customer_birthdate']);
        $("#customertag_name").text(data['customertag_name']);
        current_customer = data['customer_id'];
        $("#customer_id").val(current_customer);
        var customer_gender = "";
        if(data['customer_gender'] ===1){
            customer_gender = "Male";
        }if(data['customer_gender'] ===2){
            customer_gender = "Female";
        }if(data['customer_gender'] ===3){
            customer_gender = "Child";
        }
        $("#customer_gender").text(customer_gender);
        $('#bookingModal').modal('hide');
        order_list = data['list_service'];
        drawOrderList();
       })
       .fail(function() {
           console.log("error");
       });
    });
//TIP FUNCTION

//DELETE TICKET
$(document).on('click','#delete_ticket',function(){
  if(correct_ticket == ""){
    if(ticket_current != ""){
      $("#check-pass-box").modal('show');
      $(".delete_next").attr('onclick',"nextButton('void_ticket')");
      showCheckBox();
    }
  }else
    toastr.error('You can not void ticket!');
});

$(document).on('click','.cancel_next',function(){
  $("#check-pass-box").modal('hide');
  $("#pass_for_delete").val("");
  $(".notifi_after_check_pass").text("");
  $(".left_col").css('opacity', '1');
  $("#payTicketModal").css('opacity', '1');
});

$(document).on('click','.reason-div',function(){

  var reason = $(this).attr('reason');

  $.ajax({
    url: '{{route('void-ticket')}}',
    type: 'GET',
    dataType: 'html',
    data: {
      ticket_no: ticket_no,
      booking_reason: reason,
    },
  })
  .done(function(data) {
    if(data == 1){
      clearTicket();
      $(".reason-voided-ticket-div").hide(500);
    }
    console.log(data);
  })
  .fail(function() {
    // console.log("error");
    toastr.error('Void Ticket Error!');
  });
   loadTicketBegin();
});

   //SAVE LIST TICKET TO SESSION
// function saveTicketListToSession(){
//   $.ajax({
//           url: "{{route('set-session-payment')}}",
//           type: "post",
//           dataType: 'html',
//           data:{data:ticket_list , action:"ticket_list_payment"},
//           success: function(data){
//               clearView();
//               let number_ticket = 0;
//               if(ticket_list.length !== 0)
//                 for(var i in ticket_list){
//                   if(ticket_list[i].reason_delete === "empty" && ticket_list[i].payment == 0)
//                     number_ticket++;
//                 }
//               $('.pay_ticket').text('Pay Ticket ('+ number_ticket +')');
//           },
//           error:function(xhr, ajaxOptions, thrownError){   
//               alert("Error save ticket");
//               console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
//           }   
//         });
// }
//CLEAR TICKET WHEN DELETE
  function clearTicket(){
    ticket_current = "";
    $("#customer_id").val("");
    $(".ticket_no_top").text("");
    $('.giftcard_value').val("");
    $("#cash_back").text("");
    $(".cash_back_value").val("");
    $("#total_payment").text("");
    $('.cash_total').html("");
    $('.check').html("");
    $('.credit_card_top').html("");
    $('.debit_card_top').html("");
    $('.giftcard_top').html("");
    $('.giftcard_bottom').html("");
    $('.point_bottom').html("");
    $('.point_top').html("");
    $(".coupon_top").html("");
    $(".coupon_bottom").html("");
    $(".tips").html("");
    $(".tip_value").val("");
    $(".time_payment_top").html("");
    $(".time_payment_middle").html("");
    $(".ticket_no").text("");
    $(".ticket_value").val("");
    $(".sub_total").text("");
    $(".total_charge").text("");
    $(".balance_change").text("");
    $("#total_charge_hidden").val("");
    $(".payment_value").val("");
    $(".service_staff_list").html("");
    $('.staff_list').html("");
    $(".service_list_ticket").html("");
    $(".discount_top").html("");
    $(".info_customer_footer").html("");
    $(".product_list").html("");
    $(".payment_info_son").html("");
    $("#content-payment").html("");
    $(".membership_discount").html("");
  }
//EDIT ITEM
$("#edit_item").click(function(){
  var customer_id = current_customer;
  if(correct_ticket == ""){
    if(customer_id == ""){
      toastr.error('Choose Ticket before!');
      return;
    }
    $.ajax({
      url: '{{route('get-customer-for-edit-ticket')}}',
      type: 'GET',
      dataType: 'html',
      data: {customer_id: customer_id},
    })
    .done(function(data) {
      data = JSON.parse(data);
      $('#customer_fullname').text(data.customer_fullname) ;
      $('#customer_phone').text(data.customer_phone) ;
      $('#customer_email').text(data.customer_email) ;
      $('#customertag_name').text(data.customertag_name) ;
      $('#customer_gender').text(data.customer_gender) ;
      $('#customer_birthdate').text(data.customer_birthdate) ;
      current_customer = data.customer_id;
      saveCurrentCustomer();
      $('#payTicketModal').modal('hide');
      // console.log(data);
    })
    .fail(function(data) {
      data = JSON.parse(responseText);
      toastr.error(data.message);
    });
    //SET ORDER LIST
    order_list = [];
    $.each(service_list_payment, function(index, val) {
      $.each(val, function(index_service, val_service) {
        order = new Object();
        order.staff_id = val_service['worker_id'];
        order.staff_name = index;
        order.service_id = val_service['service_id'];
        order.service_name = val_service['service_name'];
        order.service_price = val_service['service_price'];
        order_list.push(order);
      });
    });
    ticket_edit = 1;
    drawOrderList();
  }else
    toastr.error('Can not edit this ticket. Check again!');
});

//SAVE TICKET TO DATABASE
  function saveTicketToDatabase(){
    $.ajax({
      url: '{{route('save-ticket-to-database')}}',
      type: 'POST',
      dataType: 'html',
      data: {
        tip_list: tip_list,
        staff_list_payment: staff_list_payment,
        payment_list: ticket_list[number_of_ticket], 
        ticket_current: ticket_current, 
        service_list_payment: service_list_payment,
        product_list:product_list,
        correct_ticket: correct_ticket,
        _token:'{{csrf_token()}}'},
    })
    .done(function(data) {
      // console.log(data);
      if(data == 0)
        toastr.error('Save Error!');
      if(data == 1){
        //DELETE TICKET
        correct_ticket = "";
        loadTicketBegin();
        // saveTicketListToSession();
        //RELOAD VIEW
        returnBegin()
        clearTicket();
        //RELOAD BOOKING LIST
        bookingTable.draw();
        toastr.success('Save Success!');
      }
    })
    .fail(function() {
      // console.log("error");
    });
    // console.log(ticket_list);
  }
//GET PAYMENT CURRENT
    $(document).on('click','.card-footer',function(e){

        membership_point = 0;
        ticket_no = $(this).attr('ticket_no_footer');
        ticket_current = ticket_no;
        number_of_ticket = $(this).attr('id');
        current_customer = ticket_list[number_of_ticket].customer_id;
        var order_list = ticket_list[number_of_ticket].order_list;
        if(correct_ticket == 1){
          tip_list = ticket_list[number_of_ticket].tip_list;
          product_list = ticket_list[number_of_ticket].product_list;
          drawPaymentList();
          loadCorrectTicketList();
        }
        else{
           $.ajax({
          url: '{{route('get-membership-point')}}',
          type: 'GET',
          dataType: 'html',
          data: {
            customer_id: current_customer,
            order_list: order_list
          },
        })
        .done(function(data) {
          membership_point = data;
          drawPaymentList();
          $(".membership_point").addClass('yellow');
        })
        .fail(function() {
          console.log("error");
        });
        console.log(ticket_list);
          loadTicketBegin();
        }
        loadInfoCustomerFooter();
        getCustomer(number_of_ticket);
    });
function loadInfoCustomerFooter(giftcode_point_earn = 0){
  var point_earn = 0;
  //LOAD POINT PRODUCT
  if(product_list.length != 0){
    for( var i in product_list){
      point_earn += product_list[i].product_amount * parseFloat(product_list[i].product_point);
    }
  }
  //ADD POINT FORM GIFTCARD
  point_earn += parseFloat(giftcode_point_earn);
  console.log(ticket_list[number_of_ticket]);
  ticket_list[number_of_ticket].point_earn = point_earn;

  $.ajax({
          url: '{{route('get_customer_info_payment')}}',
          type: 'GET',
          dataType: 'html',
          data: {customer_id: current_customer},
    })
    .done(function(data) {
      if(data == 0)
        toastr.error('Processing Error');
      else{
        data = JSON.parse(data);

        $(".info_customer_footer").html(`
          <div style="margin-left:8px;background-color:#1268c5;color:#fff;width:100%;padding:5px">
              <div style="width:50%;float:left;margin-bottom:5px">`+data.customer_fullname.toUpperCase()+`</div>
              <div style="width:50%;float:right;margin-bottom:5px">
                <div style="float:left;text-align:right;width:70%;padding-right:3px">Points earned</div>
                <div style="float:left;width:30%;background-color:#fff;color:#000;text-align:center">`+point_earn+`</div>
              </div>
              <div style="width:50%">
                <div style="float:left;width:50%;padding-right:3px">Redeemed</div>
                <div style="float:left;width:30%;background-color:#fff;color:#000;text-align:center">0.00</div>
              </div>
              <div style="width:50%;float:right">
                <div style="float:left;text-align:right;width:70%;padding-right:3px">Balance</div>
                <div style="float:left;width:30%;background-color:#fff;color:#000;text-align:center">`+data.customer_point+`</div>
              </div>
          </div>`);
      }
    })
    .fail(function(xhr, ajaxOptions, thrownError) {
      toastr.error("get customer error!");
     console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    });
}  
function getCustomer(number_of_ticket){
      var customer_id =  current_customer;
      if(customer_id != ""){
        $.ajax({
          url: '{{route('get_customer_info_payment')}}',
          type: 'GET',
          dataType: 'html',
          data: {customer_id: customer_id},
        })
        .done(function(data) {
          if(data == 0)
            toastr.error('Processing Error');
          else{
            data = JSON.parse(data);
            // console.log(data);

            //SET POINT AND REWARD
            $(".total_point").text(data.customer_point_total);
            $(".total_reward").text(data.total_amount_after_convert);
            $(".balance_point").text(data.customer_point);
            $(".balance_reward").text(data.balance_amount_after_convert);
            ticket_list[number_of_ticket].total_point = data.customer_point_totaL;
            ticket_list[number_of_ticket].balance_point = data.customer_point;

            if(data.description == null) data.description = "";
            //SHOW STAR RATING
              var start_html = "";
              var star = '<span class="glyphicon glyphicon-star" style="color:#ffdf00"></span>';
              var star_empty = '<span class="glyphicon glyphicon-star-empty"></span>';
              for (var i = 1; i <= 5; i++) {
                if( i <= data.rating)
                  start_html += star;
                if( i > data.rating )
                  start_html += star_empty;
              }
            $(".liststaffs_payment").html(`
                <hr style="border:.5px solid #6b6e71;margin:0px">
                <div class="col-md-4">
                  <p><b>`+data.customer_fullname.toUpperCase()+`</b><br>
                  <b>Membership level: </b>`+data.membership+`<br>
                  <b>First visit: </b>`+data.first_visit+`<br>
                  <b>Last visit: </b>`+data.last_visit+` <br>
                  </p>
                </div>
                <div class="col-md-4">
                  <p></p>
                  <p><b>Last review: </b>`+start_html+`<br>
                  <b>Last staff: </b>`+data.last_staff_name+`<br>
                  <b>Visit count: </b>12 <br></p>
                </div>
                <div class="col-md-4">
                  <p><b>Total spend: </b>$`+data.total_price+` <br>
                  <b>Current reward points: </b>200 <br>
                  <b>Reward earned value: </b>$`+data.total_amount_after_convert+` <br>
                  <b>NOTE: </b><span style="color:red">`+data.description+`</span></p>
                </div>`);
          }
        })
        .fail(function() {
          toastr.error("error");
        });
      }
    }
  function getPaymentForCorrectTicket(){
   drawPaymentList();
  }
//DRAW CURRENT PAYMENT
    function drawPaymentList(){

        staff_list_payment = [];
        var service_list_html = "";
        var staff_list_html = "";
        var service_staff_list = "";
        var tip_html = "";
        var tip = "";
        var tips_html = "";
        var coupon_html ="";
        var use_point_html = "";
        var total_point_html = "";
        var amount_credit_html = "";
        var amount_debit_html = "";
        var check_html = "";
        var cash_html = "";
        var giftcard_html = "";
        var total_price = 0;
        var total_must_payment = 0;
        var total_payment,cash_back = 0;
        var time_payment_top_html,time_payment_middle_html  = "";
        var product_list_html = "";
        var product_total_price = 0;
        var discount_html_top = '';
        var discount_html_staff = "";
        var total_charge = 0;
        var membership_html = "";
    
            if(ticket_list[number_of_ticket].tip != 0){
                tips_html = '<div style="float:left;width:70%;" class="tip">Tips</div><div style="float:right;width:30%;text-align:right;" class="ng-binding tip">'+parseFloat(ticket_list[number_of_ticket].tip).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';

            }
            ticket_list[number_of_ticket].membership_point = membership_point;
            if(ticket_list[number_of_ticket].membership_point != 0){

                membership_html = '<div style="float:left;width:70%;text-align:right" class="membership_point">Membership Discount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding membership_point">-'+parseFloat(ticket_list[number_of_ticket].membership_point).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';

            }
            if(ticket_list[number_of_ticket].coupon_amount != 0){
                coupon_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right" class="coupon_bill">Coupon</div><div style="float:right;width:30%;text-align:right;" class="ng-binding coupon_bill">'+parseFloat(ticket_list[number_of_ticket].coupon_amount).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';
            }
            if(ticket_list[number_of_ticket].use_amount != 0){
                use_point_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right" class="point">Use Point</div><div style="float:right;width:30%;text-align:right;" class="ng-binding point">'+parseFloat(ticket_list[number_of_ticket].use_amount).toFixed(0)+'</div><div style="clear:both;display:block;"></div>';
            }
            if(ticket_list[number_of_ticket].balance_point != 0){
                total_point_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right">Total Point</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+parseFloat(ticket_list[number_of_ticket].balance_point).toFixed(0)+'</div><div style="clear:both;display:block;"></div>';

            }
            if(ticket_list[number_of_ticket].giftcard_pay != 0){
                giftcard_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right" class="giftcard">Gift Card Use</div><div style="float:right;width:30%;text-align:right;" class="ng-binding giftcard">'+parseFloat(ticket_list[number_of_ticket].giftcard_pay).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';

            }
            if(ticket_list[number_of_ticket].credit_amount != 0){
                amount_credit_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right" class="credit_bill">Credit Card Amount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding credit_bill">'+parseFloat(ticket_list[number_of_ticket].credit_amount).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';
            }
            if(ticket_list[number_of_ticket].debit_amount != 0){
                amount_debit_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right" class="debit_bill">Debit Card Amount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding debit_bill">'+parseFloat(ticket_list[number_of_ticket].debit_amount).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';
            }
            if(ticket_list[number_of_ticket].check != 0){
                check_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right">Check</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+parseFloat(ticket_list[number_of_ticket].check).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';

            }
            if(ticket_list[number_of_ticket].cash != 0){
                cash_html = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right" class="cash_bill">Cash</div><div style="float:right;width:30%;text-align:right;" class="ng-binding cash_bill">'+parseFloat(ticket_list[number_of_ticket].cash).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';
            }
            if(ticket_list[number_of_ticket].date != "" && ticket_list[number_of_ticket].time != ""){
              time_payment_top_html = '<div style="float:left;width:30%;" class=""><b>#'+ticket_list[number_of_ticket].booking_id+'</b></div><div style="float:left;width:40%;text-align:right">'+ticket_list[number_of_ticket].date+'</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+ticket_list[number_of_ticket].time+'</div><div style="clear:both;display:block;"></div>';

              time_payment_middle_html = '<div style="float:left;width:30%;" class="">#'+ticket_list[number_of_ticket].booking_id+'</div><div style="float:left;width:40%;text-align:right">'+ticket_list[number_of_ticket].date+'</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+ticket_list[number_of_ticket].time+'</div><div style="clear:both;display:block;"></div>';
            }
            //SET PRODUCT FOR PAYMENT
            if(product_list.length !== 0){
              $.each(product_list, function(index, val) {
                if(val['product_amount'] != 0){
                  product_list_html += `<div style="float:left;width:60%;" class="product">`+val['product_name']+`</div><div style="float:left;width:10%;text-align:right" class="product">`+val['product_amount']+`</div><div style="float:right;width:30%;text-align:right;" class="ng-binding product">`+val['product_price']+`</div><div style="clear:both;display:block;"></div>`;
                  product_total_price += parseFloat(val['product_price']);
                }
              });
              $(".product_list").html(product_list_html);
            }
             //SET STAFF LIST PAYMENT && SERVICE LIST PAYMENT
            service_list_payment = ticket_list[number_of_ticket].order_list;
            $.each(service_list_payment, function(index, val) {
              staff_list_payment.push(index);
            });

            //SET SERVICE LIST TOP
            var total_service_price = 0;
            $.each(service_list_payment, function(index, val) {
              $.each(val, function(index_service, val_service) {
                  service_list_html += '<div style="float:left;width:70%;">'+val_service['service_name']+'</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+parseFloat(val_service['service_price']).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';
                 total_service_price +=  parseFloat(val_service['service_price']);
              });
                
            });
            $(".service_list_ticket").html(service_list_html);

            //TOTAL SERVICE PRICE
            ticket_list[number_of_ticket].total_price = total_service_price;

            total_price = ( parseFloat(ticket_list[number_of_ticket].total_price)
                            +parseFloat(ticket_list[number_of_ticket].tip)
                            +parseFloat(product_total_price) ).toFixed(2);

             //SET DISCOUNT TICKET
            ticket_list[number_of_ticket].discount_amount = 0;
            if( discount_type === 0){
              ticket_list[number_of_ticket].discount_amount = discount_amount;
            }
            if( discount_type === 1){
              ticket_list[number_of_ticket].discount_amount = discount_amount*total_price/100;
            }

            total_must_payment = ( parseFloat(ticket_list[number_of_ticket].total_price)
                                +parseFloat(ticket_list[number_of_ticket].use_point)
                                +parseFloat(ticket_list[number_of_ticket].coupon_amount)
                                +parseFloat(ticket_list[number_of_ticket].giftcard_pay) ).toFixed(2);

            total_payment = ( parseFloat(ticket_list[number_of_ticket].credit_amount)
                            +parseFloat(ticket_list[number_of_ticket].check)
                            +parseFloat(ticket_list[number_of_ticket].cash)
                            +parseFloat(ticket_list[number_of_ticket].debit_amount)
                            +parseFloat(ticket_list[number_of_ticket].coupon_amount)
                            +parseFloat(ticket_list[number_of_ticket].giftcard_pay)
                            +parseInt(ticket_list[number_of_ticket].use_amount) ).toFixed(2);

            cash_back = ( parseFloat(total_payment)
                         -parseFloat(total_price)
                         +parseFloat(ticket_list[number_of_ticket].discount_amount)
                         +parseFloat(ticket_list[number_of_ticket].membership_point) ).toFixed(2);

            total_charge = ( parseFloat(total_price)
                            -ticket_list[number_of_ticket].discount_amount
                            -parseFloat(ticket_list[number_of_ticket].membership_point)
                            ).toFixed(2);

            ticket_list[number_of_ticket].total_charge = total_charge;
            ticket_list[number_of_ticket].cash_back = cash_back;
            ticket_list[number_of_ticket].total_payment = total_payment;
            ticket_list[number_of_ticket].customer_id = current_customer;

            //VIEW DISCOUNT IN BILL
            if( ticket_list[number_of_ticket].discount_amount > 0){

              if( discount_station == 0 ){

                discount_html_top = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right;" class="discount">Ticket Discount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding discount">-'+(ticket_list[number_of_ticket].discount_amount).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';

              }
              if( discount_station == 2 ){

                    discount_html_top = '<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right;" class="discount">Ticket Discount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding discount">-'+(ticket_list[number_of_ticket].discount_amount/(staff_list_payment.length+1) ).toFixed(2)+'</div><div style="clear:both;display:block;"></div>'
                  }

              if( discount_station == 1 )
                discount_html_top = "";

              $(".discount_top").html(discount_html_top);
            }
            $('.giftcard_value').val(parseFloat(ticket_list[number_of_ticket].giftcard_price));
            $("#cash_back").text(parseFloat(cash_back).toFixed(2) );
            $(".cash_back_value").val(parseFloat(cash_back).toFixed(2) );
            $("#total_payment").text(total_payment);
            $('.cash_total').html(cash_html);
            $('.check').html(check_html);
            $('.credit_card_top').html(amount_credit_html);
            $('.debit_card_top').html(amount_debit_html);
            $('.giftcard_top').html(giftcard_html);
            $('.giftcard_bottom').html(giftcard_html);
            $('.point_bottom').html(total_point_html+use_point_html);
            $('.point_top').html(total_point_html+use_point_html);
            $(".coupon_top").html(coupon_html);
            $(".coupon_bottom").html(coupon_html);
            $(".tips").html(tips_html);
            $(".membership_discount").html(membership_html);
            $(".tip_value").val(parseFloat(ticket_list[number_of_ticket].tip) );
            $(".time_payment_top").html(time_payment_top_html);
            $(".time_payment_middle").html(time_payment_middle_html);
            $(".ticket_no").text(ticket_list[number_of_ticket].ticket_no);
            $(".ticket_no_top").text("#"+ticket_list[number_of_ticket].ticket_no);
            $(".ticket_value").val(ticket_list[number_of_ticket].ticket_no);
            $(".sub_total").text(parseFloat(ticket_list[number_of_ticket].total_price).toFixed(2));
            $(".total_charge").text(total_charge);
            $(".balance_change").text(parseFloat(total_payment).toFixed(2) );
            $("#total_charge_hidden").val(ticket_list[number_of_ticket].total_price);
            $(".payment_value").val(parseFloat(ticket_list[number_of_ticket].total_charge).toFixed(2) );
            
        //SET STAFF AND TIP
        $.each(staff_list_payment, function(index, val) {

            if(tip_list.length !== 0 && tip_list[0] != 0){

                    tip = parseFloat(tip_list[index]).toFixed(2);
                }
            staff_list_html += '<div class="row form-group"><label class="col-xs-5 col-sm-5 col-md-5">'+val+'($)</label><div class="col-xs-7 col-sm-7 col-md-7  no-padding"><input type="text" id="'+index+'"  value="'+tip+'" class="form-control form-control-sm tip_staff"></div></div>';
        });
        $('.staff_list').html(staff_list_html);
       
        //SET SERVICE LIST BOTTOM
        var total = 0;
        if(staff_list_payment.length === 1){
            $.each(staff_list_payment, function(index, staff_list_val) {

                if(tip_list.length !== 0 && tip_list[0] != 0){

                    tip_html = '<div style="float:left;width:70%;" class="tip">Tip</div><div style="float:right;width:30%;text-align:right;" class="ng-binding tip">'+parseFloat(tip_list[index]).toFixed(2)+'</div>';
                    total += parseFloat(tip_list[index]);
                }

                if( ticket_list[number_of_ticket].discount_amount !== 0){
                  if( discount_station == 1 ){
                    discount_html_staff = 
                    '<div style="float:left;width:70%;" class="discount">Ticket Discount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding discount">-'+(ticket_list[number_of_ticket].discount_amount).toFixed(2)+'</div>';
                    total -= parseInt((ticket_list[number_of_ticket].discount_amount));
                  }
                  if( discount_station == 2 ){

                    discount_html_staff = 
                    '<div style="float:left;width:70%;" class="discount">Ticket Discount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding discount">-'+(ticket_list[number_of_ticket].discount_amount/(staff_list_payment.length+1)).toFixed(2)+'</div>';
                    total -= parseFloat( (ticket_list[number_of_ticket].discount_amount/(staff_list_payment.length+1).toFixed(2)) );
                  }
                }
                total += total_service_price;

                service_staff_list = '<div style="float:left;width:70%;"><i>'+staff_list_val+'</i></div><div style="float:right;width:30%;text-align:right;" class="ng-binding"></div>'+tip_html+'<div style="clear:both;display:block;"></div><div style="border-top:1px dashed #e7eaec"></div>'+service_list_html+discount_html_staff+'<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right">Total</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+total.toFixed(2)+'</div><div style="clear:both;display:block;"></div>';
                
            });
        }else{
            $.each(staff_list_payment, function(index_staff, staff_list_val) {
                var total = 0;
                if(tip_list.length !== 0 && tip_list[0] != 0){
                    tip_html = '<div style="float:left;width:70%;" class="tip">Tip</div><div style="float:right;width:30%;text-align:right;" class="ng-binding tip">'+parseFloat(tip_list[index_staff]).toFixed(2)+'</div>';
                    total +=  parseFloat(tip_list[index_staff]) ;
                }
                service_staff_list +='<div style="float:left;width:70%;"><i>'+staff_list_val+'</i></div><div style="float:right;width:30%;text-align:right;" class="ng-binding"></div>'+tip_html+'<div style="clear:both;display:block;"></div><div style="border-top:1px dashed #e7eaec"></div>';

                $.each(service_list_payment, function(index, val) {
                  var service_price = 0;

                    if(staff_list_val === index){

                      $.each(val, function(index_service, val_service) {

                        service_staff_list += '<div style="float:left;width:70%;">'+val_service['service_name']+'</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+parseFloat(val_service['service_price']).toFixed(2)+'</div><div style="clear:both;display:block;"></div>';
                        service_price += parseInt( parseFloat(val_service['service_price']).toFixed(2) );

                      total += parseInt(service_price);
                      });
                    }
                });
                if( ticket_list[number_of_ticket].discount_amount !== 0){

                  if( discount_station == 1 ){

                    discount_html_staff = 
                    '<div style="float:left;width:70%" class="discount">Ticket Discount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding discount">-'+(ticket_list[number_of_ticket].discount_amount/staff_list_payment.length).toFixed(2)+'</div>';
                    total -= parseFloat((ticket_list[number_of_ticket].discount_amount));
                  }
                  if( discount_station == 2 ){

                    discount_html_staff = 
                    '<div style="float:left;width:70%;" class="discount">Ticket Discount</div><div style="float:right;width:30%;text-align:right;" class="ng-binding discount">-'+(ticket_list[number_of_ticket].discount_amount/(staff_list_payment.length+1) ).toFixed(2)+'</div>';
                    total -= parseFloat((ticket_list[number_of_ticket].discount_amount/(staff_list_payment.length+1) ).toFixed(2));
                  }

                }
                service_staff_list += discount_html_staff+'<div style="float:left;width:30%;"></div><div style="float:left;width:40%;text-align:right">Total</div><div style="float:right;width:30%;text-align:right;" class="ng-binding">'+total.toFixed(2)+'</div><div style="clear:both;display:block;"></div><hr>';
            });
        }
        $(".service_staff_list").html(service_staff_list);
        console.log(ticket_list[number_of_ticket]);
    }
//END DRAW PAYMENT CURRENT

//ENTER AND CHANGE TIP INPUT
    $(document).on('keyup','#total_tip',function(){

        tip_list = [];
        ratio_tip = [];

        var total_tip = $(this).val();

        var total_price = $("#total_charge_hidden").val();

        var total_tip_change = "";

        var type_tip = $("#type_tip_hidden").val();

        if(total_tip != ""){

            //IF TYPE TIP FOLLOW PERCENT
            if(type_tip == 3){

                total_tip_change = ((parseFloat(total_tip)*parseFloat(total_price))/100).toFixed(2);

                    $.each(staff_list_payment, function(index_staff, staff_list_val){

                        var total_price_service = 0;

                        $.each(service_list_payment, function(index, val) {
                          var service_price = 0;

                            if(staff_list_val === index){
                              $.each(val, function(index_service, val_service) {

                                if(val['promotion_discount'] > 0)
                                {
                                  service_price += val_service['service_price'] -
                                       ( (val_service['service_price'] * val['promotion_discount'])/100 );
                                }else{
                                  service_price += val_service['service_price'];
                                }
                              });
                                total_price_service = parseFloat(total_price_service)+parseFloat(service_price);
                        }
                    });
                        tip_average = ((parseFloat(total_tip)*parseFloat(total_price_service))/100).toFixed(2);
                        ratio_tip.push( parseFloat(tip_average)/parseFloat(total_tip_change) );

                        tip_list.push(tip_average);

                        });
                total_tip = total_tip_change;
                }
                //IF TIP SERVICE
                if(type_tip == 2){

                  var service_amount = 0;
                  //SERVICE AMOUNT
                  $.each(service_list_payment, function(index, val) {
                    $.each(val, function(index_service, val_service) {
                      service_amount++;
                    });
                  });
                  //SET TIP FOR EACH STAFF
                  $.each(staff_list_payment, function(index_staff, staff_list_val){

                    var total_price_service = 0;

                    var stt = 0;

                    $.each(service_list_payment, function(index, val) {
                      if(staff_list_val === index){
                        $.each(val, function(index_service, val_service) {
                          stt++;
                        });
                      }
                    });
                    tip_average = (parseFloat(total_tip)/service_amount*stt).toFixed(2);
                    ratio_tip.push( parseFloat(tip_average)/parseFloat(total_tip) );

                    tip_list.push(tip_average);
                  });
                }
                //IF TIP EVENT
                if(type_tip == 1){

                    tip_average = total_tip/(staff_list_payment.length);
                    total_price = parseFloat(total_price)+parseFloat(total_tip);

                    $.each(staff_list_payment, function(index, val) {

                         ratio_tip.push( parseFloat(tip_average)/parseFloat(total_tip) );
                         tip_list.push(tip_average);
                    });
                }
                if(type_tip === ""){
                    total_tip = 0;
                }
        }else
        {
            total_tip = 0;
        }
        //ADD TIP IN CURRENT TICKET
        ticket_list[number_of_ticket].tip = total_tip;
        ticket_list[number_of_ticket].tip_list = tip_list;
        drawPaymentList();
        $(".tip").addClass('yellow');
     });
//EDIT TIP WITH STAFF
$(document).on('keyup','.tip_staff',function(){
  var total_price = $("#total_charge_hidden").val();
  var type_tip = $("#type_tip_hidden").val();
  var total_tip = $("#total_tip").val();
  var key = $(this).attr('id');
  var current_tip = $(this).val();
  //total ratio expect current
  var total_expect_current = 0;

  if( total_tip != "" ){

    if(type_tip == 3)
      total_tip = ((parseFloat(total_tip)*parseFloat(total_price))/100).toFixed(2);

    if( current_tip == "")
      current_tip = 0;
    if( parseFloat(current_tip) > parseFloat(total_tip) )
      current_tip = total_tip;

    for( var i in ratio_tip){
      if( i !== key){
        total_expect_current += ratio_tip[i];
      }
    }
    for( var i in tip_list){
      if( i !== key ){
        tip_list[i] = ( (ratio_tip[i]/total_expect_current)*( parseFloat(total_tip)-parseFloat(current_tip))).toFixed(2);
      }
      tip_list[key] = current_tip;
    }
  }
  drawPaymentList();
});

//CHOOSE PERCENT BUTTON
     $('.percent_button').on('click',function(){

        removeClass('event_button');
        removeClass('service_button');

        tip_list = [];
        ratio_tip = [];

        var total_tip = $('#total_tip').val();

        var total_price = $("#total_charge_hidden").val();

        $(this).toggleClass('btn-danger btn_primary');

        $(".type_of_tip").text('%');

        if($(this).hasClass('btn-danger')){

            $('#type_tip_hidden').val('3');

            if( total_tip != ""){
              //GET TOTAL PRICE
              total_price = 0;
              $.each(service_list_payment, function(index, val) {
                  $.each(val, function(index_service, val_service) {
                    var service_price = 0;
                    if(val['promotion_discount'] > 0)
                    {
                      total_price += val_service['service_price'] -
                            ( (val_service['service_price'] * val['promotion_discount'])/100 );
                    }else{
                      total_price += val_service['service_price'];
                    }
                  });
              });
              total_tip_change = ((parseFloat(total_tip)*parseFloat(total_price))/100).toFixed(2);

                  $.each(staff_list_payment, function(index_staff, staff_list_val){

                      var total_price_service = 0;

                      $.each(service_list_payment, function(index, val) {

                        if(staff_list_val === index){
                            var service_price = 0;

                          $.each(val, function(index_service, val_service) {

                            if(val['promotion_discount'] > 0)
                            {
                              service_price += val_service['service_price'] -
                                    ( (val_service['service_price'] * val['promotion_discount'])/100 );
                            }else{
                              service_price += val_service['service_price'];
                            }
                          });
                          total_price_service += parseFloat(service_price);
                        }
                      });
                      tip_average = ((parseFloat(total_tip)*parseFloat(total_price_service))/100).toFixed(2);
                      ratio_tip.push( parseFloat(tip_average)/parseFloat(total_tip_change) );
                      tip_list.push(tip_average);
                  });

              total_tip = total_tip_change;
            }
        }else
        {
            $('#type_tip_hidden').val('');
            total_tip = 0;
        }
        //SET TOTAL TIP FOR CURRENT TICKET
        ticket_list[number_of_ticket].tip_list = tip_list;
        ticket_list[number_of_ticket].tip = total_tip;
        drawPaymentList();
        $(".tip").addClass('yellow');
        
     });
//SELECT EVENT TIP BUTTON
     $(".event_button").on('click',function(){

        removeClass('percent_button');
        removeClass('service_button');
        tip_list = [];
        ratio_tip = [];
        var total_tip = $('#total_tip').val();
        var total_price = $("#total_charge_hidden").val();
        var total_tip_change = "";

        $(this).toggleClass('btn_primary btn-danger');

        $(".type_of_tip").text('$');

        if($(this).hasClass('btn-danger')){

            $('#type_tip_hidden').val('1');

            if(total_tip != ""){

                tip_average = total_tip/(staff_list_payment.length);

                $.each(staff_list_payment, function(index, val) {

                     tip_list.push(tip_average);
                     ratio_tip.push( parseFloat(tip_average)/parseFloat(total_tip) );
                });
            }
        }else
        {
            $('#type_tip_hidden').val('');
            total_tip = 0;
        }
        //SET TIP FOR CURRENT TICKET
        ticket_list[number_of_ticket].tip_list = total_tip;
        ticket_list[number_of_ticket].tip = total_tip;
        drawPaymentList();
        $(".tip").addClass('yellow');
    });
//SELECT SERVICE TIP BUTTON
     $(".service_button").on('click',function(){
        removeClass('percent_button');
        removeClass('event_button');
        tip_list = [];
        ratio_tip = [];
        var total_tip = $('#total_tip').val();
        var total_price = $("#total_charge_hidden").val();
        $(this).toggleClass('btn-danger btn_primary');

        $(".type_of_tip").text('$');

        if($(this).hasClass('btn-danger')){
            $('#type_tip_hidden').val('2');
            if(total_tip != ""){
                var service_amount = 0;
                //GET LENGTH SERVICE
                $.each(service_list_payment, function(index, val) {
                      $.each(val, function(index_service, val_service) {
                        service_amount++;
                      });
                });
                $.each(staff_list_payment, function(index_staff, staff_list_val){
                    var total_price_service = 0;
                    var stt = 0;
                    $.each(service_list_payment, function(index, val) {
                      if(staff_list_val === index){
                        $.each(val, function(index_service, val_service) {
                          stt++;
                        });
                      }
                    });
                    tip_average = ( parseFloat(total_tip)/service_amount*stt ).toFixed(2);
                    ratio_tip.push( parseFloat(tip_average)/parseFloat(total_tip) );
                    tip_list.push(tip_average);
                });
            }
        }else
        {
            $('#type_tip_hidden').val('');
            total_tip = 0;
        }
        //SET TOTAL TIP FOR CURRENT TICKET
        ticket_list[number_of_ticket].tip_list = tip_list;
        ticket_list[number_of_ticket].tip = total_tip;
        drawPaymentList();
        $(".tip").addClass('yellow');
    });

//FUNCTION TO CHANGE STYLE BUTTON WHEN SELECT ANOTHER
     function removeClass(class_element){
        if($('.'+class_element).hasClass('btn-danger')){
            $('.'+class_element).removeClass('btn-danger');
            $('.'+class_element).addClass('btn_primary');
        }
     }

//FUNCTION TO ALLOW ONLY NUMERIC
    function isNumberKey(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
      return true;
    }  

//ENTER COUPON CODE
$("#coupon_code").keyup(function(event) {

    var coupon_code =  $(this).val();

    var total_price = $('#total_charge_hidden').val();

    if(coupon_code.length >= 5){
        $.ajax({
            url: '{{route('get-point-from-coupon')}}',
            type: 'GET',
            dataType: 'html',
            data: {
              coupon_code: coupon_code,
              total_price: total_price,
              service_list_payment: service_list_payment,
            },
        })
        .done(function(data) {
            var data = JSON.parse(data);
            $("#coupon_cash").val(data['coupon_cash']);

            if(ticket_list[number_of_ticket].total_charge >= parseFloat(data['coupon_cash']) ){
              ticket_list[number_of_ticket].coupon_amount = data['coupon_cash'];
              ticket_list[number_of_ticket].coupon_balance = 0;
            }
            else{
              ticket_list[number_of_ticket].coupon_amount = ticket_list[number_of_ticket].total_charge;
              ticket_list[number_of_ticket].coupon_balance = parseFloat(data['coupon_cash']) - parseFloat(ticket_list[number_of_ticket].total_charge);
            }
            ticket_list[number_of_ticket].coupon_code = coupon_code;
            $(".coupon_balance").val(ticket_list[number_of_ticket].coupon_balance);
            drawPaymentList();
            $(".coupon_bill").addClass('yellow');
            if(data['message'])
                toastr.success(data['message']);
        })
        .fail(function() {
            // console.log("error");
        });
    }
});
    $("#use_point").on('keyup',function(){

        var use_point = $(this).val();

        if(use_point > ticket_list[number_of_ticket].balance_point){
            $(this).val(ticket_list[number_of_ticket].balance_point);
            use_point = ticket_list[number_of_ticket].balance_point;
        }

        else
            if(use_point === ""){
                $(this).val(0);
                use_point = 0;
            }

        ticket_list[number_of_ticket].use_point = use_point;
        $(this).val(parseInt($(this).val()));

        if(use_point > 0 || use_point <= ticket_list[number_of_ticket].balance_point){
          $.ajax({
            url: '{{route('convert-use-point-to-amount')}}',
            type: 'GET',
            dataType: 'html',
            data: {user_point: use_point},
          })
          .done(function(data) {
            ticket_list[number_of_ticket].use_amount = data;
            $(".use_amount").val('$'+data);
            // alert(data);
          })
          .fail(function() {
            console.log("error");
          });
        }
        drawPaymentList();
      $(".point").addClass('yellow');
    });

    $("#coupon_button").click(function(){
        
      var customer_id =  $('#customer_id').val();
      $.ajax({
          url: '{{route('get-point-from-payment')}}',
          type: 'GET',
          dataType: 'html',
          data: {customer_id: customer_id},
      })
      .done(function(data) {

          $("#point_total").val(data);

          ticket_list[number_of_ticket].total_point = data;
      })
      .fail(function() {
          toastr.error("Processing error!");
      });
    });
    $("#giftcard_code").keyup(function(){

        var customer_id =  $('#customer_id').val();

        var giftcard_code = $(this).val();

        if(giftcard_code.length >= 5){
            $.ajax({
                url: '{{route('get-giftcard-code')}}',
                type: 'GET',
                dataType: 'html',
                data: {giftcard_code: giftcard_code,customer_id: customer_id},
            })
            .done(function(data) {
                data = JSON.parse(data);
                $('#giftcard_price').val("$"+data['giftcard_price']);

                ticket_list[number_of_ticket].giftcard_price = data['giftcard_price'];
                ticket_list[number_of_ticket].giftcard_code = giftcard_code;
                loadGiftcode(data['giftcard_price']);
                if(data['message'])
                    toastr.success(data['message']);
            })
            .fail(function(xhr, ajaxOptions, thrownError) {
              toastr.error('Change Service  Error!');
              // console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            });
        }
    });
    $("#giftcard_pay").on('keyup',function(){
        var giftcard_pay = $(this).val();
          if(giftcard_pay >  ticket_list[number_of_ticket].giftcard_price){

              $(this).val( ticket_list[number_of_ticket].giftcard_price);

              giftcard_pay =  ticket_list[number_of_ticket].giftcard_price;
          }
          else
              if(giftcard_pay === ""){

                  $(this).val("");

                  giftcard_pay = 0;
              }
          ticket_list[number_of_ticket].giftcard_pay = giftcard_pay;
          drawPaymentList();
    });
    $("#amount_credit").keyup(function(event) {

        var credit_amount = $(this).val();
        var credit_number = $("#card_number").val();
        if(credit_number == "")
          toastr.error('Enter card number before!');
        else{
          if(credit_amount == ""){
            credit_amount = 0;
          }
        ticket_list[number_of_ticket].credit_number = credit_number;
        ticket_list[number_of_ticket].credit_amount = credit_amount;
        }
        drawPaymentList();
        $(".credit_bill").addClass('yellow');
    });
    
    $("#amount_debit").keyup(function(event) {

        var debit_amount = $(this).val();
        var debit_number = $("#debit_number").val();

        if(debit_number == ""){
          toastr.error('Enter debit number before!');
          return;
        }
        if(debit_amount == ""){
          debit_amount = 0;
        }
        ticket_list[number_of_ticket].debit_number = debit_number;
        ticket_list[number_of_ticket].debit_amount = debit_amount;
        drawPaymentList();
        $(".debit_bill").addClass('yellow');
    });
   
    $("#check").keyup(function(event) {

        var check = $(this).val();

        if(check == ""){

            check = 0;
        }
        ticket_list[number_of_ticket].check = check;

        drawPaymentList();
    });
    $("#check_number").keyup(function(event) {

        var check_number = $(this).val();

        ticket_list[number_of_ticket].check_number = check_number;
    });
    $("#check_button").click(function(){
        ticket_list[number_of_ticket].card_number = "";
        ticket_list[number_of_ticket].amount_credit = 0;
        drawPaymentList();
    });
    $("#credit_button").click(function(){
        ticket_list[number_of_ticket].check_number = "";
        ticket_list[number_of_ticket].check = 0;
        drawPaymentList();
    });

    $("#value_cash").keyup(function(){
        var cash = $(this).val();

        if(cash == ""){
            cash = 0;
        }
        ticket_list[number_of_ticket].cash = cash;
        drawPaymentList();
        $(".cash_bill").addClass('yellow');
    });

    //PRINT BILL
    function printData()
    {
       var divToPrint=document.getElementById("payment_print");
       newWin= window.open("");
       newWin.document.write(divToPrint.outerHTML);
       newWin.print();
       newWin.close();
    }
    $('#print_button').on('click',function(){
      var customer_id =  current_customer;
      if(customer_id != ""){
        if( parseFloat(ticket_list[number_of_ticket].total_payment)+0.5 >= ticket_list[number_of_ticket].total_charge ){
          printData();
         //SAVE TICKET TO DATABASE
         saveTicketToDatabase();
        }
        else
          toastr.error("Not pay enough!");
        
      }
    });
    //END PRINT BILL
    var staff_id = '';
    $(document).on('click','.custom_card_header',function(){
      staff_id = $(this).attr('id');
      // loadTicketWithCurrentStaff();
      loadTicketBegin();
    });
    $(document).on('click','#pay',function(e){

      var customer_id =  current_customer;
      if(customer_id != ""){
        e.preventDefault();
           $(".div-pay").hide(500);
           $(".pay-box").slideDown(300);
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#pay").addClass('btn_active');
      }
    });
    //SPLIT TICKET
    $("#split").click(function(){
      if(correct_ticket == ""){
        var customer_id =  current_customer;
        var sub_name_arr = ['A','B','C','D','E','F','G','H','I','K','L','M','N'];
        $(".custom_btn_payticket").removeClass('btn_active');
        if(customer_id != ""){
          if(window.confirm('Do you want split this ticket?') ){
            $("#split").addClass('btn_active');

            if(ticket_list[number_of_ticket].ticket_combine == null){
              let staff_amount = 0;
              $.each(ticket_list[number_of_ticket].order_list, function(index, val) {
                staff_amount++;
              });
              if(staff_amount === 1){
                toastr.error('Can not split this ticket. Check again!');
              }else{
                $.ajax({
                  url: '{{route('split-ticket-with-staff')}}',
                  type: 'GET',
                  dataType: 'html',
                  data: {
                    ticket_no: ticket_no,
                    customer_id: current_customer
                  },
                })
                .done(function(data) {
                  // console.log(data);
                })
                .fail(function() {
                  // console.log("error");
                  toastr.error('Split Error!');
                });
              }
            }else{
              $.ajax({
                url: '{{route('split-ticket')}}',
                type: 'GET',
                dataType: 'html',
                data: {
                  ticket_no: ticket_no,
                  ticket_combine: ticket_list[number_of_ticket].ticket_combine
                },
              })
              .done(function(data) {
                if(data == 1){
                  ticket_no = "";
                }
              })
              .fail(function() {
                console.log("error");
              });
            }
          }
        }
         loadTicketBegin();
         clearTicket();
       }else
         toastr.error('You can not split!');
    });

    //COMBINE TICKET
      var ticket_combine = '';
      var ticket_combine_array = [];
    $("#combine").click(function(e){
      if(correct_ticket == ""){
        $(".custom_btn_payticket").removeClass('btn_active');
        $(this).addClass('btn_active');
        var ticket_html_choose = '';
        var ticket_combine_html = '';
        var customer_id = current_customer;
        if(customer_id != ""){
          for( var i in ticket_list){
            if(ticket_list[i].ticket_no == ticket_current){
              if(ticket_list[i].ticket_combine != null && ticket_list[i].ticket_combine != ''){
                toastr.warning('This ticket has combined with #'+ticket_list[i].ticket_combine+'. Split before combine with another!');
              }
              else{
                for(var i in ticket_list){
                  var stt = 0;
                if(ticket_list[i].ticket_no !== ticket_current && ticket_list[i].ticket_combine == null){
                  if(ticket_list[i].reason_delete === "empty" && ticket_list[i].payment == 0){
                    ticket_combine_html += `<div class="row ticket-combine-box">
                        <label for="`+ticket_list[i].ticket_no+`" class="col-md-10 float-right">`+ticket_list[i].ticket_no+`</label>
                        <input type="checkbox" class="form-control form-control-sm col-md-2 float-right" name="ticket_combine" id="`+ticket_list[i].ticket_no+`" value="`+ticket_list[i].ticket_no+`">
                      </div>`;
                    stt ++;
                    if(stt === 1){
                      ticket_combine = ticket_list[i].ticket_no;
                    }
                  }
                }
              }
              $("#combine-box").html(`
                <p>COMBINE WITH:</p>
                `+ticket_combine_html+`
                <br>
                <p hidden >KEEP NAME AND CUSTOMER INFOMATION OF:</p>
                <select hidden class="form-control form-control-sm" id="keep_combine" >
                  <option>`+ticket_current+`</option>
                </select>
                <div style="margin-top: 20px">
                <button class="btn btn-primary btn-sm float-right" id="combine-button" type="button">Combine</button>
                <button class="btn btn-danger btn-sm float-left" id="combine-cancel" type="button">Cancel</button>
                </div>
                `);
              $("#combine-check-box").modal('show');
              showCheckBox();
              }
            }
          }
        }
      }else
        toastr.error('You can not combine!');
    });

    //CHOOSE TICKET COMBINE
    $(document).on('click','input[name=ticket_combine]',function(){
      showCheckBox();
      var ticket_name = $(this).val();
      if(jQuery.inArray(ticket_name, ticket_combine_array) !== -1)
        ticket_combine_array.splice( $.inArray(ticket_name,ticket_combine_array) ,1 );
      else
        ticket_combine_array.push(ticket_name);
      var ticket_combine_html = `<option>`+ticket_current+`</option>`;
      for(var i in ticket_combine_array){
        ticket_combine_html += `<option>`+ticket_combine_array[i]+`</option>`;
      }
      $("#keep_combine").html(ticket_combine_html);
      loadCorrectTicketList();
      loadTicketBegin();
    });
    
    //CANCEL COMBINE
    $(document).on('click','#combine-cancel',function(){
      ticket_combine = null;
      $("#combine-check-box").modal('hide');
      $("#combine-box").html("");
      $(".custom_btn_payticket").removeClass('btn_active');
      hideCheckbox();
    });

    //START COMBINE TICKET
    $(document).on('click','#combine-button',function(){

      var ticket_keep = $("#keep_combine :selected").text();
      if(ticket_combine_array != [] && ticket_keep != ''){
        if(jQuery.inArray(ticket_current, ticket_combine_array) !== -1)
          {}
        else
          ticket_combine_array.push(ticket_current);
        for( var i in ticket_list ){
          if(jQuery.inArray(ticket_list[i].ticket_no, ticket_combine_array) !== -1 && ticket_list[i].ticket_no == ticket_keep){

            ticket_combine_array.splice( $.inArray(ticket_keep,ticket_combine_array) ,1 );
            ticket_list[i].ticket_parent = ticket_keep;
            ticket_list[i].ticket_combine = ticket_combine_array;
          }
          if(jQuery.inArray(ticket_list[i].ticket_no, ticket_combine_array) !== -1 && ticket_list[i].ticket_no != ticket_keep){
            
            ticket_list[i].ticket_parent = ticket_keep;
            ticket_list[i].ticket_combine = 'empty';
          }
        }
        ticket_current = ticket_keep;
        //SAVE TICKET COMBINE TO DATABASE
        saveTicketCombine(ticket_current,ticket_combine_array);
        ticket_combine_array = [];
        $("#combine-check-box").modal('hide');
        clearTicket();
        loadTicketBegin();    
      }
    });
    function saveTicketCombine(ticket_current,ticket_combine_array){
      $.ajax({
        url: '{{route('save-ticket-combine')}}',
        type: 'GET',
        dataType: 'html',
        data: {
          ticket_current: ticket_current,
          ticket_combine_array: ticket_combine_array
        },
      })
      .done(function(data) {
        if(data == 1){
          ticket_no = "";
        }
      })
      .fail(function() {
        console.log("error");
      });
    }
    // ENTER ID FOR CHECKING 
    $(".calculate-div").click(function(){
      var character = $(this).text();
      var id_character = $("#pass_for_delete").val();
      id_character += character.trim();
      id_character = id_character.trim();
      $("#pass_for_delete").val(id_character);
    });
    $(".backleft-div").mousedown(function(event) {
      var id_character = $("#pass_for_delete").val();
      id_character = id_character.slice(0,-1);
      $("#pass_for_delete").val(id_character);
      if(id_character === "")
        $(".notifi_after_check_pass").text("");
    });

    //CORRECT TICKET 
    $("#correct_ticket").click(function(){
        $("#check-pass-box").modal('show');
        $(".delete_next").attr('onclick',"nextButton('correct_ticket')");
        showCheckBox();
    });
   
    $("#tip").on('click',function(e){

      var customer_id =  current_customer;
      if(customer_id != ""){
        e.preventDefault();
        $(".div-pay").hide(500);
        $(".tip-div").slideDown(300);
        $(".custom_btn_payticket").removeClass('btn_active');
        $(this).addClass('btn_active');
      }
    });

    $(".button_show").on('click',"div",function(e){
        e.preventDefault();
        $(this).parent().find(".active").removeClass("active");
        $(this).addClass("active");
        var id = $(this).attr("id");
        $("#show_info").children().hide(400);
        $("."+id+"").show(300);
    });

    //BUY GIFTCARD
    $("#buy_giftcard").click(function(){
      clearGiftcard();
      $("#check-pass-box").modal('show');
      $(".delete_next").attr('onclick',"nextButton('buy_giftcard')");
      showCheckBox();
    });

    //SUBMIT FORM BUY GIFTCARD 
    $(".submit_buy_giftcard").click(function(event){
      var validatorResult = $("#buy_giftcard_form")[0].checkValidity();
      $("#buy_giftcard_form").addClass('was-validated');
      if(!validatorResult){
          event.preventDefault();
          event.stopPropagation();           
          return;
      }else{
        if($("#giftcard_balance").val() > 0){
          var giftcode_price = $("#giftcard_balance").val();
          var customer_phone = $("#giftcard_customer_phone").val();
          var customer_fullname = $("#giftcard_customer_fullname").val();
          var giftcode_sale_date = $("#giftcard_giftcode_sale_date").val();
          var giftcode_redemption = $("#redemption").val();
          var gift_code = $("#giftcard_gift_code").val();
          if(giftcode_redemption === "")
            giftcode_redemption = 0;

          giftcode_code.push(gift_code);
          $.ajax({
            url: '{{route('buy-giftcard-payment')}}',
            type: 'POST',
            dataType: 'html',
            data: {
              giftcode_price: giftcode_price,
              giftcode_sale_date: giftcode_sale_date,
              customer_phone: customer_phone,
              customer_fullname : customer_fullname,
              giftcode_redemption : giftcode_redemption,
              giftcode_code : giftcode_code,

            },
          })
          .done(function(response) {
            // alert(response);
            response = JSON.parse(response);
            if( response.success === true ){
              returnBegin();
              giftcode_code = [];
              var customer_id = current_customer;
              if(customer_id != "" && response.giftcode_balance !== 0){
                $("#giftcard-notifi-box").html(`
                  <h5><span class="text-danger">Buy Giftcard Success!</span><br> Do you want apply for this bill!</h5>
                  <div style="margin-top: 20px">
                    <button class="btn btn-primary btn-sm float-right" giftcode="`+response.giftcode_code+`" giftcode_balance="`+response.giftcode_balance+`" id="apply_giftcard" type="button">Apply</button>
                    <button class="btn btn-danger btn-sm float-left" id="cancel_apply" type="button">Cancel</button>
                  </div>
                  `);
                $('#buy-giftcard-notifi').modal('show');
              }
              else
                toastr.success('Get Giftcard Success!');
            }
            giftcode_code = [];
              
          })
          .fail(function(data) {
            var response = JSON.parse(data.responseText);
            var message = '';
            if($.type(response.message) == 'string')
              toastr.error(response.message);
            else{
              $.each(response.message, function(index, val) {
                  message += val+'\n';
                });
              if(message != "")
                toastr.error(message);
            }
            giftcode_code = [];
          });
        }
          
        else
          toastr.error("Giftcard Amount must greater than 0!");
      }
    });

    //APPLY GIFTCARD
    $(document).on('click','#apply_giftcard',function(){
      $("#giftcard-notifi-box").html("");
      $("#buy-giftcard-notifi").modal('hide');
      var giftcode_balance = $(this).attr('giftcode_balance');
      ticket_list[number_of_ticket].giftcard_price = giftcode_balance;
      $(".giftcard_code").val($(this).attr('giftcode'));
      loadGiftcode(giftcode_balance);
    });
    $(document).on('click','#cancel_apply',function(){
      $("#giftcard-notifi-box").html("");
      $("#buy-giftcard-notifi").modal('hide');
    });

    //CHECK GIFTCODE
   $('#giftcard_gift_code').keyup(function(e){
    if(e.keyCode == 13)
      {
        var giftcode = $(this).val();
        if( giftcode != ""){
          $.ajax({
            url: '{{route('get-giftcode-customer')}}',
            type: 'POST',
            dataType: 'html',
            data: {giftcode: giftcode},
          })
          .done(function(data) {
            data = JSON.parse(data);
            $("#giftcard_balance_amount").val(data.giftcode_balance);
            $(".balance-box").show();
            $("#giftcard_customer_fullname").val(data.customer_fullname);
            $("#giftcard_customer_phone").val(data.customer_phone);
            $("#giftcard_customer_email").val(data.customer_email);
          })
          .fail(function(data) {
            data = JSON.parse(data.responseText);
            $(".balance-box").hide();
            $("#giftcard_customer_fullname").val("");
            $("#giftcard_customer_phone").val("");
            $("#giftcard_customer_email").val("");
            toastr.error(data.message);
          });
        }
      }
    });

    //CANCEL BUY GIFTCARD
    $(".btn-cancel-giftcard").click(function(){
      returnBegin();
    });
    function showCheckBox(){
      $(".left_col").css('opacity', '.5');
      $("#payTicketModal").css('opacity', '.5');
    }
    function hideCheckbox(){
      $(".left_col").css('opacity', '1');
      $("#payTicketModal").css('opacity', '1');
    }
    function loadGiftcode(giftcode_balance){
       if(ticket_list[number_of_ticket].total_charge >= parseFloat(giftcode_balance) ){
        ticket_list[number_of_ticket].giftcard_pay = giftcode_balance;
        ticket_list[number_of_ticket].giftcard_balance = 0;
      }
      else{
        ticket_list[number_of_ticket].giftcard_pay = ticket_list[number_of_ticket].total_charge;
        ticket_list[number_of_ticket].giftcard_balance = parseFloat(giftcode_balance) - parseFloat(ticket_list[number_of_ticket].total_charge);
      }
      $(".giftcode_balance").val(ticket_list[number_of_ticket].giftcard_balance);
      drawPaymentList();
      $(".giftcard").addClass('yellow');
    }
    function clearGiftcard(){
      $(".form_control").val("");
    }

    function returnBegin(){
      $(".div-payment").slideUp(300);
      $(".payment_info").slideDown(300);
    }

    //GET INFOR MATION WHEN ENTER EMAIL OR PHONE
    $('.giftcard-left .customer_info').keyup(function() {
      getCustomerInfo('.giftcard-left',this);
    });

    function getCustomerInfo(div_closet,that){
      var customer_info = $(that).val();
      var customer_detail = $(that).attr('id');
       $.ajax({
         url: '{{route('check-customer')}}',
         type: 'GET',
         dataType: 'html',
         data: {customer_info: customer_info,customer_detail:customer_detail},
       })
       .done(function(data) {
        if(data != ""){
          var data = JSON.parse(data);
          switch(div_closet){
            case '.giftcard-left':
                    $.each(data, function(index, val) {
                     $(div_closet+" #giftcard_customer_fullname").val(data['customer_fullname']);
                     $(div_closet+" #giftcard_customer_email").val(data['customer_email']);
                     $(div_closet+" #giftcard_customer_phone").val(data['customer_phone']);
                    });
            case '.referral_giftcard_left':
                    $.each(data, function(index, val) {
                     $(div_closet+" #referral_customer_fullname").val(data['customer_fullname']);
                     $(div_closet+" #referral_customer_email").val(data['customer_email']);
                     $(div_closet+" #referral_customer_phone").val(data['customer_phone']);
                    });
            case '.membership-left':
                    $.each(data, function(index, val) {
                     $(div_closet+" #membership_customer_fullname").val(data['customer_fullname']);
                     $(div_closet+" #membership_customer_email").val(data['customer_email']);
                     $(div_closet+" #membership_customer_phone").val(data['customer_phone']);
                    });
            default: break;
          }
        }
       })
       .fail(function() {
         console.log("error");
       });
    }
    //SELECT AMOUNT PRICE GIFTCARD
    $(".div-giftcard .select_amount").click(function(event) {
      var select_amount = $(this).attr('amount');
      selectAmount(select_amount,'.div-giftcard');
    });

    $(".div-giftcard #giftcard_balance").keyup(function(event) {
      var select_amount = $(this).val();
      selectAmount(select_amount,'.div-giftcard');
    });
    
    //REFERRAL GIFTCARD
    $("#referral_giftcard").click(function(){
      clearGiftcard();
      $("#check-pass-box").modal('show');
      $(".delete_next").attr('onclick',"nextButton('referral_giftcard')");
      showCheckBox();
    });

    //CANCEL BUY GIFTCARD
    $(".div-giftcard .btn-cancel").click(function(){
      $("#buy_giftcard_form")[0].reset();
    });

    //SUBMIT FORM REFERRAL GIFTCARD 
    $(".submit_referral_giftcard").click(function(event){
      var validatorResult = $("#referral_giftcard_form")[0].checkValidity();
      $("#referral_giftcard_form").addClass('was-validated');
      if(!validatorResult){
          event.preventDefault();
          event.stopPropagation();           
          return;
      }else{
        if($(".div-referral #referral_balance").val() > 0){
          var code = $(".referral_code");
          for( var i = 0;i<code.length;i++){
            if($(code[i]).val() !== "")
              giftcode_code.push($(code[i]).val());
          }
          var giftcode_price = $("#referral_balance").val();
          var customer_phone = $("#referral_customer_phone").val();
          var customer_fullname = $("#referral_customer_fullname").val();
          var giftcode_sale_date = $("#referral_giftcode_sale_date").val();
          var giftcode_date_expire = $("#referral_giftcode_date_expire").val();
          var giftcode_bonus_point = $("#giftcode_bonus_point").val();
          if(giftcode_bonus_point === "")
            giftcode_bonus_point = 0;
          $.ajax({
            url: '{{route('buy-giftcard-payment')}}',
            type: 'POST',
            dataType: 'html',
            data: {
              giftcode_price: giftcode_price,
              giftcode_sale_date: giftcode_sale_date,
              customer_phone: customer_phone,
              customer_fullname : customer_fullname,
              giftcode_bonus_point : giftcode_bonus_point,
              giftcode_date_expire : giftcode_date_expire,
              giftcode_code : giftcode_code,

            },
          })
          .done(function(response) {
            // console.log(response);
            response = JSON.parse(response);
            if( response.success === true ){
              returnBegin();
              giftcode_code = [];
              toastr.success('Get Giftcard Success!');
            }
              
          })
          .fail(function(data) {
            var response = JSON.parse(data.responseText);
            var message = '';
            giftcode_code = [];
            if($.type(response.message) == 'string')
              toastr.error(response.message);
            else{
              $.each(response.message, function(index, val) {
                  message += val+'\n';
                });
              if(message != "")
                toastr.error(message);
            }
          });
        }
        else{
          giftcode_code = [];
          toastr.error("Giftcard Amount must greater than 0!");
        }
      }
    });
    //SELECT AMOUNT REFERRAL GIFTCARD
    $(".div-referral .select_amount").click(function(event) {
      var select_amount = $(this).attr('amount');
      selectAmountReferral(select_amount,'.div-referral');
    });

    $(".div-referral #balance").keyup(function(event) {
      var select_amount = $(this).val();
      selectAmountReferral(select_amount,'.div-referral');
    });
    //GET INFORMATION CUSTOMER FOR REFERRAL GIFTCARD
    $('.referral_giftcard_left .customer_info').keyup(function() {
      getCustomerInfo('.referral_giftcard_left',this);
    });

    //ADD GIFTCODE
    $(".add_giftcode").click(function(){
      $(".referral_giftcard_left div:eq(0)").after(`
          <div class="col-md-12 form_control sub-referral-giftcard">
            <label class="col-md-4"><b></b></label>
            <input type="text" name="" class="form-control col-md-8 referral_code" value="">
            <span class="bg-danger giftcode_delete" style="position: absolute;top:0px;right:10px;z-index: 1000;height: 38px;width: 38px;"><i class="glyphicon glyphicon-trash btn_payment" style="margin-top: -6px"></i></span>
          </div>
      `);
     stt_giftcard++;
     giftcode_code.push(gift_code);
    });
    $(".div-referral .btn-cancel").click(function(){
      $("#referral_giftcard_form")[0].reset();
    });
    //REMOVE GIFTCODE
    $(document).on('click','.giftcode_delete',function(){
      var gift_code = $(this).attr('giftcode');
      var id = $(this).attr('id');
      $("#"+gift_code).remove();
      giftcode_code.splice(id,1);
      stt_giftcard--;
      $(this).closest('div').remove();
    });

    //BUY PRODUCT
    $("#buy_product").click(function(){
      if(correct_ticket == ""){
        if(current_customer != ""){
          $("#check-pass-box").modal('show');
          $(".delete_next").attr('onclick',"nextButton('buy_product')");
          showCheckBox();
        }
        else
          toastr.error('Choose a ticket first');
      }else
        toastr.error('Can not buy product!');
        
    });
    //CANCEL BUY PRODUCT
    $("#close_product").click(function(){
      returnBegin();
    });
    
  product_list = [];
  function setProduct(product_id,product_amount){

      $.ajax({
        url: '{{route('get-product-payment')}}',
        type: 'POST',
        dataType: 'html',
        data: {product_id: product_id,_token:'{{csrf_token()}}' },
      })
      .done(function(data) {
        var data = JSON.parse(data);
        var stt = 0;

        //DISCOUNT $ WITH PRODUCT
        if( data.sn_type_discount == 0 ){
          data.sn_price = (parseFloat(data.sn_price) - parseFloat(data.sn_discount) ).toFixed(2);
          data.sn_name = data.sn_name+" ($"+data.sn_discount+"OFF)";
        }

        //DISCOUNT PERCENT WITH PRODUCT
        if( data.sn_type_discount == 1 ){
          data.sn_price = (parseFloat(data.sn_price) - ( parseFloat(data.sn_discount)*parseFloat(data.sn_price)/100 ) ).toFixed(2);
          data.sn_name = data.sn_name+"(%"+data.sn_discount+"OFF)";
        }
        var product_price = (parseFloat(data.sn_price) * parseFloat(product_amount)).toFixed(2);

        if(product_list.length !== 0){
          for(var i in product_list){
            if( product_list[i].product_id == product_id ){
              product_list[i].product_price =  product_price;
              product_list[i].product_amount = product_amount;
            }
            else
              stt ++;
          }
        }

        if(product_list.length === 0 || stt === product_list.length){

          var product = new Object();
          product.product_price = product_price;
          product.product_name = data.sn_name;
          product.product_id = data.sn_id;
          product.product_amount = product_amount;
          product.product_point = data.sn_point;
          product.product_bonus = data.sn_bonus;
          product.product_sale_tax = data.sn_sale_tax;
          product.product_discount = data.sn_discount;
          product.product_type_discount = data.sn_type_discount;
          product_list.push(product);
        }
        drawPaymentList();
        loadInfoCustomerFooter();
        $(".product").addClass('yellow');
      })
      .fail(function() {
        console.log("error");
      });
      console.log(product_list);
    }

    $(".product_amount").keyup(function() {
      var product_amount = $(this).val();
      var product_id = $(this).attr('id');

      if(product_amount == '')
        product_amount = 0;
      product_amount = parseInt(product_amount);
      $(this).val( product_amount );

       setProduct(product_id, product_amount);
    });

    $(".btn-product").click(function(){

      var product_id = $(this).attr('id');
      var product_amount = $(this).siblings(".product_amount").val();

      if( $(this).hasClass('sub_product') ){

        product_amount = parseFloat(product_amount) - 1;
      }
      else
        product_amount = parseFloat(product_amount) + 1;

      if(product_amount < 0)
        product_amount = 0;

      $(this).siblings(".product_amount").val(product_amount);

      setProduct(product_id, product_amount);

    });
    //BUY MEMBERSHIP
    $("#buy_membership").click(function(){
      $("#check-pass-box").modal('show');
        $(".delete_next").attr('onclick',"nextButton('buy_membership')");
        showCheckBox();
    })
    $('.membership-left .customer_info').keyup(function() {
      getCustomerInfo('.membership-left',this);
    });
    //SUBMIT FORM BUY MEMBERSHIP 
    $(".submit_membership").click(function(event){
      var validatorResult = $("#membership_form")[0].checkValidity();
      $("#membership_form").addClass('was-validated');
      if(!validatorResult){
          event.preventDefault();
          event.stopPropagation();           
          return;
      }else{
          var membership_id = $("#membership_name").val();
          var customer_phone = $("#membership_customer_phone").val();
          var customer_fullname = $("#membership_customer_fullname").val();
          var customer_email = $("#membership_customer_email").val();
          var payment_method = $("#payment_method").val();

          if(ticket_list[number_of_ticket].length !== 0)
            order_list = ticket_list[number_of_ticket].order_list;

          $.ajax({
            url: '{{route('buy-membership')}}',
            type: 'GET',
            dataType: 'html',
            data: {
              membership_id: membership_id,
              customer_phone: customer_phone,
              customer_fullname : customer_fullname,
              payment_method: payment_method,
              customer_email: customer_email,
              order_list: order_list
            },
          })
          .done(function(response) {
            console.log(response);
            response = JSON.parse(response);
            alert(response.message);
            if(current_customer != "" && current_customer == response.customer_id){
              membership_point = response.membership_point;
              $("#buy_membership").removeClass('btn_active');
              drawPaymentList();
              $(".membership_point").addClass('yellow');
            }
            returnBegin();
          })
          .fail(function(data) {
            var response = JSON.parse(data.responseText);
            var message = '';
            if($.type(response.message) == 'string')
              alert(response.message);
            else{
              $.each(response.message, function(index, val) {
                  message += val+'\n';
                });
              if(message != "")
                alert(message);
            }
            giftcode_code = [];
          });
      }
    });
    //CANCLE MEMBERSHIP
    $(".btn-cancel-membership").click(function(){
      $("#buy_membership").removeClass('btn_active');
      returnBegin();
    })
    $("#discount_ticket").click(function(){
      if(current_customer != ""){
        $("#check-pass-box").modal('show');
        $(".delete_next").attr('onclick',"nextButton('discount_ticket')");
        showCheckBox();
      }
      else
        toastr.error('Choose a ticket first');
    })
    //GET LIST SERVICE IN MEMBERSHIP
    $("#detail_list_membership").click(function(){

      var membership_id = $("#membership_name").val();
      $.ajax({
        url: '{{route('get-list-service-membership')}}',
        type: 'GET',
        dataType: 'html',
        data: {membership_id: membership_id},
      })
      .done(function(data) {
        var service_list_html = "";
        data = JSON.parse(data);
        $.each(data, function(index, val) {
          service_list_html += `
            <tr>
              <td>`+val.service_name+`</td>
              <td class="text-center">`+val.service_price+`</td>
              <td class="text-center">`+val.service_duration+`</td>
            </tr>
          `;
        });
        $("#service-list-box").html(
          `<h5 class="text-center">List Service on Membership</h5>
          <table class="table table-bordered table-hovered">
            <thead>
              <tr>
                <th>Service Name</th>
                <th>Service Price($)</th>
                <th>Service Duration(m)</th>
              </tr>
            </thead>
            <tbody>
              `+service_list_html+`
            </tbody>
          </table>`
          );
        $("#service-list-membership").modal('show');
        console.log(data);
      })
      .fail(function() {
        console.log("error");
      });
      
    })
    //DISCOUNT TICKET
    $(".discount_div_left .discount-box").click(function(){
      $(".discount_div_left .discount-box").removeClass('discount_div_choose').addClass('discount_div_origin');
      $(this).toggleClass('discount_div_origin discount_div_choose');
      discount_station = $(this).attr('discount_station');
      drawPaymentList();
      $(".discount").addClass('yellow');

    });
    $(".discount_div_right .discount-box").click(function(){
      $(".discount_div_right .discount-box").removeClass('discount_div_choose').addClass('discount_div_origin');
      $(this).toggleClass('discount_div_origin discount_div_choose');
      discount = $(this).attr('discount');

      if( discount == 1 ){
        $("#discount_other").show();
      }
      else{
        $("#discount_other").hide();

        //DISCOUNT TYPE = 1 IS %, DISCOUNT TYPE = 0 IS $
        if( discount == 2){
          discount_amount = 3;
          discount_type = 0;     }
        if( discount == 3){
          discount_amount = 5;
          discount_type = 0;
        }
        if( discount == 4){
          discount_amount = 5;
          discount_type = 1;
        }
        if( discount == 5){
          discount_amount = 10;
          discount_type = 1;
        }
        drawPaymentList();
      $(".discount").addClass('yellow');
      }
    });

    $(".discount_submit").click(function(){

      discount_amount = parseInt( $(".discount_amount").val() );
      discount_type = parseInt( $(".discount_type :selected").val() );
      if( discount_amount !== 0){

        $(".other_discount_box").text( "Other Discount ("+discount_amount+$(".discount_type :selected").text()+")");
        $("#discount_other").slideUp();
        drawPaymentList();
      $(".discount").addClass('yellow');
      }
    });

});
function nextButton(about){
  var pass_for_delete = $("#pass_for_delete").val();
  if( pass_for_delete != "" ){
    $.ajax({
      url: '{{route('check-pass-for-delete-ticket')}}',
      type: 'POST',
      dataType: 'html',
      data: {pass_for_delete: pass_for_delete, _token: '{{csrf_token()}}'},
    })
    .done(function(data) {

      if(data == 1){
        //SHOW LIST_STAFF_DIV AFTER CHECK TRUE PASS WITH VOIDED TICKET
        if( about == 'void_ticket' ){
          $(".div-pay").hide();
          $(".reason-voided-ticket-div").show(300);
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#delete_ticket").addClass('btn_active');
        }

        //SHOW BUY GIFTCARD_DIV AFTER CHECK TRUE PASS WITH BUY GIFTCARD
        if( about == 'buy_giftcard' ){
          //SET ARRAY FOR GIFTCARD CODE
          giftcode_code = [];
          $(".div-payment").hide(500);
          $(".div-giftcard").show(300);
          selectAmount(select_amount=0);
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#buy_giftcard").addClass('btn_active');
        }

        //SHOW REFERRAL_GIFTCARD_DIV AFTER CHEK TRUE PASS WITH REFERRAL GIFTCARD
        if( about == 'referral_giftcard' ){
          //SET ARRAY FOR GIFTCARD CODE
          giftcode_code = [];
          stt_giftcard = 1;
          $(".div-payment").hide(500);
          $(".div-referral").show(300);
          selectAmountReferral(select_amount=0);
          $(".sub-referral-giftcard").remove();
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#referral_giftcard").addClass('btn_active');
        }
        //SHOW PRODUCT_DIV AFTER CHECK TRUE PASS WITH BUY PRODUCT
        if( about == 'buy_product'){
          $(".div-payment").hide(500);
          $(".div-product").show(300);
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#buy_product").addClass('btn_active');
        }
        //SHOW DISCOUNT_DIV AFTER CHECK TRUE PASS WITH DISCOUNT
        if( about == 'discount_ticket'){
          $(".div-pay").hide(500);
          $(".discount_div").slideDown(300);
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#discount_ticket").addClass('btn_active');
        }
        if( about == 'correct_ticket'){
          $(".correct_list").slideDown(300);
          $(".ticket_list").slideUp(300);
          loadCorrectTicketList();
          correct_ticket = 1;
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#correct_ticket").addClass('btn_active');
        }
        if( about == 'buy_membership'){
          $(".div-payment").hide(500);
          $(".div-membership").show(300);
          $("#membership_customer_phone").val("");
          $("#membership_customer_email").val("");
          $("#membership_customer_fullname").val("");
          $(".custom_btn_payticket").removeClass('btn_active');
          $("#buy_membership").addClass('btn_active');
        }
          $("#check-pass-box").modal('hide');
          $("#pass_for_delete").val("");
          $(".notifi_after_check_pass").text("");
          hideCheckbox();
      }
      else
        $(".notifi_after_check_pass").text('Not exactly! Check again!');
    })
    .fail(function(xhr, ajaxOptions, thrownError) {
      console.log("error");
      console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

    });
  }
}
 function hideCheckbox(){
      $(".left_col").css('opacity', '1');
      $("#payTicketModal").css('opacity', '1');
    }
// FUNCTION SELECT AMOUNT FOE REFERRAL GIFTCARD
function selectAmountReferral(select_amount,div_closet){
      $(div_closet+" #referral_amount").val('$'+select_amount);
      $(div_closet+" #referral_balance").val(select_amount);
      $(div_closet+" .select-amount-div").css('background-color', '#959a9e');
      $(div_closet+' .'+select_amount).css('background-color', '#274360');
    }
//FUNCTION SELECT AMOUNT FOR GIFTCARD
function selectAmount(select_amount,div_closet){
      $(div_closet+" #giftcard_amount").val('$'+select_amount);
      $(div_closet+" #giftcard_balance").val(select_amount);
      $(div_closet+" .select-amount-div").css('background-color', '#959a9e');
      $(div_closet+' .'+select_amount).css('background-color', '#274360');
    }
function removeSpecialCharacter(event){
      var regex = new RegExp("^[a-zA-Z0-9]+$");
      var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
      if (!regex.test(key)) {
         event.preventDefault();
         return false;
      }
    }
//LOAD CORRECT TICKET
 function loadCorrectTicketList(){
  $.ajax({
    url: '{{route('get-correct-ticket-today')}}',
    type: 'GET',
    dataType: 'html',
  })
  .done(function(data) {
     data = JSON.parse(data);
     console.log('correct_ticket');
     console.log(data);
        ticket_list = data;
        ticket_list_html="";
        
        for(var i in data){
          order_list_in_ticket="";
          var style_background = '';
          var service_price = 0;

          $.each(data[i].order_list, function(index, val) {
            var service_name_html = "";
            $.each(val, function(index_service, val_service) {
              service_name_html += '-'+val_service['service_name']+'<br>';
              service_price += parseInt(val_service['service_price']);
            });
            order_list_in_ticket+='<p><b>'+index+'</b><br>'+service_name_html+'</p>';
          });

          if( ticket_no === data[i].booking_code){
            style_background = '#ffdf00';
            number_of_ticket = i;
          }
          if( ticket_no !== data[i].booking_code) style_background = '#959a9e';

          ticket_list_html+=`
          <div class="card ml-2 mb-1 " style="width: 12rem;" id="`+data[i].booking_code+`">
            <div class="card-header card-footer" style="background-color:`+style_background+`"  ticket_no_footer="`+data[i].booking_code+`" id="`+i+`">
              <div class="float-left"><b>#`+data[i].booking_code+ `</b></div>
              <div class="float-right">`+data[i].time+`</div>
            </div>
            <div class="card-body scrollbar scroll-style-1 card-footer" ticket_no_footer="`+data[i].booking_code+`" id="`+i+`" style="height:5.5rem;overflow-y: auto">
            `+order_list_in_ticket+`
            </div>
            <div class="col-md-12 text-danger text-right card-footer" style="position:absolute;bottom:0px;border:none"  ticket_no_footer="`+data[i].boooking_code+`" id="`+i+`">$`+service_price+`</div>
          </div>`;
        }
        $('.correct_list').html(ticket_list_html);
  })
  .fail(function(data) {
    data = JSON.parse(data.responseText);
    toastr.error(data.message);
  });
    
}
</script>
@stop


