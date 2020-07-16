<div class="scrollbar div-payment div-referral col-md-12" style="display: none;overflow-y: hidden;">
            
            <div class="col-md-12">
              <div class="col-md-12" style="height: 30px"></div>
              <div class="col-md-6 referral_giftcard_left">
                <form action="" id="referral_giftcard_form" method="post" accept-charset="utf-8">
                  @csrf
                  <h5><b>Referral  Giftcard</b></h5>
                    <div class="col-md-12 form_control div-giftcode">
                      <label class="col-md-4" for="referral_gift_code"><b>Gift code</b></label>
                      <input type="text" name="" class="form-control form_control col-md-8 referral_code" id="referral_gift_code" value="">
                      <span class="bg-primary add_giftcode" style="position: absolute;top:0px;right:10px;z-index: 1000;height: 38px;width: 38px;"><i class="glyphicon glyphicon-plus btn_payment" style="margin-top: -6px"></i></span>
                    </div>
                    <div class="col-md-12 form_control">
                      <label class="col-md-4" for="giftcode_price"><b>Amount</b></label>
                      <input type="text" name="giftcode_price" required onkeypress="return isNumberKey(event)" class="form-control col-md-8 form_control {{ $errors->has('giftcode_price') ? ' is-invalid' : '' }}" id="referral_balance" value="" placeholder="">
                    </div>
                    <div class="col-md-12 form_control">
                      <label class="col-md-4" for="referral_giftcode_sale_date"><b>Purchase Day</b></label>
                      <input type="text" name="giftcode_sale_date" required class="form-control col-md-8 form_control datepicker {{ $errors->has('giftcode_sale_date') ? ' is-invalid' : '' }}" id="referral_giftcode_sale_date" value="" placeholder="">
                    </div>
                    {{-- <div class="col-md-12 form_control">
                      <label class="col-md-4" for="referral_giftcode_date_expire"><b>Expired Day</b></label>
                      <input type="text" name="giftcode_date_expire"  class="form-control col-md-8 datepicker_expire" id="referral_giftcode_date_expire" value="" placeholder="">
                    </div> --}}
                    <div class="col-md-12 form_control">
                      <label class="col-md-4" for="giftcode_bonus_point"><b>Bonus Points</b></label>
                      <input type="text" name="giftcode_bonus_point" class="form-control form_control col-md-8 " id="giftcode_bonus_point" value="" onkeypress="return isNumberKey(event)">
                    </div>
                  <div class="col-md-12" style="height: 50px"></div>
                  <h5><b>Referral Client</b></h5>
                  <div class="col-md-12 form_control">
                    <label class="col-md-4" for="referral_customer_fullname"><b>Name</b></label>
                    <input type="text" name="customer_fullname" required class="form-control form_control col-md-8 {{ $errors->has('customer_fullname') ? ' is-invalid' : '' }}" id="referral_customer_fullname" value="" placeholder="Enter Name">
                  </div>
                  <div class="col-md-12 form_control">
                    <label class="col-md-4" for="referral_customer_phone"><b>Phone</b></label>
                    <input type="text" required name="customer_phone" class="form-control form_control col-md-8 customer_info {{ $errors->has('customer_phone') ? ' is-invalid' : '' }}" id="referral_customer_phone" value="" placeholder="Enter Phone">
                  </div>
                  <div class="col-md-12 form_control">
                    <label class="col-md-4" for="referral_customer_email"><b>Email</b></label>
                    <input type="email" name="customer_email" class="form-control form_control col-md-8 customer_info" id="referral_customer_email" value="" placeholder="Enter Email">
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-8">
                    <div class="bg-primary btn_payment submit_referral_giftcard" style="width:49%;float:left">
                      <b>Submit</b>
                    </div>
                    <div class=" bg-danger btn_payment btn-cancel-giftcard" style="width:49%;float:right">
                      <b>Cancel</b>
                    </div>
                  </div>
                </form>
                
              </div>
              <div class="col-md-6">
                <div class="col-md-8 offset-md-2 row">
                  <h5><b>Select Amount</b></h5>
                  <div class="col-md-12"></div>
                  @for($i = 1; $i <= 10; $i++)
                  <div class="btn_payment select_amount select-amount-div {{$i}}" amount="{{$i}}" style="background-color: #959a9e;height: 50px;width: 45%;margin:2px">
                    <b>${{$i}}</b>
                  </div>
                  @endfor
                </div>

              </div>
              
            </div>
            
          </div>