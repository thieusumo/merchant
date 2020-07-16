@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Website Builder | Banners')
@section('styles')
 <link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
<style>
     .switchery-small{width:40px;}
     .switchery-small > small{left: 30px;}
</style>   
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
        <th width="160">Name</th>
        <th>Description</th>
        <th class="text-center" width="40" nowrap="nowrap">Index</th>
        <th class="text-center" width="120">Image</th>       
        {{-- <th class="text-center" width="80" nowrap="nowrap">Enabled</th>          --}}
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
       oTable =  $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true, //important
             serverSide: true,  //important
             responsive: true, //important
             autoWidth: true,  //important
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Add New',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "banner/0";
                    }
                }
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
             // {
             //      "targets": 7,
             //      "className": "text-center",
             // }
             ],
             ajax:{ url:"{{ route('get-banner') }}"},
                 columns: [
                          { data: 'ba_id', name: 'ba_id' },
                          { data: 'ba_name', name: 'ba_name' },
                          { data: 'ba_descript', name: 'ba_descript' },
                          { data: 'ba_index', name: 'ba_index' },
                          { data: 'ba_image', name: 'ba_image' },
                          // { data: 'enable_status', name:'enable_status' },
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
            switchery.bindClick = change_stt;
        });
    }
   //  $(document).on('click', '.switchery', function() {

   //  if(window.confirm("Do you want to change this status?")){

   //       var ba_id = $(this).siblings('input').attr('id');

   //       var checked = $(this).siblings('input').attr('checked');
   //       $.ajax({
   //           url: "",
   //           type: 'GET',
   //           dataType: 'html',
   //           data:"checked="+checked+"&ba_id="+ba_id,
   //       })
   //       .done(function() {
   //          alert('Change Banner Status Succsess!');
             
   //       })
   //       .fail(function(xhr, ajaxOptions, thrownError) {
   //          alert('Change Banner Status Error!');
   //       });
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

      var id = $(this.element).attr("id");  
      var checked = '';
      if($isCheck == true){
        checked = "uncheck";
      }else{
        checked = "checked";
      }
         
        $.ajax({
          url:"{{ route('change-banner-status') }}",
          method:"get",
          data:{id:id,checked:checked},
          success:function(data){
            oTable.draw();
            toastr.success(data,);
            $jswitch.setPosition(labelParent);
            $jswitch.handleOnchange($isCheck);
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

    $(document).on('click','.delete-banner', function(){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this Banner"))
        {
          $.ajax({
            url:"{{route('delete-banner')}}",
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

