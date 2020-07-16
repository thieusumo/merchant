@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Website Builder | Services')
@section('styles')
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">    
@stop
@section('content')
<div class="x_panel">
<form action="" method="post" id="service_form" name="service_form" class="form-inline">
    <div class="form-group" style="margin-right:10px;">                                     
        <select id="search_service_cate" name="search_service_cate" class="form-control form-control-sm">
           <option value="">-- Category Service-- </option>
           @foreach($list_service_cates as $list_service_cate)
           <option value="{{$list_service_cate->cateservice_id}}">{{$list_service_cate->cateservice_name}}</option>
           @endforeach
         </select>
    </div>       
    <div class="form-group" style="margin-right:10px;">                                    
        <select class="form-control form-control-sm" id="search_service_status" name="search_service_status">
           <option value="">-- Service Status-- </option>
           <option value="1">Enabled</option>
           <option value="0">Disabled</option>
         </select>
    </div>      
    <div class="form-group" style="margin-right:10px;">                                       
        <select class="form-control form-control-sm" id="search_service_booking" name="search_service_booking">
           <option value="">-- Online Booking-- </option>
           <option value="1">Enabled</option>
           <option value="0">Disabled</option>
         </select>
    </div>  
    <div class="form-group">  
        <button type="submit" class="btn btn-sm btn-primary" style="margin-bottom:0px;">Search</button>
          <button class="btn btn-sm btn-default reset"  type="reset" style="margin-bottom:0px;">Clear</button>
    </div>
</form>
</div>    
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <!-- <th class="text-center" width="10"><input type="checkbox" name="check_all" class="check_all"></th>   -->
        <th class="text-center" width="10">
          <div class="custom-control custom-checkbox mb-3">
            <input type="checkbox" class="custom-control-input check_all" id="customCheck" name="check_all">
            <label class="custom-control-label" for="customCheck">ID</label>
          </div>
        </th>  
        <th width="100" >Category </th>
        <th width="100">Service Name </th>
        <th class="text-center" width="30" >Price($)</th>                
        <th class="text-center" width="30">Duration(mins)</th>
        <th class="text-center" width="30">Hold</th>        
        <th class="text-center" width="30">Enable</th>     
        <th class="text-center" width="30" nowrap="nowrap">Online Booking</th>
        <th width="80" >Last Update</th>
        <th class="text-center" width="80">Action</th>        
      </tr>
    </thead>
</table>

</div>
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<!-- excelHTML5 tri-->
<script type="text/javascript" src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<!-- end excel tri-->
<script type="text/javascript">
$(document).ready(function() {

        sTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,

             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-trash "></i> Delete More',                    
                    className: "btn-sm delete_button",
                    
                },
                 {
                    text: '<i class="glyphicon glyphicon-plus "></i> Add New',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('edit-service')}}";
                    }
                },{   
                     extend: 'excel', 
                     text: '<i class="glyphicon glyphicon-export"></i> Export',
                     className: "btn-sm",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('export-service')}}";
                    }
                 }, {
                    text: '<i class="glyphicon glyphicon-import"></i> Import',
                    className: 'btn-sm',
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('import-service')}}";
                    }
                }
             ],
             columnDefs: [
             {
                  "targets": 0,
                  "className": "text-center no-sort",
                  "bSortable": false,
             },
             {
                  "targets": 1, 
                  "className": "text-center"
             },
             {
                  "targets": 2,
                  "className": "text-left",
             },
             {
                  "targets": 3,
                  "className": "text-left",
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
             },
             {
                  "targets": 8,
                  "className": "text-center",
             },
             {
                  "targets": 9,
                  "className": "text-center",
             },
             
             ],
             ajax:{ url:"{{ route('get-services') }}",
                 data: function (d) {
                        d.search_service_cate = $('#search_service_cate :selected').val();
                        d.search_service_status = $('#search_service_status :selected').val();
                        d.search_service_booking = $('#search_service_booking :selected').val();
                    }
                  },
                 columns: [

                          // { data: 'delete', name: 'delete' },
                          { data: 'service_id', name: 'service_id' },
                          { data: 'cateservice_name', name: 'cateservice_name' , searchable: false },
                          { data: 'service_name', name: 'service_name' },
                          { data: 'service_price', name: 'service_price' },
                          { data: 'service_duration', name: 'service_duration' },
                          { data: 'service_price_hold', name: 'service_price_hold'},
                          { data: 'action1' , name: 'action1',  orderable: false, searchable: false },
                          { data: 'action2' , name: 'action2',  orderable: false, searchable: false },
                          { data: 'updated_at', name: 'updated_at'},
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                ],
                fnDrawCallback:function (oSettings) {                   
                  var elemsStatus = Array.prototype.slice.call(document.querySelectorAll('.status'));
                  elemsStatus.forEach(function (html) {
                      var switcheryStatus = new Switchery(html, {
                          color: '#0874e8',
                          className : 'switchery switchery-small',                        
                      });
                      switcheryStatus.bindClick = changeStatus;
                  });

                var elemsOnlineBooking = Array.prototype.slice.call(document.querySelectorAll('.online_booking'));
                elemsOnlineBooking.forEach(function (html) {
                    var switcheryOnlineBooking= new Switchery(html, {
                        color: '#0874e8',
                        className : 'switchery switchery-small',                        
                    });                   
                    switcheryOnlineBooking.bindClick = change_online_booking;
                });

                }
                                       
       }); 
        
        if ($(".js-switch")[0]) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html, {
                    color: '#26B99A',
                    className : 'switchery switchery-small'                
                });
            });
       }
   
      $(document).on('click', '.check_all', function() {

             var checkBoxes = $('.delete');
              checkBoxes.prop("checked", $(this).prop("checked"));
              
      });

$(document).on('click', '.delete_button', function(e) {
  var val = [];
      $('.delete:checkbox:checked').each(function(i){
      val[i] = $(this).val();
      });
      if(val == "")
      {
        toastr.error('Please select service you want to delete');
      }
      else {
        if(window.confirm("Are you sure you want to delete this service ?")){
      
          $.ajax({
              url:"{{route('delete-service')}}",
              method:"get", 
              data:{id:val},
            })
         .done(function() {
          toastr.success("Change Service Succsess!","SUCCESS!!!");
            sTable.draw();
            // e.preventDefault();
            // alert('Change Service Succsess!');
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error("Change Service Error!","ERROR!!!");
            // alert('Change Service  !');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
        }else{
            return false;
        }
      }
   
     
   });

function changeStatus() {
    var parent = this.element.parentNode.tagName.toLowerCase()
       ,$jswitch = this 
       ,$isCheck = this.element.checked
       ,$newStatus =  $isCheck?'disabled popup':"enabled popup"       
       ,labelParent = (parent === 'label') ? false : true;
    
    if(confirm("Are you sure to want to change this services?")){        

      var id = $(this.element).val();  
      var status = '';
      if($isCheck == true){
        status = 0;
      }else{
        status = 1;
      }
         
        $.ajax({
          url:"{{ route('change-service-status') }}",
          method:"post",
          data:{id:id,status:status},
          success:function(data){
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

  function change_online_booking(){
    var parent = this.element.parentNode.tagName.toLowerCase()
       ,$jswitch = this 
       ,$isCheck = this.element.checked
       ,$newStatus =  $isCheck?'disabled popup':"enabled popup"       
       ,labelParent = (parent === 'label') ? false : true;
    
    if(confirm("Are you sure to want to change this services?")){        

      var id = $(this.element).val();  
      var status = '';
      if($isCheck == true){
        status = 0;
      }else{
        status = 1;
      }
         
        $.ajax({
          url:"{{ route('change-online-booking') }}",
          method:"post",
          data:{id:id,status:status},
          success:function(data){
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



   // $(document).on('click', '.switchery', function(e) {
   //        sTable.draw();
   //        e.preventDefault();
   //    });
   $(document).on('click','.reset', function(e) {
          $("#service_form")[0].reset();
          sTable.draw();
          e.preventDefault();
      });

   $(document).on('click','.delete-service', function(){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this service ?"))
        {
          $.ajax({
            url:"{{route('delete-service')}}",
            method:"get",
            data:{id:id},
            })
         .done(function() {
            sTable.draw();
            // e.preventDefault();
            toastr.success('Change Service Success!',"SUCCESS!!!");
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Change Service Error!',"ERROR!!!");
            // alert('Change Service  Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }
        else{
          return false;
        }
    });


   $('#service_form').on('submit', function(e) {
          sTable.draw();
          e.preventDefault();
      });
   $('#search_service_cate').on('change', function(e) {
          sTable.draw();
          e.preventDefault();
      });
}); 
</script>        
@stop
