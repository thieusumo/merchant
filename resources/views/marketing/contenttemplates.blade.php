@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | Image Templates')
@section('styles') 
@stop
@section('content')
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">ID</th>          
        <th width="100">Name </th>
        <th class="text-center" width="100">Image</th>
        <th width="100">Description</th>                    
        <th width="100" class="text-center">Type</th>  
        <th width="100">Last Update</th> 
        <th width="50" class="text-center">Action</th>                 
      </tr>
    </thead>
    <tbody>
      <tr>

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
@stop
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
  oTable = $('#datatable').DataTable({
     dom: "lBfrtip",
     buttons: [
         {
            text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
            className: "btn-sm btn-add",
            action: function ( e, dt, node, config ) {
                document.location.href = "contenttemplate";
            }
        }
     ],

     ajax:{ url:"{{route('getcontenttemplates')}}",
       data: function (d) {
          }
        },
       columns: [
                { data: 'sub_id', name: 'sub_id' , sClass: "text-center"},
                { data: 'sub_name', name: 'sub_name' },
                { data: 'image', name: 'image', sClass: "text-center"},
                { data: 'description', name: 'description' },
                { data: 'type', name: 'type' , sClass: "text-center"},
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action' , name: 'action',  orderable: false, searchable: false , sClass: "text-center"}
      ],
      fnDrawCallback:function (oSettings) {
          var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
          elems.forEach(function (html) {
              var switchery = new Switchery(html, {
                  color: '#26B99A',
                  className : 'switchery switchery-small'                
              });
          });
      }              
  }); 

  $(document).on('click','.delete-content', function(){
          var id = $(this).attr('id');  

          if(window.confirm("Are you sure you want to delete this?"))
          {
              $.ajax({
                url:"{{route('deletecontenttemplate')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    oTable.ajax.reload();
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
@stop

