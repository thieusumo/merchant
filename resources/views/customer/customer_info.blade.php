@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Clients | Client Information')
@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
@stop
@section('content')
<!-- <div class="row"> -->
<div class="x_panel x_panel_form">
   <div class="x_title">
      <h3>Client Information</h3>
   </div>
   <div class="x_content">
      <div class="col-sm-5 col-md-6 ">              
         <span class=" form-control-plaintext"><h5>{{isset($customer_item->customer_gender) ? GeneralHelper::getTitleByGender($customer_item->customer_gender) : ''}}{{isset($customer_item->customer_fullname) ? $customer_item->customer_fullname : ''}}</h5>
         </span>                    
         <span class=" form-control-plaintext"><h5>{{isset($customer_item) ? $customer_item->customer_email : ''}}</h5></span>
      </div>
      <div class="col-sm-5 col-md-6 ">
         <span class=" form-control-plaintext"><h5>{{isset($customer_item) ? GeneralHelper::formatPhoneNumber($customer_item->customer_phone,$customer_item->customer_country_code) :''}}</h5></span>
         {{-- <span class=" form-control-plaintext">{{($customer_item->pos_update)}}</span>          --}}
      </div>
      <div class="clearfix"></div>
      <hr>
      <div class="col-sm-5 col-md-6 ">
         <span class=" form-control-plaintext"><b>Membership lever: </b>{{isset($customer_item) ? $customer_item->membership_name :''}}</span>
         <span class=" form-control-plaintext"><b>Client Type: </b>{{(isset($client_type) ? $client_type :'')}}</span>         
         <span class=" form-control-plaintext"><b>First visit: </b>{{isset($first_visit) ? format_datetime($first_visit) : ''}}</span>         
         <span class=" form-control-plaintext"><b>Last visit: </b>{{isset($last_visit) ? format_datetime($last_visit->order_datetime_payment) : ''}}</span>         
         <span class=" form-control-plaintext"><b>Last review: </b> 
        @for ($i = 0; $i < $rating->cr_rating; $i++)
           <i class="text-warning fa fa-star"></i>
        @endfor </span> 
         {{-- <input type="hidden" id="rating" value="{{isset($rating) ? $rating->cr_rating :'0'}}">         --}}
         <span class=" form-control-plaintext"><b>Last staff: </b>{{isset($last_visit) ? $last_visit->worker_nickname : ''}}</span>         
         <span class=" form-control-plaintext"><b>Visit count: </b>{{(isset($count_visit) ? $count_visit : '')}}</span>         
      </div>
      <div class="col-sm-5 col-md-6 ">
         <span class=" form-control-plaintext"><b>Total spend: </b>${{isset($total_spend) ? $total_spend : ''}}</span>
         <span class=" form-control-plaintext"><b>Current reward points: </b>{{isset($customer_item) ? $customer_item->customer_point_total :''}}</span>         
         <span class=" form-control-plaintext"><b>Reward earned value: </b>${{isset($rewardEarnedValue) ? $rewardEarnedValue : ''}}</span>         
         <span class=" form-control-plaintext"><b>NOTE: </b>{{isset($customer_item) ? ($customer_item->customer_note) :''}}</span>         
         <span class=" form-control-plaintext"><b>Ticket history: </b></span>         
         <span class=" form-control-plaintext"><b>Review history: </b>{{isset($rating) ? $rating->cr_description :''}}</span>         
         <span class=" form-control-plaintext"><b>Gift card: </b></span>  
    
      

      </div>
   </div>
</div>
<div class="x_panel">
<div class="tabbable-line">
   <ul class="nav nav-tabs" id="cus-info-tab">
      <li class="nav active"><a id='1' href="#tab1" data-toggle="tab">Booking </a></li>
      <li class="nav"><a id='2' href="#tab2" data-toggle="tab">Order History</a></li>
      <li class="nav"><a id='3' href="#tab3" data-toggle="tab">Reviews</a></li>
   </ul>
</div>
<div class="clearfix">&nbsp;</div>
<div class="row col-md-12 tab-content">
   <!-- tab1 -->
   <div class="tab-pane active show" id="tab1" style="width:100%">
      <div class="x_content">
         <table id="datatableTicket" class="table table-striped table-bordered" style="width:100%">
            <thead>
               <tr>
                  <th class="text-center" width="70" nowrap="nowrap">Booking ID </th>
                  <th class="text-center" width="80" >Date</th>
                  <th class="text-center" width="40" >Time</th>
                  <th class="text-right" width="60" >Duration</th>
                  <th>Rent Station & Service</th>
                  <th class="text-center" width="120">Status</th>
               </tr>
            </thead>
         </table>
      </div>
   </div>
   <!-- tab2 -->
   <div class=" tab-pane" id="tab2" style="width:100%">
      <div class="x_content">
         <table id="datatableOrder" class="table table-striped table-bordered" style="width:100%">
            <thead>
               <tr>
                  <th>Order ID </th>
                  <th>Order Date</th>
                  <th>Rent Station</th>
                  <th>Amount($)</th>
                  <th>Tip($)</th>
                  <th>Total Charge($)</th>
                  <th>Payment Type</th>
               </tr>
            </thead>
         </table>
      </div>
   </div>
   <!-- tab3 -->
   <div class="tab-pane" id="tab3" style="width:100%">
      <div class="x_content">
      </div>
   </div>
</div>
@stop
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
       $(document).on('click','#cus-info-tab li a', function() {        
           $('#cus-info-tab li a').removeClass();      
           $(this).addClass('active');
            $('#cus-info-tab li').removeClass('active');  
             $(this).parent().addClass('active');
       });
   
       oTableTicket = $('#datatableTicket').DataTable({
            dom: 'lfrtip',            
            processing: true,
            serverSide: true,
            ajax:{ url:"{{ route('get-booking') }}" ,
            data: function (d) {
                           d.id = {{$id}};
                         }
                       },
            columns: [
   
                     { data: 'booking_id', name: 'booking_id' },
                     { data: 'booking_date', name: 'booking_date'},
                     { data: 'booking_time', name: 'booking_time' },
                     { data: 'duration' , name:'duration'},
                     { data: 'rentstation_service' , name:'rentstation_service'},
                     { data: 'status' , name: 'status',  orderable: false, searchable: false }
             ],
            columnDefs: [
               {
                   "targets": 0, 
                   "className": "text-center"
              },
              {
                   "targets": 1, 
                   "className": "text-center"
              },
              {
                   "targets": 2, 
                   "className": "text-center"
              },
              {
                   "targets": 3, 
                   "className": "text-right"
              },
               {
                   "targets": 4, 
                   "className": "text-left"
              },
              {
                   "targets": 5,
                   "className": "text-center",
              }
              ],
       }); 
   // debugger;
   
   
       oTableOrder = $('#datatableOrder').DataTable({
            dom: 't',            
            processing: true,
            serverSide: true,
            ajax:{ url:"{{ route('get-orders') }}" ,
            data: function (d) {
                           d.id = '{{$id}}';
   
                         }
                       },
            columns: [
                     { data: 'order_bill', name: 'order_bill' },
                     { data: 'order_datetime_payment', name: 'order_datetime_payment' },
                     { data: 'worker_nickname', name: 'worker_nickname' },
                     { data: 'order_price' , name:'order_price'},
                     { data: 'orderdetail_tip' , name:'orderdetail_tip'},
                     { data: 'order_receipt' , name:'order_receipt'},
                     { data: 'status' , name: 'status',  orderable: false, searchable: false }
             ],
            columnDefs: [
               {
                   "targets": 0, 
                   "className": "text-center"
              },
              {
                   "targets": 1, 
                   "className": "text-center"
              },
              {
                   "targets": 2, 
                   "className": "text-center"
              },
              {
                   "targets": 3, 
                   "className": "text-right"
              },
               {
                   "targets": 4, 
                   "className": "text-left"
              },
              {
                   "targets": 5,
                   "className": "text-center",
              }
              ],
       }); 
   
   });
</script>    
<script>

  /* Javascript */
 
// $(function () {
//   var rating = $("#rating").val();
//   $("#rateYo").rateYo({
//     rating: rating,
//     fullStar: true,
//     readOnly: true
//   });
// });


</script>
@stop
