@extends('layouts.basic')
@section('title', 'Login')
@section('content')
<body class="login">
<div class="col-md-12 home_style">
    <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
              <form role="form" action="/login" method="post" autocomplete="off">
                @csrf
              <h1><img src="{{ asset('images/logo.png') }}"/> </h1>
               <div class="form-group input-group-addon">
                    <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                    <input class="form-control maskphone" placeholder="Phone" name="phone" type="text" data-inputmask="'mask' : '(999) 999-9999'">                    
                </div>								
                <div class="form-group input-group-addon">                    
                    <span class="fa fa-key form-control-feedback left" aria-hidden="true"></span>
                    <input class="form-control" placeholder="Password" name="password" type="password" value="">                    
                </div> 
              <div>               
		<button type="submit" class="btn btn-sm btn-default submit" >Login</button>                                  
              </div>
              <div>
                   <a href="#" class="left">Lost your password?</a>
              </div>
              <div class="clearfix"></div>
             
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
    });
    
</script>
@stop

