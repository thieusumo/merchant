@extends('layouts.master')
@section('title', ($id!=0)?'Clients | Edit Client':'Clients | Add Client')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="x_panel x_panel_form">
    <div class="x_title">
         <h3>@if($id!=0) Edit Client @else Add Client @endif</h3>
     </div>
    <div class="x_content">
    <form class="form-horizontal label-date" method="post" id="customer_form" action="{{route('save-customer')}}" name="frm" custom-submit="" novalidate="novalidate">
       @csrf
       <input type="hidden" name="customer_id" value="{{$id}}" />
        <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-2">Full name</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input type='text' id="customer_fullname" required name="customer_fullname" value="{{isset($customer_item->customer_fullname)? $customer_item->customer_fullname:old('customer_fullname')}}" class="form-control form-control-sm{{ $errors->has('customer_fullname') ? ' is-invalid' : '' }}"/>
             </div>            
        </div>    
        <div class="row form-group">
            <label class="col-xs-3 col-sm-3 col-md-2">Cellphone</label>
            <div class="col-xs-3 col-sm-3 col-md-3 no-padding input-group-country-phone">
                <div class="btn-group btn-group-sm btn-countrycode">
                 <button  id="current_country_selected" data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle{{ $errors->has('current_country_selected') ? ' is-invalid' : '' }}" type="button" aria-expanded="false">{{isset($customer_item->customer_country_code)? $customer_item->customer_country_code:Session::get('selected_country_code')}}  <span class="caret"></span></button>
                 <ul role="menu" id="dropdown_country" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 31px, 0px);">
                    @foreach($data['headNumber'] as $value)
                     <li value ="{{$value}}"><a value ="{{$value}}" href="#">{{$value}}</a></li>
                    @endforeach
                     <!-- <li value ="84" ><a value ="84" href="#">84</a></li>
                     <li value ="61"><a value ="61" href="#">61</a></li> -->                        
                 </ul>
                 <input type="hidden" name="country_code" id="country_code" value="{{isset($customer_item->customer_country_code)? $customer_item->customer_country_code:Session::get('selected_country_code')}}">
                 </div>                   
                 <input class="form-control form-control-sm maskphone{{ $errors->has('customer_phone.exists') ? ' is-invalid' : '' }}" required placeholder="Phone Number" id="customer_phone" name="customer_phone" value="{{isset($customer_item->customer_phone)? $customer_item->customer_phone:old('customer_phone')}}" type="number" data-inputmask="'mask' : '(999) 999-9999'">    
                 <div class="invalid-feedback">{{ $errors->first('customer_phone.exists')}}</div>             
            </div>
        </div>    
        <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-2">Email</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input type="email" id="customer_email" required value="{{isset($customer_item->customer_email)? $customer_item->customer_email:old('customer_email')}}" name="customer_email" class="form-control form-control-sm{{ $errors->has('customer_email') ? ' is-invalid' : '' }}"/>
             </div>            
        </div>    
        <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-2">Date of Birth</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                 <div class="input-group input-group-sm form-inline">
                    <input type='text' value="{{isset($customer_dateofbirth)?$customer_dateofbirth:''}}" id="customer_dateofbirth" name="customer_dateofbirth" class="form-control form-control-sm dateofbirth{{ $errors->has('customer_dateofbirth') ? ' is-invalid' : '' }}" data-format="DD-MM-YYYY" data-template="D MMM YYYY"/>
                    <div class="invalid-feedback">{{ $errors->first('customer_dateofbirth')}}</div>   
                </div>
             </div>            
        </div>    
        <div class="row form-group">
            <label class="col-xs-3 col-sm-3 col-md-2">Gender</label>
            <div class="col-xs-3 col-sm-3 col-md-3  form-group form-inline">

                  <div class="radio">
                    <label>
                      <input type="radio" class="flat checkGender" value="2" id="check2" 
                      @if(!isset($customer_item->customer_gender)) checked @endif
                      @if(isset($customer_item->customer_gender)) @if($customer_item->customer_gender ==2) checked @endif @endif
                 name="gender">&nbsp;Female
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input type="radio" class="flat checkGender" value="1" id="check1" @if(isset($customer_item->customer_gender)) @if($customer_item->customer_gender ==1) checked @endif @endif name="gender">&nbsp;Male
                    </label>
                </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input type="radio" class="flat checkGender" value="3" id="check3" @if(isset($customer_item->customer_gender)) @if($customer_item->customer_gender ==3) checked @endif @endif name="gender">&nbsp;Child
                    </label>
                </div>
            </div>            
        </div> 
        <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-2">Address</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input type='text' required="required" value="{{isset($customer_item->customer_address)? $customer_item->customer_address:old('customer_address')}}" id="customer_address" name="customer_address" class="form-control form-control-sm{{ $errors->has('customer_address') ? ' is-invalid' : '' }}"/>
             </div>            
        </div>  
        <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-2">Group</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                 <select id="customertag_id" required="required" name="customertag_id" class="form-control form-control-sm{{ $errors->has('customertag_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Client Group -- </option>
                    @foreach($list_customertag as $customertag)
                      <option 
                      @if(isset($customer_item->customer_customertag_id))
                        @if($customer_item->customer_customertag_id == $customertag->customertag_id) 
                            selected 
                        @endif
                      @endif 
                    value ="{{$customertag->customertag_id}}">{{$customertag->customertag_name}}
                     </option>
                   @endforeach
                  </select>
             </div>            
        </div>    
        <div class="row form-group">           
                <label class="col-xs-3 col-sm-3 col-md-2">&nbsp;</label>
                <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                    <button id="submit" class="btn btn-sm btn-primary" >SUBMIT</button>
                    {{-- <button class="btn btn-sm btn-default">CANCEL</button> --}}
                    <a href="{{ route('clients') }}" class="btn btn-sm btn-default">CANCEL</a>
                </div>            
        </div>
    </form>
    </div>    
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/custom/combodate.js') }}"></script>    
<script type="text/javascript">
    $(document).ready(function(){
        $("#submit").on( "click", function(event){
            // validate form
            var validatorResult = $("#customer_form")[0].checkValidity();
            $("#customer_form").addClass('was-validated');
            if(!validatorResult){
                event.preventDefault();
                event.stopPropagation();           
                return;
            }
            //form = document.createElement('#customer_form');
            $('#customer_form').submit();
        });

        $('#dropdown_country a').on('click', function(e) {
          e.preventDefault();

          $('#current_country_selected').text($(this).attr('value')+" ") ;

          // sets the input field's value to the data value of the clicked a element
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

        $("input[name='customer_email']").on("blur",function(e){
            var str = $(this).val();       
            console.log(str.search("@"));
            console.log(str.search("\\."));
            if(str.search("\\@") == -1 || str.search("\\.") == -1){
                check = 1;
                $(this).addClass('is-invalid');
            }else {
                check = 0;
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
            checkSubmit(check);
        });

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

         $("input[name='customer_address']").on("blur",function(e){
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

        $(".day").on("blur",function(e){
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

        $(".month").on("blur",function(e){
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


        $(".year").on("blur",function(e){
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

        $("#customertag_id").on("change",function(e){
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
@stop

