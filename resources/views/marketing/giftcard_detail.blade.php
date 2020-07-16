@extends('layouts.master')
@section('title', 'Marketing | Gift cards | Gift card Detail')
@section('styles')
    
@stop
@section('content')
<div class="x_panel x_panel_form">
    <div class="x_content">
        <div class="row">
           <div class="col-sm-5 col-md-6">
               <h4>Gift Card Information</h4>
               <div class="ln_solid" style="margin: 0px 0px 15px 0px;"></div> 
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Gift Code: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$giftcart_detail->giftcode_code}}</span>                    
               </div>
                
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Price: </label>          
                   <span class="col-sm-9 form-control-plaintext">${{$giftcart_detail->giftcode_price}}</span>
               </div>
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Balance: </label>          
                   <span class="col-sm-9 form-control-plaintext">${{$giftcart_detail->giftcode_surplus}}</span>
               </div>    
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Loyalty Referral: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$giftcart_detail->giftcode_loyalty_referral}}</span>
               </div>   
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Type: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$giftcart_detail->giftcode_type==1 ? 'VIP' :'Normal'}}</span>
               </div>
                <div class="row">             
                   <label class="col-sm-3 col-form-label">Created Date: </label>          
                   <span class="col-sm-9 form-control-plaintext"> {{format_datetime($giftcart_detail->created_at)}}</span>
               </div> 
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Expired Date: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{format_date($giftcart_detail->giftcode_date_expire)}}</span>
               </div>               
           </div>
           <div class="col-sm-5 col-md-6">
               <h4>Billing & Payment Information</h4>
               <div class="ln_solid" style="margin: 0px 0px 15px 0px;"></div> 
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Client Name: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$customer_fullname}}</span>
               </div>
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Client Phone: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$customer_phone}}</span>
               </div>
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Client Email: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$customer_email}}</span>
               </div>
                <div class="row">             
                   <label class="col-sm-3 col-form-label">Address: </label>          
                   <span class="col-sm-9 form-control-plaintext">
                       {{$customer_address}}
                   </span>
               </div>
                <div class="row">             
                   <label class="col-sm-3 col-form-label">Card type: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$cart_type}}</span>
               </div>
                <div class="row">             
                   <label class="col-sm-3 col-form-label">Card Number: </label>          
                   <span class="col-sm-9 form-control-plaintext">{{$cart_number}}</span>
               </div>
               <div class="row">             
                   <label class="col-sm-3 col-form-label">Transaction ID: </label>          
                   <span class="col-sm-9 form-control-plaintext"></span>
               </div>
           </div>
       </div>
        <div class="ln_solid"></div> 
        <div class="row col-md-12">
            <h4>Statement History</h4>
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                     <th class="text-center">Order #</th>
                    <th class="text-center">Order Date</th>       
                    <th class="text-center">Total Charge($)</th>
                    <th class="text-center">Client Name</th>
                    <th class="text-center">Client Phone</th>                                        
                    <th class="text-center">Rent Station</th>                                        
                  </tr>
                </thead>
                <tbody>
                  @foreach ($statement_history as $s_h)
                                     
                  <tr>
                    <td class="text-center"><a href="/marketing/giftcard/detail/{{$s_h->giftcode_code}}">{{$s_h->order_id}}</a></td>             
                    <td class="text-center">{{format_datetime($s_h->created_at)}}</td>          
                    <td class="text-right">{{$s_h->order_price}}</td>                       
                    <td class="text-left" >{{$s_h->customer_fullname}} </td>                
                    <td class="text-center">{{$s_h->customer_phone}}</td> 
                    <td class="text-left" >{{$s_h->worker_nickname}}</td>                
                  </tr>

                   @endforeach
                    
                </tbody>    
            </table>  
        </div>
    </div>
</div>    
@stop
@section('scripts')

@stop

