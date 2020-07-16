@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | Promotions')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
<style>
     .switchery-small{width:40px;}
     .switchery-small > small{left: 40px;}
</style>    
@stop
@section('content')
<div class="x_panel">
<form action="" method="post" id="promotion_form" name="service_form" class="form-inline">     
    <div class="form-group" style="margin-right:10px;">                                     
        <select class="form-control form-control-sm" id="promotion_status">
           <option value="">-- Promotion Status-- </option>
           <option value="1">Enabled</option>
           <option value="0">Disabled</option>
         </select>
    </div>       
     
    <div class="form-group">  
        <button id="btnSearch" type="button" class="btn btn-sm btn-primary" style="margin-bottom:0px;">Search</button>
        <button id="btnClear" class="btn btn-sm btn-default" type="button" style="margin-bottom:0px;">Clear</button>
    </div>
</form>
</div>    
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">ID</th>  
        <th class="text-center">Image</th>
        <th>Name </th>
        <th width="160" class="text-center">Date Range</th>
        <th width="80"  class="text-center">Time Range</th>
        <th width="40" class="text-center">Discount</th>                
        <th width="40" class="text-center">Status</th>                 
        <th width="40" class="text-center">Popup Website</th>                 
        <th>Services</th>
        <th>Promotion group</th>
        <th width="70" class="text-center">Created</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
            <td colspan="10">Data not found </td>
      </tr>
    </tbody>    
</table>   
</div>
<!-- The Modal -->
<div class="modal" id="modelViewPromotion">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        Modal body..
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
{{-- model add new --}}
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
          <a class="btn btn-primary" href="{{ route('autoAddPromotion') }}">Automatic Promotion</a>
          <a class="btn btn-warning" href="{{ route('addPromotion') }}">Custom Promotion</a>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<script type="text/javascript">
function changePromotionStatus(){
   var parent = this.element.parentNode.tagName.toLowerCase()
       ,$jswitch = this 
       ,$isCheck = this.element.checked
       ,$newStatus =  $isCheck?'disabled':"enabled"       
       ,labelParent = (parent === 'label') ? false : true;
    
    if(confirm("Are you sure to want to "+$newStatus+" this promotion ?")){   
        $.post("{{ route('changePromotionStatus') }}",{id: $(this.element).val()},
            function( data ) {   
                if(data.success){
                    $jswitch.setPosition(labelParent);
                    $jswitch.handleOnchange($isCheck);
                    toastr.success("Promotion has been "+$newStatus+" successfully");                    
                }else{
                    toastr.error(data.messages)
                }      
            },'json');          
    }    
}

function changePopupWebsite(){
  var parent = this.element.parentNode.tagName.toLowerCase()
       ,$jswitch = this 
       ,$isCheck = this.element.checked
       ,$newStatus =  $isCheck?'disabled popup':"enabled popup"       
       ,labelParent = (parent === 'label') ? false : true;
    
    if(confirm("Are you sure to want to "+$newStatus+" this promotion ?")){        

      var id = $(this.element).val();  
      var popup_website = '';
      if($isCheck == true){
        popup_website = 0;
      }else{
        popup_website = 1;
      }
         
        $.ajax({
          url:"{{ route('changePopupWebsite') }}",
          method:"post",
          data:{id:id,popup_website:popup_website},
          success:function(data){
            toastr.success(data,);
            $jswitch.setPosition(labelParent);
            $jswitch.handleOnchange($isCheck);
            table.draw();
          },
          error:function(){
            toastr.error("Error Update Popup Website!");
          },
        })
    }    
}

$(document).ready(function() {   
   if ($('#datatable').length ){       
        table = $('#datatable').DataTable({
            dom: "lBfrtip",
            buttons: [
               {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
                    className: "btn-sm btn-add",
                    // action: function ( e, dt, node, config ) {
                    //     document.location.href = "{{ route('addPromotion') }}";
                    // }
                }
            ],        
            processing: true,
            serverSide: true,
            ajax:{ url:"{{ route('getPromotionDataTables') }}",
            "data": function ( d ) {
                d.status = $("#promotion_status").val();
            }},
            columns: [
                { "data": "promotion_id", "bVisible": false ,"bSearchable": false},
                { data: 'promotion_image', name: 'promotion_image', sClass: "text-center coupon-image" },
                { data: 'promotion_name', name: 'promotion_name'},
                { data: 'date_range', orderable: false, searchable: false , sClass: "text-center" },
                { data: 'time_range', orderable: false, searchable: false , sClass: "text-center" },
                { data: 'promotion_discount', name: 'promotion_discount' , sClass: "text-right" },                
                { data: 'status' , name: 'promotion_status',  orderable: false},
                { data: 'promotion_popup_website' , name: 'promotion_popup_website',  orderable: false},
                { data: 'services' , name: 'services',  orderable: false, searchable: false },
                { data: 'promotion_group' , name: 'promotion_group',  orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' , sClass: "text-center" },
                { data: 'action' , name: 'action',  orderable: false, searchable: false , sClass: "text-center" }
            ],
            fnDrawCallback:function (oSettings) {            
                var elemsStatus = Array.prototype.slice.call(document.querySelectorAll('.status'));
                elemsStatus.forEach(function (html) {
                    var switcheryStatus = new Switchery(html, {
                        color: '#0874e8',
                        className : 'switchery switchery-small',                        
                    });
                    switcheryStatus.bindClick = changePromotionStatus;
                });

                var elemsPopupWebsite = Array.prototype.slice.call(document.querySelectorAll('.popup_website'));
                elemsPopupWebsite.forEach(function (html) {
                    var switcheryPopupWebsite = new Switchery(html, {
                        color: '#0874e8',
                        className : 'switchery switchery-small',                        
                    });
                    switcheryPopupWebsite.bindClick = changePopupWebsite;
                });
            },
            "aaSorting": [
                [ 8, "desc" ]
            ],
       }); 
    }    
    $("#btnSearch").click(function(){
        $('#datatable').DataTable().ajax.reload();
    });
    $("#btnClear").click(function(){
        $(':input','#promotion_form')
         .not(':button, :submit, :reset')
         .val('')
         .removeAttr('checked')
         .removeAttr('selected');
        $('#datatable').DataTable().ajax.reload();
    });   
    
    $("#datatable").on('click', 'a.delete', function (event) {        
        if(confirm("Are you sure to want to delete this promotion ?")){            
            $.post("{{ route('deletePromotion') }}",{id: $(this).attr("data-id")},
            function( data ) {   
                if(data.success){
                    toastr.success("Promotion has been deleted successfully");
                        $('#datatable').DataTable().ajax.reload();
                }else{
                    toastr.error(data.messages)
                }      
            },'json');
        }
        event.preventDefault();
    });

    $("#datatable").on('click', 'a.view-promotion', function (event) {
        $.get($(this).attr('href'), function(result){
           $('#modelViewPromotion .modal-body').html(result);
           $('#modelViewPromotion').modal('show'); 
        });        
        event.preventDefault();
    });

    $(".btn-add").on('click',function(e){
        e.preventDefault();
        $("#optionModal").modal("show");
     });
}); 
</script>            
@stop

