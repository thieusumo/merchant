@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Website Builder | Menu')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
@stop

@section('content')
{{-- @if (session('status'))
    <div class="alert alert-info">{{session('status')}}</div>
@endif --}}
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center" width="10">No.</th>  
        <th width="160">Menu Title</th>
        <th width="160">Parent Menu</th>
        <th width="160">Menu URL</th>
        <th class="text-center" width="40" nowrap="nowrap">Index</th>
         <th class="text-center" width="80" nowrap="nowrap">Enabled</th>              
        <th width="180">Last Update</th>        
        <th class="text-center" width="80">Action</th>        
      </tr>
    </thead>
   
</table>   
</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    
   $('#order_date').daterangepicker({ 
       startDate: moment().subtract(1, 'month').startOf('month'), 
       endDate: moment().subtract(1, 'month').endOf('month')
   }); 

      if ($('#datatable').length ){
            oTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "menu/0";
                    }
                },
                {
                    text: '<i class="glyphicon glyphicon-import"></i> Import',
                    className: 'btn-sm',
                    action: function ( e, dt, node, config ) {
                        document.location.href = "menus/import";
                    }
                },
             ],
             columnDefs: [
              {
                  "targets": 0, 
                  "className": "text-center"
             },
             {
                  "targets": 1,
                  "className": "text-left",
             },
             {
                  "targets": 2,
                  "className": "text-left",
             },
             {
                  "targets": 3,
                  "className": "text-center",
             },
             {
                  "targets": 4,
                  "className": "text-center",
             },
             {
                  "targets": 5,
                  "className": "text-center",
             },
             {
                  "targets": 6,
                  "className": "text-center",
             },
             {
                  "targets": 7,
                  "className": "text-center",
             }
             ],
             ajax:{ url:"{{ route('get-menu') }}"},
                 columns: [
                          { data: 'menu_id', name: 'menu_id' },
                          { data: 'menu_name', name: 'menu_name' },
                          { data: 'parent_name', name: 'parent_name' },
                          { data: 'menu_url', name: 'menu_url' },
                          { data: 'menu_index', name: 'menu_index' },
                          { data: 'menu_type', name:'menu_type' },
                          { data: 'updated_at', name:'updated_at'},
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }

                       ],
                fnDrawCallback:function (oSettings) {
                    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                    elems.forEach(function (html) {
                        var switchery = new Switchery(html, {
                            color: '#0874e8',
                            className : 'switchery switchery-small'                
                        });
                        switchery.bindClick = change_stt;
                        // e.preventDefault();
                    });
                }
                                       
       }); 
      }
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#0874e8',
                className : 'switchery switchery-small'                
            });
        });
   }
   $(document).on('click','.delete-menu', function(){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this Menu"))
        {
          $.ajax({
            url:"{{route('delete-menu')}}",
            method:"get",
            data:{id:id},
            success:function(data)
            {
                oTable.draw();
                toastr.success(data,"SUCCESS!!!");
            },
            error:function(data)
            {
               toastr.error(data,"ERROR!!!");
            }
          })
        }
        else{
          return false;
        }
    });
   // $(document).on('click', '.switchery', function() {

   //  if(window.confirm("Do you want to change this status?")){

   //       var menu_id = $(this).siblings('input').attr('id');

   //       var checked = 'uncheck';         
   //       if($(this).siblings('input').attr('checked')){
   //        checked = $(this).siblings('input').attr('checked');
   //       }
   //       console.log(checked);

   //       $.ajax({
   //           url: "",
   //           method: 'post',
   //           data:{checked:checked,menu_id:menu_id},
   //           success:function(data){
   //            toastr.success(data);
   //            oTable.draw();
   //           },
   //           error:function(){
   //            toastr.error("Error update!");
   //           }
   //       })
         
   //   }else{
   //          return false;
   //      }
     
   // });
  function change_stt(){
    var parent = this.element.parentNode.tagName.toLowerCase()
       ,$jswitch = this 
       ,$isCheck = this.element.checked
       ,$newStatus =  $isCheck?'disabled popup':"enabled popup"       
       ,labelParent = (parent === 'label') ? false : true;
    
    if(confirm("Are you sure to want to change this services?")){        

      var id = $(this.element).val();  
      var checked = '';
      if($isCheck == true){
        checked = "uncheck";
      }else{
        checked = "checked";
      }
         
        $.ajax({
          url:"{{ route('change-menu-status') }}",
          method:"get",
          data:{id:id,checked:checked},
          success:function(data){
            toastr.success(data,);
            $jswitch.setPosition(labelParent);
            $jswitch.handleOnchange($isCheck);
            oTable.draw();
          },
          error:function(){
            toastr.error("Error Update Popup Website!");
          },
        })
    }    

  }
   $(document).on('click', '.switchery', function(e) {
          oTable.draw();
          e.preventDefault();
      });

}); 
</script>        
@stop

