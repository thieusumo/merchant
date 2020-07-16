@extends('layouts.master')
@section('title', 'Website Builder | Social Network Website')
@section('styles')
    
@stop
@section('content')
<div class='x_panel'>
    <div class="x_content">
    <form method="post" action="{{route('save-social')}}" class="form-horizontal form-label-left">
      @csrf
        <div class="row col-md-12">
            @foreach( $socialNetworkArr as $key => $item)
            <div class="col-md-6 col-sm-5 col-xs-12 form-group">  
               <label for="{{ str_replace(' ', '_', $key) }}" class="control-label col-md-3 col-sm-2 col-xs-12">{{ $key }}</label>
               <div class="col-md-8 col-sm-7 col-xs-12">
                 <input id="{{ str_replace(' ', '_', $key) }}"  type='text' class="form-control form-control-sm" name="{{ str_replace(' ', '_', $key) }}" value="{{$item}}" />
               </div> 
           </div>
           @endforeach
           <div class="col-md-6 col-sm-5 col-xs-12 form-group">  
               </div>
           <div class="col-md-6 col-sm-5 col-xs-12 form-group">  
               <label class="control-label col-md-3 col-sm-2 col-xs-12">&nbsp;</label>
               <div class="col-md-8 col-sm-7 col-xs-12">
                 <button class="btn btn-sm btn-primary" >Save changes</button>                   
               </div> 
           </div>
        </div>
    </form>
    </div>
</div>    


@stop
@section('scripts')
    
@stop

