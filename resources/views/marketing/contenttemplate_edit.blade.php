@extends('layouts.master')
@section('title', 'Marketing | Image Templates | Add/Edit Image Template')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">  
@stop
@section('content')
 <div class="x_panel x_panel_form" style="min-height:296px;">
     <div class="x_title">
        @if($id==0)
          <h3>Add Image Template</h3>
        @else
          <h3>Edit Image Template</h3>
        @endif
     </div>
    <div class="x_content"> 
    <form method="post" id="user-form" name="user-form" action="{{route('save-contenttemplate')}}" enctype="multipart/form-data"> 
      <input type="hidden" name="content_id" value="{{$id}}" />
      @csrf
         @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Image</label>
            <div class="col-sm-5 col-md-4">
               <div class="catalog-image-upload">
                   <div class="catalog-image-edit">
                       <input type='file' id="imageUpload3" name="image" data-target="#catalogImagePreview3" accept=".png, .jpg, .jpeg" />
                       <label for="imageUpload3"></label>
                   </div>
                   <div class="catalog-image-preview">
                       <img onerror="this.style.display='none'" src="{{isset($subject->sub_image)?config('app.url_file_view').'/'.$subject->sub_image:old('catalogImagePreview3')}}" id="catalogImagePreview3" src="" style="width:inherit; height:inherit"/>
                   </div>
               </div>
            </div>          
        </div>  
        <div class="row form-group">
            <label class="col-sm-3 col-md-2">Name</label>
            <div class="col-sm-8 col-md-8">
               <input id="name" type='text' name="name" class="form-control form-control-sm" value="{{isset($subject->sub_name)?$subject->sub_name:old('name')}}" data-msg="Please enter name" required />
            </div> 
        </div>                  
         <div class="row form_group" style="margin-top:10px">
            <label class="col-sm-3 col-md-2">Description</label>
            <div class="col-sm-8 col-md-8">                
                <textarea id="description" class="form-control texteditor" name="description" value="{{isset($subject->sub_description)?$subject->sub_description:old('description')}}"></textarea>
            </div>     
       </div> 
        <div class="row form-group" style="margin-top:10px">
            <label class="col-sm-3 col-md-2">Type</label>
            <div class="col-sm-10 col-md-10 form-inline">
                <div class="radio">
                    <label>
                      <input type="radio" class="flat icheckstyle" name="userType" 
                        @if(isset($subject->sub_type))
                            @if($subject->sub_type==0)
                                checked
                            @endif
                        @else
                            checked
                        @endif
                      value="0">&nbsp;Gift card and Coupon
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                      <input type="radio" class="flat icheckstyle" name="userType"
                        @if(isset($subject->sub_type))
                            @if($subject->sub_type==1)
                                checked
                            @endif
                        @endif
                      value="1">&nbsp;Gift card
                    </label>
                  </div>
                <div class="radio" style="margin-left:10px;">
                    <label>
                        <input type="radio" class="flat icheckstyle" name="userType"
                          @if(isset($subject->sub_type))
                            @if($subject->sub_type==2)
                                checked
                            @endif
                          @endif
                        value="2">&nbsp;Coupon
                    </label>
                  </div>
            </div>                             
        </div>  
         <div class="row form-group" style="margin-top:10px">
             <label class="col-sm-3 col-md-2">&nbsp;</label>
            <div class="col-sm-6 col-md-6  form-group">
               <button class="btn btn-sm btn-primary" id="submit">SUBMIT</button>
               <a class="btn btn-sm btn-default" href="{{ route('contenttemplates')}}">CANCEL</a>
            </div>            
        </div>   

    </form>
    </div>
</div>     
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/summernote/summernote-bs4.js') }}"></script>
<script type="text/javascript">
  function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $($(input).attr("data-target")).attr('src', e.target.result);
            $($(input).attr("data-target")).hide();
            $($(input).attr("data-target")).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }      
  }
$(document).ready(function() {

  $("input[type=file]").change(function() {
    readURL(this);
  });

  if ($("input.icheckstyle")[0]) {
      $('input.icheckstyle').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
      });       
  } 
  if ($("textarea.texteditor")[0]) {
      $('textarea.texteditor').summernote({height: 150});

  }
  $("textarea.texteditor").summernote("code", '{{isset($subject->sub_description)?$subject->sub_description:old('description')}}');


  
  
  // $('.note-codable').attr('required', 'true');
  $("#submit").on( "click", function(event){
      // validate form
      var validatorResult = $("#user-form")[0].checkValidity();
      $("#user-form").addClass('was-validated');
        if(!validatorResult){
            event.preventDefault();
            event.stopPropagation();           
            return;
        }
        //form = document.createElement('#customer_form');
        $('#user-form').submit();
  });
}); 
</script>        
@stop

