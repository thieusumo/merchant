@extends('layouts.master')
@section('title', 'Setting | System')
@section('styles')
    
@stop
@section('content')
  
<div class="x_panel setting"> 
<div class="row no-margin no-padding">

    <div class="col-6">       
        <div class="x_panel bg-light">             
            <div class="x_title">               
                <h2>SMTP SERVER SETTING </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">     
            <form action="{{ route('postServerSetting') }}" method="post" id="smtpserver-form" name="user-form">    
             @csrf
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Server</label>
                    <div class="col-sm-8 col-md-8">
                       <input required id="host" name="host" type='text' class="form-control form-control-sm {{ $errors->has('host') ? ' is-invalid' : '' }}" value="{{$post_place->place_email_host}}" />
                       <small>The address of your outgoing SMTP server.</small>
                    </div> 
                </div>                  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Port </label>
                    <div class="col-sm-8 col-md-8">
                       <input required="" id="port" name="port" type='number' class="form-control form-control-sm {{ $errors->has('port') ? ' is-invalid' : '' }}" value="{{$post_place->place_email_port}}"/>                       
                    </div> 
                </div>  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Encryption</label>
                    <div class="col-sm-8 col-md-8">
                        <select name="encryption" id="encryption" class="form-control form-control-sm">
                           <option value="standard" @if($post_place->place_email_encryption == 'standard')selected="selected"@endif>No</option>
                           <option value="ssl" @if($post_place->place_email_encryption == 'ssl')selected="selected"@endif>Use SSL</option>
                           <option value="tls" @if($post_place->place_email_encryption == 'tls')selected="selected"@endif>Use TLS</option>
                        </select>
                    </div> 
                </div>  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Authentication Username
                    </label>
                    <div class="col-sm-8 col-md-8">
                       <input id="auth_username" name="auth_username" type='text' class="form-control form-control-sm {{ $errors->has('auth_username') ? ' is-invalid' : '' }}" value="{{$post_place->place_email}}"/>
                       <small>Leave blank if your SMTP server does not require authentication.</small>
                    </div> 
                </div>  
                 <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Authentication Password
                    </label>
                    <div class="col-sm-8 col-md-8">
                       <input  name="auth_password" id="auth_password" type='text' class="form-control form-control-sm {{ $errors->has('auth_password') ? ' is-invalid' : '' }}" {{-- value="{{$post_place->place_email_password}}" --}}/>
                       <small>Leave blank if you have already entered your password before.</small>
                    </div> 
                </div> 
                <div class="modal fade" id="emailModal" role="dialog">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">
                        <div class="modal-header">
                            <p>Email Test</p>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body row">
                          <input type="email" id="email_test" name="email_test" class="form-control form-control-sm">
                        </div>
                        <div class="modal-footer">
                          <button type="button" id="send_email_test" class="btn btn-primary btn-sm" >Send</button>
                        </div>
                      </div>
                    </div>
                  </div>
                 <div class="row">
                     <label class="col-sm-4 col-md-3">&nbsp;</label>
                    <div class="col-sm-6 col-md-6">
                        <button class="btn btn-sm btn-primary" type="submit">Save changes</button>
                        <a href="#" id="sent_test_email" class="btn btn-sm btn-danger"  data-toggle="modal">Test email</a>
                    </div>                   

                </div>

            </form>    
            </div>
        </div>   
    </div>
    <div class="col-6">
       
        <div class="x_panel bg-light">
            <div class="x_title">
                <h2>AUTHORIZE.NET PAYMENT</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">     
            <form action="{{ route('postAuthorize') }}" method="post" id="authnet-form" name="authnet-form"> 
                @csrf()
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">API LOGIN ID</label>
                    <div class="col-sm-8 col-md-8">
                       <input required="" name="api_login_id" type='text' class="form-control form-control-sm {{ $errors->has('api_login_id') ? ' is-invalid' : '' }}" value="{{isset($api_login_id)?$api_login_id :''}}" />
                    </div> 
                </div>                  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">TRANSACTION KEY </label>
                    <div class="col-sm-8 col-md-8">
                       <input required="" name="transaction_key" type='text' class="form-control form-control-sm {{ $errors->has('transaction_key') ? ' is-invalid' : '' }}" value="{{isset($transaction_key)?$transaction_key:''}}"/>   
                       <small>Leave blank if you have already entered your transaction key before.</small>
                    </div> 
                </div>  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">TEST MODE</label>
                    <div class="col-sm-8 col-md-8">
                        <select name="test_mode" class="form-control form-control-sm">
                           <option value="0" @if(isset($test_mode) && $test_mode == '0')selected="selected"@endif>FALSE</option>
                           <option value="1" @if(isset($test_mode) && $test_mode == '1')selected="selected"@endif>TRUE</option> 
                        </select>
                    </div> 
                </div>  
                 <div class="row">
                     <label class="col-sm-4 col-md-3">&nbsp;</label>
                    <div class="col-sm-6 col-md-6">
                        <button class="btn btn-sm btn-primary" type="submit">Save changes</button>
                    </div>            
                </div>                       
            </form>     
            </div>
        </div>         
         <div class="x_panel bg-light">
            <div class="x_title">
                <h2>SOCIAL NETWORK ACCOUNT <small>Using reviews in the marketing</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">     
            <form action="{{ route('postSocialNetworkAccount') }}" method="post" id="social-form" name="social-form">  
            @csrf()   
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Yelp ID</label>
                    <div class="col-sm-8 col-md-8">
                       <input name="yelp_id" type='text' class="form-control form-control-sm {{ $errors->has('yelp_id') ? ' is-invalid' : '' }}" value="{{isset($yelp_id)?$yelp_id:''}}" />
                    </div> 
                </div>   
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Yelp Url</label>
                    <div class="col-sm-8 col-md-8">
                       <input name="yelp_url" type='text' class="form-control form-control-sm {{ $errors->has('yelp_url') ? ' is-invalid' : '' }}" value="{{isset($yelp_url)?$yelp_url:''}}" />
                    </div> 
                </div>                
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Facebook ID </label>
                    <div class="col-sm-8 col-md-8">
                       <input name="facebook_id" type='text' class="form-control form-control-sm {{ $errors->has('facebook_id') ? ' is-invalid' : '' }}" value="{{isset($facebook_id)?$facebook_id:''}}"/>                       
                    </div> 
                </div>  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Facebook Url</label>
                    <div class="col-sm-8 col-md-8">
                       <input name="facebook_url" type='text' class="form-control form-control-sm {{ $errors->has('facebook_url') ? ' is-invalid' : '' }}" value="{{isset($facebook_url)?$facebook_url:''}}"/>                       
                    </div> 
                </div>  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Google ID</label>
                     <div class="col-sm-8 col-md-8">
                       <input  name="google_id" type='text' class="form-control form-control-sm {{ $errors->has('google_id') ? ' is-invalid' : '' }}" value="{{isset($google_id)?$google_id:''}}"/>                       
                    </div> 
                </div>  
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Google Url</label>
                     <div class="col-sm-8 col-md-8">
                       <input  name="google_url" type='text' class="form-control form-control-sm {{ $errors->has('google_url') ? ' is-invalid' : '' }}" value="{{isset($google_url)?$google_url:''}}"/>                       
                    </div> 
                </div>  
                <div class="row">
                     <label class="col-sm-4 col-md-3">&nbsp;</label>
                    <div class="col-sm-6 col-md-6">
                        <button class="btn btn-sm btn-primary" type="submit">Save changes</button>
                    </div>            
                </div>              
            </form>      
            </div>  
        </div> 
    </div>    
</div>
</form>
</div>    
@stop
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#sent_test_email').click(function(event){
            var validatorResult = $("#smtpserver-form")[0].checkValidity();
            $("#smtpserver-form").addClass('was-validated');
            if(!validatorResult){
                event.preventDefault();
                event.stopPropagation();           
                return;
            }else
            {
                $('#emailModal').modal();
            }
        });
        $('#send_email_test').click(function(){
                var email_test = $('#email_test').val();
                if(email_test === ""){
                    toastr.error('Enter email test, Please!');
                }
                else{
                    var host = $('#host').val();
                    var port = $('#port').val();
                    var encryption = $('#encryption').val();
                    var auth_username = $('#auth_username').val();
                    var auth_password = $('#auth_password').val();

                    $.ajax({
                        url: '{{route('send_email_test')}}',
                        type: 'GET',
                        dataType: 'html',
                        data: {host: host,port:port,encryption:encryption,auth_username:auth_username,auth_password:auth_password,email_test:email_test},
                    })
                    .done(function(data) {
                        $('#emailModal').modal('toggle');
                        //alert("Message has been sent. Check your testing email. Thanks!");
                        toastr.success(data);
                        console.log(data);
                    })
                    .fail(function(data) {
                        toastr.error(data);
                        //console.log("error");
                    });
                }  
        });
    });
</script>
<script>
    //check validate
    $(document).ready(function(){

        var check = 0;
        $("input[name='host']").on("blur",function(e){
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

        $("input[name='port']").on("blur",function(e){
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


        $("input[name='api_login_id']").on("blur",function(e){
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


        $("input[name='transaction_key']").on("blur",function(e){
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

