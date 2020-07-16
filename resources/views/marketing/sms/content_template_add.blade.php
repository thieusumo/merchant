@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Add Content Template')
@section('styles')
<style type="text/css">
    .top_nav{height: 84px;} 
    table.dataTable tbody tr:hover {
      background-color: #9dbfa6;
      color: #fff;
      cursor: -webkit-grab; 
      cursor: grab;
    }      
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
                <h5 class="border_bottom">{{isset($data["edit"]) ? 'Edit Content Template' : 'Add Content Template'}}</h5>
            </div>
            <div class="x_content">
                  <form action="" id="smsTemplateForm" method="post" name="usersmsTemplateForm" enctype="multipart/form-data">
                    {{csrf_field()}}
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                  <label for="templateTitle">Template Title</label>
                                  <input required="" name="templateTitle" type="text" class="form-control form-control-sm {{ $errors->has('templateTitle') ? ' is-invalid' : '' }}" value="{{isset($edit->template_title) ? $edit->template_title : old('templateTitle')}}">
                                  </div>
                                 <div class="form-group">
                                    <label for="textMessage">SMS Content Template </label>
                                    <textarea required="" name="smsContentTemplate" class="form-control {{ $errors->has('smsContentTemplate') ? ' is-invalid' : '' }}" id="textMessage" rows="3">{{isset($edit->sms_content_template) ? $edit->sms_content_template : old('smsContentTemplate')}}</textarea>
                                    <span class="note"><span id="length">0</span>/160 characters</span>
                                  </div>
                                <div class='form-group'>
                                     <button type="button" class="btn btn-sm btn-dark" data-toggle="modal" data="coupon_code">Coupon Code</button>
                                     <button type="button" class="btn btn-sm btn-dark" data-toggle="modal"  data="coupon_link">Coupon Link</button>
                                     <button type="button" class="btn btn-sm btn-dark" data-toggle="modal"  data="promotion_link">Promotion Link</button>
                                 </div>
                                <div class="form-group">
                                    <button type="button" id="clear" class="btn btn-sm btn-secondary">CLEAR</button>
                                    <button type="submit" class="btn btn-sm btn-primary">SUBMIT</button>
                                    <a href="{{ route('smsTemplate') }}" class="btn btn-sm btn-primary">BACK</a>
                                  </div>
                            </div>
                            <div class="col-md-7">
                                <div class='form-group'><h6>Params Content</h6></div>                                
                                <div class='form-group'>
                                     <div>Client/Receiver</div>
                                     <button type="button" data="{name}" class="params_content btn btn-sm btn-secondary">Name</button>
                                     <button type="button" data="{phone}" class="params_content btn btn-sm btn-secondary">Phone</button>                                     
                                 </div>
                                <div class='form-group'>
                                     <div>About Shop</div>
                                     <button type="button" data="{{isset($PosPlace->place_name) ? $PosPlace->place_name : ''}}" class="params_content btn btn-sm btn-secondary">Shop Name</button>
                                     <button type="button" data="{{isset($PosPlace->place_phone) ? $PosPlace->place_phone : ''}}" class="params_content btn btn-sm btn-secondary">Shop Phone</button>
                                     <button type="button" data="{{isset($PosPlace->place_email) ? $PosPlace->place_email : ''}}" class="params_content btn btn-sm btn-secondary">Shop Email</button>
                                     <button type="button" data="{{isset($PosPlace->place_address) ? $PosPlace->place_address : ''}}" class="params_content btn btn-sm btn-secondary">Shop Address</button>
                                 </div>
                                <div class='form-group'>
                                    <div>Ticket</div>
                                     <button type="button" data="{ticket_number}" class="params_content btn btn-sm btn-secondary">Ticket Number</button>
                                     <button type="button" data="{ticket_price}" class="params_content btn btn-sm btn-secondary">Ticket Price</button>
                                     <button type="button" data="{ticket_date}" class="params_content btn btn-sm btn-secondary">Ticket Date</button>
                                     <button type="button" data="{ticket_time}" class="params_content btn btn-sm btn-secondary">Ticket Time</button>                                     
                                 </div>                                
                            </div>
                        </div>
                </form>
            </div> 
        </div>
    </div>
</div> 

<!--counpon_code Modal -->
<div class="modal fade" id="coupon_codeModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ashowModalLabel">Coupon Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="coupon_code_datatable" class="table table-striped table-bordered" style="width: 100%">
          <thead>
            <tr>  
              <th>Code</th>
              <th>Price</th>    
            </tr>
          </thead>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>
<!--coupon_link Modal -->
<div class="modal fade" id="coupon_linkModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bshowModalLabel">Coupon Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="coupon_link_datatable" class="table table-striped table-bordered" style="width: 100%">
          <thead>
            <tr>  
              <th>Code</th>
              <th>Price</th>    
            </tr>
          </thead>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>
<!--promotion link Modal -->
<div class="modal fade" id="promotion_linkModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cshowModalLabel">Promotion Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="promotion_link_datatable" class="table table-striped table-bordered" style="width: 100%">
          <thead>
            <tr>  
              <th>Code</th>
              <th>Price</th>    
            </tr>
          </thead>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>

@stop
@section('scripts') 
<script type="text/javascript">
$(document).ready(function() {

   $(".params_content").on("click",function(){
    var data = $(this).attr("data");
    var text = $("#textMessage").val();
    text += data;
    $("#textMessage").val(text);

    convertSmsContentTemplate();

   });

   $("#clear").on("click",function(){
    $("input[name='templateTitle']").val('');
    $("#textMessage").val('');
   });

   // maxlength = 160
   $("#textMessage").on("keyup",function(){
        convertSmsContentTemplate();
   });

   function convertSmsContentTemplate(){
        var MaxLength = 160;
        var length = $("#textMessage").val().length;
        $("#length").text(length);

        if(length > MaxLength){
            $("#length").text(160);
           var str = $("#textMessage").val();
           var s_str = str.substring(0,MaxLength);
           $("#textMessage").val(s_str);
        }   
    }

    $("button[data-toggle='modal']").on("click",function(){        
        var data = $(this).attr("data");
        
        if(data == "coupon_code"){
            $("#coupon_codeModal").modal("show");    

        } else {
            if(data == "coupon_link"){
                $("#coupon_linkModal").modal("show");
            }
            else{
                $("#promotion_linkModal").modal("show");
            }
        }

        
        
    });

    //data tables coupon code
           aTable = $('#coupon_code_datatable').DataTable({
                 processing: true,
                 serverSide: true,
                 
                 ajax:{ url:"{{ route('coupon_code')}}"},
                     columns: [
                              { data: 'coupon_code', name: 'coupon_code' },
                              { data: 'coupon_discount', name: 'coupon_discount' },
                           ]    
            });
    //-----------------------
    //data tables coupon link
           bTable = $('#coupon_link_datatable').DataTable({
                 processing: true,
                 serverSide: true,
                 
                 ajax:{ url:"{{ route('coupon_code')}}"},
                     columns: [
                              { data: 'coupon_code', name: 'coupon_code' },
                              { data: 'coupon_discount', name: 'coupon_discount' },
                           ]    
            });
    //-----------------------
    //data tables promotion link
           cTable = $('#promotion_link_datatable').DataTable({
                 processing: true,
                 serverSide: true,
                 
                 ajax:{ url:"{{ route('promotion_link')}}"},
                     columns: [
                              { data: 'promotion_name', name: 'promotion_name' },
                              { data: 'promotion_discount', name: 'promotion_discount' },
                           ]    
            });

    //-----------------------
    $('#coupon_code_datatable tbody').on('click', 'tr', function(){ 
        var data = aTable.row(this).data();
        var str = $("#textMessage").val();
        str += data['coupon_code'];
        $("#textMessage").val(str);
        $("#coupon_codeModal").modal("hide");
        convertSmsContentTemplate();
    });
    //----      
    //-----------------------
    $('#coupon_link_datatable tbody').on('click', 'tr', function(){ 
        var data = bTable.row(this).data();
        var str = $("#textMessage").val();
        str += data['coupon_code'];
        $("#textMessage").val(str);
        $("#coupon_linkModal").modal("hide");
        convertSmsContentTemplate();
    });
    //----
    //-----------------------
    $('#promotion_link_datatable tbody').on('click', 'tr', function(){ 
        var data = cTable.row(this).data();
        var str = $("#textMessage").val();
        str += data['promotion_name'];
        $("#textMessage").val(str);
        $("#promotion_linkModal").modal("hide");
        convertSmsContentTemplate();
    });
    //----

    
}); 
</script>      
@stop

