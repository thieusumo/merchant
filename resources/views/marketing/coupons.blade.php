@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | Coupons')
@section('styles')
<link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">  
<style>
    .repeat{
        display: none;
    }
    .coupon-image{
        width: 100px;
        max-height: 100px;
    }    
    select[name=content_template]{ margin-bottom: 5px;}
    ul.dayinweek li{ display: inline-block; min-width: 80px; padding: 4px 0px;}
    ul.dayinweek li span{ margin-left: 5px;}
    .inputsendto{ padding-left:24px;}
    .hidden{ display: none;}
</style>
@stop
@section('content')
<div class="modal fade " id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="col-xs-12 col-md-12 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
        <div class="x_panel border-0"> 
            <div class="x_title">
                <h5 class="border_bottom text-center">CREATE SEND SMS EVENT</h5>
            </div>
            <div class="x_content">
            <form action="{{route('send-sms-coupon')}}" method="post" id="calendar_form" name="search_form" enctype="multipart/form-data">
            {{csrf_field()}}  
                <div class="col-xs-12 col-md-12 no-padding">
                    <div class="col-xs-6 col-md-6 no-padding"> 
                        <div class="row form-group">
                             <label for="event_title" class="col-xs-3 col-md-3">Title</label>
                             <div class="col-xs-8 col-md-8">
                                 <input required="" type="text" class="form-control form-control-sm {{$errors->has('event_name') ? 'is-invalid' : ''}}" value="{{old('event_name')}}" id="event_title" name="event_name"/>
                             </div>            
                        </div>        
                        <div class="row form-group">
                             <label for="event_type" class="col-xs-3 col-md-3 ">Type</label>
                             <div class="col-xs-8 col-md-8">
                                 <select name="event_type" id="event_type" class="form-control form-control-sm">
                                     @foreach( $listEventType as $id => $name)                            
                                            <option value="{{ $id }}">{{ $name }}</option>                                     
                                      @endforeach                                     
                                 </select>
                             </div>            
                        </div>                         
                         <div class="row form-group">
                             <label for="content_template" class="col-xs-3 col-md-3">Sms Message</label>
                             <div class="col-xs-8 col-md-8">
                                 <select required="" id="content_template" name="content_template" class="form-control form-control-sm">
                                     <option value=""> -- Content Template -- </option>
                                     @foreach ($template_list_default as $c_t)
                                         <option value="{{$c_t->sms_content_template_id}}">{{$c_t->template_title}}</option>
                                     @endforeach
                                 </select>
                                 <textarea class="form-control" readonly="readonly" id="sms_message" rows="2" cols="50"></textarea>
                             </div>            
                        </div> 
                        <div class="row">
                             <label for="start_date" class="col-xs-3 col-md-3">Start Date</label>
                             <div class="col-xs-8 col-md-8 input-group-spaddon">
                                 <div class="col-xs-6 col-md-6 no-padding">
                                    <div class='input-group date'>                    
                                        <input type='text' id="start_date"  value="{{old('start_date')}}" class="form-control form-control-sm datepicker {{$errors->has('start_date') ? 'is-invalid' : ''}}"  required="required"/>
                                        <input type='hidden'  name="start_date" value="{{old('start_date')}}" />
                                        <span class="input-group-addon">
                                           <span class="glyphicon glyphicon-calendar"></span>
                                        </span>                                     
                                    </div>
                                 </div>                                     
                                 <div class="col-xs-6 col-md-6" style="padding-right:0px;">
                                    <div class='input-group date'>  
                                        <input type='text' id="start_time" name="start_time" value="{{old('start_time')}}" class="form-control form-control-sm timepicker {{$errors->has('start_time') ? 'is-invalid' : ''}}"  required="required" placeholder="Time Send" />
                                        <span class="input-group-addon">
                                           <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>                
                                          
                                 </div>
                             </div>            
                        </div> 
                        <div class="row form-group">
                             <label for="repeat" class="col-xs-3 col-md-3 repeat">Repeat</label>
                              <div class="col-xs-8 col-md-8">
                                  <select id="repeat" name="repeat" class="form-control form-control-sm repeat">
                                      <option value="no">Don't repeat</option>
                                      <option value='w'>Weekly</option>
                                      <option value='m'>Monthly</option>
                                      <option value='y'>Yearly</option>
                                  </select>
                             </div>           
                        </div> 
                        <div class="row repeat_no">
                             <label for="send_before" class="col-xs-3 col-md-3">Send before</label>
                              <div class="col-xs-8 col-md-8">
                                  <div class="input-group input-group-spaddon">                                    
                                    <input type="number" class="form-control form-control-sm {{$errors->has('send_before') ? 'is-invalid' : ''}}" value="{{old('send_before')}}"  onkeypress="return isNumberKey(event)" name="send_before" style="max-width: 100px;">
                                    <span class="input-group-addon" style="width:auto">days</span> 
                                  </div>
                             </div>           
                        </div>                         
                        <div class="row form-group repeat_y hidden">
                             <label for="repeat_year" class="col-xs-3 col-md-3">Repeat on</label>
                             <div class="col-xs-8 col-md-8">
                                 <ul class="no-padding" style="list-style: none;">
                                    <li class="list-inline-item">day</li>
                                    <li class="list-inline-item"><select name="repeat_year_day" class="form-control form-control-sm" style="width:60px">
                                         @for ($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                         @endfor
                                        </select></li>
                                    <li class="list-inline-item">month</li>  
                                    <li class="list-inline-item"><select name="repeat_year_month" class="form-control form-control-sm" style="width:60px">
                                         @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                         @endfor
                                        </select></li>
                                    <li class="list-inline-item">every year</li>          
                                 </ul>
                             </div>           
                        </div>
                        <div class="row form-group repeat_m hidden">
                             <label for="repeat_month_day" class="col-xs-3 col-md-3">Repeat on</label>
                             <div class="col-xs-8 col-md-8">
                                 <ul class="no-padding" style="list-style: none;">
                                    <li class="list-inline-item">day</li>
                                    <li class="list-inline-item"><select name="repeat_month_day" class="form-control form-control-sm" style="width:60px">
                                         @for ($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                         @endfor
                                        </select></li>
                                    <li class="list-inline-item">every month</li>          
                                 </ul>
                             </div>           
                        </div>
                        <div class="row form-group repeat_w hidden">
                             <label for="repeat_weekly" class="col-xs-3 col-md-3">Repeat on</label>
                             <div class="col-xs-8 col-md-8">
                                 <ul class="list-inline dayinweek">
                                     <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="1"><span>Monday</span></li>
                                     <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="2"><span>Tuesday</span></li>
                                     <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="3"><span>Wednesday</span></li>
                                     <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="4"><span>Thursday</span></li>
                                     <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="5"><span>Friday</span></li>
                                     <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="6"><span>Saturday</span></li>
                                     <li><input type="checkbox" class="icheckstyle" name="repeat_weekly[]" value="0"><span>Sunday</span></li>
                                 </ul>       
                             </div>           
                        </div> 
                        <div class="row repeat_w repeat_m repeat_y hidden">
                             <label for="end_date" class="col-xs-3 col-md-3">End Date</label>
                              <div class="col-xs-8 col-md-8 input-group-spaddon">                                  
                                 <div class='col-xs-6 col-md-6 no-padding input-group date'>                    
                                     <input type='text' id="end_date"  value="{{old('end_date')}}" class="form-control form-control-sm datepicker {{$errors->has('end_date') ? 'is-invalid' : ''}}"  />
                                     <input type='hidden'  name="end_date" value="{{old('end_date')}}"  />
                                     <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                     </span>
                                 </div>                
                             </div>           
                        </div> 
                    </div>
                    <div class="col-md-6">                         
                        <h6>Send to</h6>
                        <div class="form-group">
                            <input type="checkbox" class="icheckstyle" checked="checked" name="checkbox_group_receiver"> Group Receiver
                            <div class="inputsendto">
                               <select name="group_receiver" class="form-control form-control-sm">
                                       <option value="">--Group Receiver--</option>
                                       @foreach ($group_list as $key => $value)
                                           <option value="{{$key}}">-{{ucwords($value)}}</option>
                                       @endforeach
                               </select>
                            </div>    
                        </div>
                        <div class="form-group add_more_phone repeat">
                            <input name="checkbox_add_receiver" type="checkbox" class="icheckstyle " checked="checked" data-toggle='collapse' data-target='#collapsediv1'> Add More Phone
                            <div class="inputsendto">
                               <input type="text" onkeypress="return isNumberKey(event)" name="add_receiver" class="form-control form-control-sm padding-top-10"/>
                               <div class="small">(Only alphanumeric characters:, to separate numbers)</div>
                            </div>
                        </div>      
                        <div class="form-group">  
                             <div class="inputsendto">
                                <button type="submit" class="btn btn-sm btn-primary" id="btnSubmit">Submit</button>
                            </div>
                        </div>  
                    </div>
                </div>    
            </form>
            </div>
        </div>
    </div>
      </div>
      
    </div>
  </div>
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <td width="1">Id</td>
        <th class="text-center">Image</th>
        <th width="50" class="text-center">Code</th>        
        <th width="70" class="text-center">Date Start</th>
        <th width="70"  class="text-center">Date End</th>
        <th width="40" class="text-center">Discount</th>                
        <th width="40" class="text-center">Quantity</th>                 
        <th width="40" class="text-center">Balance</th>  
        <th>Services</th>
        <th width="70" class="text-center">Created</th>
        <th class="text-center" width="70">Action</th>
      </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="11">Data not found </td>
        </tr>
    </tbody>    
</table>   
</div>
<!-- Modal -->
 <div id="myModalImage" class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog" style="max-width:585px;">       
         <div class="modal-content">
               <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin: 3px 20px;">&times;</button>                   
                </div>
             <div class="modal-body text-center" style="padding-top:0px;">
                 <img>
             </div>
         </div>
         <!-- /.modal-content -->
     </div>
     <!-- /.modal-dialog -->
 </div>
 <!-- /.modal -->


<div class="modal fade" id="optionModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Setup Coupon</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body text-center">
          <a class="btn btn-primary" href="{{ route('autoAddCoupon') }}">Automatic Coupon</a>
          <a class="btn btn-warning" href="{{ route('addCoupon') }}">Custom Coupon</a>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('input.icheckstyle').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    $('input#start_date').daterangepicker({         
        singleDatePicker: true, 
        isInvalidDate: true,
        autoUpdateInput: false,
        minDate: moment()
    });        
    $('input#end_date').daterangepicker({         
        singleDatePicker: true, 
        isInvalidDate: true,
        autoUpdateInput: false,
        minDate: moment()
    });        
    $('input#end_date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
        $('input[name="end_date"]').val(picker.startDate.format('YYYY/MM/DD'));
    });
    $('input#start_date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
        $('input[name="start_date"]').val(picker.startDate.format('YYYY/MM/DD'));
    });

    $('input#end_date').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('input.timepicker').datetimepicker({            
           format: 'hh:mm A',
    }).on('dp.change', function (e) {
        $(e.target).trigger('change');
    });
    $('select#repeat').on('click', function() {
         var arrDiv = ['repeat_no','repeat_w','repeat_m','repeat_y'];
         $.each(arrDiv, function(idx, item){
            $("div."+item).hide(); 
         });
         $("div.repeat_"+$(this).val()).show();
    });
   if ($('#datatable').length ){       
        $('#datatable').DataTable({
            dom: "lBfrtip",
            buttons: [
               {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Setup Coupon',                    
                    className: "btn-sm btn-add",
                }
            ],
            processing: true,
            serverSide: true,
            ajax:{ url:"{{ route('getCouponDataTables') }}" },
            columns: [
                { "data": "coupon_id", "bVisible": false ,"bSearchable": false},
                { data: 'coupon_linkimage', name: 'coupon_linkimage', sClass: "text-center coupon-image" },
                { data: 'coupon_code', name: 'coupon_code' , sClass: "text-center" },
                { data: 'date_start', name: 'coupon_startdate' , sClass: "text-center" },
                { data: 'date_end', name: 'coupon_deadline' , sClass: "text-center" },
                { data: 'coupon_discount', name: 'coupon_discount' , sClass: "text-right" },
                { data: 'quantity', name: 'coupon_quantity_limit' , sClass: "text-right" },
                { data: 'balance', name: 'balance' , sClass: "text-right" ,searchable: false },
                { data: 'services' , name: 'services',  orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' , sClass: "text-center" },
                { data: 'action' , name: 'action',  orderable: false, searchable: false , sClass: "text-center nowrap" }
             ],
             "aaSorting": [
                [ 9, "desc" ]
            ],
       }); 
    }
    $('#content_template').on('change',function(){
            var id = $(this).children('option:selected').attr("value");
            
            $.ajax({
                url:"{{ route('get-content-template-booking') }}",
                method:"get",
                data:{id:id},
                success:function(data){
                       $("#sms_message").val(data);                    
                }
            });
        }); 
    
    $("#datatable").on('click', 'a.delete', function (event) {
        if(confirm("Are you sure to want to delete this coupon ?")){
            
            $.post("{{ route('deleteCoupon') }}",{id: $(this).attr("data-id")},
            function( data ) {   
                if(data.success){
                    toastr.success("Coupon has been deleted successfully");
                        $('#datatable').DataTable().ajax.reload();
                }else{
                    toastr.error(data.messages)
                }      
            },'json');
        }
        event.preventDefault();
    });
    
    $("#datatable").on('click', 'td.coupon-image a img', function (event) {
        $("#myModalImage .modal-body img").attr('src',$(this).attr("src"));
        $('#myModalImage').modal('show')
        event.preventDefault();
    });
    // $(document).on('click','.send-sms',function(){
    //     $("#myModal").modal('show');
    // });
    $("#event_type").change(function(event) {

            var event_type_id = $("#event_type option:selected").val();

            if(event_type_id == 1){
                $(".repeat").slideUp();
                $(".add_more_phone").slideUp();
            }
            else{
                $(".repeat").slideDown();
                $(".add_more_phone").slideDown();
            }
        });
     $(".btn-add").on('click',function(e){
        e.preventDefault();
        $("#optionModal").modal("show",200);
     });

        
}); 
</script>            
@stop

