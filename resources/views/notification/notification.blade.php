@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Notification | show')
@section('styles')
@stop
@section('content')
<div class="container">
    <div class="chat">
       
        <div class="chat-title">
            <h1>Notification!!!</h1>
        </div>
        <div class="message-box">
            <input type="hidden" name="id_user" id="{{Auth::user()->id}}" value="{{Auth::user()->name}}">
            <input id='msg' type="text" name="message" class="message-input" placeholder="Type message..."></input>
            <button type="button" id="{{Auth::user()->id}}" name="{{Auth::user()->username}}" class="message-submit">Send</button>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click','.message-submit',function(){
        var msg=$('#msg').val();
        var user_phone="{{ session::get('current_user_phone') }}"
        $.ajax({
            url: "{{route('postNotification')}}",
            type: "GET",
            cache: false,
            data:{
                'message': msg,
                'user_phone':user_phone
            },
            success:function(data){
                socket.emit("client-sent-data",data);
                toastr.success('Sent !!');
            },
        });
    })
})
  </script>
@stop

