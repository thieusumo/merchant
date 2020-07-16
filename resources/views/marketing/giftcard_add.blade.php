@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Marketing | Giftcards | Add Gift card')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/blue.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<style>
    a.select-price{ font-size: 14px; padding: .4em .8em;}
   #giftcard-form .x_panel{min-height: 492px;}

    .top_nav{height: 84px;}    
    table#client-datatable.dataTable tbody tr:hover {
      background-color: #9dbfa6;
      color: #fff;
      cursor: -webkit-grab; 
      cursor: grab;
    }
</style>
@stop
@section('content')
 
<form method="post" id="giftcard-form" name="giftcard-form" action="{{route('save-gift-card')}}">
    @csrf 
    <div class="col-xs-12 col-md-12 padding-10">
        <div class="x_panel col-md-4 full-height scroll-view">
            {{-- @if ($errors->any())
                    {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif --}}
            <div class="x_content"> 
                <h4>Gift Card Information</h4>
                <div class="ln_solid" style="margin: 0px 0px 15px 0px;"></div> 
                <div class="row form-group">
                    <label class="col-sm-3 col-md-3">Gift code</label>
                    <div class="col-sm-9 col-md-9">
                        <input name="gift_code" readonly type="text" class="form-control form-control-sm" value="{{$giftCardCode}}" required="required">                      
                        <div class="text-danger feedback">{{ $errors->first('gift_code.exists')}}</div> 
                    </div>   
                </div> 
                <div class="row ">
                    <label class="col-sm-3 col-md-3">Price</label>
                    <div class="col-sm-9 col-md-9 input-group-spaddon">
                         <div class="input-group">
                            <span class="input-group-addon">$</span>                        
                            <input name="price" type="number" id="price" class="form-control form-control-sm" required="required">
                        </div>
                    </div>   
                </div>
                <div class="row form-group">
                    <label class="col-sm-3 col-md-3"></label>
                    <div class="col-sm-9 col-md-9 ">
                        <a class="btn badge bg-blue select-price " rel="10">$10</a>
                        <a class="btn badge bg-blue select-price " rel="20">$20</a>
                        <a class="btn badge bg-blue select-price " rel="30">$30</a>
                        <a class="btn badge bg-blue select-price " rel="40">$40</a>
                        <a class="btn badge bg-blue select-price " rel="50">$50</a>
                        <a class="btn badge bg-blue select-price " rel="60">$60</a>
                        <a class="btn badge bg-blue select-price " rel="70">$70</a>
                        <a class="btn badge bg-blue select-price " rel="80">$80</a>
                        <a class="btn badge bg-blue select-price " rel="90">$90</a>
                        <a class="btn badge bg-blue select-price " rel="100">$100</a>
                    </div>   
                </div>
                <div class="row form-group">
                    <label class="col-sm-3 col-md-3">Loyalty Referral</label>
                    <div class="col-sm-9 col-md-9">
                        <input id="loyalty_referral" name="loyalty_referral" type="text" class="form-control form-control-sm"  required="required">
                    </div>  
                </div>
                 <div class="row form-group">
                    <label for="expire_date" class="col-sm-3 col-md-3">Expire Date </label>
                    <div class="col-sm-9 col-md-9 input-group-spaddon">                         
                        <div class='input-group date'>                    
                            <input type='hidden'  name="expire_date" value="" class="form-control form-control-sm datepicker" required="required"/>
                            <input  type='text' id="expire_date"  value="" class="form-control form-control-sm datepicker" required="required"/>
                            <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>  
                </div>
              
                <div class="row form-group">
                    <label class="col-sm-3 col-md-3">Type</label>
                    <div class="col-sm-9 col-md-9 form-inline">
                        <div class="radio">
                            <label>
                              <input type="radio" id="radio_royal" class="flat icheckstyle" name="giftCardType" value="0" checked="checked">&nbsp;Royal
                            </label>
                          </div>
                        <div class="radio" style="margin-left:10px;">
                            <label>
                              <input type="radio"  id="radio_vip" class="flat icheckstyle" name="giftCardType" value="1" >&nbsp;Vip
                            </label>
                          </div>
                    </div>                             
                </div>  
                
            </div>
        </div>    
        <div class="x_panel col-md-8 full-height scroll-view">
            <div class="x_content">             
                <h4>Billing & Payment Information</h4>
                 <div class="ln_solid" style="margin: 0px 0px 15px 0px;"></div> 
                 @include('marketing.partials.giftcard_ccinfo')
                 <div class="ln_solid" style="margin: 5px 0px 15px 0px;"></div> 
                 <div class="row">  
                        <label class="col-sm-3 col-md-2">&nbsp;</label>
                        <div class="col-sm-10 col-md-10 input-group">
                            <button id="submit" class="btn btn-sm btn-primary" >SUBMIT</button>                     
                            <a href="{{ route('giftcards.index') }}" class="btn btn-sm btn-default">CANCEL</a>
                         </div>      
                 </div> 
            </div>
        </div>
    </div>  
</form> 

<!-- The Modal -->
<div class="modal fade" id="selectClientModal">
<div class="modal-dialog modal-lm">
  <div class="modal-content">
  
    <!-- Modal Header -->
    <div class="modal-header">
      <h4 class="modal-title">Select Client</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <!-- Modal body -->
    <div class="modal-body">
      <table id="client-datatable" class="table table-striped table-bordered" style="width: 100%">
          <thead>
            <tr>  
              <th>Name</th>
              <th>Cellphone</th>
              <th>Email</th>      
            </tr>
          </thead>
      </table>   
    </div>
    <!-- Modal footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>   
@stop
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/custom/combodate.js') }}"></script>  
<script type="text/javascript">
  // format date time
  $(function() {
    $('input[id="expire_date"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      },
      singleDatePicker: true,
      // showDropdowns: true,
      // minYear: 1901,
      // maxYear: parseInt(moment().format('YYYY'),10)

    });
    
    $('input[id="expire_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $('input[id="expire_date"]').on('apply.daterangepicker', function(ev, picker) {
    // value hidden expire_date 
    $('input[name="expire_date"]').val(picker.startDate.format('YYYY-MM-DD'));
  });

  });
  

  
  // --
$(document).ready(function() {
   if ($("input.icheckstyle")[0]) {
        $('input.icheckstyle').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });       
    }   
    $("a.select-price").on("click", function() {
        // var val = $("input#price").val($(this).attr("rel"));
        // $("input#price").trigger("change");
        var val = $(this).attr("rel");
        $("input#price").val(val);
        $("input#loyalty_referral").val(val);
    });
    $("input#price").on("change paste keyup", function() {
         //console.log($(this).val());

        $("span#payment-amount").text($(this).val());
    }); 

    $('.pay_type').on('ifChanged ', function(event) {
      if(this.value==2)
      {
        $('.show_authorize').show();
      }
      else{
        $('.show_authorize').hide();
      }
      
    
    });


    cTable = $('#client-datatable').DataTable({
         dom: "ftip",
         processing: true,
         serverSide: true,
         columnDefs: [
          {
              "targets": 0, 
              "className": "text-left"
         },
         {
              "targets": 2,
              "className": "text-left",
         }
         ],
         ajax:{ url:"{{ route('get-customers-payment')}}"},
             columns: [
                      { data: 'customer_fullname', name: 'customer_fullname' },
                      { data: 'customer_phone', name: 'customer_phone' },
                      { data: 'customer_email', name: 'customer_email' }
                   ]    
    });

    $('#client-datatable tbody').on('click', 'tr', function () {
        var data = cTable.row( this ).data();

        $('#customer_fullname').val(data['customer_fullname']) ;
        $('#customer_phone').val(data['customer_phone']) ;
        $('#customer_email').val(data['customer_email']) ;
        $('#customer_address').val(data['customer_address']) ;
        $('#customer_state').val(data['customer_state']) ;
        $('#customer_country').val(data['customer_country']);
        $('#customer_city').val(data['customer_city']);
        $('#customer_zip').val(data['customer_zip']);
        $('#customer_id').val(data['customer_id']);

        $('#selectClientModal').modal('hide');
    } ); 
   
    $('#exporation_date_card').combodate({
        smartDays: true,
        maxYear:2030,
        minYear:2018,
        customClass: "form-control form-control-sm"
    });
    
    

    $("#submit").on( "click", function(event){
        // validate form
        var validatorResult = $("#giftcard-form")[0].checkValidity();
        $("#giftcard-form").addClass('was-validated');
        if(!validatorResult){
            event.preventDefault();
            event.stopPropagation();           
            return;
        }
        $('#giftcard-form').submit();
    });
}); 
</script>  


<script>
    //check validate
    $(document).ready(function(){

        var check = 0;
        $("input[name='price']").on("blur",function(e){
            var num = parseInt($(this).val());
            var str = $(this).val();
            if(num<0 || str.length<=0){
                $(this).addClass('is-invalid');
                check = 1;
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });


        $("input[name='loyalty_referral']").on("blur",function(e){
            var num = parseInt($(this).val());
            var str = $(this).val();
            if(num<0 || str.length<=0){
                $(this).addClass('is-invalid');
                check = 1;
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });


        $("#expire_date").on("blur",function(e){
            var str = $(this).val();
            console.log(str.search('\\/'));
            if(str.length <=0 || str.search('\\/')==-1){
                $(this).addClass('is-invalid');
                check = 1;
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });
        $("input[name='customer_phone']").on("blur",function(e){
            var str = $(this).val();
            if(str.length !=10){
                $(this).addClass('is-invalid');
                check = 1;
            }else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                check = 0;
            }
            checkSubmit(check);
        });
        $("input[name='customer_email']").on("blur",function(e){
            var str = $(this).val();       
            console.log(str.search("@"));
            console.log(str.search("\\."));
            if(str.search("\\@") == -1 || str.search("\\.") == -1){
                check = 1;
                $(this).addClass('is-invalid');
            }else {
                check = 0;
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
            checkSubmit(check);
        });

        $("input[name='customer_fullname']").on("blur",function(e){
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

        

        $("input[name='customer_state']").on("blur",function(e){
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


        $("input[name='customer_country']").on("blur",function(e){
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

        $("input[name='customer_city']").on("blur",function(e){
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

        $("input[name='customer_zip']").on("blur",function(e){
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

        $("input[name='customer_address']").on("blur",function(e){
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

