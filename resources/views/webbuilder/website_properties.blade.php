@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Website Builder | Website properties')
@section('styles')
    
@stop

@section('content')
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">Variable</th>          
        <th>Name</th>
        <th width="160">Value</th>
        <th class="text-center" width="80">Action</th>        
      </tr>
    </thead>
   {{--  <tbody>
      <tr>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>3</td>
      </tr>
    </tbody> --}}
</table>   
</div>

<!-- Modal add -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog"  aria-hidden="true">
   <form method="post" class="" enctype="multipart/form-data">  
        @csrf 
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Add Website Property</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                     
            <div class="row form-group">
               <label class="control-label col-md-2 col-sm-2 col-xs-12">Variable</label>
               <div class="col-md-9 col-sm-9 col-xs-12">
                 <input type="text" required="" class="form-control form-control-sm " name="variable" value="">
               </div>
             </div>

            <div class="row form-group">
               <label class="control-label col-md-2 col-sm-2 col-xs-12">Name</label>
               <div class="col-md-9 col-sm-9 col-xs-12">
                   <input type="text" required="" class="form-control form-control-sm" name="name" value="">
               </div>
             </div>    
             
             <div class="row form-group" id="addText">
               <label class="control-label col-md-2 col-sm-2 col-xs-12">Value</label>
               <div class="col-md-9 col-sm-9 col-xs-12">
                   <input type="text" class="form-control form-control-sm" name="value" value="">
               </div>
             </div> 

             <div class="row form-group" id="addImage" style="display: none">
               <label class="control-label col-md-2 col-sm-2 col-xs-12">Image</label>
                <div class="col-md-9 col-sm-9 col-xs-12" style="overflow: hidden;">
                    <div class="catalog-image-upload">
                           <div class="catalog-image-edit">
                               <input type="file" id="imageUpload1" name="image" value="" data-target="#catalogImagePreview1" accept=".png, .jpg, .jpeg">
                               <label for="imageUpload1"></label>
                           </div>
                           <div class="catalog-image-preview">
                               <img id="catalogImagePreview1" style="display:none" src="" height="100px">
                           </div>
                       </div>
                 </div>
             </div>

        
      </div>
      <div class="modal-footer" style="display: block;">
        <button type="button" class="btn btn-sm btn-success float-left addText">Add Text</button>
        <button type="button" class="btn btn-sm btn-warning float-left addImage">Add Image</button>

        <button type="button" class="btn btn-secondary btn-sm float-right" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm float-right">Save changes</button>
        <input type="hidden" name="action" value="">
      </div>
    </div>
  </div>
  </form>
</div>

@stop
@section('scripts')

<script type="text/javascript" src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>

<script type="text/javascript">
function readURL(input) {
  if (input.files && input.files[0]) {
    $('img').show();
      var reader = new FileReader();
      reader.onload = function(e) {
          $($(input).attr("data-target")).attr('src', e.target.result);
          $($(input).attr("data-target")).hide();
          $($(input).attr("data-target")).fadeIn(650);
      }
      reader.readAsDataURL(input.files[0]);
  }    
} 

$(document).ready(function() {
  
    table = $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true, 
             responsive: true,
             autoWidth: true,
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                       
                    }
                }, {
                    text: '<i class="glyphicon glyphicon-import"></i> Import',
                    className: 'btn-sm import',
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('importWebsiteProperties')}}";
                    }
                },
                { 
                    // extend:'',
                    text: '<i class="glyphicon glyphicon-export "></i> Export',
                    className: "btn-sm export",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('exportWebsiteProperties')}}";
                    }
                }

             ],

             ajax:{ url:"{{ route('wpDatatable') }}"},
                 columns: [
                          { data: 'wp_variable', name: 'wp_variable' },
                          { data: 'wp_name', name: 'wp_name' },
                          { data: 'wp_value', name: 'wp_value' },
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                       ]    
       });             
    
    $(document).on('click','.delete', function(e){
        e.preventDefault();
        var data = $(this).attr('data');  
        if(window.confirm("Are you sure you want to delete this data?"))
        {
          $.ajax({
            url:"{{route('deleteWebsiteProperty')}}",
            method:"get",
            data:{data:data},
            success:function(data)
            {
                table.ajax.reload(null, false);
                toastr.success(data);
            }
          })
        }
        else{
          return false;
        }
    });

}); 
</script>   
<script>
function addToggle(thisTag){
  var tag = $(thisTag).css('display');

  if(tag != 'none'){
    return false;
  }

  $("#addImage").find("input").attr('disabled',true);
  $("#addText").find("input").attr('disabled',true);

  $(thisTag).find("input").removeAttr('disabled');

  $("#addImage").toggle(200);
  $("#addText").toggle(200);
}
function submitForm(){
    var form = $("form")[0];
    var form_data = new FormData(form);
  
    $.ajax({
      url:"{{ route('saveWebsiteProperty') }}",
      method:"post",
      data:form_data,
      dataType:"json",
      cache:false,
      contentType: false,
      processData: false,
      success:function(data){
        if(data.status == 1){
          toastr.success(data.msg);
          table.ajax.reload(null, false);
          $("#addModal").modal("hide");
        } else {
          toastr.error(data.msg);
        }
      }, error:function(){
          toastr.error("Error!");
      }
    });
}

function clear(){
    $("input[name='variable']").val('');
    $("input[name='name']").val('');
    $("input[name='value']").val('');
    $("input[name='image']").val('');
    $(".catalog-image-preview img").hide();
}

$(document).ready(function(){

  $(document).on('click','.btn-add',function(e){
    e.preventDefault();
    $("#modalLabel").text("Add Website Property");
    $("input[name='variable']").removeAttr("readonly");
    $("input[name='action']").val('Create');
    clear();
    $("#addModal").modal("show");
  });

  $(document).on('click','.edit',function(e){
    e.preventDefault();
    clear();
    var data = $(this).attr("data");
    $.ajax({
      url:"{{ route('getWebsitePropertyByVariable') }}",
      method:"get",
      data: {data},
      dataType:"json",
      success:function(data){
        if(data.status){
          $("#modalLabel").text("Edit Website Property");
          $("input[name='variable']").attr("readonly",true);
          $("input[name='action']").val('Update');
          $("input[name='variable']").val(data.data.wp_variable);
          $("input[name='name']").val(data.data.wp_name);
          if(data.data.wp_type == 1){
            $("input[name='value']").val(data.data.wp_value);
            $(".addText").trigger('click');
          } else if(data.data.wp_type == 2){
            $(".catalog-image-preview img").attr('src','{{config('app.url_file_view')}}'+data.data.wp_value);
            $(".catalog-image-preview img").show();
            $(".addImage").trigger('click');
          }
          
          // $("input[name='image']").val('');
          // $(".catalog-image-preview img").hide();
          $("#addModal").modal("show");
        }
      }
    });
  });

  $("input[type=file]").change(function() {
    readURL(this);
  });

  $(".addText").on('click',function(){
    addToggle('#addText');
  });
  $(".addImage").on('click',function(){
    addToggle('#addImage');
  });

  $("form").on("submit",function(e){
    e.preventDefault();
    submitForm();
  });



});  
</script>     
@stop


