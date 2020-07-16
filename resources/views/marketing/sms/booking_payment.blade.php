@extends('layouts.master')
@section('title', 'Marketing | SMS | Booking & Payment')
@section('styles')
<style type="text/css">
    .top_nav{height: 84px;}       
     select[name=content_template]{ margin-bottom: 5px;}
     .content_template_message{ background: #f2f3dd; padding: 10px;}
</style>
@stop
@section('content')
<div id="sms" class="col-xs-12 col-md-12 fixLHeight no-padding full-height bg-white">
     <div class="col-xs-2 col-md-2 no-padding full-height scroll-view scroll-style-1">
        @include('marketing.sms.partials.menu') 
    </div>   
    <div class="col-xs-10 col-md-10 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
        <div class="x_panel border-0"> 
            <div class="x_title">
                <h5 class="border_bottom">Setup Booking & Payment</h5>
            </div>
            <div class="x_content">
            <form action="{{ route('post_smsBookingPayment') }}" method="post" id="calendar_form" name="search_form">  
                {{csrf_field()}}
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-top-0" width="150"> Event Type</th>
                            <th class="border-top-0"> Template</th>
                            <th class="border-top-0"> Remind before </th>
                        </tr>
                    </thead>                       
                    <tbody>
                    <tr>
                        <td>Booking Website</td>
                        <td>
                            <select name="booking_website" class="loadContent form-control form-control-sm">
                                <option value=""> -- Content Template -- </option>
                                @foreach ($content_template as $c_t)
                                    <option {{$c_t->template_title == $name_booking ? 'selected' : ''}} value="{{$c_t->sms_content_template_id}}" > {{$c_t->template_title}} </option>
                                @endforeach 
                            </select>
                            <div class="err" id="booking_website" ></div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Remind Appointment</td>
                         <td>
                            <select name="remind_appointment" class="loadContent form-control form-control-sm">
                                <option value=""> -- Content Template -- </option>
                                @foreach ($content_template as $c_t)
                                    <option {{$c_t->template_title == $name_appointment ? 'selected' : ''}} value="{{$c_t->sms_content_template_id}}" > {{$c_t->template_title}} </option>
                                @endforeach
                            </select>
                             <div class="err" id="remind_appointment" ></div>
                        </td>
                        <td>
                            <select name="event_type" class="form-control form-control-sm" style="width:auto; float:left;">
                                <option value="15" {{$remind_before == 15 ? 'selected' : ''}}>15</option>
                                <option value="30" {{$remind_before == 30 ? 'selected' : ''}}>30</option>
                                <option value="60" {{$remind_before == 60 ? 'selected' : ''}}>60</option>
                            </select>
                            &nbsp; (minutes)
                        </td>
                    </tr>
                     <tr>
                        <td>Payment Service</td>
                         <td>
                            <select name="payment_service" class="loadContent form-control form-control-sm">
                                <option value=""> -- Content Template -- </option>
                                @foreach ($content_template as $c_t)
                                    <option {{$c_t->template_title == $name_payment ? 'selected' : ''}} value="{{$c_t->sms_content_template_id}}" > {{$c_t->template_title}} </option>
                                @endforeach 
                            </select>
                            <div class="err" id="payment_service" ></div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                         <td>
                            <button class="btn btn-sm btn-primary">Submit</button>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
               
            </form>
            </div>
        </div>
    </div>
</div> 

@stop
@section('scripts') 

<script type="text/javascript">
$(document).ready(function() {
   $('input.icheckstyle').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
   
   $('.loadContent').on('change',function(){ 
    var id = $(this).children('option:selected').val();    
    var name = $(this).attr('name');
    
    $.ajax({ 
        url:"{{ route('ajax_smsBookingPayment') }}",
        method:"get",
        data:{id:id},
        success:function(data){           

            if(name == 'booking_website'){ 
                
                if(data)
                    $('#booking_website').html('<div class="content_template_message">'+data+'</div>');
                else
                    $('#booking_website').html('');

            }
            else if(name == 'remind_appointment'){ 
                
                if(data)
                    $('#remind_appointment').html('<div class="content_template_message">'+data+'</div>');
                else
                    $('#remind_appointment').html('');

            }else if(name == 'payment_service'){ 
                
                if(data)
                    $('#payment_service').html('<div class="content_template_message">'+data+'</div>');
                else 
                    $('#payment_service').html('');

            }
        }
    })
   });

}); 
</script>      
@stop

