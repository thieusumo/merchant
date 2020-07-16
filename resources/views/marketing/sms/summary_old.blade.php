@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Account Summary')
@section('styles')
<link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">  
<style type="text/css">
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
                <h5>Account Summary</h5>
            </div>
            <div class="x_content">          
                <div class="row form-group">
                    <div class="col-xs-2 col-md-2">TOTAL USED</div>
                    <div class="col-xs-2 col-md-2"> <strong>50</strong></div>
                    <div class="col-xs-2 col-md-2">BALANCE</div>
                    <div class="col-auto"> <strong>0</strong></div>
                </div>  
            </div> 
        </div>
        <div class="x_panel border-0">               
            <div class="x_title">
                <h5>Tracking History</h5>
            </div>
            <div class="x_content">    
                <div class="form-group">
                <form action="" method="get" action="" id="service_form" name="service_form" class="form-inline">   
                    <div class="form-group" style="margin-right:10px;">                                                                            
                        <div class="input-group-sm">
                          <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                          <input type="text" style="width: 200px" name="search_join_date" id="search_join_date" class="form-control form-control-sm" value=""/>
                        </div>                    
                    </div>
                    <div class="form-group" style="margin-right:10px;">                                     
                        <select class="form-control form-control-sm" name="event_type_id" id="event_type_id">
                           <option value="">-- Event Type-- </option>
                              @foreach($event_list as $id => $name)
                                <option value="{{$id}}">{{$name}}</option>
                              @endforeach
                         </select>
                    </div> 
                    <div class="form-group">  
                        <button type="submit" class="btn btn-sm btn-primary search" style="margin-bottom:0px;">Search</button>
                          <button class="btn btn-sm btn-default" id="" type="reset" style="margin-bottom:0px;">Clear</button>
                    </div>
                </form>
                </div>
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                      <tr>                       
                       <th class="text-center">Event Type</th>   
                       <th class="text-center">Date & Time</th>
                       <th class="text-center">Content</th>              
                       <th class="text-center">Sent to</th>

                      </tr>
                    </thead> 
                </table>  
            </div> 
        </div>
       
    </div>
</div> 

@stop
@section('scripts') 
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>   
<script type="text/javascript">
$(document).ready(function() {   
   
    $('#search_join_date').daterangepicker({ 
       autoUpdateInput: false,

      locale: {
        cancelLabel: 'Clear'
      }
   }); 

  $('#search_join_date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('#search_join_date').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });
    sTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,

             buttons: [
                 
             ],
             columnDefs: [
             {
                  "targets": 0,
                  "className": "text-left",
             },
             {
                  "targets": 1, 
                  "className": "text-center"
             },
             {
                  "targets": 2,
                  "className": "text-left",
             },
             {
                  "targets": 3,
                  "className": "text-center",
             },
             ],
             ajax:{ url:"{{ route('get-event') }}",
                 data: function (d) {
                        d.event_type_id = $('#event_type_id :selected').val();
                        d.search_join_date = $('#search_join_date').val();
                    }
                  },
                 columns: [

                          { data: 'event_type', name: 'event_type' },
                          { data: 'date_time', name: 'date_time' },
                          { data: 'content', name: 'content' },
                          { data: 'send_to', name: 'send_to' },
                ],
       });
     $(document).on('click', '.search', function(e) {
          sTable.draw();
          e.preventDefault();
      });
    
}); 
</script>      
@stop

