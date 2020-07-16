@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Management | Loyalty')
@section('styles')
<style type="text/css">
   .background1{
   background-color: #E0EAF6;
   height: 30px;
   }
   .background2{
   background-color: #A9CAEA;
   height: 30px;
   }
   .background3{
   background-color: #B5C6E7;
   }
   .background4{
   background-color: #DEEBF6;
   }
   .paddingtop{
   padding-top: 20px;
   }
   .fonttittle{
   font-size: 13px;
   }
   .fonttittle1{
   font-size: 13px;
   font-weight: bold;
   padding-top: 5px;
   }
   .withbutton{
   width: 150px;
   }
   .glyphicon-plus-sign, .glyphicon-trash{
    cursor: pointer;
    color: #B30909;
    font-size: 23px;
    padding-top: 3px;
   }
   .glyphicon-plus-sign:hover, .glyphicon-trash:hover{
    color: #394148;
   }
   .fa-exchange{
      padding-top: 3px;
   }

</style>
@stop
@section('content')
<div class="x_panel">
   <form action="" method="post">
      @csrf()
      <div class="container">
         <div class="row">
            <!-- left -->
            <div class="col-sm-6" style="padding-left: 0px">
               <div class="row">
                  <!-- Convert from price point -->
                  <div class="col-sm-12 background1"><div class="fonttittle1">Convert from price to point</div>
                  </div>
                  <div class="paddingtop">
                     <div class="col-sm-3 text-center">
                        <div class="input-group mb-3 input-group-sm">
                           <div class="input-group-prepend">
                              <span class="input-group-text">$</span>
                           </div>
                           <input required="" type="number" class="form-control priceToPoint" placeholder="" id="" name="priceToPoint_price" value="{{isset($priceToPoint_price) ? $priceToPoint_price : ''}}">
                        </div>
                     </div>
                     <div class="col-sm-2 text-center">
                        <i class="fa fa-exchange" style='font-size:23px' aria-hidden="true"></i>
                     </div>
                     <div class="col-sm-3 text-left ">
                        <div class="input-group mb-3 input-group-sm">
                           <input required="" type="number" class="form-control priceToPoint" placeholder=""  name="priceToPoint_point" value="{{isset($priceToPoint_point) ? $priceToPoint_point : ''}}">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- end -->
                  <!-- Convert from service to point -->
                  <div class="col-sm-12 background1"><div class="fonttittle1">Convert from service to point</div>
                  </div>
                  <div class="paddingtop">
                     <div class="col-sm-3 text-center">
                        <div class="input-group mb-3 input-group-sm">
                           <input required=""  type="number" class="form-control serviceToPoint" placeholder=""  name="serviceToPoint_service" value="{{isset($serviceToPoint_service) ? $serviceToPoint_service : ''}}">
                           <div class="input-group-append">
                              <span class="input-group-text">service</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-2 text-center">
                        <i class="fa fa-exchange" style='font-size:23px' aria-hidden="true"></i>
                     </div>
                     <div class="col-sm-3 text-left">
                        <div class="input-group mb-3 input-group-sm">
                           <input required="" type="number" class="form-control serviceToPoint" placeholder=""  name="serviceToPoint_point" value="{{isset($serviceToPoint_point) ? $serviceToPoint_point : ''}}">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- end -->
                  <!-- Rewards points when paying by cash -->
                  <div class="col-sm-12 background1"><div class="fonttittle1">Rewards points when paying by cash</div></div>
                  <div class="col-sm-4 text-right paddingtop">
                     <div class="input-group mb-3 input-group-sm">
                        <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_paying_by_cash : ''}}" name="paying_by_cash">
                        <div class="input-group-append">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <!-- end -->
                  <!-- Rewards points based on the times return to the salon in a month -->
                  <div class="col-sm-12 background1"><div class="fonttittle1">Rewards points based on the times return to the salon in a month</div>
                  </div>
                  <br><br><br>
                  @if(isset($returnInAMonth))
                  @foreach ($returnInAMonth as $element)                 
                  <div class="col-md-12">
                     <div class="col-sm-3 text-center">
                        <div class="input-group mb-3 input-group-sm">
                           <input type="number" class="form-control" placeholder="" value="{{$element[0]}}"  name="times[]">
                           <div class="input-group-append">
                              <span class="input-group-text">times</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-2 text-center">
                        <i class="fa fa-exchange" style='font-size:23px' aria-hidden="true"></i>
                     </div>
                     <div class="col-sm-3">
                        <div class="input-group mb-3 input-group-sm">
                           <input type="number" class="form-control" placeholder="" value="{{$element[1]}}" name="point[]">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 text-left">
                        <i class="glyphicon glyphicon-trash deleteGroupReturn" data="data" aria-hidden="true"></i>
                     </div>
                  </div>
                  @endforeach
                  @else
                  <div class="col-md-12">
                     <div class="col-sm-3 text-center">
                        <div class="input-group mb-3 input-group-sm">
                           <input type="number" class="form-control" placeholder="" name="times[]">
                           <div class="input-group-append">
                              <span class="input-group-text">times</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-2 text-center">
                        <i class="fa fa-exchange" style='font-size:23px' aria-hidden="true"></i>
                     </div>
                     <div class="col-sm-3">
                        <div class="input-group mb-3 input-group-sm">
                           <input type="number" class="form-control" placeholder=""  name="point[]">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 text-left">
                        <i class="glyphicon glyphicon-trash deleteGroupReturn" data="data" aria-hidden="true"></i>
                     </div>
                  </div>                  
                  <div class="col-md-12">
                     <div class="col-sm-3 text-center">
                        <div class="input-group mb-3 input-group-sm">
                           <input  type="number" class="form-control" placeholder=""  name="times[]">
                           <div class="input-group-append">
                              <span class="input-group-text">times</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-2 text-center">
                        <i class="fa fa-exchange" style='font-size:23px' aria-hidden="true"></i>
                     </div>
                     <div class="col-sm-3">
                        <div class="input-group mb-3 input-group-sm">
                           <input  type="number" class="form-control" placeholder=""  name="point[]">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 text-left">
                        <i class="glyphicon glyphicon-trash deleteGroupReturn" aria-hidden="true"></i>
                     </div>
                  </div>
                  @endif
                  <div class="appendGroupReturn"></div>
                  {{-- <div class="col-md-12">
                     <div class="col-sm-3 text-center">
                        <div class="input-group mb-3">
                           <input required type="number" class="form-control" placeholder="" name="times[]">
                           <div class="input-group-append">
                              <span class="input-group-text">times</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-2 text-center">
                        <i class="fa fa-exchange" style='font-size:36px' aria-hidden="true"></i>
                     </div>
                     <div class="col-sm-3">
                        <div class="input-group mb-3">
                           <input required type="number" class="form-control" placeholder=""  name="point[]">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 text-left">
                        <i class="glyphicon glyphicon-trash" style='font-size:36px; color: #B30909' aria-hidden="true"></i>
                     </div>
                  </div> --}}
                  <div class="col-sm-5 text-center">
                     <i class="glyphicon glyphicon-plus-sign addGroupReturnMonth" style='font-size:30px;color: #4f93a9; ' aria-hidden="true"></i>
                  </div>
               </div>
            </div>
            <!-- end -->
            <!-- end left -->
            <!-- right -->
            <div class="col-sm-6" style="padding-left: 20px">
               <div class="row">
                  <!-- Gift card -->
                  <div class="col-sm-12 background1"><div class="fonttittle1">Gift card</div>
                  </div>
                  <br><br><br>
                     <div class="col-sm-7" style="height: 20px;">Rewards points when referral gift card
                     </div>
                     <div class="col-sm-3 text-left">
                        <div class="input-group mb-3 input-group-sm">
                           <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_referral_gift_card : ''}}"  name="referral_gift_card">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                  
                  
                     <div class="col-sm-7" style="height: 20px;">Rewards points when buying gift card</div>
                     <div class="col-sm-3 text-left">
                        <div class="input-group mb-3 input-group-sm">
                           <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_buying_gift_card : ''}}" name="buying_gift_card">
                           <div class="input-group-append">
                              <span class="input-group-text">Point</span>
                           </div>
                        </div>
                     </div>
                  
                  <!-- Customer style -->
                  <div class="col-sm-12 background1"><div class="fonttittle1">Customer style</div>
                  </div>
                  <div class="col-sm-7 paddingtop" style="height: 20px;">Rewards points for new customer</div>
                  <div class="col-sm-3 text-left paddingtop">
                     <div class="input-group mb-3 input-group-sm">
                        <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_new_customer : ''}}" name="new_customer">
                        <div class="input-group-append">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-7" style="height: 20px;">Bonus points for VIP customer</div>
                  <div class="col-sm-3 text-left">
                     <div class="input-group mb-3 input-group-sm">
                        <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_vip_customer : ''}}"  name="vip_customer">
                        <div class="input-group-append">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <!-- MEMBERSHIP -->
                  <div class="col-sm-12 background1"><div class="fonttittle1">MEMBERSHIP</div></div>
                  <div class="col-sm-7 paddingtop" style="height: 20px;">Rewards points for normal</div>
                  <div class="col-sm-3 paddingtop">
                     <div class="input-group mb-3 input-group-sm">
                        <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_for_normal : ''}}"  name="for_normal">
                        <div class="input-group-append">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-7" style="height: 20px;">Rewards points for Siver</div>
                  <div class="col-sm-3 text-center">
                     <div class="input-group mb-3 input-group-sm">
                        <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_for_siver : ''}}" name="for_siver">
                        <div class="input-group-append">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-7" style="height: 20px;">Rewards points for Golden</div>
                  <div class="col-sm-3 text-center">
                     <div class="input-group mb-3 input-group-sm">
                        <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_for_golden: ''}}"  name="for_golden">
                        <div class="input-group-append">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-7" style="height: 20px;">Bonus points for Dimond</div>
                  <div class="col-sm-3 text-center">
                     <div class="input-group mb-3 input-group-sm">
                        <input required type="number" class="form-control" placeholder="" value="{{isset($loyalty)? $loyalty->loyalty_for_dimond: ''}}" name="for_dimond">
                        <div class="input-group-append">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- end right -->
            <!-- convert point to amount and customer style base on point -->
            <!-- <div class="col-sm-12"> -->
            <div class="col-sm-6 background2" style="border:1px solid white; margin-left: 0px;"><div class="fonttittle1">convert point to amount</div></div>
            <div class="col-sm-6 background2" style="border:1px solid white"><div class="fonttittle1">customer style base on point</div></div>
            <div class="col-sm-6">
               <div class="paddingtop">
                  <div class="col-sm-3" style="padding-left: 0px;">
                     <div class="input-group mb-3 input-group-sm">
                        <input required="" name="pointToAmount_point"  type="number" class="form-control pointToAmount" placeholder=""  value="{{isset($pointToAmount_point) ? $pointToAmount_point : ''}}">
                        <div class="input-group-prepend">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-2 text-center">
                     <i class="fa fa-exchange" style='font-size:23px' aria-hidden="true"></i>
                  </div>
                  <div class="col-sm-3">
                     <div class="input-group mb-3 input-group-sm">
                        <div class="input-group-append">
                           <span class="input-group-text">$</span>
                        </div>
                        <input required="" name="pointToAmount_amount" type="number" class="form-control pointToAmount" placeholder="" value="{{isset($pointToAmount_amount) ? $pointToAmount_amount : ''}}" >
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-sm-6">
               <div class="paddingtop">
                  <div class="col-sm-1"> </div>
                  <div class="col-sm-4 text-center">
                     <div class="input-group mb-3 input-group-sm">
                        <input required="" type="number" class="form-control" placeholder=""  value="{{isset($loyalty)? $loyalty->loyalty_vip_point: ''}}" name="vip_point">
                        <div class="input-group-prepend">
                           <span class="input-group-text">Point</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-2 text-center">
                     <i class="fa fa-exchange" style='font-size:23px' aria-hidden="true"></i>
                  </div>
                  <div class="col-sm-3">
                     <strong style="font-size: 24px;">VIP</strong>
                  </div>
               </div>
            </div>
            <!-- </div> -->
            <!-- end convert -->
            <!-- submit -->
            <div class="col-sm-12">
               <div style="text-align: center; margin-top: 10px;">
                  <button type="button" class="btn btn-danger withbutton clear">Clear</button>
                  <input type="submit" class="btn btn-primary withbutton" value="Submit" />
               </div>
            </div>
            <!-- end submit -->
         </div>
      </div>
</div>
</div>
</form>
</div>
@stop
@section('scripts')
<script type="text/javascript">
   $(document).ready(function(){
   
     $('form .clear').on('click',function(e){
       e.preventDefault();
       $("input[type='number']").val('');
     });
     //check validate
     $(".priceToPoint").on('blur',function(e){
      // var price = $("input[name='priceToPoint_price']").val();
      // var point = $("input[name='priceToPoint_point']").val();     
      
      if($(this).val() == '' ){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $(".serviceToPoint").on('blur',function(e){
      var service = $("input[name='serviceToPoint_service']").val();
      var point = $("input[name='serviceToPoint_point']").val();

      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $(".pointToAmount").on('blur',function(e){
      var point = $("input[name='pointToAmount_point']").val();
      var amount = $("input[name='pointToAmount_amount']").val();
      console.log(parseInt(point)/parseInt(amount));
      if($(this).val() == '' || parseInt(point)/parseInt(amount) < 10){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='paying_by_cash']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='referral_gift_card']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='buying_gift_card']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='new_customer']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='vip_customer']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='for_normal']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='for_siver']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='for_golden']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='for_dimond']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });
     $("input[name='vip_point']").on('blur',function(e){
      if($(this).val() == ''){
       InputError(this);
      }else{
       InputSuccess(this);
      }
     });  

     $(".addGroupReturnMonth").on('click',function(e){
      e.preventDefault();
      var html = '<div class="col-md-12">'
                    +'<div class="col-sm-3 text-center">'
                        +'<div class="input-group mb-3 input-group-sm">'
                           +'<input  type="number" class="form-control" placeholder=""  name="times[]">'
                           +'<div class="input-group-append">'
                              +'<span class="input-group-text">times</span>'
                           +'</div>'
                        +'</div>'
                     +'</div>'
                     +'<div class="col-sm-2 text-center">'
                        +'<i class="fa fa-exchange" style="font-size:23px" aria-hidden="true"></i>'
                     +'</div>'
                     +'<div class="col-sm-3">'
                        +'<div class="input-group mb-3 input-group-sm">'
                           +'<input  type="number" class="form-control" placeholder=""  name="point[]">'
                           +'<div class="input-group-append">'
                              +'<span class="input-group-text">Point</span>'
                          +'</div>'
                        +'</div>'
                     +'</div>'
                     +'<div class="col-sm-3 text-left">'
                        +'<i class="glyphicon glyphicon-trash deleteGroupReturn" aria-hidden="true"></i>'
                     +'</div>'
                  +'</div>';

      $(".appendGroupReturn").append(html);      
     });   

     $(document).on('click','.deleteGroupReturn',function(e){
      e.preventDefault();    

      if($(this).attr('data') != 'data'){
         $(this).parent().parent().html('');
      }else{
         toastr.warning('Error Delete');
      }     
      
     });
   
   });
   function InputError(tag){
        $(tag).removeClass('is-valid');
        $(tag).addClass('is-invalid');
        $("input[type='submit']").attr('disabled',true);
   }
   function InputSuccess(tag){
        $(tag).removeClass('is-invalid');
        $(tag).addClass('is-valid');
        var check = $("input[type='number']").parent().find(".is-invalid");        
        if(check.length == 0){
          $("input[type='submit']").attr('disabled',false);
        }
   }
</script>            
@stop