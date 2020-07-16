@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | SMS | Add Group Receivers')
@section('styles')
<link type="text/css" href="{{ asset('plugins/datatables.net/dataTables.checkboxes.css') }}" rel="stylesheet" />
<style type="text/css">
    .top_nav{height: 84px;}
    /*table.dataTable tbody tr:hover {
      background-color: #9dbfa6;
      color: #fff;
      cursor: -webkit-grab; 
      cursor: grab;
    }*/      
</style>
@stop
@section('content')
<div id="sms" class="col-xs-12 col-md-12 fixLHeight no-padding full-height bg-white">
     <div class="col-xs-2 col-md-2 no-padding full-height scroll-view scroll-style-1">
        @include('marketing.sms.partials.menu') 
    </div>   
    <div class="col-xs-10 col-md-10 no-padding full-height scroll-view scroll-style-1 padding-top-10 padding-right-5">
        <div class="x_panel border-0">               
            <div class="x_title">
                <h5 class="border_bottom">Add Group Receivers</h5>
            </div>
            <div class="x_content">
                  <form  id="smsTemplateForm" method="post" name="usersmsTemplateForm" enctype="multipart/form-data">
                      {{csrf_field()}}
                    <div class="col-md-5">
                        <div class="form-group">
                          <label for="groupName">Group Name</label>
                          <input required="" name="groupName" type="text" class="form-control form-control-sm {{ $errors->has('groupName') ? ' is-invalid' : '' }}" value="{{old('groupName')}}">
                          </div>
                          <div class="radio">
                            <label>
                              <input type="checkbox" id="checkbox" name="checkbox" {{ (! empty(old('checkbox')) ? 'checked' : '') }} >&nbsp;Import File

                            </label>
                          </div>
                          {{-- import file --}}
                         <div id="import_file" class="form-group" style="display: none">
                            <label for="fileImport">List Contact Phones </label>
                              <div class="custom-file">
                                <input accept=".xls,.xlsx" required="" name="import_ListConteactPhones" type="file" class="custom-file-input" id="validatedCustomFile" style="width:300px;">
                                <label id="name_file" class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                <div class="note"><a href="{{ route('download_templatefile') }}">Download template file</a></div>
                              </div>
                          </div>
                          {{-- search clients --}}
                          <div class="row">
                            <form action="" id="group_receive_search" name="group_receive_search">
                              <input type="text" name="search_customer_date" id="search_customer_date" class="col-md-4 form-control form-control-sm" >
                              <select name="client_group" id="client_group" class="col-md-4 form-control form-control-sm text-center">
                                <option value="" disabled >--Client Group--</option>
                                <option value="" selected>All</option>
                                @foreach($customertag_list as $customertag)
                                <option value="{{$customertag->customertag_id}}">{{$customertag->customertag_name}}</option>
                                @endforeach
                              </select>
                              <input type="button" name="" class="btn btn-primary btn-sm submit" value="Search">
                              <input type="reset" name="" class="btn btn-danger btn-sm reset" value="Reset">
                            </form>
                          </div>
                          <div id="list_contact_phone" class="form-group" style="display: none">
                            <label for="fileImport">List Contact Phones</label>
                               <div class="row table-responsive">
                                 <div class="col-sm-12">
                                      <table id="contact_datatable" class="table table-striped table-bordered" width="100%">
                                        <thead>
                                        <tr>        
                                            <th class="text-center"><input type="checkbox" name="select_all" value="1"></th>
                                            <th class="text-center">Phone</th>
                                            <th class="text-center">Name</th>   
                                            <th class="text-center">Birthday</th>        
                                        </tr>
                                        </thead>
                                    </table>
                                  </div>
                              </div>
                          </div>  
                          <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">SUBMIT</button> 
                          </div>
                    </div>                            
                        
                </form>
            </div> 
        </div>
    </div>
</div> 

@stop
@section('scripts') 
<script type="text/javascript" src="{{ asset('plugins/datatables.net/dataTables.checkboxes.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('input[name=search_customer_date]').daterangepicker({ 
       autoUpdateInput: false,

      locale: {
        cancelLabel: 'Clear'
      }
   }); 

  $('input[name=search_customer_date]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('input[name=search_customer_date]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });
  //// Array holding selected row IDs
  var rows_selected = [];
  sTable = $('#contact_datatable').DataTable({
             dom: "lBfrtip",
             processing: true,
             serverSide: true,
             autoWidth: true,
             buttons: [
             ],
        
         ajax:{ url:"{{ route('group_receivers_add')}}",
              data:function(d){
                  d.search_customer_date = $('#search_customer_date').val();
                  d.client_group = $("#client_group option:selected").val();
                  }
                },
               'columnDefs': [
               {
                  'targets': 0,
                  'checkboxes': {
                     'selectRow': true
                  }
               }
            ],
            'select': {
               'style': 'multi'
            },
            'order': [[1, 'asc']],
            
             columns: [
                      { data: 'checkbox', name: 'checkbox' ,orderable: false, searcheble: false},
                      { data: 'customer_phone', name: 'customer_phone' },
                      { data: 'customer_fullname', name: 'customer_fullname' },
                      { data: 'customer_birthdate', name: 'customer_birthdate' }
                   ] ,

    }); 
  //---- submit form
     $('#smsTemplateForm').on('submit', function(e){
      var form = this;

      var rows_selected = sTable.column(0).checkboxes.selected();

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'checkbox_client[]')
                .val(rowId)
         );
      });
   });
     //----
  
  
  if($("#checkbox").attr('checked')){
    checkbox_checked_true();
  }
  else{
    checkbox_checked_false();
  }
  

   $('#checkbox').on('click',function(){ 
    if(this.checked){ 
      checkbox_checked_true();      
    }else{    
      checkbox_checked_false();      
    }
   });
   
   function checkbox_checked_true(){
        $('#list_contact_phone').css('display','none');
        $('#import_file').css('display','');
        $('input[name="data_ListConteactPhones"]').prop('disabled', true);
        $('input[name="import_ListConteactPhones"]').prop('disabled', false);
      }

  function checkbox_checked_false(){ 
        $('#import_file').css('display','none');
        $('#list_contact_phone').css('display','');
        $('input[name="import_ListConteactPhones"]').prop('disabled', true);
        $('input[name="data_ListConteactPhones"]').prop('disabled', false);
      }
  //script  upload file
  $('input[type="file"]').on('change',function(e){
    try {
    var file = $(this)[0].files[0].name;
    $('#name_file').text(file);
    }
    catch(err){
      $('#name_file').text('Choose file...');
    }
  });

  $(".submit").on('click', function(e) {
      sTable.draw();
      e.preventDefault();
  });

});

// $(document).on('click','.reset', function(e) {
//   $("#group_receive_search")[0].reset();
//     sTable.draw();
//     e.preventDefault();
// });
</script>      
@stop

