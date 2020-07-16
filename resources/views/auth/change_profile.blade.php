@extends('layouts.master')
@section('title', 'Edit Profile')
@section('styles')
    
@stop
@section('content')
<div class="x_panel x_panel_form">
    <div class="x_content">
    <form class="form-horizontal" name="frm" action="{{asset('/change-profile')}}" method="post" custom-submit="" novalidate="novalidate" enctype="multipart/form-data">
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
                 <label class="col-xs-3 col-sm-3 col-md-1">Phone</label>
                 <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                    <input type='text' disabled value="{{ Session::get('current_user_phone')}}" class="form-control form-control-sm"/>
                 </div>  
                  <label class="col-xs-3 col-sm-3 col-md-1">Email</label>
                 <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                    <input type='text' disabled value="{{ Session::get('current_user_email')}}" class="form-control form-control-sm"/>
                 </div>   
            </div>    
            
        <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-1">First name</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input type='text' placeholder="Last name" value="{{ $user_name[0] }}"  name="firstname" class="form-control form-control-sm"/>
             </div>  
               <label class="col-xs-3 col-sm-3 col-md-1">Last name</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input type='text' placeholder="Last name" value="{{ $user_name[1] }}" name="lastname" class="form-control form-control-sm"/>
             </div>    
        </div>    
        
        <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-1">Nickname</label>
             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <input type='text' placeholder="Nickname" value="{{ $nickname }}" name="nickname" class="form-control form-control-sm"/>
             </div>            
        </div>    
        <div class="row form-group">
            <label class="col-xs-3 col-sm-3 col-md-1">Image</label>
            <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                <div class="catalog-image-upload">
                           <div class="catalog-image-edit">
                              <input type="hidden" name="cateservice_image_old" value="{{isset($avatar)? $avatar:old('profile_image')}}">
                               <input type='file' id="profile_image" name="profile_image" data-target="#catalogImagePreview1" accept=".png, .jpg, .jpeg" />
                               <label for="profile_image"></label>
                           </div>
                           <div class="catalog-image-preview">
                               <img id="catalogImagePreview1" style='display:{{isset($avatar)?"":"none"}}' src ="{{config('app.url_file_view')}}{{isset($avatar)?$avatar:""}}" height ="100%" />     
                           </div>
                </div>
                <!-- <div style="width:300px;height: 300px; border: 1px solid whitesmoke ;text-align: center;position: relative" id="image">
                    @if( $avatar != "" )
                        <img width="100%" height="100%" id="preview_image"  src="uploads/{{$avatar}}"/>
                    @else
                    <img width="100%" height="100%" id="preview_image"  src="{{asset('images/noimage.jpg')}}"/>
                    @endif
                    <i id="loading" class="fa fa-spinner fa-spin fa-3x fa-fw" style="position: absolute;left: 40%;top: 40%;display: none"></i>
                </div>
                <p>
                    <a href="javascript:changeProfile()" style="text-decoration: none;">
                        <i class="glyphicon glyphicon-edit"></i> Change
                    </a>&nbsp;&nbsp;
                    <a href="javascript:removeFile()" style="color: red;text-decoration: none;">
                        <i class="glyphicon glyphicon-trash"></i>
                        Remove
                    </a>
                </p>
                <input type="file" id="file" style="display: none"/>
                <input type="hidden" name="file_name" value="{{$avatar}}" id="file_name"/> -->
            </div>
        </div>
       
        <div class="row form-group">           
                <label class="col-xs-3 col-sm-3 col-md-1">&nbsp;</label>
                <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                    <button class="btn btn-sm btn-primary" >SUBMIT</button>
                    <!-- <button class="btn btn-sm btn-default">CANCEL</button> -->
                </div>            
        </div>
    </form>
    </div>
</div>
@stop

@section('scripts')

<script>
    function changeProfile() {
        $('#file').click();
    }
    $('#file').change(function () {
        if ($(this).val() != '') {
            upload(this);

        }
    });
    function upload(img) {
        var form_data = new FormData();
        form_data.append('file', img.files[0]);
        form_data.append('_token', '{{csrf_token()}}');
        $('#loading').css('display', 'block');
        $.ajax({
            url: "{{url('ajax-image-upload')}}",
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.fail) {
                    $('#preview_image').attr('src', '{{asset('images/noimage.jpg')}}');
                    toastr.error(data.errors['file']);
                }
                else {
                    $('#file_name').val(data);
                    $('#preview_image').attr('src', '{{asset('uploads')}}/' + data);
                }
                $('#loading').css('display', 'none');
            },
            error: function (xhr, status, error) {
                toastr.error(xhr.responseText);
                $('#preview_image').attr('src', '{{asset('images/noimage.jpg')}}');
            }
        });
    }
    function removeFile() {
        if ($('#file_name').val() != '')
            if (confirm('Are you sure want to remove profile picture?')) {
                $('#loading').css('display', 'block');
                var form_data = new FormData();
                form_data.append('_method', 'DELETE');
                form_data.append('_token', '{{csrf_token()}}');
                $.ajax({
                    url: "ajax-remove-image/" + $('#file_name').val(),
                    data: form_data,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#preview_image').attr('src', '{{asset('images/noimage.jpg')}}');
                        $('#file_name').val('');
                        $('#loading').css('display', 'none');
                    },
                    error: function (xhr, status, error) {
                        toastr.error(xhr.responseText);
                    }
                });
            }
    }

    //readUrl
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
</script>
@stop
