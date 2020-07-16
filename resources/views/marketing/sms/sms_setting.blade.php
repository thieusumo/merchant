@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Content Template')
@section('styles')
<style>
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
{{-- <div  class="col-xs-6 col-md-6 fixLHeight no-padding full-height bg-white">  
    
</div>  --}}
<!-- list Client model -->
<div class="modal fade" id="list_client_model" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ashowModalLabel">List Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="list_client_datatable" class="table table-striped table-bordered" style="width: 100%">
          <thead>
            <tr>  
              <th>Full name</th>
              <th>Phone</th>    
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>1</td>
            </tr>
          </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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

{{-- add or edit sms content template --}}
<div  class="col-xs-8 col-md-8 fixLHeight no-padding full-height bg-white"> 
    <div class="col-xs-12 col-md-12 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
        <div class="x_panel border-0 add_or_edit" style="display: none">               
            <div class="x_title">
                <h5 class="border_bottom add-edit">{{isset($edit) ? 'Edit Content Template' : 'Add Content Templatee'}}</h5>
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
                                    <input type="hidden" name="id">
                                    <button type="button" id="clear" class="btn btn-sm btn-secondary">CLEAR</button>
                                    <button type="submit" class="btn btn-sm btn-primary">SUBMIT</button>
                                    <a href="#" id="hide_add_or_edit" class="btn btn-sm btn-primary">HIDE</a>
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
                                     <button type="button" data="{ticket_time}" class=" params_content btn btn-sm btn-secondary">Ticket Time</button>                                     
                                 </div>                                
                            </div>
                        </div>
                </form>
            </div> 
        </div>
{{-- sms content template --}}
         <div class="col-xs-12 col-md-12 ">
          
        <div class="x_panel border-0">   
            <div class="x_title">
                <h5 class="border_bottom">Content Template</h5>
            </div>
            <div style="float: right;">
              <button class="btn btn-sm btn-primary" id="add_content_template">Add</button>
            </div>
            
            <table id="content_template_datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
               {{-- <th class="text-center">ID</th>    --}}
               <th class="text-center">Title</th>   
               <th>Content</th>                
               <th class="text-center">Last Update</th>
              </tr>
            </thead>
            <tbody>
              {{-- @foreach ($smsContentTemplate as $element)    
                <tr>
                    <td class="text-center">{{$element['sms_content_template_id']}}</td>                    
                    <td><a href="#" data="{{isset($element['user_nickname']) ? $element['sms_content_template_id'] : ''}}" class="view-template">{{$element['template_title']}}</a></td>
                    <td>{{$element['sms_content_template']}}</td>
                    <td class="text-center">{{$element['updated_at']}} {{isset($element['user_nickname']) ? ' by '.$element['user_nickname'] : ''}}</td> 
                </tr>
              @endforeach --}}
                   {{-- <tr>
                    <td class="text-center">2</td>
                    <td><a href="{{ route('editSmsTemplate',2)}}" class="view-template">Thanksgiving Holiday</a></td>
                    <td>Happy Birthday to {client_name}</td>
                    <td class="text-center">20/04/2019 11:20 AM by Admin</td> 
                </tr> --}}
            </tbody>    
        </table>   
        </div>
    </div>
    </div>


   
</div> 


{{--sms client group --}}
<div  class="col-xs-4 col-md-4 fixLHeight no-padding full-height bg-white"> 

    <div class="col-xs-12 col-md-12 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
        <div class="x_panel border-0">   
            <div class="x_title">
                <h5 class="border_bottom">List Client Group</h5>
            </div>
            <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
               <th class="text-center">Group Name</th>   
               <th class="text-center">Total User</th>   
               {{-- <th>Content</th>                
               <th class="text-center">Last Update</th> --}}
              </tr>
            </thead>
            <tbody>             
                @foreach ($listClientGroup as $element)
                  <tr>                                    
                    <td><a href="#" data="{{$element['list_id']}}" class="view_client_group">{{$element['group_name']}}</a></td>                    
                    <td class="text-center">{{$element['total_user']}}</td> 
                  </tr>
                @endforeach
            </tbody>    
        </table>   
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

<script>
  $(document).ready(function(){
    ajax_DataTableSmsSetting();
    $("#add_content_template").on('click',function(e){
      e.preventDefault();
      $("input[name='id']").val('');
      $("input[name='templateTitle']").val('');
      $("#textMessage").val('');
      $(".add_or_edit").show(250);
    });

    $("#hide_add_or_edit").on('click',function(e){
      e.preventDefault();
      $(".add_or_edit").hide(250);
    });

    //submit form
    $("#smsTemplateForm").on('submit',function(e){
      e.preventDefault();
      var form = $(this).serialize();
      $.ajax({
        url:"{{ route('post_addOrEditContentTemplate') }}",
        method:"post",
        data:form,
        dataType:"json",
        success:function(data){
          if(data.error.length > 0){              
              for(var count = 0; count < data.error.length;count++){
                toastr.error(data.error[count]);
              }              
            }else{
              toastr.success(data.success);
              $(".add_or_edit").hide(250);
              ajax_DataTableSmsSetting();
            }
        },
        error:function(){
          toastr.error('Submit Form Error!');
        }
      });
    });

    $(document).on('click',".view-template",function(e){
      e.preventDefault();
      var id = $(this).attr('data');
      if(id){
      $.ajax({
        url:"{{ route('get_SmsContentTemplate') }}",
        data:{id:id},
        method:"get",
        dataType:"json",
        success:function(data){
          console.log(data);
          $("input[name='id']").val(id);
          $("input[name='templateTitle']").val(data.template_title);
          $("#textMessage").val(data.sms_content_template);
          $(".add_or_edit").show(250);
          $(".add-edit").html("Edit Content Template");
        },
        error:function(){
          toastr.error("Error Get Content Template!");
        }
      });
      } else {
        $(".add_or_edit").hide(250);
        toastr.warning("Error edit!");
      }
    });

    $(document).on('click','.view_client_group',function(e){
      e.preventDefault();
      var string_id = $(this).attr('data');
      $.ajax({
        url:'{{ route('ajax_getCustomerByStringCustomerId') }}',
        data:{string_id:string_id},
        method:"get",
        dataType:'json',
        success:function(data){
          if(data.length > 0){
            //--
            var html  = '';
            for(var i = 0; i < data.length; i++){
                  html += '<tr>'
                  html += '<td class="text-center">'+data[i].customer_fullname+'</td>'                  
                  html += '<td class="text-center">'+data[i].customer_phone +'</td>'
                  html += '</tr>'
            }
            $("#list_client_datatable tbody").html(html);
            //
             $("#list_client_model").modal("show");
          }else{
            toastr.warning('Empty Client');
          }     
        },
        error:function(){
          toastr.warning('Empty Client');
        }
      });      
    });
  
  });

  function ajax_DataTableSmsSetting(){
    $.ajax({
      url:"{{ route('ajax_DataTableSmsSetting') }}",
      method:"get",
      dataType:"json",
      success:function(data){
        if(data){
          var html  = '';
          for(var i = 0; i < data.length; i++){
                html += '<tr>'
                // html += '<td class="text-center">'+data[i].sms_content_template_id+'</td>'
                if(data[i].user_nickname)
                html += '<td><a href="#" data="'+data[i].sms_content_template_id +'" class="view-template">'+data[i].template_title+'</a></td>'
                else
                html += '<td><a href="#" data="" class="view-template">'+data[i].template_title+'</a></td>' 
                html += '<td>'+data[i].sms_content_template+'</td>'
                if(data[i].user_nickname)
                html += '<td class="text-center">'+data[i].updated_at +" by "+ data[i].user_nickname +'</td>'
                else
                html += '<td class="text-center">'+data[i].updated_at +'</td>'
                html += '</tr>'
          }
          $("#content_template_datatable tbody").html(html);
          // console.log(html);
        }
      }
    });
  }
</script>
@stop

