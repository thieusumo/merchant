@extends('layouts.master')
@section('title', 'Page Title')
@section('styles')
    
@stop
@section('content')
<div class="x_panel">
    <form>
        <div class="row">
            <div class="col-sm-5 col-md-6">
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Order ID: </label>          
                    <span class="col-sm-9 form-control-plaintext">#{{$order->order_bill}}</span>
                 </div>
                 <div class="row">             
                    <label class="col-sm-3 col-form-label">Order Date: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{format_datetime($order->order_datetime_payment)}}</span>
                 </div>
                <div class="form-group- row">             
                    <label class="col-sm-3 col-form-label">Order Amount: </label>          
                    <span class="col-sm-9 form-control-plaintext">${{$order->order_price-$tip}}</span>
                 </div>
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Order Tip: </label>          
                    <span class="col-sm-9 form-control-plaintext">${{$tip}}</span>
                </div>
                <div class=" row">             
                    <label class="col-sm-3 col-form-label">Order Total Charge: </label>          
                    <span class="col-sm-9 form-control-plaintext">${{$order->order_price}}</span>
                </div>
            </div>
            <div class="col-sm-5 col-md-6">
                <div class=" row">             
                    <label class="col-sm-3 col-form-label">Payment Type: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{GeneralHelper::convertPaymentType($order->order_payment_method)}}</span>
                </div>
                <div class=" row">             
                    <label class="col-sm-3 col-form-label">CASH: </label>          
                    <span class="col-sm-9 form-control-plaintext">${{$order->order_cash_amount}}</span>
                </div>
                <div class=" row">             
                    <label class="col-sm-3 col-form-label">CREDIT CARD: </label>          
                    <span class="col-sm-9 form-control-plaintext">${{$order->order_card_amount}}</span>
                </div>
                <div class=" row">             
                    <label class="col-sm-3 col-form-label">CC NUMBER: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{ccMasking($order->order_card_number)}}</span>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="row col-sm-12">   
            <div class="row" style="min-width: 280px">             
                    <label class="col-sm-3 col-form-label">Customer: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{GeneralHelper::getTitleByGender($order->customer_gender)}}{{$order->customer_fullname}}</span>
                </div>
                <div class="row" style="min-width: 250px">             
                    <label class="col-sm-3 col-form-label">Phone: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{GeneralHelper::formatPhoneNumber($order->customer_phone,$order->customer_country_code)}}</span>
                </div>
                <div class="row">             
                    <label class="col-sm-3 col-form-label">Email: </label>          
                    <span class="col-sm-9 form-control-plaintext">{{$order->customer_email}}</span>
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
                    @foreach ($order_services as $key => $value)
                        <tr>
                            <td>{{$value->worker_nickname}}</td>
                            <td>{{$value->service_name}}</td>
                            <td> <span class="badge badge-primary badge-pill">${{$value->service_price}}</span></td>
                        </tr> 
                    @endforeach
 
                </tbody>
              </table>
            </div>
        </div>
    </form>                  
</div>
@stop
@section('scripts')
    
@stop

