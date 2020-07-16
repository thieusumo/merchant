@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Website Builder | Categories Services')
@section('styles')
    
@stop

@section('content')
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">No.</th>  
        <th width="160">Category Name</th>
        <th>Description</th>
        <th class="text-center" width="40" nowrap="nowrap">Index</th>
        <th class="text-center" width="120">Image</th>        
        <th width="180">Last Update</th>        
        <th class="text-center" width="80">Action</th>        
      </tr>
    </thead>
</table>   
</div>
@stop
@section('scripts')
<!-- excelHTML5 tri-->
<script type="text/javascript" src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<!-- end excel tri-->
<script type="text/javascript">
$(document).ready(function() {
    
   $('#order_date').daterangepicker({ 
       startDate: moment().subtract(1, 'month').startOf('month'), 
       endDate: moment().subtract(1, 'month').endOf('month')
   }); 
      
        oTable=$('#datatable').DataTable({
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
                        document.location.href = "{{route('cateservice')}}";
                    }
                }, {
                    text: '<i class="glyphicon glyphicon-import"></i> Import',
                    className: 'btn-sm',
                    action: function ( e, dt, node, config ) {
                        document.location.href = "service/import";
                    }
                },
                // {   
                //      extend: 'csv', 
                //      text: '<i class="glyphicon glyphicon-export"></i> Export',
                //      className: "btn-sm"
                //  }
                { 
                    extend:'excel',
                    text: '<i class="glyphicon glyphicon-export"></i> Export',
                    className: "btn-sm",
                    exportOptions: {
                      modifier: {
                        page: 'all', 
                      }
                    },
                }

             ],

             ajax:{ url:"{{ route('get-cateservice') }}"},
                 columns: [
                          { data: 'cateservice_id', name: 'cateservice_id' },
                          { data: 'cateservice_name', name: 'cateservice_name' },
                          { data: 'cateservice_description', name: 'cateservice_description' },
                          { data: 'cateservice_index', name: 'cateservice_index' },
                          { data: 'cateservice_image', name: 'cateservice_image' },
                          { data: 'updated_at', name:'updated_at'},
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                       ]    
       });             
    
    $(document).on('click','.delete-cateservice', function(){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this CateService ?"))
        {
          $.ajax({
            url:"{{route('delete-cateservice')}}",
            method:"get",
            data:{id:id},
            success:function(data)
            {
                oTable.draw();
                toastr.success(data,"SUCCESS!!!");
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

