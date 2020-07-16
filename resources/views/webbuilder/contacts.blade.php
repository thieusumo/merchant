@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Website Builder | Contacts/Website')
@section('styles')
    
@stop
@section('content')  
<div class="x_panel">   
    <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Customer Name </th>
        <th>Email</th>
        <th class="text-center">Phone</th>             
        <th>Subject</th>                 
        <th>Content</th>
        <th width="160" class="text-center">Created</th>
        <th class="text-center">Action</th>
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
  
   if ($('#datatable').length ){       
       oTable =  $('#datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,           
             buttons: [
                 {
                    extend: 'excel', 
                    text: '<i class="glyphicon glyphicon-export"></i> Export',
                    className: "btn-sm"                    
                }
             ],

             columnDefs: [
             {
                  "targets": 2,
                  "className": "text-center",
             },
             {
                  "targets": 5,
                  "className": "text-center",
             },
             {
                  "targets": 6,
                  "className": "text-center",
             }
             ],
             ajax:{ url:"{{ route('get-contacts') }}",
                 data: function (d) {
                        d.search_cate = $('#search_cate :selected').val();
                        d.search_cate_parent = $('#search_cate_parent :selected').val();
                    }
                  },
                 columns: [
                          { data: 'cc_fullname', name: 'cc_fullname' },
                          { data: 'cc_email', name: 'cc_email' },
                          { data: 'cc_phone', name: 'cc_phone' },
                          { data: 'cc_subject', name: 'cc_subject' },
                          { data: 'cc_content', name: 'cc_content' },
                          { data: 'cc_datetime', name: 'cc_datetime' },
                          { data: 'action' , name: 'action',  orderable: false, searchable: false }
                ],
       }); 
    }    
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A',
                className : 'switchery switchery-small'                
            });
        });
    }    
   
    $(document).on('click','.delete-contact', function(e){
        var id = $(this).attr('id');  
        if(window.confirm("Are you sure you want to delete this Contact"))
        {
         $.ajax({
           url: '{{route('delete-contact')}}',
           type: 'GET',
           dataType: 'text',
           data: {id:id},
         })
         .done(function() {
            oTable.draw();
            e.preventDefault();
          toastr.success('Delete contact success!','Success!');
         })
         .fail(function() {
          toastr.success('Delete contact error!',"Error!")
           console.log("error");
         });
         
        }
        else{
          e.preventDefault();
        }
    });
   
}); 
</script>     
@stop

