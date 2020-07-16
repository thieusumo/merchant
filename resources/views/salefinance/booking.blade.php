@extends('layouts.master')
@section('title', 'Sales & Finances | Booking')
@section('styles')
<link href="{{ asset('/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">  
<link href="{{ asset('/css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">  
<link href="{{ asset('/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">  
<link href="{{ asset('/css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet"> 
<style>
.wizard_verticle ul.wizard_steps li a.done .step_no, .wizard_verticle ul.wizard_steps li a.done:before {
    background: #0874e8;
    color: #fff;
}
.x_panel{
  border:0px;
}
.datepicker{
border-radius: 5px;
border: 1px solid #0874e8;
  }
.wizard_verticle ul.wizard_steps{
  padding: 15px;
  text-align: center;
  width: 5%;
}
.wizard_verticle .stepContainer{
  width: 91%;
}
option{
     line-height: 40px;
   }
button.btn-danger{

  cursor: no-drop;
}
.staff-attendances .block-staff{
  width: 16%;
}
.block-staff:hover{
      background-color: #0874e8;
      color: #fff;
}
.service-remove{
  padding-top: 10px;
  color: red;
  cursor: pointer;
}
.buttonFinish{
  background-color: #e5bd37;
  color: #fff;
}
.stepContainer{
   overflow:hidden;
}
   td.day{
  position:relative;  
}
td.day.disabled{
  text-decoration: line-through;
}

td.day.disabled:hover:before {
    content: 'This time is closed';
    border: 1px red solid;
    border-radius: 11px;
    color: red;
    background-color: white;
    top: -22px;
    position: absolute;
    width: 136px;
    left: -34px;
    z-index: 1000;
    text-align: center;
    padding: 2px;
}
#worker_rent{
  /*color: #0874e8;*/
  font-size: 16px;
}
.old-color{
  color: #0874e8;
}
.add-new{
  color: red;
}

 </style> 
@if(empty(Session::get('service_arr')))
<style>
  .actionBar{
  display: none;
  }
</style>
@endif
@stop
@section('content')
@if(isset($booking_from_clone))
<style>
  .buttonNext, .buttonPrevious{
    display: none;
  }
</style>
@endif
@php
$service_session = Session::get('service_arr');
\Session::put('service_worker_arr',[]);
$today = \Carbon\Carbon::today()->format('m/d/Y');
@endphp
@foreach($date_action_list as $key => $date)
<input type="hidden" id="{{$key}}" name="{{$key}}" value="{{$date['closed']}}">
@endforeach
<div class="col-xs-9 col-md-9 no-padding">
   <!-- Smart Wizard -->
   <div id="wizard_verticle" class="form_wizard wizard_verticle" width="100%" >
      <ul class="wizard_steps anchor">
        @if(!isset($booking_from_clone))
         <li>
            <a href="#step-1" >
            <span class="step_no">1</span>
            <span class="step_descr">
            <!-- <small>Service</small> -->
            </span>
            </a>
         </li>
         {{-- <li class="stp-2"></li> --}}
         {{-- @if(!isset($booking_from_schedule)) --}}
          <li class="stp-2">
            <a href="#step-2" >
            <span class="step_no">2</span>
            <span class="step_descr">
            </span>
            </a>
          </li>
          {{-- @endif --}}
           @endif

         <li style="display:{{!isset($booking_from_clone)?'':'none'}}">
            <a href="#step-3" >
            <span class="step_no">3</span>
            <span class="step_descr">
            <!-- <small>Date & Time</small> -->
            </span>
            </a>
         </li>
          @if(!isset($booking_from_schedule))
            @if(!isset($booking_from_clone))
             <li>
                <a href="#step-4" >
                <span class="step_no">4</span>
                <span class="step_descr">
                <!-- <small>Client info</small> -->
                </span>
                </a>
             </li>
            @endif
         @endif
      </ul>
      <div class="" >
        @if(!isset($booking_from_clone))
         <div id="step-1" class="" >
            <form class="" id="service_form full-height">
                <div class="x_panel x_panel_form col-xs-12 col-sm-12 col-md-12">
                    <h2 class="StepTitle text-center">Choose Service</h2>

                  @if(empty($service_session))
                    <span  class="service_html col-xs-10 col-sm-10 col-md-10 offset-md-1" id="0">
                    <select class="selectpicker form-control form-control-sm cateservice_list " style="height: 37px;" data-show-subtext="true" data-live-search="true" >
                      <option value="">Select Service</option>
                        @foreach($cateservice_list as $cateservice)
                          <optgroup label="{{$cateservice->cateservice_name}}">
                            @php
                            $service_collect = collect($service_list);
                            $service_array = $service_collect->where('service_cate_id',$cateservice->cateservice_id);
                            @endphp

                            @foreach($service_array as $service)
                                <option value="{{$service->service_id}}">
                                  {{$service->service_name}} (Duration: {{$service->service_duration}}) - Price: ${{$service->service_price}}
                                </option>
                            @endforeach

                          </optgroup>
                        @endforeach
                    </select>
                    </span>
                    <div class="clearfix"></div>
                  @else
                  @foreach($service_session as $key => $result)

                    <span  class="service_html col-xs-10 col-sm-10 col-md-10 offset-md-1" id="{{$key}}">
                    <select class="selectpicker form-control form-control-sm cateservice_list" style="height: 37px;" data-show-subtext="true" data-live-search="true" >
                        @foreach($cateservice_list as $cateservice)
                          <optgroup  label="{{$cateservice->cateservice_name}}">
                            @php
                            $service_collect = collect($service_list);
                            $service_array = $service_collect->where('service_cate_id',$cateservice->cateservice_id);
                            @endphp
                            @foreach($service_array as $service)
                                <option  {{($result['service_id'] == $service->service_id)?"selected":""}} value="{{$service->service_id}}">
                                  {{$service->service_name}} (Duration: {{$service->service_duration}}) - Price: ${{$service->service_price}}
                                </option>
                            @endforeach

                          </optgroup>
                        @endforeach
                    </select>
                    </span>

                    @endforeach
                  @endif
                  <div id="list_add"></div>

                <div class="clearfix"></div>
                <div class="offset-md-1"><button type="button" id="service_add" style="margin: 10px;" class=" btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add Service</button>
                </div>
                
                </div>
              </div>
            </form>
         </div>
        @endif
        @if(!isset($booking_from_schedule))
          @if(!isset($booking_from_clone))
           <div id="step-2" >
              <div class="x_panel x_panel_form ">
                <h2 class="StepTitle text-center">Choose Rent</h2>
                  <form class="" id="service_form">
                  
                          <ul class="list-inline col-md-12 staff-attendances">

                              @foreach($worker_list as $worker)
                  
                              <li class="list-inline-item block-staff worker_list" id="{{$worker->worker_id}}"
                                style="{{($worker->worker_id == $booking_worker_id)?"background-color: #0874e8":""}}" 
                               worker_name="{{$worker->worker_nickname}}">
                                  <div class="staff-name"><h6>{{$worker->worker_nickname}}</h6></div>
                                  <div class="staff-img"><img src="{{(isset($worker->worker_avatar))?config('app.url_file_view').$worker->worker_avatar:asset('/images/user.png')}}"></div>
                                  <div class="staff-actions text-center"> 
                                  <input type="hidden" name="worker_id" id="worker_id" value="{{$worker->worker_id}}">              
                                  </div>                    
                              </li>

                              @endforeach
                              
                          </ul>

                  </form>
              </div>
           </div>
          @endif
        @endif

        <input type="hidden" id="expense_date" value="{{($date_booking!='')?$date_booking:date('m/d/Y')}}">
        {{-- @if( !isset($booking_from_schedule) ) --}}
         <div id="{{isset($booking_from_schedule)?"step-2":"step-3"}}"  >
            <div class="col-xs-12 col-sm-12 col-md-12 x_panel x_panel_form " id="content_time">
              <h2 class="StepTitle text-center">Choose Date & Time</h2>
              <form class="form-horizontal form-addon-ext label-date" name="frm-expense" custom-submit="" novalidate="novalidate">
                
                <div class="clear">&nbsp;</div>

                <div class="row form-group">
                    <div class="row form-group col-xs-4 col-md-4" style="">
                        <div class=" no-padding">
                          
                             <div id="sandbox-container" class='input-group date'>
                                  <div id="datepicker"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-8 col-md-8" >
                      <div id="notice" style="color: red;text-transform: uppercase;text-align: center"></div>
                      <span id="time_">
                        <div class="row form-group">
                          <div class="booking-morning">MORNING</div>
                        </div>
                        <div class="row form-group morning_list" ></div>
                        <div class="row form-group ">
                          <div class="booking-morning">AFTERNOON</div>
                        </div>
                        <div class="row form-group afternoon_list" ></div>
                        <div class="row form-group ">
                          <div class="booking-morning">EVENING</div>
                        </div>
                        <div class="row form-group evening_list" ></div>
                      </span>
                    </div>
                </div>
              </form>
            </div>
         </div>
         {{-- @endif --}}

        @if(!isset($booking_from_clone))
         <div id="{{isset($booking_from_schedule)?"step-3":"step-4"}}">
          <div class="x_panel x_panel_form col-xs-12 col-sm-12 col-md-12">
              <h2 class="StepTitle text-center">Client info</h2>
              <hr>
              <form action="{{route('send-booking')}}" id="booking_form" method="post" accept-charset="utf-8">
                @csrf
                <input type="hidden" value="booking" name="type_noti" id="type_noti">
                <input type="hidden" value="{{route('schedule-index')}}" name="link_noti" id="link_noti">
                <input type="hidden" value="{{$booking_id}}" name="booking_id">
                <div class="row form-group">
                  <label for="customer_phone"  class="col-md-3 col-sm-3">Customer Phone</label>
                  <input type="number" required class="form-control form-control-sm col-md-7 col-sm-7 customer_info{{ $errors->has('customer_phone') ? ' is-invalid' : '' }}"  name="customer_phone" id="customer_phone" value="{{($customer_list != "")?$customer_list->customer_phone:""}}">
                </div>
                <div class="row form-group">
                  <label for="customer_email" class="col-md-3 col-sm-3">Customer Email</label>
                  <input type="email" class="form-control form-control-sm col-md-7 col-sm-7 customer_info"  name="customer_email" id="customer_email" value="{{($customer_list != "")?$customer_list->customer_email:""}}">
                </div>
                <div class="row form-group">
                  <label for="customer_name" class="col-md-3 col-sm-3">Customer Full Name</label>
                  <input type="text" id="customer_fullname" required class="form-control form-control-sm col-md-7 col-sm-7 {{ $errors->has('customer_name') ? ' is-invalid' : '' }}" name="customer_fullname" value="{{($customer_list != "")?$customer_list->customer_fullname:""}}">
                </div>
                <div class="row form-group">
                  <label for="customer_name" class="col-md-3 col-sm-3">Booking Note</label>
                      <textarea rows="4" class="form-control col-md-7 col-sm-7" name="booking_note" id="booking_note"></textarea>
                  </div>
                <div class="row form-group">
                  <label for="customer_gender" class="col-md-3 col-sm-3">Customer Gender </label>
                  <div class=" col-md-7 col-sm-7">
                    <input type="radio"  name="customer_gender" {{($customer_list != "" && $customer_list->customer_gender==1)?"checked":""}} value="1"> Male &nbsp
                    <input type="radio" {{(($customer_list != "" && $customer_list->customer_gender==2) || ($customer_list==""))?"checked":"" }} name="customer_gender" value="2"> Female &nbsp
                    <input type="radio" {{($customer_list != "" && $customer_list->customer_gender==3)?"checked":""}} name="customer_gender" value="3"> Child &nbsp
                  </div>
                </div>
                <div class="row form-group">
                  <label for="booking_type" class="col-md-3 col-sm-3">Booking Type</label>
                  <div class=" col-md-7 col-sm-7">
                    <input type="radio" checked name="booking_type" value="1"> Welcome Guest &nbsp
                    <input type="radio"  name="booking_type" value="2"> Client Call &nbsp
                    <input type="radio"  name="booking_type" value="3"> Website
                  </div>
                </div>

                <input type="hidden" name="date_booking_hidden" class="date_booking_hidden" value="{{$date_booking?$date_booking:$today}}">
                <input type="hidden" name="time_booking_hidden" class="time_booking_hidden" value="{{$time_booking?$time_booking:""}}">
              </form>
          </div>
        @endif
         </div>
        
      </div>
   </div>

<div class="col-xs-3 col-md-3 no-padding booking-detail"> 
  <div class="x_panel">
    <center><h2>Booking Details</h2></center> 
    <div class="row form-group ">
        <div class="col-md-12 offset-md-1"><h6>Service</h6></div>
        <div class="col-md-12 offset-md-1">
            <span id="service_add_list" style="color: #0874e8;font-size: 16px">
              @if(!empty($service_name_list))
                @foreach($service_name_list as $key => $service_name)
                  <span class="{{$key}}">- {{$service_name}}</span><br>
                @endforeach
              @endif
            </span>
        </div>    
    </div>
    <div class="row form-group ">
        <div class="col-md-12 offset-md-1"><h6>Rent</h6></div>
        <div class="col-md-12 offset-md-1">
            <span id="worker_rent" >
              @if($worker_nickname != "")
                 {{"- ".$worker_nickname}}</span>
              @endif
        </div>    
    </div>
    <div class="row form-group ">
        <div class="col-md-12 offset-md-1"><h6>Date & Time</h6></div>
        <div class="col-md-12 offset-md-1">
          <span class="date_booking"  style="font-size: 16px">{{$date_booking?$date_booking:""}}</span><br>
          <span class="time_booking" style="font-size: 16px"></span><br>

          @if($time_booking != "")
          <div class="old_timme_booking" style="display: none;">
            Old Time Booking: 
              <span style="color: #0874e8;font-size: 16px">
                {{"- ".$time_booking}}{{",".$date_booking}}
              </span><br>
              <div class="row skip_div">
                 <button type="button" class="btn btn-sm skip" style="background-color: #0874e8;color: #fff">Skip</button>
                 <p>If don't change time booking</p>
              </div>
          </div>
          @endif
        </div>
    </div> 
  </div>
    
</div>
@if(isset($booking_from_clone))
<div class="col-md-12" style="display: none">
  <form action="{{route('send-booking')}}" id="booking_form" method="post" accept-charset="utf-8">
  @csrf
  <input type="hidden" value="{{$booking_id}}" name="booking_id">
  <div class="row form-group">
    <label for="customer_phone"  class="col-md-3 col-sm-3">Customer Phone</label>
    <input type="number" required class="form-control form-control-sm col-md-7 col-sm-7 customer_info{{ $errors->has('customer_phone') ? ' is-invalid' : '' }}"  name="customer_phone" id="customer_phone" value="{{($customer_list != "")?$customer_list->customer_phone:""}}">
  </div>
  <div class="row form-group">
    <label for="customer_email" class="col-md-3 col-sm-3">Customer Email</label>
    <input type="text" class="form-control form-control-sm col-md-7 col-sm-7 customer_info"  name="customer_email" id="customer_email" value="{{($customer_list != "")?$customer_list->customer_email:""}}">
  </div>
  <div class="row form-group">
    <label for="customer_name" class="col-md-3 col-sm-3">Customer Full Name</label>
    <input type="text" id="customer_fullname" required class="form-control form-control-sm col-md-7 col-sm-7 {{ $errors->has('customer_name') ? ' is-invalid' : '' }}" name="customer_fullname" value="{{($customer_list != "")?$customer_list->customer_fullname:""}}">
  </div>
  <div class="row form-group">
    <label for="customer_name" class="col-md-3 col-sm-3">Booking Note</label>
        <textarea rows="4" class="form-control col-md-7 col-sm-7" name="booking_note" id="booking_note"></textarea>
    </div>
  <div class="row form-group">
    <label for="customer_gender" class="col-md-3 col-sm-3">Customer Gender </label>
    <div class=" col-md-7 col-sm-7">
      <input type="radio"  name="customer_gender" {{($customer_list != "" && $customer_list->customer_gender==1)?"checked":""}} value="1"> Male &nbsp
      <input type="radio" {{(($customer_list != "" && $customer_list->customer_gender==2) || ($customer_list==""))?"checked":"" }} name="customer_gender" value="2"> Female &nbsp
      <input type="radio" {{($customer_list != "" && $customer_list->customer_gender==3)?"checked":""}} name="customer_gender" value="3"> Child &nbsp
    </div>
  </div>
  <div class="row form-group">
    <label for="booking_type" class="col-md-3 col-sm-3">Booking Type</label>
    <div class=" col-md-7 col-sm-7">
      <input type="radio" checked name="booking_type" value="1"> Welcome Guest &nbsp
      <input type="radio"  name="booking_type" value="2"> Client Call &nbsp
      <input type="radio"  name="booking_type" value="3"> Website
    </div>
  </div>

  <input type="hidden" name="date_booking_hidden" class="date_booking_hidden" value="{{$date_booking?$date_booking:$today}}">
  <input type="hidden" name="time_booking_hidden" class="time_booking_hidden" value="{{$time_booking?$time_booking:""}}">
</form>

@endif
<div class="div_time_hidden" style="display: none">
  
</div>
@stop
@section('scripts')
<!-- jQuery Smart Wizard -->
<script type="text/javascript" src="{{ asset('plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
  
  
    var minutes = ['00','15','30','45'];
    $(document).ready(function() {

        $(window).on( "load", onChangeDate() );

        $('.form_wizard').smartWizard();
        $('#wizard_verticle').smartWizard({
        transitionEffect: 'slide'
      });

      $('.buttonNext').addClass('btn btn-danger');
      $('.buttonPrevious').addClass('btn btn-primary');
      $('.buttonFinish').addClass('btn btn-default');

        var id = '{{$max_key}}';
        //DELETE BOOKING
        $(document).on('click','.service-remove',function(){
            $(this).closest('.closet').remove();
            var id_hide = $(this).attr('id');
            $("#service_add_list ."+id_hide).remove();
            $("#wizard_verticle").smartWizard("fixHeight");
            $(this).parent();
            $.ajax({
                url: '{{route('delete-booking-session')}}',
                type: 'GET',
                dataType: 'html',
                data: {id_hide: id_hide},
            })
            .done(function(data) {

            })
            .fail(function() {
                console.log("error");
            })
            ;
            
        });
        
        //VIEW ADD SERVICE
        $("#service_add").click(function(){
            id++;
            var service_html = $(".service_html").html();
            $('#list_add').append("<span class='closet' id='"+id+"'><div class='col-xs-10 col-sm-10 col-md-10 offset-md-1'>"+service_html+"</div><div class='col-xs-1 col-sm-1 col-md-1'><span class=' glyphicon glyphicon-trash fa-lg service-remove' id='"+id+"' ></span></div><div class='clearfix'></div></span>");
            $("#wizard_verticle").smartWizard("fixHeight");
        });
        //ADD SERVICE BUTTON
        $(document).on('change','.cateservice_list',function(){

            $("#wizard_verticle").smartWizard("fixHeight");

            $('.actionBar').slideDown();

            //$('#service_add').show();

            var id_stt = $(this).closest('span').attr('id');

            var service_id = $(this).find('option:selected').val();

            var service_name = $(this).find('option:selected').text();

            if($("#service_add_list").children('span').hasClass(id_stt))
           {
            $("span").removeClass('add-new');
            $("#service_add_list ."+id_stt).text("- "+service_name);
            $("#service_add_list ."+id_stt).addClass('add-new');
           }
           else{
            $("span").removeClass('add-new');
            $("#service_add_list").append("<span class='"+id_stt+" add-new'>- "+service_name+"</span><br>");
           }

            $("#wizard_verticle").smartWizard("fixHeight");

            $.ajax({
                url: '{{route("get-service-booking")}}',
                type: 'GET',
                dataType: 'html',
                data: {service_id: service_id,id_stt:id_stt},
            })
            .done(function(data) {
              onChangeDate() ;
            })
            .fail(function() {
                console.log("error");
            });
            
        });

        $(document).on('click','.worker_list',function(){

          // var date_booking = '{{$date_booking?$date_booking:$today}}';

          // $('.date_booking').text("-"+date_booking);

          $("span").removeClass('add-new');

          buttonNextClick();

          $('.old_timme_booking').slideDown();

          $('.worker_list').css('backgroung-color', '');

          $(this).css('backgroung-color', '#0874e8');

           var worker_name = $(this).attr('worker_name');

           var worker_id = $(this).attr('id');

           //$("span").removeClass('add-new');

           $('#worker_rent').text("- "+worker_name);

           $('#worker_rent').addClass('add-new');

           $.ajax({
               url: '{{route('add-worker-session')}}',
               type: 'GET',
               dataType: 'html',
               data: {worker_id: worker_id},
           })
           .done(function(data) {
           })
           .fail(function() {
               console.log("error");
           });

           onChangeDate() ;
        });
    });

$(document).ready(function() {

  var arr = [];

  var arr_date = ['sun','mon','tue','wed','thur','fri','sat'];

  $.each(arr_date, function(index, val) {

    if($('input[name='+val+']').val() == 1){

      arr.push(index);
    }
  });
   

    var date = new Date();
    var date_booking_before = new Date('{{$date_booking?$date_booking:today()}}');
    $("#datepicker").datepicker({
        daysOfWeekDisabled: arr,
        //todayHighlight: true,
        autoclose: true,
        startDate: date,
        defaultViewDate: {year:date_booking_before.getFullYear(),month:date_booking_before.getMonth(),day:date_booking_before.getDate()},
    });
    $('#datepicker').on('changeDate', function() {
    $('#expense_date').val(
         $('#datepicker').datepicker('getFormattedDate')
    );
    onChangeDate();
    });

    
    
});

$(document).ready(function() {

  $(document).on('click','#step-3 button',function(){
    var expense_date = $('.date_booking').text();
    if(expense_date == ""){
      toastr.error('Choose Expense Date Before, Please!');
    }
    else{
      var time_booking = $(this).text();
      $('.time_booking').text("- "+time_booking);
      var date_booking = $('#expense_date').val();
      $('.date_booking_hidden').val(date_booking);
      $('.time_booking_hidden').val(time_booking);
      $('.buttonNext').removeClass('disabled');
    }
    
  });

  $('.customer_info').keyup(function(event) {
     var customer_info = $(this).val();
     var customer_datail = $(this).attr('id');
     $.ajax({
       url: '{{route('check-customer')}}',
       type: 'GET',
       dataType: 'html',
       data: {customer_info: customer_info,customer_datail:customer_datail},
     })
     .done(function(data) {
      if(data != ""){
        var data = JSON.parse(data);
        $.each(data, function(index, val) {
         $("#customer_fullname").val(data['customer_fullname']);
         $("#customer_email").val(data['customer_email']);
         $("#customer_phone").val(data['customer_phone']);
         $("#customer_birthdate").val(data['customer_birthdate']);
         $("#customer_address").val(data['customer_address']);
      });
      }
     })
     .fail(function() {
       console.log("error");
     });
     
  });
  // var socket = io.connect(window.location.hostname+':8001');
  $('.buttonFinish').click(function(event){
            // validate form
            var flag=0;
            var validatorResult = $("#booking_form")[0].checkValidity();
            $("#booking_form").addClass('was-validated');
            if(!validatorResult){
                event.preventDefault();
                event.stopPropagation();           
                return;
            }else{
              var msg=$('#booking_note').val();
              var type_noti=$('#type_noti').val();
              var link=$('#link_noti').val();
              // alert(msg);
              var user_phone="{{ session::get('current_user_phone') }}"
              $.ajax({
                  url: "{{route('postNotification')}}",
                  type: "GET",
                  cache: false,
                  data:{
                      'message': msg,
                      'user_phone':user_phone,
                      'type_noti':type_noti,
                      'link':link,
                  },
                  success:function(data){

                    if(socket!="")
                    {
                      socket.emit("client-sent-data",data);
                      //alert('Sent !!');
                    }
                      $('#booking_form').submit();
                  },
              });
            }

            //form = document.createElement('#customer_form');
  });

  $('.buttonNext').click(function(){

    var id_time_booking = '{{$time_booking_12h}}'.replace(/[^A-Z0-9]+/ig,'_');
    if($("#"+id_time_booking).hasClass('btn-danger') || $("#"+id_time_booking).attr('type') == "")
      {
        toastr.error('Your time not booking, Choose again, please!');
      }else{
      }

    $(this).addClass('disabled');
  });

  $('.buttonPrevious').click(function(){
    $('.buttonNext').removeClass('disabled');
  });

  $('.skip').click(function(){
    buttonNextClick();
    onChangeDate();
  })

});

function buttonNextClick(){
  $(".buttonNext").click();
}

// Prevent user enter press in input
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
   });
 function onChangeDate(e){
      $('.skip_div').remove();
      var date_booking = $('#expense_date').val();
      $("span").removeClass('add-new');
      $('.date_booking').text("- "+date_booking);
      $(".date_booking").addClass('add-new');
      $.ajax({
          url: '{{route("get-booking-first")}}',
          type: 'GET',
          dataType: 'html',
          data: {date_booking: date_booking},
      })
      .done(function(data) {
        console.log(data);
        $(".booking-detail span").addClass('old-color');
        $('.date_booking').text("- "+date_booking);
        $(".date_booking").addClass('add-new');
        $("#wizard_verticle").smartWizard("fixHeight");
        morning_html ="";
        afternoon_html="";
        evening_html="";
        var time_list = JSON.parse(data);
        //console.log(time_list);
        if(time_list['open_close'] === "opend"){
          $("#notice").hide();
          $("#time_").show();

          var time_opend = Date.parse(date_booking+" "+time_list['time_opend']);
          var time_closed = Date.parse(date_booking+" "+time_list['time_closed']);

                     if(time_list['time_night']!=""){
                      
                      $.each(time_list['time_night'], function(index, val) {
                      evening_html="";
                      for(i=5;i<11;i++)
                          {
                              $.each(minutes, function(index, val_minute) {
                                  var time = i+":"+val_minute+" PM";
                                  var id = time.replace(/[^A-Z0-9]+/ig,'_');
                                  var time_start = Date.parse(val['date']+" "+val['service_duration_booking']);
                                  var time_end = Date.parse(val['date']+" "+val['time_finish_night']);
                                  var time_bet = Date.parse(val['date']+" "+time);
                                  var time_date = Date.parse(val['date']+"")
                                  var time_now = new Date();
                                  if(time_bet >= time_opend && time_bet < time_closed){
                                    if( (time_bet <= time_end && time_bet >= time_start)||time_bet <= time_now)
                                    {
                                        evening_html+='<button class="btn btn-danger btn-sm" disabled id='+id+' type="button">'+time+'</button>';
                                    }
                                    else{
                                      evening_html+='<button class="btn btn-primary btn-sm time_click" id='+id+' type="button">'+time+'</button>';
                                    }
                                  }

                              });
                          }
                  });
                     }if(time_list['time_night'].length === 0)
                     {
                      evening_html="";
                      for(i=5;i<11;i++)
                          {
                              $.each(minutes, function(index, val_minute) {
                                  var time = i+":"+val_minute+" PM";
                                  var time_now = new Date();
                                  var time_bet = Date.parse(date_booking+" "+time);
                                  var id = time.replace(/[^A-Z0-9]+/ig,'_');
                                  if(time_bet >= time_opend && time_bet < time_closed){
                                    if(time_bet <= time_now){
                                      evening_html+='<button class="btn btn-danger btn-sm" disabled id='+id+' type="button">'+time+'</button>';
                                    }
                                    else{
                                      evening_html+='<button class="btn btn-primary btn-sm time_click" id='+id+' type="button">'+time+'</button>';
                                    }
                                  }
                                });
                          }
                     }
                     if(time_list['time_morning']!=""){

                      $.each(time_list['time_morning'], function(index, val) {
                        morning_html ="";
                        for(i=7;i<13;i++)
                          {
                              $.each(minutes, function(index, val_minute) {
                                  var time = i+":"+val_minute+" AM";
                                  var id = time.replace(/[^A-Z0-9]+/ig,'_');
                                  var time_start = Date.parse(val['date']+" "+val['service_duration_booking']);
                                  var time_end = Date.parse(val['date']+" "+val['time_finish_morning']);
                                  var time_bet = Date.parse(val['date']+" "+time);
                                  var time_now = new Date();
                                  if(time_bet >= time_opend && time_bet < time_closed){
                                    if((time_bet <= time_end && time_bet >= time_start)||time_bet <= time_now)
                                    {
                                        morning_html+='<button class="btn btn-danger btn-sm" disabled id='+id+' type="button">'+time+'</button>';
                                    }
                                    else{
                                      morning_html+='<button class="btn btn-primary btn-sm time_click" id='+id+' type="button">'+time+'</button>';
                                    }
                                  }
                              });
                          }
                  });
                     }if(time_list['time_morning'].length === 0)
                     {
                      morning_html ="";
                        for(i=7;i<13;i++)
                          {
                              $.each(minutes, function(index, val_minute) {
                                var time = i+":"+val_minute+" AM";
                                  var time_now = new Date();
                                  var time_bet = Date.parse(date_booking+" "+time);
                                  var id = time.replace(/[^A-Z0-9]+/ig,'_');
                                  if(time_bet >= time_opend && time_bet < time_closed){
                                    if(time_bet <= time_now ){
                                      morning_html+='<button class="btn btn-danger btn-sm" disabled id='+id+' type="button">'+time+'</button>';
                                    }
                                    else{
                                      morning_html+='<button class="btn btn-primary btn-sm time_click" id='+id+' type="button">'+time+'</button>';
                                    }
                                  }

                              });
                          }
                     }
                  
                     if(time_list['time_afternoon'].length > 0){
                      $.each(time_list['time_afternoon'], function(index, val) {
                          afternoon_html="";
                          for(i=1;i<5;i++)
                          {
                              $.each(minutes, function(index, val_minute) {
                                  var time = i+":"+val_minute+" PM";
                                  var id = time.replace(/[^A-Z0-9]+/ig,'_');
                                  var time_start = Date.parse(val['date']+" "+val['service_duration_booking']);
                                  var time_end = Date.parse(val['date']+" "+val['time_finish_afternoon']);
                                  var time_bet = Date.parse(val['date']+" "+time);
                                  var time_now = new Date();
                                  if(time_bet >= time_opend && time_bet < time_closed){
                                    if( (time_bet <= time_end && time_bet >= time_start)||time_bet <= time_now)
                                    {
                                        afternoon_html+='<button class="btn btn-danger btn-sm" disabled id='+id+' type="button">'+time+'</button>';
                                    }
                                    else{
                                      afternoon_html+='<button class="btn btn-primary btn-sm time_click" id='+id+' type="button">'+time+'</button>';
                                    }
                                  }

                              });
                          }

                  });
                      }else
                      {
                        afternoon_html="";
                        for(i=1;i<5;i++)
                          {
                              $.each(minutes, function(index, val_minute) {
                                  var time = i+":"+val_minute+" PM";
                                  var time_now = new Date();
                                  var time_bet = Date.parse(date_booking+" "+time);
                                  var id = time.replace(/[^A-Z0-9]+/ig,'_');
                                  if(time_bet >= time_opend && time_bet < time_closed){
                                    if(time_bet <= time_now){

                                      afternoon_html+='<button class="btn btn-danger btn-sm" disabled id='+id+' type="button">'+time+'</button>';
                                    }
                                    else{
                                      afternoon_html+='<button class="btn btn-primary btn-sm time_click" id='+id+' type="button">'+time+'</button>';
                                    }
                                  }

                                });
                          }
                      }
            $(".morning_list").html(morning_html);
            $(".afternoon_list").html(afternoon_html);
            $(".evening_list").html(evening_html);
            
            //console.log(time_list);
            $("#wizard_verticle").smartWizard("fixHeight");
        }
        if(time_list['open_close'] === "closed"){
          $("#time_").hide();
          $("#notice").show().text("Today, the salon has closed. Choose another date to view and book. Thanks!");
        }
        
      })
      .fail(function() {
          console.log("error");
      });
}

// function onDayClick(e){
//     alert('onDayClick');
// }
$(document).ready(function() {
  // $('#expense_date').on('changeDate', onChangeDate);
  //$('.day').on('click', onDayClick);
  $(document).on('click','.time_click',function(){
    $("span").removeClass('add-new');
    $(".time_booking").addClass('add-new');
      buttonNextClick();

  });
});

</script>

<script>
    //check validate
    $(document).ready(function(){

        var check = 0;
        $("input[name='customer_phone']").on("blur",function(e){
            var str = $(this).val();
            if(str.length !=10){
                $(this).addClass('is-invalid');
                check = 1;
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });

        // $("input[name='customer_email']").on("blur",function(e){
        //     var str = $(this).val();       
        //     console.log(str.search("@"));
        //     console.log(str.search("\\."));
        //     if(str.search("\\@") == -1 || str.search("\\.") == -1){
        //         check = 1;
        //         $(this).addClass('is-invalid');
        //     }else {
        //         check = 0;
        //         $(this).removeClass('is-invalid').addClass('is-valid');
        //     }
        //     checkSubmit(check);
        // });

        $("input[name='customer_fullname']").on("blur",function(e){
            var str = $(this).val();
            if(str.length <=0){
                $(this).addClass('is-invalid');
                check = 1;
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });


        function checkSubmit(check){
            if(check == 1){
                $("#submit").attr('disabled',true);
            } else {
                $("#submit").attr('disabled',false);
            }
        }

    });
</script>  
<script>
  $(document).ready(function(){
    $(".buttonNext").on('click',function(){
      setTimeout(function(){ 
        var step1 = $("a[href='#step-1']").attr("class");
        var step2 = $("a[href='#step-2']").attr("class");

        if(step1 == 'done' && step2 == 'selected'){
          $(".buttonNext").removeClass("disabled");
        }
      }, 1000);
    })    
  });
</script>
@stop
