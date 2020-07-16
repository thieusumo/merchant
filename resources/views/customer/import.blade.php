@extends('layouts.master')
@section('title', 'Clients | Import')
@section('styles')
 <link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">       
@stop
@section('content')
@if (session('status'))
    <div class="alert alert-info">{{session('status')}}</div>
@endif
 <div class="x_panel">
     <div class="x_title">
         <h3>Import Clients</h3>
     </div>
    <div class="x_content">
        <form action="{{ route('import-clients') }}" method="POST" enctype="multipart/form-data" id="customer-import-form" name="customer-import-form"> 
            {{ csrf_field() }}
            <div class="row col-md-10">
                <a href="{{route('export-clients')}}" class="blue">Download an import template spreadsheet</a>
            </div>
            <div class="row col-md-6">    
                <input type="file" id="file" name="file" data-buttonText="Select a File" class="form-control form-control-sm">        
                
            </div>                
            <div class="clear row  form-group" style="padding-top:20px;">    
                <label class="col-auto">Begin Row Index</label>
                <div class="col-auto">
                    <input type='number' name="begin_row" class="form-control form-control-sm" value="0"/>
                </div>    
                <label class="col-auto">End Row Index</label>
                <div>
                    <input type='number' name="end_row" class="form-control form-control-sm" value="1000"/>
                 </div>     
            </div> 
            <div class="clear row form-group">
                <label class="col-auto">
                    <input type="checkbox" disabled="true" class="checkFlat"  checked="checked"> Ignore first row 
                </label>
            </div>     
             <div class="clear row form-group">
                <label class="col-auto">
                    <input type="checkbox" class="checkFlat" name="check_update_exist" checked="checked"> Update existing Clients
                </label>
            </div>  
            <div class="ln_solid"></div>                
            <div class="row col-md-10">   
                 <button class="btn btn-primary" >SUBMIT</button>                  
            </div>   

        </form>
    </div>        
</div>    
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/custom/bootstrap-filestyle.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {   
    if ($("input.checkFlat")[0]) {
        $('input.checkFlat').iCheck({
            checkboxClass: 'icheckbox_flat-green'
        });       
       
    }
     $('#file').filestyle({ 
        text : 'Choose File',
        btnClass : 'btn-primary'
    });     
    
}); 
</script>   
@stop

