@extends('layouts.master')
@section('title', 'Setting | Business Store')
@section('styles')
<link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

@stop
@section('content')

{{-- @php
    echo config('app.url_file_view');
    echo $place_list->place_logo;
    die();
@endphp --}}
<div class="x_panel setting">
     {{-- @if ($errors->any())
     @foreach ($errors->all() as $error)
         <div style="color: red">{{$error}}</div>
     @endforeach
     @endif --}}
    <div class="x_content">
        <div class="col-md-12 no-padding">
            <form action="{{route('saveBusinessStore')}}" id="setting_form" enctype="multipart/form-data" method="post">
            @csrf
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="col-md-4">Logo</label>
                        <input type="hidden" name="place_logo_hidden" value="{{$place_list->place_logo}}">
                        <div class="col-md-8 logo-upload-container">
                            <div class="catalog-image-upload">
                                <div class="catalog-image-edit">
                                    <input type='file' id="imageUpload3" name="place_logo" data-target="#catalogImagePreview3" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload3"></label>
                                </div>
                                <div class="catalog-image-preview">
                                    <img id="catalogImagePreview3" height ="100%" src="{{!empty($place_list->place_logo)?config('app.url_file_view'):""}}{{!empty($place_list->place_logo)?$place_list->place_logo:""}}" />
                                </div>
                                
                            </div><span style="color: red">{{ $errors->has('place_logo')?$errors->first('place_logo')  : '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-4">Favicon</label>
                        <div class="col-md-8 logo-upload-container">
                            <div class="catalog-image-upload">
                                <div class="catalog-image-edit">
                                    <input type="hidden" name="place_favicon_hidden" value="{{$place_list->place_favicon}}">
                                    <input type='file' name="place_favicon" id="imageUpload4" data-target="#catalogImagePreview4" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload4"></label>
                                </div>
                                <div class="catalog-image-preview"  >
                                    <img height ="100%" id="catalogImagePreview4" src="{{!empty($place_list->place_favicon)?config('app.url_file_view'):""}}{{!empty($place_list->place_favicon)?$place_list->place_favicon:""}}"  />
                                </div>
                                <span style="color: red">{{ $errors->has('place_favicon')?$errors->first('place_favicon')  : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="col-md-4">Business name</label>
                        <div class="col-md-8">
                            <input required="" type='text' name="place_name" value="{{$place_list->place_name}}"  class="form-control form-control-sm{{ $errors->has('place_name') ? ' is-invalid' : '' }}" />
                            <span class="invalid-feedback feedback_bsn_name" role="alert"><strong></strong></span>
                            <span style="color: red">{{$errors->first('place_name')}}</span>   
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-sm-4">Address</label>
                        <div class="col-md-8">
                            <input type='text' required name="place_address" value="{{$place_list->place_address}}"  class="form-control form-control-sm{{ $errors->has('place_address') ? ' is-invalid' : '' }}" />

                            <span style="color: red">{{$errors->first('place_address')}}</span>   
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="col-sm-4">Tax code</label>
                       <div class="col-md-8">
                           <input type='number' name="place_taxcode" value="{{$place_list->place_taxcode??old('place_taxcode')}}"  class="form-control form-control-sm{{ $errors->has('place_taxcode') ? ' is-invalid' : '' }}" />

                            <span style="color: red">{{$errors->first('place_taxcode')}}</span>     
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-sm-4">Email</label>
                        <div class="col-md-8">

                            <input type='email' name="place_email" value="{{$place_list->place_email??$place_list->place_email}}"  class="form-control form-control-sm" /> 
                            <span class="invalid-feedback feedback_place_email" role="alert"><strong></strong></span>

                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="col-sm-4">Business phone</label>
                        <div class="col-sm-8 input-group-sm input-group-addon input-group-country-phone">
                            <div class="btn-group btn-group-sm btn-countrycode">
                             <button id="selected_phone" style="padding:4px 0px;" data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" type="button" aria-expanded="false">{{$place_list->place_country_id??old('place_country_id')}}<span class="caret"></span></button>
                             <ul id='select_phone' role="menu" class="dropdown-menu">
                                 {{-- <li id='84'><a href="#">84</a></li>
                                 <li id='1'><a href="#">1</a></li>
                                 <li id='61'><a href="#">61</a></li> --}} 
                                @foreach($data['headNumber'] as $value)
                                    <li value ="{{$value}}"><a value ="{{$value}}" href="#">{{$value}}</a></li>
                                @endforeach                       
                             </ul>
                             <input type="hidden" name="place_country_id" id="country_code" value="{{$place_list->place_country_id??old('place_country_id')}}">
                             </div>                   
                             <input onblur="formatPhone(this);" required class="form-control form-control-sm maskphone" placeholder="" value="{{$place_list->place_phone??$place_list->place_phone}}" name="place_phone" size="10" type="text" data-inputmask="'mask' : '(999) 999-9999'" >   
                             <span class="invalid-feedback feedback_place_phone" role="alert"><strong></strong></span>                 

                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-sm-4">Website</label>
                        <div class="col-sm-8">
                            <input required type='text' name="place_website" value="{{$place_list->place_website??$place_list->place_websiste}}"  class="form-control form-control-sm" /> 
                            <span class="invalid-feedback feedback_place_website" role="alert"></span>         
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="col-sm-4">Price floor</label>
                       <div class="col-sm-8 input-group-spaddon">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>                        
                                <input name="place_worker_mark_bonus" type="number"  class="form-control form-control-sm" value="{{$place_list->place_worker_mark_bonus??$place_list->place_worker_mark_bonus}}" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-sm-4">Interest($)</label>
                        <div class="col-sm-8 input-group-spaddon">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>                        
                                <input name="place_interest" type="number" class="form-control form-control-sm" value="{{$place_list->place_interest??$place_list->place_interest}}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row form-group">
                    <div class="col-md-6">
                        <label class="col-sm-4">Datetime option</label>
                       <div class="col-sm-8">
                           <input type='text' name="place_actiondate_option" value="{{($place_list->place_actiodate_option)?$place_list->place_actiondate_option:""}}"  class="form-control form-control-sm" />
                           <span>{{$errors->first('place_actiondate_option')}}</span>  
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-sm-4">Last long address</label>
                        <div class="col-sm-8">
                            <input type='text' required name="place_latlng" value="{{$place_list->place_latlng??$place_list->place_latlng}}"  class="form-control form-control-sm{{ $errors->has('place_latlng') ? ' is-invalid' : '' }}" />
                            <span>{{$errors->first('place_latlng')}}</span>  
                        </div>
                    </div>
                </div> -->
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="col-sm-4">Hide service price</label>
                       <div class="col-sm-8">
                           <div class="btn-group btn-group-toggle working-day" data-toggle="buttons">
                                    <label class="btn btn-sm btn-day {{$place_list->hide_service_price == '1' ? 'focus active' : '' }}">
                                        <input name="hide_service_price" {{$place_list->hide_service_price == '1' ? 'checked' : '' }} value="1" type="radio"> On
                                    </label>
                                    <label class="btn btn-sm btn-day {{$place_list->hide_service_price == '0' ? 'focus active' : '' }}">
                                      <input name="hide_service_price"   {{$place_list->hide_service_price == '0' ? 'checked' : '' }} value="0" type="radio"> Off
                                    </label>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <input type="hidden" name="place_actiondate" value="{{isset($place_list->place_actiondate)?$place_list->place_actiondate:""}}">
                        <label class="col-sm-2" style="margin-top:10px;">Working Day</label>
                        <div class="col-sm-10 workingtime">                 
                            @php $weekday = ['mon'=>'monday','tue'=>'tuesday','wed'=>'wednesday','thur'=>'thursday','fri'=>'friday','sat'=>'saturday','sun'=>'sunday']; @endphp
                            @foreach( $weekday as $key => $day)
                              <div class="col-day">  
                                <label>{{ ucfirst(trans($day)) }}</label>
                                <div class="btn-group btn-group-toggle working-day" data-toggle="buttons">
                                    <label class="btn btn-sm btn-day {{(isset($place_actiondate)&&$place_actiondate[$key]['closed']==false)?"focus active":""}}">
                                        <input name="work_{{ $key }}" {{((isset($place_actiondate)&&$place_actiondate[$key]['closed']==false)||!$place_actiondate)?"checked":""}} value="1" type="radio"  rel="{{ $day }}"> Open
                                    </label>
                                    <label class="btn btn-sm btn-day {{(isset($place_actiondate)&&$place_actiondate[$key]['closed']==true)?"focus active":""}}">
                                      <input name="work_{{ $key }}" value="0" {{(isset($place_actiondate)&&$place_actiondate[$key]['closed']==true)?"checked":""}} type="radio" rel="{{ $day }}"> Close
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>   
                    </div>    
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="col-sm-2">Time Start</label>
                        <div class="col-sm-10 workingtime">
                           @foreach( $weekday as $key => $day)
                            <div class="col-day input-group-spaddon day_{{ $day }}" style="visibility:{{(isset($place_actiondate)&&$place_actiondate[$key]['closed']==false||!$place_actiondate)?"":"hidden"}}">
                                <div class="input-group date">
                                    <input type='text' name="time_start_{{ $key }}" value="{{(isset($place_actiondate))?$place_actiondate[$key]['start']:""}}"  class="form-control form-control-sm timepicker"/>
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-time"></span>
                                    </span>                            
                                </div>
                            </div>
                            @endforeach      
                        </div>   
                    </div>    
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="col-sm-2">Time End</label>
                        <div class="col-sm-10 workingtime">
                           @foreach( $weekday as $key => $day)
                            <div class="col-day input-group-spaddon day_{{ $day }}" style="visibility:{{(isset($place_actiondate)&&$place_actiondate[$key]['closed']==false||!$place_actiondate)?'':'hidden'}}">
                                <div class="input-group date">
                                    <input type='text' name="time_end_{{ $key }}" value="{{(isset($place_actiondate))?$place_actiondate[$key]['end']:''}}"  class="form-control form-control-sm timepicker"/>
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-time"></span>
                                    </span>                            
                                </div>
                            </div>
                            @endforeach      
                        </div>   
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="col-sm-2">Description</label>
                        <div class="col-sm-10" >
                            <textarea rows="4" class="form-control" placeholder="description" name="description">{{$place_list->place_description??old('description')}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="col-sm-2">&nbsp;</label>
                        <div class="col-sm-8" >
                            <input class="btn btn-sm btn-primary" value="Save changes" id="submit" type="submit">
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@stop
@section('scripts')

<script type="text/javascript" src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    //select phone
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
        $('#selected_phone').text(headPhone);
        $('#country_code').val(del_plus);
    });

    if ($("input.timepicker")[0]) {
        $('input.timepicker').datetimepicker({
            format: 'LT'
        });
    }
    $('.working-day input').on( "change", function(e){
        var $day = $(e.target).attr("rel");
        $(".day_"+$day).css('visibility', $(e.target).val() == 1?'visible':'hidden');
    });
    $("#submit").on( "click", function(event){
            // validate form
            var validatorResult = $("#setting_form")[0].checkValidity();
            $("#setting_form").addClass('was-validated');
            if(!validatorResult){
                event.preventDefault();
                event.stopPropagation();           
                return;
            }
            //form = document.createElement('#customer_form');
            $('#setting_form').submit();
        });
});
function readURL(input) {
    if (input.files[0] && input.files[0]) {
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
//select first phone number
// $("#select_phone li").click(function() {
//     $v= $(this).attr('id');
//     // alert($v);
//     $('#selected_phone').text($v);
// });

</script>

<script>
    //check validate
    $(document).ready(function(){

        var check = 0;
        $("input[name='place_phone']").on("keypress blur",function(e){
            var str = $(this).val();
            // console.log(str.length);
            if(str.length < 7){
                $(this).addClass('is-invalid');
                check = 1;
                // $(".feedback_place_phone").text('must be phone number');
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $(".feedback_place_phone").text('');
                check = 0;
            }
            checkSubmit(check);
        });
        $("input[name='place_name']").on("keypress blur",function(e){
            var str = $(this).val();
            if(str.length <=0){
                $(this).addClass('is-invalid').addClass('is-valid');
                check = 1;
                // $(".feedback_bsn_name").text('please enter name');
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $(".feedback_bsn_name").text('');
                check = 0;
            }
            checkSubmit(check);
        });

        // $(document).on("keypress blur","input[name='place_worker_mark_bonus']",function(e){
        //     var str = $(this).val();
        //     var num = parseInt($(this).val());
        //     if(str.length <=0){
        //         $(this).addClass('is-invalid').addClass('is-valid');
        //         check = 1;
        //         // $(".feedback_bsn_name").text('please enter name');
        //     }else {
        //         $(this).removeClass('is-invalid').addClass('is-valid');
        //         $(".feedback_bsn_name").text('');
        //         check = 0;
        //     }
        //     checkSubmit(check);
        // });

        $("input[name='place_email']").on("keypress blur",function(e){
            var str = $(this).val();       
            // console.log(str.search("@"));
            // console.log(str.search("\\."));
            if(str != ''){
                if(str.search("\\@") == -1 || str.search("\\.") == -1){
                    check = 1;
                    // $(".feedback_place_email").text('must be email');
                    $(this).addClass('is-invalid');
                }else {
                    check = 0;
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $(".feedback_place_email").text('');
                }
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });

        $("input[name='place_website']").on("keypress blur",function(e){
            var str = $(this).val();     
            
            if(str.search("\\.") == -1){
                check = 1;
                // $(".feedback_place_website").text('must be website');
                $(this).addClass('is-invalid');
            }else {
                check = 0;
                $(this).removeClass('is-invalid').addClass('is-valid');
                $(".feedback_place_website").text('');
            }
            checkSubmit(check);
        });

        function checkSubmit(check){
            if(check == 1){
                $("input[type='submit']").attr('disabled',true);
            } else {
                $("input[type='submit']").attr('disabled',false);
            }
        }

    });
</script>
@stop

