@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Group Receiver')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
<style>
     .switchery-small{width:40px;}
     .switchery-small > small{left: 40px;}
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
                <h5 class="border_bottom ">Group Receivers</h5>
            </div>
            <div class="x_content">                
               
                <table id="datatableGroup" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">ID</th>   
                    <th class="text-center">Group Name</th>   
                    <th>Total User</th>                
                    <th class="text-center">Last Update</th>
                    <th class="text-center">Type</th>
                    <th>Action</th>                
                  </tr>
                </thead>
                {{-- <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-center">VIP Users 1</td>                           
                        <td class="text-center"><a href="#" class="view-group-receivers">134</a></td>
                        <td class="text-center">20/04/2019 11:20 AM by Admin</td> 
                        <td class="text-center"><a class="btn btn-sm btn-secondary  delete" href="#"><i class="fa fa-trash"></i></a></td> 
                    </tr>
                     <tr>
                        <td class="text-center">2</td>
                        <td class="text-center">VIP Users 2</td>                           
                        <td class="text-center"><a href="#" class="view-group-receivers">500</a></td>
                        <td class="text-center">20/04/2019 11:20 AM by Admin</td> 
                        <td class="text-center"><a class="btn btn-sm btn-secondary  delete" href="#"><i class="fa fa-trash"></i></a></td> 
                    </tr>
                </tbody>   --}}  
                </table>   
            </div>
        </div>
    </div>
</div> 
<!-- The Modal -->
<div class="modal" id="modelViewUsers">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
           <div class="row table-responsive">
               <div class="col-sm-12">
                    <table id="datatableReceiver" class="table table-striped table-bordered" width="100%">
                      <thead>
                      <tr>        
                          <th class="text-center">Phone</th>
                          <th class="text-center">Name</th>   
                          <th class="text-center">Birthday</th>        
                          <th class="text-center status">id Group</th>        
                      </tr>
                      </thead>
                      {{-- <tbody>
                          <tr>
                              <td>{Phone}</td>            
                              <td>{Name}</td>            
                              <td>{Proccessing}</td>            
                          </tr>
                      </tbody> --}}
                  </table>
                </div>
            </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
@stop
@section('scripts') 
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {

   var datatable = $('#datatableGroup').DataTable({
        dom: "lBfrtip",
        buttons: [],
        processing: true,
        serverSide: true,
        ajax:{ url:"{{ route('group_receivers')}}"},
                     columns: [
                              { data: 'sms_group_receivers_id', name: 'sms_group_receivers_id' },
                              { data: 'sms_group_receivers_group_name', name: 'sms_group_receivers_group_name' },
                              { data: 'total_user', name: 'total_user' },
                              { data: 'updated_at', name: 'updated_at' },
                              { data: 'sms_group_receivers_type', name: 'sms_group_receivers_type' },
                              { data: 'action', name: 'action' },
                           ]    
   }); 
   var datatableReceiver = $('#datatableReceiver').DataTable({
        dom: "lBfrtip",
        buttons: [],
        "columnDefs":[
        {
           "targets": [ 3 ],
            "visible": false
        }
        ],
        ajax:{ url:"{{ route('group_receivers_detail')}}"},
                     columns: [
                              { data: 'sms_group_receivers_detail_phone', name: 'sms_group_receivers_detail_phone' },
                              { data: 'sms_group_receivers_detail_name', name: 'sms_group_receivers_detail_name' },
                              { data: 'sms_group_receivers_detail_dob', name: 'sms_group_receivers_detail_dob' },                              
                              { data: 'sms_group_receivers_detail_group_receivers_id', name: 'sms_group_receivers_detail_group_receivers_id',orderable: false, searcheble: false },                              
                           ]
   }); 

  $("#datatableGroup").on('click', 'a.view-group-receivers', function (event) {
        event.preventDefault(); 
        var id = $(this).attr("data");
        //search id - datatableReceiver
        datatableReceiver.columns('.status').search(id).draw();        
        $('#modelViewUsers').modal('show');         
        
    });

  $("#datatableGroup").on('click', 'a.delete',function(e){ 
    e.preventDefault();
    var data = $(this).attr('data');
    var data_place = $(this).attr('data-place');
    if(confirm("Are you sure want to delete?")){
      $.ajax({ 
        url:"{{ route('delete.sms.greceiver') }}",
        method:"get",
        data:{data:data,data_place:data_place},
        success:function(data){ 
          toastr.success(data);
          datatable.draw();
        }
      })
    } else return false;
  });

}); 
</script>      
@stop

