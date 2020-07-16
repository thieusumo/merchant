@extends('layouts.master')
@section('title', 'Management | Rent Stations | Add Rent Station')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="x_panel x_panel_form">
    <div class="x_title">
        @if($id==0)
            <h3>Add Rent Station</h3>
        @else
            <h3>Edit Rent Station</h3>
        @endif
     </div>
   <div class="x_content"> 
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        @if(Session::has('status'))
            <div class="alert alert-primary">
              {{ Session::get('status')}}
            </div>
        @endif
    <form method="post" id="user_form" name="user-form" action="{{route('save-staff')}}" enctype="multipart/form-data"> 
        @csrf
        <input type="hidden" name="staff_id" value="{{$id}}" />
         <div class="row form-group">
            <label class="col-sm-3 col-md-2">Avatar</label>
            <div class="col-sm-5 col-md-4">
               <div class="catalog-image-upload">
                   <div class="catalog-image-edit">
                       <input name="avatar" type='file' id="imageUpload3" data-target="#catalogImagePreview3" accept=".png, .jpg, .jpeg" />
                       <label for="imageUpload3"></label>
                   </div>
                   <div class="catalog-image-preview">
                       <img onerror="this.style.display='none'" id="catalogImagePreview3" src="{{isset($worker->worker_avatar)? config('app.url_file_view').$worker->worker_avatar : old('catalogImagePreview3')}}" style="width:inherit; height:inherit" />
                   </div>
               </div>
            </div>            
            
        </div>  
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">First Name</label>
            <div class="col-sm-5 col-md-4">
               <input name="first_name" type='text' class="form-control form-control-sm" value="{{isset($worker->worker_firstname)?$worker->worker_firstname:old('first_name')}}" required/>
               <span class="invalid-feedback feedback_first_name" role="alert"><strong></strong></span> 
            </div>            
            <label class="col-sm-3 col-md-2">Last Name</label>
            <div class="col-sm-5 col-md-4">
               <input name="last_name" type='text' class="form-control form-control-sm" value="{{isset($worker->worker_lastname)?$worker->worker_lastname:old('last_name')}}" required/>
               <span class="invalid-feedback feedback_last_name" role="alert"><strong></strong></span>
            </div>             
        </div>  
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Nickname</label>
            <div class="col-sm-5 col-md-4">
                <input name="nick_name" type='text' class="form-control form-control-sm" value="{{isset($worker->worker_nickname)?$worker->worker_nickname:old('nick_name')}}" required/>
                <span class="invalid-feedback feedback_nick_name" role="alert"><strong></strong></span>
            </div>            
            <label class="col-sm-3 col-md-2">Birthday</label>
            <div class="col-sm-5 col-md-4">
               <div class="input-group input-group-sm form-inline">
                    <input  type='text' id="date_of_birth" name="date_of_birth" value="{{isset($workerDateOfBirth)?$workerDateOfBirth:old('date_of_birth')}}"  class="form-control form-control-sm date_of_birth" data-format="DD-MM-YYYY" data-template="D MMM YYYY"/>
                    <span class="invalid-feedback feedback_birth_day" role="alert"><strong></strong></span>
                </div>
            </div>             
        </div> 
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Phone</label>
            <div class="col-sm-5 col-md-4 input-group-sm input-group-addon ">          
                <input onblur="formatPhone(this);" class="form-control form-control-sm" placeholder="{{ __('Phone Number') }}" name="phone" type="text" data-inputmask="'mask' : '(999) 999-9999'" value="{{isset($worker->worker_phone)?$worker->worker_phone:old('phone')}}" required>
                <span class="invalid-feedback feedback_phone" role="alert"><strong></strong></span>
                <div class="text-danger feedback">{{ $errors->first('phone.exists')}}</div>                       
            </div>
            <label class="col-sm-3 col-md-2">Email</label>
            <div class="col-sm-5 col-md-4">
               <input type='email' name="email" class="form-control form-control-sm" value="{{isset($worker->worker_email)?$worker->worker_email:old('email')}}" />
               <span class="invalid-feedback feedback_email" role="alert"><strong></strong></span>
               <div class="text-danger feedback">{{ $errors->first('email.exists')}}</div>      
            </div>  
        </div>
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Address</label>
            <div class="col-sm-5 col-md-4">
                <input name="address" type='text' class="form-control form-control-sm" value="{{isset($worker->worker_address)?$worker->worker_address:old('address')}}" />
                <span class="invalid-feedback feedback_address" role="alert"><strong></strong></span>
            </div>            
            <label class="col-sm-3 col-md-2">City</label>
            <div class="col-sm-5 col-md-4">
               <input  name="city" type='text' class="form-control form-control-sm" value="{{isset($worker->worker_city)?$worker->worker_city:old('city')}}" />
               <span class="invalid-feedback feedback_city" role="alert"><strong></strong></span>
            </div>             
        </div>         
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">State</label>
            <div class="col-sm-5 col-md-4">
                <input name="state" type='text' class="form-control form-control-sm" value="{{isset($worker->worker_state)?$worker->worker_state:old('state')}}"/>
                <span class="invalid-feedback feedback_state" role="alert"><strong></strong></span>
            </div>            
            <label class="col-sm-3 col-md-2">Zip Code</label>
            <div class="col-sm-5 col-md-4">
                 <input name="zip_code" type='text' class="form-control form-control-sm" value="{{isset($worker->worker_zipcode)?$worker->worker_zipcode:old('zip_code')}}"/>
                 <span class="invalid-feedback feedback_zipcode" role="alert"><strong></strong></span>
            </div>             
        </div> 
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">SSN</label>
            <div class="col-sm-5 col-md-4">
                <input name="ssn" class="form-control form-control-sm maskphone" type="text" data-inputmask="'mask' : '999-99-9999'" value="{{isset($worker->worker_ssn)?$worker->worker_ssn:old('ssn')}}"  />
                <span class="invalid-feedback feedback_ssn" role="alert"><strong></strong></span>                    
            </div>            
            <label class="col-sm-3 col-md-2">Nationality</label>
            <div class="col-sm-5 col-md-4">
                <input name="nationality" type='text' class="form-control form-control-sm"  value="{{isset($worker->worker_country)?$worker->worker_country:old('nationality')}}" />
                <span class="invalid-feedback feedback_nationality" role="alert"><strong></strong></span>
                {{-- <select class="form-control form-control-sm">
                   <option value="1">Country</option>
               </select> --}}
            </div>             
        </div> 
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Agreement</label>
            <div class="col-sm-5 col-md-4 input-group-spaddon">
                 <div class="input-group">
                    <span class="input-group-addon">%</span>                        
                    <input name="agreement" type="number" step="0.001" class="form-control form-control-sm" value="{{isset($worker->worker_percent)?$worker->worker_percent:old('agreement')}}">
                    <span class="invalid-feedback feedback_agreement" role="alert"><strong></strong></span>
                </div>
            </div>            
            <label class="col-sm-3 col-md-2">Cash</label>
            <div class="col-sm-5 col-md-4 input-group-spaddon">
                <div class="input-group">
                    <span class="input-group-addon">%</span>                        
                    <input name="cash" type="number" step="0.001" class="form-control form-control-sm" value="{{isset($worker->worker_cash_percent)?$worker->worker_cash_percent:old('cash')}}">
                    <span class="invalid-feedback feedback_cash" role="alert"><strong></strong></span>
                </div>
            </div>             
        </div>
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">W2 Tax</label>
            <div class="col-sm-5 col-md-4 input-group-spaddon">
                 <div class="input-group">
                    <span class="input-group-addon">%</span>                        
                    <input name="w2_tax" type="number" step="0.001" class="form-control form-control-sm" value="{{isset($worker->worker_cash_tax)?$worker->worker_cash_tax:old('w2_tax')}}">
                    <span class="invalid-feedback feedback_w2tax" role="alert"><strong></strong></span>
                </div>
            </div>            
            <label class="col-sm-3 col-md-2">Fix Amount</label>
            <div class="col-sm-5 col-md-4">
                 <input name="fix_amount" type='number' step="0.001" class="form-control form-control-sm" value="{{isset($worker->worker_fix_amount)?$worker->worker_fix_amount:old('fix_amount')}}" />
                 <span class="invalid-feedback feedback_fixamout" role="alert"><strong></strong></span>
            </div>       
        </div>

        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Sdi</label>
            <div class="col-sm-5 col-md-4">
                 <input name="sdi" type='number' step="0.001" class="form-control form-control-sm" value="{{isset($worker->worker_sdi)?$worker->worker_sdi:old('sdi')}}"/>
                 <span class="invalid-feedback feedback_sdi" role="alert"><strong></strong></span>
            </div>            
            <label class="col-sm-3 col-md-2">Hour rate</label>
            <div class="col-sm-5 col-md-4">
                 <input name="hour_rate" type='number' min="0" max="40" class="form-control form-control-sm" value="{{isset($worker->worker_hour_rate)?$worker->worker_hour_rate:old('hour_rate')}}"/>
                 <span class="invalid-feedback feedback_hourrate" role="alert"><strong></strong></span>
            </div>       
        </div>
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Social Security</label>
            <div class="col-sm-5 col-md-4">
                 <input name="social_security" type='number' step="0.001" class="form-control form-control-sm"  value="{{isset($worker->worker_social_security)?$worker->worker_social_security:old('social_security')}}"/>
                 <span class="invalid-feedback feedback_socialsecurity" role="alert"><strong></strong></span>
            </div>            
            <label class="col-sm-3 col-md-2">Medicare</label>
            <div class="col-sm-5 col-md-4">
                 <input name="medicare" type='number' step="0.001" class="form-control form-control-sm" value="{{isset($worker->worker_medicare)?$worker->worker_medicare:old('medicare')}}"/>
                 <span class="invalid-feedback feedback_medicare" role="alert"><strong></strong></span>
            </div>       
        </div>


        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Rent boot</label>
            <div class="col-sm-5 col-md-4">
                 <input name="rent_boot" type='number' step="0.001" class="form-control form-control-sm" value="{{isset($worker->worker_rent_boot)?$worker->worker_rent_boot:old('rent_boot')}}"/>
                 <span class="invalid-feedback feedback_rentboot" role="alert"><strong></strong></span>
            </div>            
            <label class="col-sm-3 col-md-2">Start Date</label>
            <div class="col-sm-5 col-md-4 input-group-spaddon">
                <div class='input-group date'>
                    <input id="start_date" name="start_date" type='' class="form-control form-control-sm" value="{{isset($worker->worker_date_join)?$worker->worker_date_join:old('start_date')}}"  required/>
                    <span class="invalid-feedback feedback_startdate" role="alert"><strong></strong></span>
                    <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>       
        </div>


        <div class="row form-group">
           <label class="col-sm-3 col-md-2">No receipt</label>
            <div class="col-sm-5 col-md-4 form-inline">
                 <div class="radio">
                    <label>
                      <input value="on" type="radio" class="flat icheckstyle" 
                        @if(isset($worker->worker_receipt))
                            @if($worker->worker_receipt==0)
                                checked
                            @endif
                        @else
                            checked
                        @endif

                        name="no_receipt">&nbsp;ON
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input value="off" type="radio" class="flat icheckstyle"
                        @if(isset($worker->worker_receipt))
                            @if($worker->worker_receipt==1)
                                checked
                            @endif
                        @endif
                       name="no_receipt">&nbsp;OFF
                    </label>
                  </div>
            </div>
           <label class="col-sm-3 col-md-2">Receptionist</label>
            <div class="col-sm-5 col-md-4 form-inline">
                 <div class="radio">
                    <label>
                      <input value="on" type="radio" class="flat icheckstyle"
                            @if(isset($worker->worker_receiptionist))
                                @if($worker->worker_receiptionist==1)
                                    checked
                                @endif
                            @else
                                checked
                            @endif
                        name="receptionist">&nbsp;ON
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input value="off" type="radio" class="flat icheckstyle" 
                         @if(isset($worker->worker_receiptionist))
                            @if($worker->worker_receiptionist==0)
                                checked
                            @endif
                        @endif
                       name="receptionist">&nbsp;OFF
                    </label>
                  </div>
            </div>
        </div> 
        <div class="row form-group">
           <label class="col-sm-3 col-md-2">No Open Cash Drawer</label>
            <div class="col-sm-5 col-md-4 form-inline">
                 <div class="radio">
                    <label>
                      <input value="on" type="radio" class="flat icheckstyle" 
                         @if(isset($worker->worker_cash_draw))
                            @if($worker->worker_cash_draw==0)
                                checked
                            @endif
                        @else
                            checked
                        @endif

                       name="no_open_cash_drawer">&nbsp;ON
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input value="off" type="radio" class="flat icheckstyle"  
                        @if(isset($worker->worker_cash_draw))
                            @if($worker->worker_cash_draw==1)
                                checked
                            @endif
                        @endif
                      name="no_open_cash_drawer">&nbsp;OFF
                    </label>
                  </div>
            </div>
           <label class="col-sm-3 col-md-2">Tip Include Check</label>
            <div class="col-sm-5 col-md-4 form-inline">
                 <div class="radio">
                    <label>
                      <input value="on" type="radio" class="flat icheckstyle" 
                        @if(isset($worker->worker_tip_include_check))
                            @if($worker->worker_tip_include_check==1)
                                checked
                            @endif
                        @else
                            checked
                        @endif
                       name="tip_include_check">&nbsp;ON
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input value="off" type="radio" class="flat icheckstyle" 
                        @if(isset($worker->worker_tip_include_check))
                            @if($worker->worker_tip_include_check==0)
                                checked
                            @endif
                        @endif
                      name="tip_include_check">&nbsp;OFF
                    </label>
                  </div>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Status</label>
            <div class="col-sm-5 col-md-4 form-inline">
                 <div class="radio">
                    <label>
                      <input value="active" type="radio" class="flat icheckstyle"
                        @if(isset($worker->enable_status))
                            @if($worker->enable_status==1)
                                checked
                            @endif
                        @else
                            checked
                        @endif
                       name="status">&nbsp;Active
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input value="inactive" type="radio" class="flat icheckstyle"
                        @if(isset($worker->enable_status))
                            @if($worker->enable_status==0)
                                checked
                            @endif
                        @endif
                       name="status">&nbsp;Inactive
                    </label>
                  </div>
            </div>   

            <label class="col-sm-3 col-md-2">Gender</label>
            <div class="col-sm-5 col-md-4 form-inline">
                 <div class="radio">
                    <label>
                      <input value="male" type="radio" class="flat icheckstyle"
                        @if(isset($worker->worker_gender))
                            @if($worker->worker_gender==1)
                                checked
                            @endif
                        @else
                            checked
                        @endif
                       name="gender">&nbsp;Male
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input value="female" type="radio" class="flat icheckstyle"
                        @if(isset($worker->worker_gender))
                            @if($worker->worker_gender==2)
                                checked
                            @endif
                        @endif
                       name="gender">&nbsp;Female
                    </label>
                  </div>
            </div>             
        
        </div>  
         
         
         <div class="row form-group">
             <label class="col-sm-3 col-md-2">&nbsp;</label>
            <div class="col-sm-6 col-md-6  form-group">
               <button id="submit" class="btn btn-sm btn-primary" >SUBMIT</button>
               <a class="btn btn-sm btn-default" href="{{ url('management/staffs') }}">CANCEL</a>
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
        var check = 0;

        $("input[name='first_name']").on("blur",function(e){
          // alert(111);
            var str = $(this).val();
            if(str.length<=0){
                $(this).addClass('is-invalid');
                check = 1;
                $(".feedback_first_name").text('Please enter First Name');
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
                $(".feedback_first_name").text('');
                check = 0;
            }
            checkSubmit(check);
        });

        $("input[name='last_name']").on("blur",function(e){
          // alert(111);
            var str = $(this).val();
            if(str.length<=0){
                $(this).addClass('is-invalid');
                check = 1;
                $(".feedback_last_name").text('Please enter Last Name');
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
                $(".feedback_last_name").text('');
                check = 0;
            }
            checkSubmit(check);
        });
  

        $("input[name='nick_name']").on("blur",function(e){
          // alert(111);
            var str = $(this).val();
            if(str.length<=0){
                $(this).addClass('is-invalid');
                check = 1;
                $(".feedback_nick_name").text('Please enter Nick Name');
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
                $(".feedback_nick_name").text('');
                check = 0;
            }
            checkSubmit(check);
        });

        //  $("input[name='date_of_birth']").on("blur",function(e){
        //   // alert(111);
        //     var str = $(this).val();
        //     if(str.length<=0){
        //         $(this).addClass('is-invalid');
        //         check = 1;
        //         $(".feedback_birth_day").text('Please enter date of birth');
        //     }else {
        //         $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
        //         $(".feedback_birth_day").text('');
        //         check = 0;
        //     }
        //     checkSubmit(check);
        // });

        $("input[name='phone']").on("blur",function(e){
          // alert(111);
            var str = $(this).val();
            if(str.length<10){
                $(this).addClass('is-invalid');
                check = 1;
                $(".feedback_phone").text('Please enter phone');
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
                $(".feedback_phone").text('');
                check = 0;
            }
            checkSubmit(check);
        });

        $("input[name='email']").on("blur",function(e){
          // alert(111);
            var str = $(this).val();
            if(str != ''){
                if(str.search("\\@") == -1 || str.search("\\.") == -1){
                    check = 1;
                    $(".feedback_email").text('must be email');
                    $(this).addClass('is-invalid');
                }else {
                    check = 0;
                    $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
                    $(".feedback_email").text('');
                }
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            } 
            checkSubmit(check);
        });

        // $("input[name='address']").on("blur",function(e){
        //   // alert(111);
        //     var str = $(this).val();
        //     if(str.length<=0){
        //         $(this).addClass('is-invalid');
        //         check = 1;
        //         $(".feedback_address").text('Please enter address');
        //     }else {
        //         $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
        //         $(".feedback_address").text('');
        //         check = 0;
        //     }
        //     checkSubmit(check);
        // });

 
        // $("input[name='ssn']").on("blur",function(e){
        //   // alert(111);
        //     var str = $(this).val();
        //     if(str.length<=0){
        //         $(this).addClass('is-invalid');
        //         check = 1;
        //         $(".feedback_ssn").text('Please enter ssn');
        //     }else {
        //         $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
        //         $(".feedback_ssn").text('');
        //         check = 0;
        //     }
        //     checkSubmit(check);
        // });

  // $("input[name='start_date']").on("blur",function(e){
  //         // alert(111);
  //           var str = $(this).val();
  //           if(str.length<=0){
  //               $(this).addClass('is-invalid');
  //               check = 1;
  //               $(".feedback_startdate").text('Please enter start date');
  //           }else {
  //               $(this).removeClass('is-invalid').addClass('is-valid').attr("required");
  //               $(".feedback_startdate").text('');
  //               check = 0;
  //           }
  //           checkSubmit(check);
  //       });


        // $(".day").on("blur",function(e){
        //     var str = $(this).val();
        //     // alert(str);
        //     if(str.length <=0){
        //         $(this).addClass('is-invalid');
        //         check = 1;
        //     }else {
        //         $(this).removeClass('is-invalid').addClass('is-valid');
        //         check = 0;
        //     }
        //     checkSubmit(check);
        // });

        // $(".month").on("blur",function(e){
        //     var str = $(this).val();
        //     if(str.length <=0){
        //         $(this).addClass('is-invalid');
        //         check = 1;
        //     }else {
        //         $(this).removeClass('is-invalid').addClass('is-valid');
        //         check = 0;
        //     }
        //     checkSubmit(check);
        // });


        // $(".year").on("blur",function(e){
        //     var str = $(this).val();
        //     if(str.length <=0){
        //         $(this).addClass('is-invalid');
        //         check = 1;
        //     }else {
        //         $(this).removeClass('is-invalid').addClass('is-valid');
        //         check = 0;
        //     }
        //     checkSubmit(check);
        // });
});


      function checkSubmit(check){
            // if(check == 1){
            //     $("#submit").attr('disabled',true);
            // } else {
            //     $("#submit']").attr('disabled',false);
            // }
        }



    $('#start_date').daterangepicker({ singleDatePicker: true }) ;

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $($(input).attr("data-target")).attr('src', e.target.result);
                $($(input).attr("data-target")).hide();
                $($(input).attr("data-target")).fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }      
    }
    $(document).ready(function(){
        $('input.date_of_birth').combodate({
            customClass: "form-control form-control-sm"
         })

        // $('select').attr('required', 'true');

        if ($("input.icheckstyle")[0]) {
            $('input.icheckstyle').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });       
        } 

        $("input[type=file]").change(function() {
            readURL(this);
        });

         $("#submit").on( "click", function(event){
            // validate form
            var validatorResult = $("#user_form")[0].checkValidity();
            $("#user_form").addClass('was-validated');
            if(!validatorResult){
                event.preventDefault();
                event.stopPropagation();           
                return;
            }
            //form = document.createElement('#customer_form');
            $('#user_form').submit();
        });

    });

</script>
 
@stop

