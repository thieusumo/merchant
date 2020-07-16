@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Clients | Groups')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">           
@stop
@section('content')

    <div class="col-sm-7 col-md-7">
        <div class="x_panel" style="min-height:306px;">
            <table id="datatable" class="table table-striped table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th width="70">Name</th>  
                    <th width="70">Description</th> 
                    <th width="60">Rule Charged Up</th>
                    <th width="40">Rule Months</th>                   
                    <th class="text-center" width="30">Status</th>        
                    <th class="text-center" width="50">Action</th>        
                  </tr>
                </thead>
                 
            </table>  
        </div>
    </div>
    <div class="col-sm-5 col-md-5">
        <div class="x_panel x_panel_form" style="min-height:300px;">
            <div class="x_title"><h3 id="title_group" >Add Client Group</h3></div>            
            <div class="x_content">
            <form action="" method="post" id="customer-group-form" name="customer-group-form">                      
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Name</label>
                    <div class="col-sm-7 col-md-7  form-group">
                        <input type="hidden" id="customertag_id" value ="" />
                       <input id="group_name" type='text' class="form-control form-control-sm"/>
                    </div>            
                </div>    
                 <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Description</label>
                    <div class="col-sm-7 col-md-7  form-group">
                       <input type='text' id="group_description" class="form-control form-control-sm"/>
                    </div>            
                </div>
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Rule Charged Up</label>
                    <div class="col-sm-7 col-md-7 input-group-spaddon">
                      <div class="input-group input-group-sm">
                        <span class="input-group-addon">$</span>                        
                        <input type='number' value="0" data-number-stepfactor="1" id="group_rule_chargedup" class="form-control form-control-sm"/>
                      </div>
                    </div>            
                </div>
                <div class="row form-group">
                    <label class="col-sm-4 col-md-3">Rule Months</label>
                    <div class="col-sm-7 col-md-7 input-group-spaddon">
                      <div class="input-group input-group-sm ">
                        <span class="input-group-addon">month</span>
                        <input type='number' value="1" max="100" id="group_rule_months" class="form-control form-control-sm"/>
                     </div>
                    </div>            
                </div>    
                 <div class="row form-group">
                    <label class="col-sm-3 col-md-3">Status</label>
                    <div class="col-sm-7 col-md-7  form-group form-inline">
                          <div class="radio">
                            <label>
                              <input type="radio" class="flat checkStatus" value="1" id="check1" checked name="status">&nbsp;Active
                            </label>
                          </div>
                        <div class="radio" style="margin-left:10px;">
                            <label>
                              <input type="radio" class="flat checkStatus" value="0" id="check2" name="status">&nbsp;Inactive
                            </label>
                          </div>
                    </div>            
                </div>   
                 <div class="row form-group">
                     <label class="col-sm-4 col-md-3">&nbsp;</label>
                    <div class="col-sm-7 col-md-7  form-group">
                       <div id="submit_group" class="btn btn-sm btn-primary" >SUBMIT</div>
                       <div id="reset_group" class="btn btn-sm btn-default" >ADD NEW</div>
                    </div>            
                </div>   

            </form>
            </div>    
        </div>
    </div>    
   
@stop
@section('scripts')
 <!-- Checkbox --> 
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
   if ($("input.checkStatus")[0]) {
        $('input.checkStatus').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });       
    } 
    //GET DATATABLE CLIENT GROUP
    oTable = $('#datatable').DataTable({
         dom: 't',
         paging: false,
         searching: false,             
         ordering: false,
         info:     false,
         processing: true,
        serverSide: true,
         ajax:{ url:"{{ route('get-groups') }}" },
         columns: [

                  { data: 'customertag_name', name: 'customertag_name' },
                  { data: 'customertag_description', name: 'customertag_description' },
                  { data: 'customertag_rule_chargedup', name: 'customertag_rule_chargedup' },
                  { data: 'customertag_rule_months', name: 'customertag_rule_months' },
                  { data: 'customertag_status_value', name: 'customertag_status_value' },
                  { data: 'action' , name: 'action',  orderable: false, searchable: false }
          ],
         columnDefs: [
            {
                "targets": 2, 
                "className": "text-right"
           },
           {
                "targets": 3, 
                "className": "text-right"
           },
            {
                "targets": 4, 
                "className": "text-center"
           },
           {
                "targets": 5,
                "className": "text-center",
           }
           ],
    }); 
    //SELECT ROW TABLE
    $('#datatable tbody').on( 'click', 'tr', function () {
      $('#reset_group').text('ADD NEW');
      $('#title_group').text("Edit Client Group");
      $("#group_name").val(oTable.row(this).data()['customertag_name']) ;
      $('#customertag_id').val(oTable.row(this).data()['customertag_id']);
      $("#group_description").val(oTable.row(this).data()['customertag_description']);
      $("#group_rule_chargedup").val(oTable.row(this).data()['customertag_rule_chargedup']);
      $("#group_rule_months").val(oTable.row(this).data()['customertag_rule_months']);
      if(oTable.row(this).data()['customertag_status'])
      {
        $('#check1').prop('checked',true).iCheck('update');
        $('#check2').prop('checked',false).iCheck('update');
      }
      else{
        $('#check1').prop('checked',false).iCheck('update');
        $('#check2').prop('checked',true).iCheck('update');
      }
    });
    //DELETE GROUP
    $(document).on('click','.delete-group', function(){
        var id = $(this).attr('id');
        if(window.confirm("Are you sure you want to delete this group ?"))
        {
          $.ajax({
            url:"{{route('delete-group')}}",
            method:"get",
            data:{id:id},
            success:function(data)
            {
                oTable.draw();
                toastr.success(data);
            }
          })
        }
        else{
          return false;
        }
    });

    //SUBMIT BUTTON CLICK
    $( "#submit_group" ).click(function() {
      var radioActive = $("input[type='radio']:checked"). val();
      var group_name = $("#group_name").val();
      var group_description = $("#group_description").val();
      var group_rule_chargedup = $("#group_rule_chargedup").val();
      var group_rule_months = $("#group_rule_months").val();
      var group_id = $('#customertag_id').val();
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
        $.ajax({  
            url:"{{route('save-group')}}",   
            method:"POST",  
            data:{group_id:group_id , group_name : group_name , group_description: group_description , group_rule_chargedup: group_rule_chargedup, group_rule_months: group_rule_months ,  active: radioActive },    
                success: function( data ) { 
                    oTable.draw();
                    toastr.success(data);
                   
            }
       });
    });
    
    //RESET BUTTON CLICK
    $( "#reset_group" ).click(function() {
        $(this).text('RESET');
        $('#customer-group-form').trigger("reset");
        $('#title_group').text("Add Client Group");
        $('#customertag_id').val("");
        
        $('#check1').prop('checked',true).iCheck('update');
        $('#check2').prop('checked',false).iCheck('update');
    });
    
}); 
</script>     
@stop

