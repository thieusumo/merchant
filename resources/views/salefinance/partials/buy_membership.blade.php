<div class="scrollbar div-payment div-membership col-md-12" style="display: none;overflow-y: hidden;">
            
            <div class="col-md-12">
              <div class="col-md-12" style="height: 30px"></div>
              <div class="col-md-8 membership-left">
              	<form action="" id="membership_form" method="post" accept-charset="utf-8">
              		@csrf
	                <h5><b>Buy Membership</b></h5>
                  <div class="col-md-12">
                    <label class="col-md-4" for="giftcard_gift_code"><b>Membership</b></label>
                    <select name="membership_name" class="form-control col-md-6" id="membership_name">
                      @foreach( $membership_list as $membership)
                         <option value="{{$membership->membership_id}}" >{{strtoupper($membership->membership_name)}} ({{ $membership->membership_detail_price}})</option>
                      @endforeach
                    </select>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-sm btn-primary" id=detail_list_membership>Detail</button>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label class="col-md-4" for="giftcard_gift_code"><b>Payment method</b></label>
                    <select name="payment_method" class="form-control col-md-6" id="payment_method">
                      @foreach( \App\Helpers\GeneralHelper::paymentMethod() as $key => $method)
                         <option value="{{$key}}">{{strtoupper($method)}}</option>
                      @endforeach
                    </select>
                  </div>
	                <div class="col-md-12" style="height: 50px"></div>

	                <h5><b>Client Information</b></h5>
                    <label class="col-md-4" for="membership_customer_phone"><b>Phone</b></label>
                    <input type="text" name="customer_phone" required class="form-control col-md-6 form_control customer_info {{ $errors->has('customer_phone') ? ' is-invalid' : '' }}" id="membership_customer_phone" value="" placeholder="Enter phone">
                    <label class="col-md-4" for="membership_customer_email"><b>Email</b></label>
                    <input type="email" name="customer_email" class="form-control col-md-6 form_control customer_info " id="membership_customer_email" value="" placeholder="Enter email">
	                  <label class="col-md-4" for="membership_customer_fullname"><b>Name</b></label>
	                  <input type="text" name="customer_fullname" required class="form-control col-md-6 form_control {{ $errors->has('customer_fullname') ? ' is-invalid' : '' }}" id="membership_customer_fullname" value="" placeholder="Enter name">
                  <div class="col-md-4"></div>
                  <div class="col-md-6">
                    <div class="bg-primary btn_payment submit_membership" style="width:49%;float:left">
                      <b>Submit</b>
                    </div>
                    <div class=" bg-danger btn_payment btn-cancel-membership" style="width:49%;float:right">
                      <b>Cancel</b>
                    </div>
                  </div>
                  
                </form>
              </div>
            </div>
            
          </div>