@extends('layouts.master')
@section('title', 'Website Builder | Themes | Payment')
@section('styles')
 <style>
     .thumbnail{margin: 10px 10px; min-height: 250px;}
     .thumbnail img{height: auto;}
     .paymentinfo{background: #F2F5F7; padding: 20px 20px;}
 </style>
@stop
@section('content')
<div class='x_panel x_panel_form'>
    <div class="x_content">
        <form method="post" class="form-horizontal form-label-left">                                  
           <div class="row">                         
            <div class="col-sm-5 col-md-4">
                <div class="row"><label class="col-auto"><h4>Choose Template</h4</label></div>   
                <select id="sel_theme" class="form-control form-control-sm">
                    @foreach($themes as $theme)
                        @if($theme->theme_id==$id)
                            <option value="{{$theme->theme_id}}" selected="selected">{{$theme->theme_name}}</option>
                        @else 
                            <option value="{{$theme->theme_id}}">{{$theme->theme_name}}</option>
                        @endif
                        
                    @endforeach                  
                   {{--  <option value="1">Theme 1</option>
                    <option value="1">Theme 2</option>
                    <option value="1">Theme 3</option>
                    <option value="1" selected="selected">Theme 18</option> --}}
                </select>
                <div class="row">
                    <!-- Noi dung thumnail thay doi khi chon lai template-->
                    <div id="thumnail_theme" class="thumbnail thumbnail_themes">   
                        <img id="my_image" style="width: 500px; display: block;" src="{{ config('app.url_file_view').$themes[$id]->theme_image}}"/>
                        <span class="badge bg-orange price">{{$themes[$id]->theme_price}}</span>
                    </div>                      
                </div>
            </div>
            <div class="col-sm-8 col-md-8">
                @include('webbuilder.partials.ccinfo')
            </div>        
         </div>          
           
        </form>
    </div>        
</div>
@stop
@section('scripts')

<script type="text/javascript">

$(document).ready(function() {    
    $("#sel_theme").change(function(){
        var id = $(this).val();
        var env = '{{ config('app.url_file_view')}}';
        console.log(id);
        // debugger;
        $.ajax({
            url: '{{ route('get.img.theme') }}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success:function(response){
                $("#my_image").attr("src",env+response.image);

            }
        });
    });
}); 
</script>      
@stop

