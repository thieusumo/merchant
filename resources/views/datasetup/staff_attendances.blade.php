@extends('layouts.master')
@section('title', 'Management | Rent Stations Attendances')
@section('styles')    
@stop
@section('content')
<div class="x_panel x_panel_form ">

        <div class="row">
            <div class="col-6" style="border-right: double;">
                <ul class="list-inline col-md-12 staff-attendances staff-active">
                @php
                $count = 0;
                foreach($staff_list as $staff){
                $collection =  collect($checkin_array);
                $staff_checkin = $collection->where('checkin_worker_id',$staff->worker_id);

                //check url image
                $routes = Route::getRoutes();
                $request = Request::create(config('app.url_file_view').'/'.$staff->worker_avatar);

                $image = config('app.url_file_view').'/'.$staff->worker_avatar;                   
                
                @endphp
                
                        @if(isset($staff_checkin[$count]['checkin_type']) && $staff_checkin[$count]['checkin_type']==1)
                            <li class="list-inline-item block-staff staff-ac{{$staff->worker_id}}">
                                <div class="staff-name"><h6>{{$staff->worker_nickname}}</h6></div>                                                         
                                <div class="staff-img"><img width="109px" height="109px" src="{{isset($staff->worker_avatar) ? $image : asset('images/user.png')}}" ></div>
                                <div class="staff-actions text-center"> 
                                <input type="hidden" name="worker_id" id="worker_id" value="{{$staff->worker_id}}">              
                                    <button id="btn-checkin{{$staff->worker_id}}"  onclick="changeCheckinStatus(this,'btn-checkout{{$staff->worker_id}}')"  class="btn-checkin btn btn-sm btn-primary" type="button" disabled><i class="fa fa-check-square-o"></i></button>
                                    <button id="btn-checkout{{$staff->worker_id}}" onclick="opendModal('btn-checkout{{$staff->worker_id}}','btn-checkin{{$staff->worker_id}}','{{$staff->worker_id}}')" class="btn-checkout btn btn-sm btn-danger" type="button"><i class="fa fa-power-off"></i></button>
                                </div>                    
                            </li>
                        @endif
                @php
                $count++;
                }
                @endphp
                
                </ul>
            </div>  
            <div class="col-6">
                <ul class="list-inline col-md-12 staff-attendances staff-inactive">
                @php
                $count = 0;
                foreach($staff_list as $staff){
                
                $collection =  collect($checkin_array);

                $staff_checkin = $collection->where('checkin_worker_id',$staff->worker_id);
                //check url image                
                $image = config('app.url_file_view').'/'.$staff->worker_avatar;                   
                
                @endphp
                    @if(!isset($staff_checkin[$count]['checkin_type']) || $staff_checkin[$count]['checkin_type'] == 0 )
                            <li class="list-inline-item block-staff staff-in{{$staff->worker_id}}">
                                <div class="staff-name"><h6>{{$staff->worker_nickname}}</h6></div>                                                              
                                <div class="staff-img"><img width="109px" height="109px" src="{{isset($staff->worker_avatar) ? $image : asset('images/user.png')}}" ></div>
                                <div class="staff-actions text-center"> 
                                <input type="hidden" name="worker_id" id="worker_id" value="{{$staff->worker_id}}">              
                                    <button id="btn-checkin{{$staff->worker_id}}"  onclick="changeCheckinStatus(this,'btn-checkout{{$staff->worker_id}}')"  class="btn-checkin btn btn-sm" type="button" ><i class="fa fa-check-square-o"></i></button>
                                    <button id="btn-checkout{{$staff->worker_id}}" onclick="opendModal('btn-checkout{{$staff->worker_id}}','btn-checkin{{$staff->worker_id}}','{{$staff->worker_id}}')" class="btn-checkout btn btn-sm" type="button" disabled><i class="fa fa-power-off"></i></button>
                                </div>                    
                            </li>
                    @endif
                @php
                    $count++;
                    }
                @endphp
                </ul>
            </div>  
        </div>
</div>
<div class="modal fade" id="reason_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reason checkout</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <span class="col-2" id="message" >Reason:</span>
                <input type="text" class="col-10 form-control form-control-sm" id="reason" name="reason" >
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="modal_checkin_id" name="">
            <input type="hidden" id="modal_checkout_id" name="">
            <input type="hidden" id="modal_worker_id" name="">
            <button type="button"  class="btn btn-primary btn-modal-checkout" >Checkout</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <input type="hidden" id="imageHidden" value="{{asset('images/user.png')}}">
@stop
@section('scripts')
<script>
    function opendModal(checkout_id,checkin_id,worker_id){
         $('#modal_checkout_id').val(checkout_id);
         $('#modal_checkin_id').val(checkin_id);
         $('#modal_worker_id').val(worker_id);
         $('#reason_modal').modal();
    }
    
    $(document).ready(function() {

        $('.btn-modal-checkout').on('click',function(){

            var checkout_id = $('#modal_checkout_id').val();

            var checkin_id = $('#modal_checkin_id').val();

            var reason = $('#reason').val();
            $('#reason').val("");

            var worker_id = $('#modal_worker_id').val();
            var checkin_type = 0;
            $.ajax({
            url: 'change-checkin-status',
            type: 'GET',
            dataType: 'html',
            data: {worker_id: worker_id, checkin_type:checkin_type,reason:reason},
            })
            .done(function(data) {
                $('#'+checkout_id).removeClass("btn-danger");
                $('#'+checkin_id).removeClass("btn-primary");
                $('.staff-inactive').append(
                    '<li class="list-inline-item block-staff staff-in'+worker_id+'">'+$('.staff-ac'+worker_id).html()+'</li>' );
                $('.staff-ac'+worker_id).remove();
                $('#reason_modal').modal('toggle');
                $('.staff-inactive').find('button.btn-checkin').attr("disabled", false);
                $('.staff-inactive').find('button.btn-checkout').attr("disabled", true);
                //alert(data);
            })
            .fail(function() {
                toastr.error('Checkin Error. Please Check again!');
                //console.log("error");
            });

        })
    });
    function changeCheckinStatus(that,btn_id_change)
    {
            var worker_id = $(that).siblings('input').val();

            if($(that).hasClass('btn-checkin')){

                var checkin_type = 1;

                var class_change = "btn-primary";

                var class_add = "btn-danger";
            }
            $.ajax({
                url: 'change-checkin-status',
                type: 'GET',
                dataType: 'html',
                data: {worker_id: worker_id, checkin_type:checkin_type},
            })
            .done(function(data) {

                $(that).addClass(class_change);
                $('#'+btn_id_change).addClass(class_add);
                $('.staff-active').append(
                    '<li class="list-inline-item block-staff staff-ac'+worker_id+'">'+$('.staff-in'+worker_id).html()+'</li>' );
                $('.staff-in'+worker_id).remove();
                //alert(data);
                //console.log(data);
                $('.staff-active').find('button.btn-checkin').attr("disabled", true);
                $('.staff-active').find('button.btn-checkout').attr("disabled", false);
                // $('.staff-inactive').find('button.btn-checkout').attr("disabled", true);
            })
            .fail(function() {
                toastr.error('Checkin Error. Please Check again!');
                //console.log("error");
            });
            

    }
</script>
<script>
    $(document).ready(function(){
        // alert(111);
        // $('.staff-active').find('button.btn-checkin').attr("disabled", true);
        // $('.staff-inactive').find('button.btn-checkout').attr("disabled", true);
    });
</script>
@stop

