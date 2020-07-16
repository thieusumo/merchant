@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing |  Gift cards')
@section('styles') 
@stop
@section('content')
<div class="x_panel">
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">Code</th>         
        <th class="text-right">Price($)</th>                    
        <th class="text-right">Balance($)</th>
        <th class="text-center">Loyalty Referral</th>
        <th class="text-center">Type</th>  
        <th>Customer Name </th>        
        <th class="text-center">Customer Phone</th>
        <th class="text-center">Expired Date</th> 
        <th class="text-center">Created Date</th> 
        <th width="80" class="text-center">Action</th>                 
      </tr>
    </thead>
    <tbody><tr>
            <td colspan="9">Not found data</td>
        </tr></tbody>
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
  
   if ($('#datatable').length ){       
        gTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "giftcard/add";
                    }
                }
             ],
            processing: true,
            serverSide: true,
            ajax: {url:"{{ route('loadDatatables.giftcard') }}",
              data: function(data){

              }
            },  
            columns:[
              {data:'giftcode_code',name:'giftcode_code', sClass: "text-center"},
              {data:'giftcode_price',name:'giftcode_price', sClass: "text-right"},
              {data:'giftcode_surplus',name:'giftcode_surplus', sClass: "text-right"},
              {data:'giftcode_loyalty_referral',name:'giftcode_loyalty_referral', sClass: "text-right"},
              {data:'giftcode_type',name:'giftcode_type', sClass: "text-center"},
              {data:'customer_fullname',name:'customer_fullname'},
              {data:'customer_phone',name:'customer_phone', sClass: "text-center"},
              {data:'giftcode_date_expire',name:'giftcode_date_expire', sClass: "text-center"},
              {data:'created_at',name:'created_at', sClass: "text-center"},
              {data:'action',name:'action', orderable:false, searcheble:false, sClass: "text-center"},
            ],             
       }); 
    } 

    $(document).on('click','.deleteColumn_giftcart',function(e){
      e.preventDefault();
      var code = $(this).attr('data');
      if(confirm("Are you sure want to Delete this data?")){
        $.ajax({
          url:"{{ route('deleteColumn.giftcard') }}",
          method:"get",
          data:{code:code},
          success:function(data){
            if(data==1)
              toastr.success('Deleted!');
            else 
              toastr.success('Error!');
            gTable.draw(); 
          }
        })
      }else return false;
    });   
   
}); 
</script>            
@stop

