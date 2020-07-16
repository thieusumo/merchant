@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Content Template')
@section('styles')
<style>
     .top_nav{height: 84px;}       
</style>
@stop
@section('content')
<div id="sms" class="col-xs-12 col-md-12 fixLHeight no-padding full-height bg-white">
     <div class="col-xs-2 col-md-2 no-padding full-height scroll-view scroll-style-1">
        @include('marketing.sms.partials.menu') 
    </div>   
    <div class="col-xs-10 col-md-10 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
        <div class="x_panel border-0">   
            <div class="x_title">
                <h5 class="border_bottom">Content Template</h5>
            </div>
            <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
               <th class="text-center">ID</th>   
               <th class="text-center">Title</th>   
               <th>Content</th>                
               <th class="text-center">Last Update</th>
              </tr>
            </thead>
            {{-- <tbody>
                <tr>
                    <td class="text-center">1</td>                    
                    <td><a href="{{ route('editSmsTemplate',1)}}" class="view-template">Happy Birthday</a></td>
                    <td>Happy Birthday to {client_name}</td>
                    <td class="text-center">20/04/2019 11:20 AM by Admin</td> 
                </tr>
                   <tr>
                    <td class="text-center">2</td>
                    <td><a href="{{ route('editSmsTemplate',2)}}" class="view-template">Thanksgiving Holiday</a></td>
                    <td>Happy Birthday to {client_name}</td>
                    <td class="text-center">20/04/2019 11:20 AM by Admin</td> 
                </tr>
            </tbody>   --}}  
        </table>   
        </div>
    </div>
</div> 
@stop
@section('scripts') 
<script type="text/javascript">
$(document).ready(function() {
   $('#datatable').DataTable({
        dom: "lBfrtip",
        buttons: [],
        processing: true,
                 serverSide: true,
                 
                 ajax:{ url:"{{ route('content_template')}}"},
                     columns: [
                              { data: 'sms_content_template_id', name: 'sms_content_template_id' },
                              { data: 'template_title', name: 'template_title' },
                              { data: 'sms_content_template', name: 'sms_content_template' },
                              { data: 'updated_at', name: 'updated_at' },
                           ]    
  }); 
}); 
</script>      
@stop

