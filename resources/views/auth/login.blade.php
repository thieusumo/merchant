@extends('layouts.basic')
@section('title', 'Login')
@section('styles')
  <style>
  ::-webkit-input-placeholder {
    font-style:italic;
    color: #d2d2d2;
    font-size: 15px;
  }
    .login_content {
    margin: 0 auto;
    padding: 25px 40px;
    position: relative;
    text-align: center;
    background: #2d323847;
    border-radius: 12px;
  }
  .btn-success,.btn-success:hover,.btn-success:focus,.btn-success:not(:disabled):not(.disabled).active, .btn-success:not(:disabled):not(.disabled):active, .show>.btn-success.dropdown-toggle {
      background: #ffffff;
      color: #01adff;
      border-color: #01adff;
  }
  .btn-success:hover{
      border: 1px solid #01adff;
  }
  .form-control-feedback{
    color: #01adff;
  }
  .btn-success:focus{
    border: 2px solid #01adff;
  }
  .login_content a,.login_content a:hover{
    text-decoration: none; 
    color: #fff;
  }
  .btn-success:not(:disabled):not(.disabled).active:focus, .btn-success:not(:disabled):not(.disabled):active:focus, .show>.btn-success.dropdown-toggle:focus{
    box-shadow: 0 0 0 0.1rem rgb(8, 159, 229);
  }
  .login_content a:hover{
    color: #d8e7ec;
  }
  .btn-success, .btn-success:hover, .btn-success:focus, .btn-success:not(:disabled):not(.disabled).active, .btn-success:not(:disabled):not(.disabled):active, .show>.btn-success.dropdown-toggle {
    background: #f7f7f7;
    color: #01adff;
    border-color: none;
  }
  .login_form .dropdown-menu li a{
    margin: 0;
    padding: 0;
    display: block;
    background: #ffffff;
    text-align: center;
    font-size: 14px;
    text-decoration: none;
    color: #01adff;
  }

  </style>
@endsection
@section('content')
<body class="login">
<div class="col-md-12 home_style">
    <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
              <form role="form" action="{{ route('login') }}" method="post" autocomplete="off">
                @csrf
              <img class="logo" src="{{ asset('images/logo-169x46.png') }}"/>
                 @if($errors->has('errorlogin'))
                <div class="alert alert-error">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{$errors->first('errorlogin')}}
                </div>
               @endif    
               @if (session('status'))
                  <div class="alert alert-success" role="alert">
                      {{ session('status') }}
                  </div>
              @endif           
               <div class="form-group input-group-addon">
                   <div class="btn-group btn-countrycode">
                    {{-- <button style="border: none" data-toggle="dropdown" class="form-control btn-success " type="button" aria-expanded="false">1<span class="caret"></span>
                    </button> --}}
                   
                       <input type="hidden" name="country_code" id="country_code" value="1">
                    </div>  
                    <span class="fa fa-phone  form-control-feedback left" aria-hidden="true"></span>              
                    <input class="form-control maskphone{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter your phone') }}" name="phone" type="text" data-inputmask="'mask' : '(999) 999-9999'">                    
                </div>								
                <div class="form-group input-group-addon">                    
                    <span class="fa fa-lock  form-control-feedback left" aria-hidden="true"></span>
                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter your password') }}" name="password" type="password" value="">                    
                </div> 
              <div>               
		<button type="submit" class="btn btn-success submit" >{{ __('Login') }}</button>                                  
              </div>
              <div style="padding-top: 20px">
                  @if (Route::has('password.request'))
                    <a class="center" href="{{ route('reset-password') }}" >
                        {{ __('Forgot Your Password?') }}
                    </a>
                  @endif                   
              </div>
              <div class="clearfix"></div>
             {{--device token --}}
             <input type="hidden" name="deviceToken" id="deviceToken">
            </form>
          </section>
        </div>
    </div>    
</div>
</body>    
@stop
@section('scripts')
<script src="{{ asset('plugins/jquery.inputmask/jquery.inputmask.bundle.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("input.maskphone").inputmask();
        $(".btn-countrycode .dropdown-menu a").click(function(){
            $(".btn-countrycode button").text(($(this).text()));
            $("#country_code").val($(this).text());
            console.log($(this).text());
        });
    });    
</script>


@stop

