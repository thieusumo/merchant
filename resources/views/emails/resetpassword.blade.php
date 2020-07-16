@extends('layouts.basic')
@section('content')
<body class="login">
<div class="col-md-12 home_style">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-10">
            	<div class="card center">
            		<div class="card-header center"><h5>{{ __('Reset New Password') }}</h5>
            		</div>
            		<div class="card-body">
            			<div class="form-group center row"><p style="width: 100%" class="text-center">
            				You are receiving this email because we received a password reset request for your account.</p>
            			</div>
            			<div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="col-md-3 btn btn-md btn-danger"><a href="{{env('APP_URL')}}/find-token?token={{$token}}">Reset Password</a>
                                </button>
                            </div>
                        </div>
                        <div class="form-group row"><p style="width: 100%" class="text-center">
                        	If you did not request a password reset, no further action is required.
                        	</p>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop