<div class="scrollbar div-payment div-giftcard col-md-12" style="display: none;overflow-y: hidden;">
            
            <div class="col-md-12">
              <div class="col-md-12" style="height: 30px"></div>
              <div class="col-md-6 giftcard-left">
              	<form action="" id="buy_giftcard_form" method="post" accept-charset="utf-8">
              		@csrf
	                <h5><b>Buy Giftcard</b></h5>
	                  <label class="col-md-4" for="giftcard_gift_code"><b>Gift code</b></label>
	                  <input type="text" name="" required class="form-control col-md-8 form_control" id="giftcard_gift_code" value="">
                    <label class="col-md-4 balance-box" for="giftcard_balance"><b>Balance</b></label>
                    <input type="text" name="giftcode_price"class="form-control col-md-8 form_control balance-box" id="giftcard_balance_amount" value="" disabled placeholder="">
	                  <label class="col-md-4" for="giftcard_balance"><b>Amount</b></label>
	                  <input type="text" name="giftcode_price" required onkeypress="return isNumberKey(event)" class="form-control col-md-8 form_control {{ $errors->has('giftcode_price') ? ' is-invalid' : '' }}" id="giftcard_balance" value="" placeholder="">
	                
	                  <label class="col-md-4" for="giftcard_giftcode_sale_date"><b>Purchase Day</b></label>
	                  <input type="text" name="giftcode_sale_date" required class="form-control col-md-8 form_control datepicker {{ $errors->has('giftcode_sale_date') ? ' is-invalid' : '' }}" id="giftcard_giftcode_sale_date" value="" placeholder="">
	                  {{-- <label class="col-md-4" for="giftcard_giftcode_date_expire"><b>Expired Day</b></label>
                      <input type="text" name="giftcode_date_expire"  class="form-control col-md-8 datepicker_expire form_control" id="giftcard_giftcode_date_expire" value="" placeholder="">
	                 --}}
	                  <label class="col-md-4" for="redemption"><b>Redemption</b></label>
	                  <input type="text" name="giftcode_redemption" class="form-control col-md-8 form_control" id="redemption" onkeypress="return isNumberKey(event)" value="" placeholder="">
	                <div class="col-md-12" style="height: 50px"></div>
	                <h5><b>Client Information</b></h5>

                    <label class="col-md-4" for="giftcard_phone"><b>Phone</b></label>
                    <input type="text" name="customer_phone" required class="form-control col-md-8 form_control customer_info {{ $errors->has('customer_phone') ? ' is-invalid' : '' }}" id="giftcard_customer_phone" value="" placeholder="Enter phone">

	                  <label class="col-md-4" for="giftcard_customer_fullname"><b>Name</b></label>
	                  <input type="text" name="customer_fullname" required class="form-control col-md-8 form_control {{ $errors->has('customer_fullname') ? ' is-invalid' : '' }}" id="giftcard_customer_fullname" value="" placeholder="Enter name">
	               
	                  <label class="col-md-4" for="giftcard_email"><b>Email</b></label>
	                  <input type="email" name="customer_email" class="form-control col-md-8 form_control customer_info " id="giftcard_customer_email" value="" placeholder="Enter email">
                  <div class="col-md-4"></div>

                  <div class="col-md-8">
                    <div class="bg-primary btn_payment submit_buy_giftcard" style="width:49%;float:left">
                      <b>Submit</b>
                    </div>
                    <div class=" bg-danger btn_payment btn-cancel-giftcard" style="width:49%;float:right">
                      <b>Cancel</b>
                    </div>
                  </div>
                  
                </form>
              </div>
              <div class="col-md-6">
                <div class="col-md-8 offset-md-2 row amount-div">
                  <h5><b>Select Amount</b></h5>
                  <div class="col-md-12"></div>
                  @for($i = 10; $i <= 100; $i+=10)
                  <div class="btn_payment select_amount select-amount-div {{$i}}" amount="{{$i}}" style="background-color: #959a9e;height: 50px;width: 45%;margin:2px">
                    <b>${{$i}}</b>
                  </div>
                  @endfor
                </div>

              </div>
              
            </div>
            
          </div>