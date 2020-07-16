@extends('layouts.master',['displayDataTables' => FALSE])

@section('title', 'Marketing | Reviews | Buy SMS Package')

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
    <div class="col-xs-12 col-md-12 padding-10">
        <div class="x_panel col-md-4 full-height scroll-view">
            <div class="x_content"> 
                <h4>Sms Package Information</h4>
                <div class="ln_solid" style="margin: 0px 0px 15px 0px;"></div> 
                <div class="row form-group">
                    <table class="table table-bordered">
                <thead>
                  <tr class="text-center" style="background-color: #009FD6">
                    <th colspan="2">
                      <h5 style="color: white;">SMS MARKETING FULL PACKAGE</h>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="background-color: #BED6EE">
                    <td class="with_column1">Package Price</td>
                    <td class="">${{$data->servicedetail_price}}</td>
                  </tr>
                  <tr style="background-color: #E0EAF6">
                    <td class="">Total SMS</td>
                    <td class="">{{$explode[0]}}</td>
                  </tr>
                  <tr style="background-color: #EEEFEA">
                    <td class="">Bonus SMS</td>
                    <td class="">{{$explode[1]}}</td>
                  </tr>
                  @foreach($arrpackage as $k=>$vl)
                  <tr>
                    <td class="">{{$vl}}</td>
                        
                        @if(in_array($k,$explode1))
                           <td class="text-center"><i class='fa fa-check' style='font-size:24px'></i></td>
                        @else
                            <td class="text-center"><i class='fa fa-close' style='font-size:24px; color: #B30909'></i></td>
                        @endif
                  </tr>
                  @endforeach

                </tbody>
              </table>   
                </div>                 
            </div>
        </div>    
        <div class="x_panel col-md-8 full-height scroll-view">
            <div class="x_content"> 
              <form action="{{route('post_authorization_sms_pakage')}}" method="post">
                @csrf
                <input type="hidden" name="price" value="{{$data->servicedetail_price}}">
                <input type="hidden" name="serviceId" value="{{$data->servicedetail_id}}">
                <input type="hidden" name="total_sms" value="{{$explode[0]}}">
                <input type="hidden" name="bonus_sms" value="{{$explode[1]}}">
                <h4>Billing & Payment Information</h4>
                <div class="ln_solid" style="margin: 0px 0px 15px 0px;"></div>             
                <div class="row">
                    <div class="col-md-6" style="padding-right:20px;">
                      <div class="row">  
                        <label class="col-md-4">Card Type</label>
                        <div class="col-md-8 input-group">
                          <select class="form-control form-control-sm" name="card_type">
                            <option value=""> -- Card Type -- </option>
                            <option value="Visa">Visa</option>
                            <option value="Master">Master</option>
                            <option value="Discover">Discover</option>
                            <option value="American Express">American Express</option>
                          </select>
                        </div>      
                      </div> 
                      <div class="row">
                        <label class="col-md-4">Card Number</label>
                        <div class="col-md-8 input-group">
                          <input type='number' required name="card_number" class="form-control form-control-sm"/>
                        </div>    

                      </div>
                      <div class="row">
                        <label class="col-md-4">Name On card</label>
                        <div class="col-md-8 input-group">
                          <input type='text' name="name_on_card" class="form-control form-control-sm"/>
                        </div>          
                      </div>
                    </div>
                    <div class="col-md-6">        
                      <div class="row">
                        <label class="col-md-4">CCV</label>
                        <div class="col-md-8 input-group">
                          <input type='number' required name="ccv" class="form-control form-control-sm" maxlength="3" ="999" style="max-width:80px;" onKeyPress="if(this.value.length==3) return false;" min="0" >
                        </div>   
                      </div>
                      <div class="row">
                        <label class="col-md-4">Expiration Date</label>
                        <div class="col-md-8 input-group form-inline">
                          {{-- <select class="form-control form-control-sm" name="month" style="margin-right:10px;">
                            <option value="">Month</option>
                          </select>
                          <select class="form-control form-control-sm" name="year">
                            <option value="">Year</option>
                          </select> --}}
                          <input required type="text" id="exporation_date_card" data-format="YYYY-MM" data-template="YYYY MM" name="exporation_date_card">
                        </div>  
                      </div>
                    </div>
                </div>
                <div class="ln_solid" style="margin: 5px 0px 15px 0px;"></div> 
                 <div class="row">  
                        <label class="col-sm-3 col-md-2">&nbsp;</label>
                        <div class="col-sm-10 col-md-10 input-group">
                            <button id="submit" type="submit" class="btn btn-sm btn-primary" >SUBMIT</button>                     
                            <a href="{{ route('list_reviews') }}" class="btn btn-sm btn-default">CANCEL</a>
                         </div>      
                 </div> 
              </form>
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

    $('#exporation_date_card').combodate({
        smartDays: true,
        maxYear:2030,
        minYear:2018,
        customClass: "form-control form-control-sm"
    });
    
}); 
</script>  

<script>
    //check validate
    $(document).ready(function(){

        var check = 0;
        $("input[name='card_number']").on("blur",function(e){
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
        
        $("input[name='ccv']").on("blur",function(e){
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

        $(".year").on("blur",function(e){
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

        $(".month").on("blur",function(e){
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