
<div class="modal" id="payTicketModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-full" role="document">
      <div class="modal-content">
         <div class="modal-body" style="color: #000;padding: 2px 0 0 0 ">
           
            <div class="col-md-7" style="background-color: #eaeaea ;padding-right: 0px;padding: 0px">
            <div class="payment_info div-payment" style="width: 100%">
            <div class="scrollbar scroll-style-1 border-left detail_list"  id="" style="margin-right:;height: 350px;overflow-y: auto;" >
               <div class="receipt-modal-dialog out-of-signature-box clearfix" style="display:block; width: 100%">
                  <div class="receipt-wrap receipt-print">
                     <div class="receipt-wrap" id="bill-need-clone" style="width:100%;">
                        <div style="width:100%;font-size:16px;line-height:1.5;padding:2%;" class="ticket_list row">
                        </div>
                        <div style="width:100%;font-size:16px;line-height:1.5;display: none" class="correct_list row">
                        </div>
                     </div>
                  </div>
               </div>               
            </div>

            <div>
              <div class="scrollbar scroll-style-1 border-left detail_list" id="content-payment" style="height: 260px;margin-right: 10px;width: 100%;background-color: #fff;overflow-y: auto;border-top: .5px solid #6b6e71">
            <div class="pay-box div-pay" style="display: none">
              <div class="col-md-12 row button_show">
                <div id="reward" class="active text-center btn_default custom_btn_payticket" style="line-height: 60px" ><span>Reward</span></div>
                <div id="coupon" class="text-center btn_default custom_btn_payticket" style="line-height: 60px" ><span>Coupon</span></div>
                <div id="gift_card" class="text-center btn_default custom_btn_payticket" style="line-height: 60px" ><span>Gift Card</span></div>
                <div id="cash" class="text-center btn_default custom_btn_payticket" style="line-height: 60px" ><span>Cash</span></div>
                <div id="credit" class="text-center btn_default custom_btn_payticket" style="line-height: 60px" ><span>Credit</span></div>
                <div id="debit" class="text-center btn_default custom_btn_payticket" style="line-height: 60px" ><span>Debit</span></div>
              </div>
              <div class="clearfix"></div>
              <br>
              <div id="show_info" class="col-md-12">
                <div class="reward">
                <div class="col-md-4">
                  <b class="clearfix"><span style="float: left;">Point</span><span style="float: right;">Reward</span></b>
                  <div class="btn_default clearfix " style="color: #fff;padding: 10px;" ><span style="float: left;" class="balance_point" ></span><span style="float: right;">$<span class="balance_reward"></span></span></div>              
                </div>
                <div class="col-md-4">
                  <b class="clearfix"><span style="float: left;">Use Point</span><span style="float: right;">Reward</span></b>
                  <input type="text" class="col-md-8" id="use_point" onkeypress="return isNumberKey(event)" style="padding: 8px; float: left;" value="">
                <input type="text" class="col-md-4 use_amount" disabled style="padding: 8px; float: right;" value="">             
                </div>
                </div>
                <div class="coupon" style="display: none;">
                  <div class="col-md-4">
                    <b class="clearfix"><span style="float: left;">Coupon Code</span></b>
                    <input type="text" class="col-md-12" id="coupon_code" style="padding: 8px; float: left;">
                  </div>
                  
                  <div class="col-md-4">
                    <b class="clearfix"><span style="float: left;">Amount</span><span style="float: right;"></span></b>
                    <input type="text" class="col-md-12" id="coupon_cash" disabled style="padding: 8px; float: left;" value="">
                  </div>
                  {{-- <div>
                  <b class="clearfix"><span style="float: left;">Gift Card Use</span><span style="float: right;"></span></b>
                  <input type="text" class="col-md-12 " id="giftcard_pay" onkeypress="return isNumberKey(event)" style="padding: 8px; float: left;" value="">
                  </div> --}}
                  <div class="col-md-4">
                    <b class="clearfix"><span style="float: left;">Balance</span><span style="float: right;"></span></b>
                    <input type="text" class="col-md-12 coupon_balance" disabled style="padding: 8px; float: left;" value="">
                  </div>
                </div>

                <div class="gift_card" style="display: none;">
                  <div class="col-md-4">
                    <b class="clearfix"><span style="float: left;">Gift Card Code</span></b>
                    <input type="text" class="col-md-12" id="giftcard_code" style="padding: 8px; float: left;">
                  </div>
                  
                  <div class="col-md-4">
                    <b class="clearfix"><span style="float: left;">Amount</span><span style="float: right;"></span></b>
                    <input type="text" class="col-md-12 giftcard_value" disabled style="padding: 8px; float: left;" value="">
                  </div>
                  {{-- <div>
                  <b class="clearfix"><span style="float: left;">Gift Card Use</span><span style="float: right;"></span></b>
                  <input type="text" class="col-md-12 " id="giftcard_pay" onkeypress="return isNumberKey(event)" style="padding: 8px; float: left;" value="">
                  </div> --}}
                  <div class="col-md-4">
                    <b class="clearfix"><span style="float: left;">Balance</span><span style="float: right;"></span></b>
                    <input type="text" class="col-md-12 giftcode_balance" disabled style="padding: 8px; float: left;" value="">
                  </div>
                </div>
                <div class="cash" style="display: none;">
                <div class="col-md-4">
                <b class="clearfix"><span style="float: left;">Cash</span><span style="float: right;">Cash Back</span></b>
                <input type="text" class="col-md-8" id="value_cash" onkeypress="return isNumberKey(event)" style="padding: 8px; float: left;" value="">
                <input type="text" class="col-md-4 cash_back_value" disabled style="padding: 8px; float: right;" value="">
                </div>
                </div>

                <div class="credit" style="display: none;">
                  <div class="col-md-4">
                  <b class="clearfix"><span style="float: left;">Credit Card Number</span><span style="float: right;"></span></b>
                  <input type="text" class="col-md-12 " id="card_number" onkeypress="return removeSpecialCharacter(event)" style="padding: 8px; float: left;" value="">
                  </div>
                  <div class="col-md-4">
                  <b class="clearfix"><span style="float: left;">Amount</span><span style="float: right;"></span></b>
                  <input type="text" class="col-md-12 " id="amount_credit" onkeypress="return isNumberKey(event)" style="padding: 8px; float: left;" value="">
                  </div>
                </div>
                <div class="debit" style="display: none;">
                <div class="col-md-4">
                <b class="clearfix"><span style="float: left;">Debit Card Number</span><span style="float: right;"></span></b>
                <input type="text" class="col-md-12 " id="debit_number" onkeypress="return removeSpecialCharacter(event)" style="padding: 8px; float: left;" value="">
                </div>
                <div class="col-md-4">
                <b class="clearfix"><span style="float: left;">Amount</span><span style="float: right;"></span></b>
                <input type="text" class="col-md-12 " id="amount_debit" onkeypress="return isNumberKey(event)" style="padding: 8px; float: left;" value="">
                </div> 
              </div>
              </div>
            </div>
            <div class="tip-div col-md-8 div-pay" style="display: none;margin: 10px">
              <div class="tip-div action-div tip-div-change">
                <div class="row form-group top_tip_money" style="float: right;">
                  <a class="btn btn-danger event_button btn-tip" href="#" >Even</a>
                  <a class="btn btn_primary service_button btn-tip" href="#" >Service</a>
                  <a class="btn btn_primary percent_button btn-tip" href="#" >%</a>
                </div>
                <div class="clearfix"></div>
                <input type="hidden" id="type_tip_hidden" value="">
                <div class="row form-group total_tip">
                         <label class="col-xs-5 col-sm-5 col-md-5">Total Tip(<span class="type_of_tip">$</span>)</label>
                         <div class="col-xs-7 col-sm-7 col-md-7  no-padding">
                            <input type="text" id="total_tip" placeholder="Tip" name="total_tip"  onkeypress="return isNumberKey(event)" value="" class="form-control form-control-sm">
                         </div>            
                    </div>
                    <div class="staff_list"></div>
              </div>
            </div>
            <div class="discount_div col-md-12 div-pay" style="display: none;padding: 0px">
                <h6 class="" style="background-color: #959a9e;padding-left: 50px">Select Discount</h6>
                <div class="col-md-3 discount_div_left">
                  <div class="col-md-12 discount-box text-center discount_div_origin" discount_station=0 reason="System testing">
                    <b>Owner</b>
                  </div>
                  <div class="col-md-12 discount-box text-center discount_div_origin" discount_station=1 reason="System testing">
                    <b>Rent Station</b>
                  </div>
                  <div class="col-md-12 discount-box text-center discount_div_origin" discount_station=2 reason="System testing">
                    <b>50/50</b>
                  </div>
                </div>
                <div class="col-md-6 discount_div_right">
                  <div class="col-md-5 discount-box text-center discount_div_origin other_discount_box" discount=1 reason="System testing">
                    <b>Other Discount</b>
                  </div>
                  <div class="col-md-5 row " id="discount_other" style="display: none">
                    <input type="number" style="line-height: 34px;margin-top: 2px;" onkeypress="return isNumberKey(event)"class="col-md-5 discount_amount"  name="" value="" placeholder="Enter Discount">
                    <select name="" class="col-md-3 discount_type" style="line-height: 34px;margin-top: 2px;padding: 0px">
                      <option selected value="0">$</option>
                      <option value="1">%</option>
                    </select>
                    <div class="col-md-4 bg-primary discount_submit" style="line-height: 34px;margin-top: 2px;color: #fff"><b>OK</b></div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-5 discount-box text-center discount_div_origin" discount=2 reason="Wrong Item">
                    <b>$3.00 OFF</b>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-5 discount-box text-center discount_div_origin" discount=3 reason="Void Mistake">
                    <b>$5.00 OFF</b>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-5 discount-box text-center discount_div_origin" discount=4 reason="Too long">
                    <b>5% OFF</b>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-5 discount-box text-center discount_div_origin" discount=5 reason="Wrong Tech">
                    <b>10% OFF</b>
                  </div>
                </div>
            </div>
            <div class="reason-voided-ticket-div div-pay col-md-12" style="display: none;padding: 0px">
                <h6 class="" style="background-color: #959a9e;padding-left: 50px">Select void Reasons</h6>
                <div class="col-md-3 reason-div text-center" reason="System testing">
                  <b>System testing</b>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3 reason-div text-center" reason="Wrong Item">
                  <b>Wrong Item</b>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3 reason-div text-center" reason="Void Mistake">
                  <b>Void Mistake</b>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3 reason-div text-center" reason="Too long">
                  <b>Too long</b>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3 reason-div text-center" reason="Wrong Tech">
                  <b>Wrong Tech</b>
                </div>
            </div>
            
              </div>
            	<div class="border-left detail_list"  id="" style="overflow-y: auto;" >
              <div class="receipt-modal-dialog out-of-signature-box liststaffs_payment payment_info_son" style="display:block; width: 100%" >
               </div>
            </div>
            </div>
        	</div>
        	<div class="pay col-md-12 font_size_18px div-payment" style="display: none;">
            <div class="customer_info">
            </div>
        		
        		<div class="clearfix"></div>
        	</div>
          @include('salefinance.partials.buy_giftcard')
          @include('salefinance.partials.referral_giftcard')
          @include('salefinance.partials.buy_membership')
          @include('salefinance.partials.buy_product')
          @include('salefinance.partials.correct_ticket_list')
            </div>
            
            <div class="col-md-3 "  id="payment_print" style="max-height: 720px; margin-left: 0px;padding: 0px" >
               <div class="receipt-modal-dialog out-of-signature-box clearfix" style="display:block; width: 100%">
                  <div class="receipt-wrap receipt-print">
                     <div class="receipt-wrap" id="bill-need-clone" style="width:100%;">
                        <div class="scrollbar scroll-style-1 border-left detail_list"  style="width:100%;max-height:700px;font-size:16px;line-height:1.5;padding: 10px;overflow-y: auto;">

                           <div style="width:100%;text-align:center;font-size:20px;font-weight: bold" class="ng-binding">Ticket <span class="ticket_no_top" style="color: #ffdf00"></span></div>
                           <div style="width:100%;text-align:center;font-size:18px;" class="ng-binding">{{$place_info->place_name}}</div>
                           <div style="width:100%;text-align:center;font-size:17px;" class="ng-binding">{{$place_info->place_address}}</div>
                           <div style="width:100%;text-align:center;font-size:17px;" class="ng-binding">{{$place_info->place_phone}}</div>                          
                           <br>
                           <div class="row time_payment_top"></div>
                           <div class="row service_list_ticket"></div>
                           <hr>
                           <div class="row product_list"></div>
                           <div class="row tips"></div>
                           <div class="row membership_discount"></div>
                           <div class="row">
                             <div style="float:left;width:30%;"></div>
                             <div style="float:left;width:40%;text-align:right">Subtotal</div>
                             <div style="float:right;width:30%;text-align:right;" class="ng-binding sub_total"></div>
                             <div style="clear:both;display:block;"></div>
                           </div>
                           <div class="discount_top row"></div>
                           <div class="row">
                             <div style="float:left;width:30%;"></div>
                             <div style="float:left;width:40%;text-align:right">Total Charge</div>
                             <div style="float:right;width:30%;text-align:right;" class="ng-binding total_charge"></div>
                           </div>
                           <div class="row">
                             <div style="float:left;width:30%;"></div>
                             <div style="float:left;width:40%;text-align:right">Balance/Change</div>
                             <div style="float:right;width:30%;text-align:right;" class="ng-binding balance_change"></div>
                           </div>
                           <div class="row giftcard_top"></div>
                           <div class="row coupon_top"></div>
                           <div class="row cash_total"></div>
                           <div class="row check"></div>
                           <div class="row credit_card_top"></div>
                           <div class="row debit_card_top"></div>

                           <div class="row">
                             <div style="float:left;width:30%;"></div>
                             <div style="float:left;width:40%;text-align:right">Cash Back</div>
                             <div style="float:right;width:30%;text-align:right;" class="ng-binding" id="cash_back"></div>
                           </div>
                           <div class="row point_top"></div>
                           <div class="clearfix"></div>
                           <hr>
                           <div class="row time_payment_middle"></div>                   
                           <div class="row service_staff_list"></div>
                           <hr>
                        </div>
                        <div style="clear:both;display:block;"></div>
                        <div class="row info_customer_footer" style="padding: 10px">
                        </div> 
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-2 text-center function_list font_size_18px" style="float: right;">               
               		<div id="buy_giftcard" class="col-md-6 text-center btn_primary custom_btn_payticket" ><span>Buy Gift Card</span></div>
               		<div class="col-md-6 text-center bg-success custom_btn_payticket" ><span>Record Invoice</span></div>
               		<div id="referral_giftcard" class="col-md-6 text-center btn_primary custom_btn_payticket" ><span>Referral Gift card</span></div>
               		<div class="col-md-6 text-center bg-success custom_btn_payticket" ><span>Complete print option</span></div>
               		<div id="buy_product" class="col-md-6 text-center btn_primary custom_btn_payticket" ><span>Buy Product</span></div>
               		<div id="print_button" class="col-md-6 text-center bg-success custom_btn_payticket" ><span>Print Client Bill</span></div>
               		<div id="buy_membership" class="col-md-6 text-center btn_primary custom_btn_payticket" ><span>Buy Membership</span></div>
               		<div id="delete_ticket" class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Void Ticket</span></div>
               		<div id="discount_ticket" class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Discount Ticket</span></div>
               		<div id="correct_ticket" class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Correct ticket</span></div>
               		<div id="combine" class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Combine</span></div>
               		<div id="edit_item" class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Edit Item</span></div>
               		<div id="split" class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Split</span></div>
               		<div id="tip" class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Tip</span></div>
               		<div id="pay" class="col-md-6 text-center bg-success custom_btn_payticket" ><span>Pay</span></div>
               		<div class="col-md-6 text-center btn_default custom_btn_payticket" ><span>Cash Draw</span></div>
               		<div class="col-md-6 text-center bg-warning custom_btn_payticket" ><span>Quick cash</span></div>
               		<div class="col-md-6 text-center bg-danger custom_btn_payticket"  data-dismiss="modal"><span>Close</span></div>               		
            </div>
         </div>
      </div>
   </div>
</div>