@extends('layouts.master')
@section('title', 'Sale Finance / Ticket')
@section('styles')
    
@stop
@section('content')
   <div class="page-title">
    <div class="title_left">
      <h3>View Ticket Appointment #{{$id}}</h3>
    </div>
</div>
<div class="x_panel">
    <form>
        <div class="row">
            <div class="col-sm-5 col-md-6">
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Booking Number </label>          
                    <span class="col-sm-9 form-control-plaintext">#{{$id}}</span>
                 </div>
                 <div class="row">             
                    <label class="col-sm-3 col-form-label">Booking Date: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{format_datetime($customer_item->booking_time_selected)}}</span>
                 </div>
                 <div class="row">             
                    <label class="col-sm-3 col-form-label">Booking Type: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{GeneralHelper::getTitleByBookingType($customer_item->booking_type)}}</span>
                 </div>
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Status: </label>          
                    <span class="col-sm-9">
                        <div name="dropdown_statushtml" id="dropdown_statushtml" class="btn-group">
                          {!!$booking_status_html!!}
                            <!-- <button data-toggle="dropdown" class="btn btn-round btn-primary dropdown-toggle btn-sm" type="button" aria-expanded="false">
                                <i class="fa fa-ticket"></i>
                                <span style="padding: 0px 20px;">NEW APPOINTMENT</span> <span class="caret">                            
                            </span></button>
                            <ul role="menu" name="dropdown_status" id="dropdown_status" class="dropdown-menu" style="min-width: 200px;">
                              <li><a href="#"><i class="fa fa-check-circle-o blue" style="margin-right:10px;"></i> CONFIRMED</a></li>
                              <li class="border-top"><a href="#"><i class="fa fa-arrow-circle-down green" style="margin-right:10px;"></i> STARTED</a></li>
                              <li class="border-top"><a href="#"><i class="fa fa-sign-in red" style="margin-right:10px;"></i> WALK-IN</a></li>
                              <li class="border-top"><a href="#"><i class="fa fa-trash-o gray-dark" style="margin-right:10px;"></i> CANCEL</a></li>
                            </ul>           -->          
                     </div>
                    </span>
                 </div>
                  <div class="row">             
                    <label class="col-sm-3 col-form-label">Booking Note: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{$customer_item->booking_note}}</span>
                 </div>
            </div>
            <div class="col-sm-5 col-md-6">
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Customer: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{GeneralHelper::getTitleByGender($customer_item->customer_gender)}}{{$customer_item->customer_fullname}}
                            <!-- <span class="badge bg-blue-sky" style="font-size: x-small">NEW CUSTOMER<span> -->
                    </span>                    
                </div>
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Gender: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{GeneralHelper::convertGender($customer_item->customer_gender)}}</span>
                </div>    
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Phone: </label>          
                    <span class="col-sm-9 form-control-plaintext">(+{{$customer_item->customer_country_code}}){{$customer_item->customer_phone}}</span>
                </div>
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Email: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{$customer_item->customer_email}}</span>
                </div>    
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Date of Birth: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{format_date($customer_item->customer_birthdate)}}</span>
                </div>    
            </div>
        </div>
        <div class="clearfix"></div>
        <hr class="my-4">
        <div class="row col-sm-12">        
            <div class="table-responsive">
            <table class="table table-borderless table-condensed" style="width: auto;">
                <thead>
                  <tr>
                    <th style="width: 150px;">Rent Station</th>
                    <th style="min-width: 300px;">Service</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($services as $service)
                      <tr>
                        <td>{{$worker_nickname}}</td>
                        <td>{{$service->service_name}}</td>
                        <td> <span class="badge badge-primary badge-pill">${{$service->service_price}}</span></td>
                      </tr>  
                  @endforeach
                </tbody>
              </table>
            </div>
        </div>
        {{-- <div class="row col-sm-12">
                <button type="button" class="btn btn-sm btn-primary">EDIT/RESCHEDULE</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="window.location.href='{{ url("salefinance/payment/$id")}}';">PAYMENT</button>
        </div> --}}
    </form>                  
</div>
@stop
@section('scripts')
  
  <script type="text/javascript">
    $(document).ready(function() {

      $(document).on('click', '#dropdown_status li', function() {
       status_id = $(this).val();
          if(window.confirm("Are you sure you want to Change this Status to "+$(this).text()+" ?"))
          {
            $.ajax({
              url:"{{route('update-ticket-status')}}",
              method:"get",
              data:{status_id:status_id , id:{{$id}} },
              success:function(data)
              {
                  //$( "#dropdown_statushtml" ).html(data);
                  document.getElementById("dropdown_statushtml").innerHTML = data;
                  toastr.success("Change Status Success!");
              }
            })
          }
          else{
            return false;
          }
     });
     

    }); 
  </script> 
@stop

