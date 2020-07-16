@extends('layouts.master')
@section('title', 'Marketing | SMS | CREATE SEND SMS EVENT ')
@section('styles')
<link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">  
<style type="text/css">
    .repeat{
        display: none;
    }
    .add_more_phone{
        display: none;
    }
    .top_nav{height: 84px;}       
    select[name=content_template]{ margin-bottom: 5px;}
    ul.dayinweek li{ display: inline-block; min-width: 80px; padding: 4px 0px;}
    ul.dayinweek li span{ margin-left: 5px;}
    .inputsendto{ padding-left:24px;}
    .hidden{ display: none;}
</style>
@stop
@section('content')
 
<div class="col-xs-12 col-md-12 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
    <div class="x_panel border-0"> 
        <div class="x_title">
            <h5 class="border_bottom">CREATE SEND SMS EVENT</h5>
        </div>
        <div class="x_content">
        <form action="{{route('send-sms-coupon')}}" method="post" id="calendar_form" name="search_form" enctype="multipart/form-data">
        {{csrf_field()}}  
            <div class="col-xs-12 col-md-12 no-padding">
                <div class="col-xs-6 col-md-6 no-padding"> 
                    <div class="row form-group">
                         <label for="event_title" class="col-xs-3 col-md-3">Title</label>
                         <div class="col-xs-8 col-md-8">
                             <input required="" type="text" class="form-control form-control-sm {{$errors->has('event_name') ? 'is-invalid' : ''}}" value="{{$coupon_title}}" id="event_title" name="event_name"/>
                         </div>            
                    </div>        
                    <div class="row form-group">
                         <label for="event_type" class="col-xs-3 col-md-3 ">Type</label>
                         <div class="col-xs-8 col-md-8">
                             <select name="event_type" id="event_type" class="form-control form-control-sm">
                                 @foreach( $listEventType as $id => $name)                            
                                        <option value="{{ $id }}">{{ $name }}</option>                                     
                                  @endforeach                                     
                             </select>
                         </div>            
                    </div>                         
                     <div class="row form-group">
                         <label for="content_template" class="col-xs-3 col-md-3">Sms Message</label>
                         <div class="col-xs-8 col-md-8">
                             <select required="" id="content_template" name="content_template" class="form-control form-control-sm">
                                 <option value=""> -- Content Template -- </option>
                                 @foreach ($template_list_default as $c_t)
                                     <option value="{{$c_t->sms_content_template_id}}">{{$c_t->template_title}}</option>
                                 @endforeach
                             </select>
                             <textarea class="form-control" readonly="readonly" id="sms_message" rows="2" cols="50"></textarea>                               
                         </div>            
                    </div> 
                    <div class="row">
                         <label for="start_date" class="col-xs-3 col-md-3">Start Date</label>
                         <div class="col-xs-8 col-md-8 input-group-spaddon">
                             <div class="col-xs-6 col-md-6 no-padding">
                                <div class='input-group date'>                    
                                    <input type='text' id="start_date"  value="{{old('start_date')}}" class="form-control form-control-sm datepicker {{$errors->has('start_date') ? 'is-invalid' : ''}}"  required="required"/>
                                    <input type='hidden'  name="start_date" value="{{old('start_date')}}" />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>                                     
                                </div>
                             </div>                                     
                             <div class="col-xs-6 col-md-6" style="padding-right:0px;">
                                <div class='input-group date'>  
                                    <input type='text' id="start_time" name="start_time" value="{{old('start_time')}}" class="form-control form-control-sm timepicker {{$errors->has('start_time') ? 'is-invalid' : ''}}"  required="required" placeholder="Time Send" />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>                
                                      
                             </div>
                         </div>            
                    </div> 
                    <div class="row form-group">
                         <label for="repeat" class="col-xs-3 col-md-3 repeat">Repeat</label>
                          <div class="col-xs-8 col-md-8">
                              <select id="repeat" name="repeat" class="form-control form-control-sm repeat">
                                  <option value="no">Don't repeat</option>
                                  <option value='w'>Weekly</option>
                                  <option value='m'>Monthly</option>
                                  <option value='y'>Yearly</option>
                              </select>
                         </div>           
                    </div> 
                    <div class="row repeat_no">
                         <label for="send_before" class="col-xs-3 col-md-3">Send before</label>
                          <div class="col-xs-8 col-md-8">
                              <div class="input-group input-group-spaddon">                                    
                                <input type="number" class="form-control form-control-sm {{$errors->has('send_before') ? 'is-invalid' : ''}}" value="{{old('send_before')}}"  onkeypress="return isNumberKey(event)" name="send_before" style="max-width: 100px;">
                                <span class="input-group-addon" style="width:auto">days</span> 
                              </div>
                         </div>           
                    </div>                         
                    <div class="row form-group repeat_y hidden">
                         <label for="repeat_year" class="col-xs-3 col-md-3">Repeat on</label>
                         <div class="col-xs-8 col-md-8">
                             <ul class="no-padding" style="list-style: none;">
                                <li class="list-inline-item">day</li>
                                <li class="list-inline-item"><select name="repeat_year_day" class="form-control form-control-sm" style="width:60px">
                                     @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                     @endfor
                                    </select></li>
                                <li class="list-inline-item">month</li>  
                                <li class="list-inline-item"><select name="repeat_year_month" class="form-control form-control-sm" style="width:60px">
                                     @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                     @endfor
                                    </select></li>
                                <li class="list-inline-item">every year</li>          
                             </ul>
                         </div>           
                    </div>
                    <div class="row form-group repeat_m hidden">
                         <label for="repeat_month_day" class="col-xs-3 col-md-3">Repeat on</label>
                         <div class="col-xs-8 col-md-8">
                             <ul class="no-padding" style="list-style: none;">
                                <li class="list-inline-item">day</li>
                                <li class="list-inline-item"><select name="repeat_month_day" class="form-control form-control-sm" style="width:60px">
                                     @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                     @endfor
                                    </select></li>
                                <li class="list-inline-item">every month</li>          
                             </ul>
                         </div>           
                    </div>
                    <div class="row form-group repeat_w hidden">
                         <label for="repeat_weekly" class="col-xs-3 col-md-3">Repeat on</label>
                         <div class="col-xs-8 col-md-8">
                             <ul class="list-inline dayinweek">
                                 <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="1"><span>Monday</span></li>
                                 <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="2"><span>Tuesday</span></li>
                                 <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="3"><span>Wednesday</span></li>
                                 <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="4"><span>Thursday</span></li>
                                 <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="5"><span>Friday</span></li>
                                 <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="6"><span>Saturday</span></li>
                                 <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="0"><span>Sunday</span></li>
                             </ul>       
                         </div>           
                    </div> 
                    <div class="row repeat_w repeat_m repeat_y hidden">
                         <label for="end_date" class="col-xs-3 col-md-3">End Date</label>
                          <div class="col-xs-8 col-md-8 input-group-spaddon">                                  
                             <div class='col-xs-6 col-md-6 no-padding input-group date'>                    
                                 <input type='text' id="end_date"  value="{{old('end_date')}}" class="form-control form-control-sm datepicker {{$errors->has('end_date') ? 'is-invalid' : ''}}"  />
                                 <input type='hidden'  name="end_date" value="{{old('end_date')}}"  />
                                 <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                 </span>
                             </div>                
                         </div>           
                    </div> 
                </div>
                
                <div class="col-md-6">                         
                    <h6>Send to</h6>
                    <div class="form-group">
                        <div class="inputsendto">
                           <select name="group_receiver" class="form-control form-control-sm">
                                   <option value="">--Group Receiver--</option>
                                   @foreach ($data1 as $key => $value)
                                        @php
                                            $ten=$value['name'];
                                        @endphp
                                       <option value="{{$key}}" id=group_{{$key}}>-{{ucwords($value['name'])}} - Clients: {{$value[$ten]}}</option>
                                       {{-- <option value="16" >BIRTHDAY JANUARY - Clients: {{$BIRTHDAY_JANUARY}}</option>
                                       <option value="17" >BIRTHDAY FEBRUARY - Clients: {{$BIRTHDAY_FEBRUARY}}</option>
                                       <option value="18" >BIRTHDAY MARCH - Clients: {{$BIRTHDAY_MARCH}}</option>
                                       <option value="19" >BIRTHDAY APRIL - Clients: {{$BIRTHDAY_APRIL}}</option>
                                       <option value="20" >BIRTHDAY MAY - Clients: {{$BIRTHDAY_MAY}}</option>
                                       <option value="21" >BIRTHDAY JUNE - Clients: {{$BIRTHDAY_JUNE}}</option>
                                       <option value="22" >BIRTHDAY JULY - Clients: {{$BIRTHDAY_JULY}}</option>
                                       <option value="23" >BIRTHDAY AUGUST - Clients: {{$BIRTHDAY_AUGUST}}</option>
                                       <option value="24" >BIRTHDAY SEPTEMBER - Clients: {{$BIRTHDAY_SEPTEMBER}}</option>
                                       <option value="25" >BIRTHDAY OCTOBER - Clients: {{$BIRTHDAY_OCTOBER}}</option>
                                       <option value="26" >BIRTHDAY NOVEMBER - Clients: {{$BIRTHDAY_NOVEMBER}}</option>
                                       <option value="27" >BIRTHDAY DECEMBER - Clients: {{$BIRTHDAY_DECEMBER}}</option> --}}
                                   @endforeach
                           </select>
                        </div>    
                    </div>
                    <div class="form-group">  
                         <div class="inputsendto">
                            <button type="submit" class="btn btn-sm btn-primary" id="btnSubmit">Submit</button>
                        </div>
                    </div>  
                </div>
            </div>    
        </form>
        </div>
    </div>
</div>

@stop
@section('scripts') 
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>   
<script type="text/javascript">
$(document).ready(function() {
   $('input.icheckstyle').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    $('input#start_date').daterangepicker({         
        singleDatePicker: true, 
        isInvalidDate: true,
        autoUpdateInput: false,
        minDate: moment()
    });        
    $('input#end_date').daterangepicker({         
        singleDatePicker: true, 
        isInvalidDate: true,
        autoUpdateInput: false,
        minDate: moment()
    });        
    $('input#end_date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
        $('input[name="end_date"]').val(picker.startDate.format('YYYY/MM/DD'));
    });
    $('input#start_date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
        $('input[name="start_date"]').val(picker.startDate.format('YYYY/MM/DD'));
    });

    $('input#end_date').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('input.timepicker').datetimepicker({            
           format: 'hh:mm A',
    }).on('dp.change', function (e) {
        $(e.target).trigger('change');
    });
    $('select#repeat').on('click', function() {
         var arrDiv = ['repeat_no','repeat_w','repeat_m','repeat_y'];
         $.each(arrDiv, function(idx, item){
            $("div."+item).hide(); 
         });
         $("div.repeat_"+$(this).val()).show();
    });
}); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
        //ajax load content template
        check();
        $('#content_template').on('change',function(){
            var id = $(this).children('option:selected').attr("value");
            
            $.ajax({
                url:"{{ route('get-content-template-booking') }}",
                method:"get",
                data:{id:id},
                success:function(data){
                       $("#sms_message").val(data);                    
                }
            });
        });
        $("#event_type").change(function(event) {
            check();
        });

        function check(){
            var event_type_id = $("#event_type option:selected").val();

            if(event_type_id == 1){
                for (var i = 0; i <= 27; i++) {
                    $("#group_"+i).show();
                }
                for (var i = 0; i <= 15; i++) {
                    $("#group_"+i).hide();
                }
            }
            if(event_type_id == 2){
                for (var i = 0; i <= 27; i++) {
                    $("#group_"+i).show();
                }
                for (var i = 0; i <= 7; i++) {
                    $("#group_"+i).hide();
                }
                for (var i =16; i <= 27; i++) {
                    $("#group_"+i).hide();
                }
            }
            if(event_type_id == 3 && event_type_id != 4){
                for (var i = 0; i <= 27; i++) {
                    $("#group_"+i).show();
                }
            }
        }
    }); 
</script>      
@stop
