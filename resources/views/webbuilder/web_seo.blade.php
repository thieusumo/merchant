@extends('layouts.master')
@section('title', 'Website Builder | Web Seo')
@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/tags-input/tagsinput.css') }}">
@stop
@section('content')   	
	<div class="x_panel">
    <form method="post" >  
    	@csrf      
            <div class="row form-group">
                 <label class="col-xs-3 col-sm-3 col-md-1">Description</label>
                 <div class="col-9 no-padding">                 
                    <textarea required="" class="form-control form-control-sm" name="description" id="" cols="30" rows="5">{{isset($webSeo->web_seo_descript) ? $webSeo->web_seo_descript : '' }}</textarea>
                 </div>                
            </div>    

            <div class="row form-group">
                  <label class="col-xs-3 col-sm-3 col-md-1">Keywords</label>
                 <div class="col-9 no-padding">
                    <input required="" type="text" name="keywords" data-role="tagsinput" value="{{isset($webSeo->web_seo_meta) ? $webSeo->web_seo_meta : '' }}">
                 </div>   
            </div>                  
       
        <div class="row form-group">           
                <label class="col-xs-3 col-sm-3 col-md-1">&nbsp;</label>
                <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
                    <button class="btn btn-sm btn-primary">SUBMIT</button>
                    <!-- <button class="btn btn-sm btn-default">CANCEL</button> -->
                </div>            
        </div>
    </form>
    </div>
@stop
@section('scripts')
    <script src="{{ asset('plugins/tags-input/tagsinput.js') }}"></script>
@stop

