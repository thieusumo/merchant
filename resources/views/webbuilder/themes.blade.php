@extends('layouts.master')
@section('title', 'Website Builder | Themes')
@section('styles')
<style>  
    .col-md-55{width: 22%;}    
</style>

@stop
@section('content')
<div class="x_panel x_panel_form">   
    <div class="x_content">
        <div class="row">
@php
    $list_themes = [
        [
            "name" => "Theme 01",
            "url" => "https://cdn.dataeglobal.com/images/place/theme/theme1.jpg",
            "price" => "199"           
        ],
        [
            "name" => "Theme 02",
            "url" => "https://cdn.dataeglobal.com/images/place/theme/demo_18.jpg",
            "price" => "299"           
        ],
        [
            "name" => "Theme 01",
            "url" => "https://cdn.dataeglobal.com/images/place/theme/theme1.jpg",
            "price" => "199"           
        ],
        [
            "name" => "Theme 02",
            "url" => "https://cdn.dataeglobal.com/images/place/theme/demo_18.jpg",
            "price" => "299"           
        ],[
            "name" => "Theme 01",
            "url" => "https://cdn.dataeglobal.com/images/place/theme/theme1.jpg",
            "price" => "199"           
        ],
        [
            "name" => "Theme 02",
            "url" => "https://cdn.dataeglobal.com/images/place/theme/demo_18.jpg",
            "price" => "299"           
        ],
    ];
    
@endphp   
@foreach( $themes as $theme)
    <div class="col-md-55">
        <div class="thumbnail thumbnail_themes">              
          <div class="image view view-first">                  
            <img style="width: 100%; display: block;" src="{{ config('app.url_file_view').$theme->theme_image}}" alt="image" />                       
            <div class="caption">
                <label class="text-white">{{ $theme->theme_name}}</label>
                <div class="float-right">
                    <a href="{{ $theme->theme_url}}" class="btn btn-sm btn-default" target="_blank">View</a>
                    <a href="{{asset("webbuilder/theme/payment/".$theme->theme_id)}}" class="btn btn-sm btn-default">Buy now</i></a>
                 </div>  
              </div>
          </div>          
          <span class="badge bg-orange price">${{ $theme->theme_price }}</span>   
        </div>
    </div>    
@endforeach
        </div>
    </div>        
</div>
{!! $themes->links() !!}
@stop
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
   
}); 
</script>        
@stop

