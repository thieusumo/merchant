@extends('layouts.master')
@section('title', 'Change Password')
@section('styles')

@section('content')
<div class="x_panel x_panel_form">
    <div class="x_content">
    <form class="form-horizontal" name="frm" action="{{asset('/change-password')}}" method="post" custom-submit="" novalidate="novalidate">
        @if($errors->any())
            <div class="alert alert-danger">
              @foreach($errors->all() as $err)
                <li>{{$err}}</li>
              @endforeach
            </div>
            @endif
            @if(session('notification'))
              <div class="alert alert-success">
                  {{session('notification')}}
                
              </div>
            @elseif(session('error'))
                <div class="alert alert-warning">
                  {{session('error')}}
                
              </div>
            @endif
            {{ csrf_field() }}
        <div class="row form-group">
             <label class="col-xs-4 col-sm-3 col-md-2">Old Password</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input class="form-control form-control-sm" type="password" placeholder="Old Password" name="oldpassword" >
             </div>            
        </div>    

        <div class="row form-group">
             <label class="col-xs-4 col-sm-3 col-md-2">New Password</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input class="form-control form-control-sm" type="password" placeholder="Enter New Password" name="newpassword">
             </div>            
        </div>    

        <div class="row form-group">
             <label class="col-xs-4 col-sm-3 col-md-2">Re New Password</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input class="form-control form-control-sm" type="password" placeholder="ReEnter New Password" name="password_confirmation">
             </div>            
        </div>    
          
        <div class="row form-group">           
                <label class="col-xs-3 col-sm-3 col-md-2">&nbsp;</label>
                <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                    <button class="btn btn-sm btn-primary" >SUBMIT</button>
                    <!-- <button class="btn btn-sm btn-default">CANCEL</button> -->
                </div>            
        </div>
    </form>
    </div>    
</div>

@stop