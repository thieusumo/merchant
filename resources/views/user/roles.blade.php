@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Users | Roles')
@section('styles')    
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">
<style>
  .btn{
    cursor: pointer;
  }
</style>
@stop
@section('content')

    <div class="col-sm-6 col-md-6">
        <div class="x_panel" style="min-height:296px;">
            <table id="datatable" class="table table-striped table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th>Name</th>  
                    <th>Description</th>                    
                    <th class="text-center" width="60">Status</th>        
                    <th class="text-center" width="60">Action</th>        
                  </tr>
                </thead>
                  
            </table>  
        </div>
    </div>
    <div class="col-sm-6 col-md-6">
        <div class="x_panel" style="min-height:296px;">
            <h3 id="title_role">Add User Role</h3> <br/>    
        <form  id="user_role_form" name="user_role_form">                      
            <div class="row form-group">
                <label class="col-sm-3 col-md-2">Name</label>
                <div class="col-sm-6 col-md-6  form-group">
                  <input type="hidden" id="ug_id" name="">
                   <input required="" type='text' id="ug_name" name="ug_name" class="form-control form-control-sm"/>
                </div>            
            </div>    
             <div class="row form-group">
                <label class="col-sm-3 col-md-2">Description</label>
                <div class="col-sm-6 col-md-6  form-group">
                   <input type='text' id="ug_description" class="form-control form-control-sm"/>
                </div>            
            </div>  
            <div class="row form-group">
                <label class="col-sm-3 col-md-2">Status</label>
                <div class="col-sm-6 col-md-6  form-group form-inline">
                      <div class="radio">
                        <label>
                          <input type="radio" class="flat checkStatus" id="check1" value="1" checked name="ug_status">&nbsp;Active
                        </label>
                      </div>
                    <div class="radio" style="margin-left:10px;">
                        <label>
                          <input type="radio" class="flat checkStatus" id="check2" value="0"  name="ug_status">&nbsp;Inactive
                        </label>
                      </div>
                </div>            
            </div>   
             <div class="row form-group">
                 <label class="col-sm-3 col-md-2">&nbsp;</label>
                <div class="col-sm-6 col-md-6  form-group">
                       <div id="submit_role" class="btn btn-sm btn-primary" >SUBMIT</div>
                       <div id="reset_role" class="btn btn-sm btn-default" >CLEAR</div>
                </div>            
            </div>   
             
        </form>
        </div>
    </div>        
@stop
@section('scripts')
 <script type="text/javascript" src="{{ asset('plugins/datatables.net/dataTables.bootstrap.min.js') }}"></script>
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
   if ($('#datatable').length ){       
        oTable = $('#datatable').DataTable({
             dom: 't',
             paging: false,
             searching: false,             
             ordering: false,
             info:     false,
             processing: true,
             serverSide: true,
             ajax:{ url:"{{ route('get-roles') }}" },
             columns: [

                  { data: 'ug_name', name: 'ug_name' },
                  { data: 'ug_description', name: 'ug_description' },
                  { data: 'ug_status_name', name: 'ug_status_name' },
                  { data: 'action' , name: 'action',  orderable: false, searchable: false }
          ],
          columnDefs: [
            {
                "targets": 2, 
                "className": "text-center"
           },
           {
                "targets": 3, 
                "className": "text-center"
           },
           ]
       }); 
    }    
    //SELECT ROW TABLE TO EDIT
    $('#datatable tbody').on( 'click', 'tr', function () {
      $('#title_role').text("Edit User Role");
      $("#ug_name").val(oTable.row(this).data()['ug_name']) ;
      $('#ug_id').val(oTable.row(this).data()['ug_id']);
      $("#ug_description").val(oTable.row(this).data()['ug_description']);
      if(oTable.row(this).data()['ug_status'])
      {
        $('#check1').prop('checked',true).iCheck('update');
        $('#check2').prop('checked',false).iCheck('update');
      }
      else{
        $('#check1').prop('checked',false).iCheck('update');
        $('#check2').prop('checked',true).iCheck('update');
      }
    });
    //SUBMIT BUTTON CLICK
    $( "#submit_role" ).on('click',function() {
      var radioActive = $("input[type='radio']:checked"). val();
      var ug_name = $("#ug_name").val();
      var ug_description = $("#ug_description").val();
      var ug_id = $('#ug_id').val();
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
        $.ajax({  
            url:"{{route('save-roles')}}",   
            method:"POST",  
            data:{ug_id:ug_id , ug_name : ug_name , ug_description: ug_description ,  active: radioActive },    
                success: function( data ) { 
                  if(data==0)
                  {
                    toastr.error('Please enter Name!');
                  }
                  else
                  {
                    oTable.draw();
                    toastr.warning(data);
                  }
                   
            }
       });
    });
 //RESET BUTTON
    $( "#reset_role" ).click(function() {
        $('#user_role_form').trigger("reset");
        $('#title_role').text("Add User Role");
        $('#ug_id').val("");
        
        $('#check1').prop('checked',true).iCheck('update');
        $('#check2').prop('checked',false).iCheck('update');
    });

    $(document).on('click','.delete-role', function(){
        var id = $(this).attr('id');
        if(window.confirm("Are you sure you want to delete this user role ?"))
        {
          $.ajax({
            url:"{{route('delete-role')}}",
            method:"get",
            data:{id:id},
            success:function(data)
            {
                oTable.draw();
                toastr.warning(data);
            }
          })
        }
        else{
          return false;
        }
    });
}); 
</script>     

<script>
    //check validate
    $(document).ready(function(){

        var check = 0;
        $("#ug_name").on("blur",function(e){
            var str = $(this).val();
            if(str.length <=0){
                $(this).addClass('is-invalid');
                check = 1;
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });
        function checkSubmit(check){
            if(check == 1){
                $("#submit").attr('disabled',true);
            } else {
                $("#submit").attr('disabled',false);
            }
        }
    });
</script> 
@stop
