@extends('layouts.master')
@section('title')
User|{{($id==0)?"Add":"Edit"}} User
@endsection
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">               
@stop
@section('content')

 <div class="x_panel x_panel_form" style="min-height:296px;">
     <div class="x_title">
         <h3>{{($id==0)?"Add":"Edit"}} User</h3>
     </div>
    <div class="x_content"> 
    <form action="{{route('save-user')}}" id="user_form" method="post" id="user-form" name="user-form" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" name="user_id" value="{{$id}}">
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">First Name</label>
            <div class="col-sm-5 col-md-4">
               <input type='text' name="first_name" value="{{isset($user_name)?$user_name[0]:old('first_name')}}" class="form-control form-control-sm{{ $errors->has('first_name') ? ' is-invalid' : '' }}" required / >
               <span>{{$errors->first('first_name')}}</span>
            </div>            
            <label class="col-sm-3 col-md-2">Last Name</label>
            <div class="col-sm-5 col-md-4">
               <input type='text' name="last_name" value="{{isset($user_name)?$user_name[1]:old('last_name')}}" class="form-control form-control-sm{{ $errors->has('last_name') ? ' is-invalid' : '' }}" required/>
               <span>{{$errors->first('last_name')}}</span>
            </div>             
        </div>  
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Phone</label>
            <div class="col-sm-5 col-md-4 input-group-sm input-group-addon input-group-country-phone">
                <div class="btn-group btn-group-sm btn-countrycode{{ $errors->has('country_code') ? ' is-invalid' : '' }}">
                 <button id="current_country_selected" style="padding:4px 0px;" data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle{{ $errors->has('current_country_selected') ? ' is-invalid' : '' }}" type="button" aria-expanded="false">{{isset($customer_item->customer_country_code)? $customer_item->customer_country_code:Session::get('selected_country_code')}} <span class="caret" required></span></button>
                 <ul role="menu" id="select_phone" class="dropdown-menu">
                    @foreach($data['headNumber'] as $value)
                        <li value ="{{$value}}"><a value ="{{$value}}" href="#">{{$value}}</a></li>
                    @endforeach                     
                 </ul>
                 <input type="hidden" name="country_code" id="country_code" value="{{isset($customer_item->customer_country_code)? $customer_item->customer_country_code:Session::get('selected_country_code')}}">
                 </div>                   
                 <input required class="form-control form-control-sm maskphone{{ $errors->has('user_phone') ? ' is-invalid' : '' }}" placeholder="" value="{{isset($user_phone)?$user_phone:old('user_phone')}}" name="user_phone" type="number" data-inputmask="'mask' : '(999) 999-9999'" >
                 <span>{{$errors->first('user_phone')}}</span>                
            </div>
            <label class="col-sm-3 col-md-2">Email</label>
            <div class="col-sm-5 col-md-4">
               <input type='email' name="user_email" value="{{isset($user_list)?$user_list->user_email:old('user_email')}}" class="form-control form-control-sm{{ $errors->has('user_email') ? ' is-invalid' : '' }}" required/>
               <span>{{$errors->first('user_email')}}</span>
            </div>  
        </div>
        @if($id>0)
        <div class="row form-group" hidden>
            <label class="col-sm-3 col-md-2">Password</label>
            <div class="col-sm-5 col-md-4">
                <input type='password' name="user_password"  value="{{isset($user_list)?$user_list->user_password:old('user_password')}}"  class="form-control form-control-sm"  />
            </div>            
            <label class="col-sm-3 col-md-2">Confirm Password</label>
            <div class="col-sm-5 col-md-4">
                <input type='password' name="user_password_confirm"   value="{{isset($user_list)?$user_list->user_password:old('user_password')}}"  class="form-control form-control-sm{{ $errors->has('user_password') ? ' is-invalid' : '' }}" />
            </div>             
        </div> 
        @endif
        @if($id==0)
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Password</label>
            <div class="col-sm-5 col-md-4">
                <input type='password' name="user_password" class="form-control form-control-sm{{ $errors->has('user_password') ? ' is-invalid' : '' }}" required />
                <span>{{$errors->first('user_password')}}</span>
            </div>            
            <label class="col-sm-3 col-md-2">Confirm Password</label>
            <div class="col-sm-5 col-md-4">
                <input type='password' name="user_password_confirm" class="form-control form-control-sm{{ $errors->has('user_password_confirm') ? ' is-invalid' : '' }}" required />
                <span>{{$errors->first('user_password_confirm')}}</span>
            </div>             
        </div> 
        @endif
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Nickname</label>
            <div class="col-sm-5 col-md-4">
                <input type='text' name="user_nickname" required value="{{isset($user_list)?$user_list->user_nickname:old('user_nickname')}}"  class="form-control form-control-sm{{ $errors->has('user_nickname') ? ' is-invalid' : '' }}" />
            </div>            
            <label class="col-sm-3 col-md-2">Role Permission</label>
            <div class="col-sm-5 col-md-4">
                <select class="form-control form-control-sm" name="user_usergroup_id">
                    @foreach($ug_list as $ug)
                    <option {{(isset($user_list)&&$user_list->user_usergroup_id==$ug->ug_id)?"selected":""}} value="{{$ug->ug_id}}">{{$ug->ug_name}}</option>
                    @endforeach
                </select>
            </div>             
        </div>  
        <div class="row form-group">
            {{-- <label class="col-sm-3 col-md-2">Agent or Owner</label>
            <div class="col-sm-5 col-md-4 form-inline">
                <div class="radio">
                    <label>
                      <input type="radio" class="flat icheckstyle" checked name="userType">&nbsp;Agent
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input type="radio" class="flat icheckstyle" name="userType">&nbsp;Owner
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                        <input type="radio" class="flat icheckstyle" name="userType" checked="">&nbsp;Other
                    </label>
                  </div>
            </div> --}}            
            <label class="col-sm-3 col-md-2">Status</label>
            <div class="col-sm-5 col-md-4 form-inline">
                 <div class="radio">
                    <label>
                      <input type="radio" class="flat icheckstyle" {{!isset($user_list)?"checked":""}}  {{(isset($user_list)&&$user_list->enable_status==1)?"checked":""}} value="1"  name="enable_status">&nbsp;Active
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input type="radio" class="flat icheckstyle" {{(isset($user_list)&&$user_list->enable_status==0)?"checked":""}} value="0" name="enable_status">&nbsp;Inactive
                    </label>
                  </div>
            </div>             
        </div>  
        
         <div class="row form-group">
             <!-- Only available/display for multi store-->
            {{-- <label class="col-sm-3 col-md-2">Business Store Permission</label>
            <div class="col-sm-5 col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="flat icheckstyle" name="place" checked="checked"> Business Store 1
                    </label>
               </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="flat icheckstyle" name="place"> Business Store 1
                    </label>
               </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="flat icheckstyle" name="place"> Business Store 1
                    </label>
               </div>
            </div>      --}}       
            <!-- ----- -->
            <label class="col-sm-3 col-md-2">Avatar</label>
            <div class="col-sm-5 col-md-4">                
                <div class="catalog-image-upload">
                   <div class="catalog-image-edit">
                       <input type='file' id="imageUpload1" name="user_avatar" data-target="#catalogImagePreview1" accept=".png, .jpg, .jpeg" />
                       <input type="hidden" name="user_avatar_hidden" value="{{(isset($user_list))?$user_list->user_avatar:old('user_avatar_hidden')}}">
                       <label for="imageUpload1"></label>
                   </div>
                   <div class="catalog-image-preview">
                       <img id="catalogImagePreview1" style='display:{{(isset($user_list)&&$user_list->user_avatar!="")?"":"none"}}' src="{{config('app.url_file_view')}}{{isset($user_list)?$user_list->user_avatar:""}}" height="100%" />
                   </div>
               </div>
            </div>             
        </div> 
         <div class="row form-group">
             <label class="col-sm-3 col-md-2">&nbsp;</label>
            <div class="col-sm-6 col-md-6  form-group">
               {{-- <button id="submit" class="btn btn-sm btn-primary" >SUBMIT</button> --}}
               <input type="submit" id="submit" class="btn btn-sm btn-primary" value="SUBMIT">
               <a href="{{url('users/')}}"><button type="button" class="btn btn-sm btn-default" >CANCEL</button></a>
            </div>            
        </div>   

    </form>
    </div>
</div>     
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {

   if ($("input.icheckstyle")[0]) {
        $('input.icheckstyle').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });       
    }

    $(document).on('click','#select_phone li',function(){
        var headPhone=$(this).val();
        // alert(headPhone);
        var del_plus=1;
        if(headPhone=='+84')
        {
            del_plus=84;
        }
        if(headPhone=='+64')
        {
            del_plus=64;
        }
        $('#current_country_selected').text(headPhone);
        $('#country_code').val(del_plus);
    });
});
function readURL(input) {
    if (input.files && input.files[0]) {
      $('img').show();
        var reader = new FileReader();
        reader.onload = function(e) {
            $($(input).attr("data-target")).attr('src', e.target.result);
            $($(input).attr("data-target")).hide();
            $($(input).attr("data-target")).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }    
}
$("input[type=file]").change(function() {
    readURL(this);
});
$(document).ready(function(){
        $("#submit").on( "click", function(event){
            // validate form
            var validatorResult = $("#user_form")[0].checkValidity();
            $("#user_form").addClass('was-validated');
            if(!validatorResult){
                event.preventDefault();
                event.stopPropagation();           
                return;
            }else
            //form = document.createElement('#customer_form');
            $('#user_form').submit();
        });
        $('#dropdown_country a').on('click', function(e) {
          e.preventDefault();

          $('#current_country_selected').text($(this).attr('value')+" ") ;

          // sets the input field's value to the data value of the clicked a element
          $('#country_code').val($(this).attr('value'));
        });

});
</script>   

<script>
    //check validate
    $(document).ready(function(){

        var check = 0;
        $("input[name='first_name']").on("blur",function(e){
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
        $("input[name='user_phone']").on("blur",function(e){
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

        $("input[name='user_password']").on("blur",function(e){
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

        $("input[name='user_nickname']").on("blur",function(e){
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

        $("input[name='last_name']").on("blur",function(e){
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

        $("input[name='user_password_confirm']").on("blur",function(e){
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

        $("input[name='user_email']").on("blur",function(e){
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

