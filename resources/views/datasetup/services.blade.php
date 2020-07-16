@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Management | Services')
@section('styles')
   
@stop
@section('content')
<div class="x_panel">
<form action="" method="post" id="service_form" name="service_form"  class="form-inline">
    <div class="form-group" style="margin-right:10px;">                                     
        <select class="form-control form-control-sm parent-cate" id="search_cate_parent">           
           <option cate="service" value="{{route('get-services-management')}}">Service</option>
           <option cate="combo" value="{{route('get-combo')}}">Combo</option>
           <option cate="product" value="{{route('get-product')}}">Product</option>
           <option cate="drink" value="{{route('get-drink')}}">Drink</option>
         </select>
    </div>    
    <div class="form-group" style="margin-right:10px;">                                     
        <select class="form-control form-control-sm" id="search_cate">
           <option value="0">-- Category Service-- </option>
           @foreach($cateservice_list as $cateservice)
           <option value="{{$cateservice->cateservice_id}}">{{$cateservice->cateservice_name}}</option>
           @endforeach
         </select>
    </div>       
     
    <div class="form-group">  
        <button type="submit" class="btn btn-sm btn-primary" id="submit" style="margin-bottom:0px;">Search</button>
          <button class="btn btn-sm btn-default reset" type="reset" style="margin-bottom:0px;">Clear</button>
    </div>
</form>
</div>    
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center">ID</th>  
        <th>Category </th>
        <th>Service Name </th>
        <th class="text-center">Price($)</th>                
        <th class="text-center">Index Display</th>          
        <th>Last Update</th>
        <th class="text-center" width="60">Action</th>        
      </tr>
    </thead>   
</table>   
</div>
@stop
@section('scripts')
<script type="text/javascript">

$(document).ready(function() {
    
        sTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             retrieve: true,
             processing: true,
             serverSide: true,
             responsive: true,
             autoWidth: true,
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Set Service',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "service";
                    }
                },{
                    text: '<i class="glyphicon glyphicon-import"></i> Import',
                    className: 'btn-sm',
                    action: function ( e, dt, node, config ) {
                        document.location.href = "import";
                    }
                },
                        {   
                     extend: 'csv', 
                     text: '<i class="glyphicon glyphicon-export"></i> Export',
                     className: "btn-sm"
                 }
             ],
             columnDefs: [
             {
                  "targets": 0,
                  "className": "text-center",
                  "width":"10",
             },
             {
                  "targets": 3,
                  "className": "text-right",
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
                  "className": "text-center nowrap",
             }
             ],
             ajax:{ url:"{{ route('get-services-management') }}",
                 data: function (d) {
                        d.search_cate = $('#search_cate :selected').val();
                        d.search_cate_parent = $('#search_cate_parent :selected').val();
                    }
                  },
                 columns: [
                          { data: 'service_id', name: 'service_id' },
                          { data: 'cateservice_name', name: 'cateservice_name' , searchable: false },
                          { data: 'service_name', name: 'service_name' },
                          { data: 'service_price', name: 'service_price' },
                          { data: 'cateservice_index', name: 'cateservice_index', searchable: false },
                          { data: 'updated_at', name: 'updated_at'},
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                ],
       }); 

});

$(document).on('change','.parent-cate', function(e){
  var cate = $('#search_cate_parent option:selected').attr('cate');
  if(cate == "combo"){
    $('#search_cate,#submit,.reset').slideUp();
    column1 = "ID Combo";         data_column1 = "package_id";
    column2 = "Name Combo";       data_column2 = "package_name";
    column3 = "Price Combo";      data_column3 = "package_price";
    column4 = "Duration Combo";   data_column4 = "package_duration";
    column5 = "Description Combo";data_column5 = "package_description";
  }
  if(cate == "drink"){
    $('#search_cate,#submit,.reset').slideUp();   
    column1 = "ID Drink";         data_column1 = "beverage_id";
    column2 = "Name Drink";       data_column2 = "beverage_name";
    column3 = "Price Drink";      data_column3 = "beverage_price";
    column4 = "Duration Drink";   data_column4 = "beverage_price"; hidden = "hidden";
    column5 = "Description Drink";data_column5 = "beverage_description";
  }
   if(cate == "service"){
    $('#search_cate,#submit,.reset').slideDown();          
    column1 = "ID Service";         data_column1 = "service_id";
    column2 = "Name Service";       data_column2 = "service_name";
    column3 = "Price Service";      data_column3 = "service_price";
    column4 = "Index Service";      data_column4 = "cateservice_index";
    column5 = "Description Service";data_column5 = "service_description";
  }
  if(cate == "product"){
    $('#search_cate,#submit,.reset').slideUp();     
    column1 = "ID Product";         data_column1 = "sn_id";
    column2 = "Name Product";       data_column2 = "sn_name";
    column3 = "Price Product";      data_column3 = "sn_price";
    column4 = "Image Product";      data_column4 = "sn_image";
    column5 = "Description Product";data_column5 = "sn_description";
  }
  var url_search = $(this).val();
  sTable.destroy();
  sTable = $('#datatable').DataTable({
             dom: "lBfrtip",
             retrieve: true,
             processing: true,
             serverSide: true,
             responsive: true,
             autoWidth: true,
             buttons: [
                 {
                    text: '<i class="glyphicon glyphicon-plus fa fa-plus"></i> Set Service',                    
                    className: "btn-sm btn-add",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "service";
                    }
                },{   
                     extend: 'csv', 
                     text: '<i class="glyphicon glyphicon-export"></i> Export',
                     className: "btn-sm",
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('export-service-mana')}}";
                    }
                 }, {
                    text: '<i class="glyphicon glyphicon-import"></i> Import',
                    className: 'btn-sm',
                    action: function ( e, dt, node, config ) {
                        document.location.href = "{{route('import-service-mana')}}";
                    }
                }
             ],
             columnDefs: [
             {
                  "title": column1,
                  "targets": 0,
                  "className": "text-center",
                  "width":"10",
             },
             {
                  "title": column2,
                  "targets": 1,
             },
             {
                  "title": column3,
                  "targets": 2,
                  "className": "text-right",
             },
             {
                  "title": column4,
                  "targets": 3,
                  "className": "text-right",
             },
             {
                  "title": column5,
                  "targets": 4,
             },
             {
                  "targets": 5,
                  "className": "text-center",
             },
             {
                  "targets": 6,
                  "className": "text-center",
                  "width":"100",
             }
             ],
             ajax:{ url:url_search,
                 data: function (d) {
                        d.search_cate = $('#search_cate :selected').val();
                        d.search_cate_parent = $('#search_cate_parent :selected').val();
                    }
                  },
                  columns: [
                          { data: data_column1, name: data_column1 },
                          { data: data_column2, name: data_column2 },
                          { data: data_column3, name: data_column3 },
                          { data: data_column4, name: data_column4 },
                          { data: data_column5, name: data_column5 },
                          { data: 'updated_at', name: 'updated_at'},
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }

                    
                ],
       }); 
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
            e.preventDefault();
            toastr.success('Change Service Success!');
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Change Service  Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }
        else{
          return false;
        }
    });
$(document).on('click','.delete-combo', function(){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this combo ?"))
        {
          $.ajax({
            url:"{{route('delete-combo')}}",
            method:"get",
            data:{id:id},
            })
         .done(function() {
            sTable.draw();
            e.preventDefault();
            toastr.success('Delete Combo Success!');
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Delete Combo Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }
        else{
          return false;
        }
    });
$(document).on('click','.delete-drink', function(){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this kind of drink ?"))
        {
          $.ajax({
            url:"{{route('delete-drink')}}",
            method:"get",
            data:{id:id},
            })
         .done(function() {
            sTable.draw();
            e.preventDefault();
            toastr.success('Delete Success!');
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Delete Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }
        else{
          return false;
        }
    });
$(document).on('click','.delete-product', function(){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this kind of product ?"))
        {
          $.ajax({
            url:"{{route('delete-product')}}",
            method:"get",
            data:{id:id},
            })
         .done(function() {
            sTable.draw();
            e.preventDefault();
            toastr.success('Delete Product Success!');
             
         })
         .fail(function(xhr, ajaxOptions, thrownError) {
            toastr.error('Delete Product Error!');
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         });
     }
        else{
          return false;
        }
    });

    //SEARCH BUTTON
     $('#service_form').on('submit', function(e) {
          sTable.draw();
          e.preventDefault();
      });
        //RESET BUTTON
        $(document).on('click','.reset', function(e) {
          $("#service_form")[0].reset();
          sTable.draw();
          e.preventDefault();
      });
        

</script>            
@stop

