
<div class="modal" id="payTicketModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h5 class="modal-title">Pay Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div> -->
            <div class="modal-body">
            	<div class="row col-sm-6 no-padding ticket_list" >
            		<!-- @for ($i = 1; $i < 7; $i++)
					    <div class="card ml-2 mb-3 " style="width: 13rem;">
						  <div class="card-header bg-warning">
						  	<div class="float-left">#B0001</div>
						  	<div class="float-right"><a href="#" class="a-red" ><i class="glyphicon glyphicon-remove"></i></a></div>
						    
						  </div>
						  <div class="card-body">
						  	<div class="mb-4">
						  		<span class='glyphicon glyphicon-time float-left'>10:30 AM</span>
						  		<span class='glyphicon glyphicon-calendar float-right' >04/03/2019</span>
						  	</div>
						  	

						  	<p>Service 1</p>
						  	<p>Service 2</p>
						  	<p>Service 3</p>
						  </div>
						  <div class="btn-primary card-footer text-center">
						   Pay Order
						  </div>
						</div>
					@endfor -->
		            
				</div>
				<div class="row col-sm-3 function_list" >
					<div class="height-40p border-bottom border-left col-sm-12" style="min-height: 250px;">
						<div class="tip-div action-div tip-div-change">
							<div class="row form-group top_tip_money">
								<a class="btn btn-danger event_button disabled" href="#" >Even</a>
								<a class="btn btn-primary service_button disabled" href="#" >Service</a>
								<a class="btn btn-primary percent_button disabled" href="#" >%</a>
							</div>
							<input type="hidden" id="type_tip_hidden" value="">
							<div class="row form-group total_tip">
					             <label class="col-xs-5 col-sm-5 col-md-5">Total Tip(<span class="type_of_tip">$</span>)</label>
					             <div class="col-xs-7 col-sm-7 col-md-7  no-padding">
					                <input type="text" id="total_tip" placeholder="Tip" name="total_tip"  onkeypress="return isNumberKey(event)" value="" class="form-control form-control-sm">
					             </div>            
					        </div>
					        <div class="staff_list"></div>
						</div>
						<div class="coupon-div action-div" style="display: none">
							<div class="row form-group">
								<label class="col-xs-5 col-sm-5 col-md-5">Coupon code</label>
					             <div class="col-xs-5 col-sm-5 col-md-5 no-padding">
					                <input type="text" id="coupon_code" name=""  onkeypress="return isNumberKey(event)" value="" class="form-control form-control-sm">
					             </div>   
					             <div class="col-xs-2 col-sm-2 col-md-2 no-padding">
					                <input type="text" id="coupon_cash" value="$ 0" disabled class="form-control form-control-sm">
					             </div>  
							</div>
							<div class="row form-group">
								<label class="col-xs-5 col-sm-5 col-md-5">Total Point</label>
					             <div class="col-xs-7 col-sm-7 col-md-7 no-padding">
					                <input type="text" id="point_total" disabled class="form-control form-control-sm">
					             </div>   
							</div>
							<div class="row form-group">
								<label class="col-xs-5 col-sm-5 col-md-5">Use point</label>
					             <div class="col-xs-4 col-sm-4 col-md-4 no-padding">
					                <input type="text" id="use_point" onkeypress="return isNumberKey(event)" class="form-control form-control-sm">
					             </div> 
					             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
					                <input type="text" id="use_point_cash" value="=>  $0" disabled class="form-control form-control-sm">
					             </div>  
							</div>
						</div>

						<div class="giftcard-div action-div" style="display: none">
							<div class="row form-group">
								<label class="col-xs-5 col-sm-5 col-md-5">Gift card code</label>
					             <div class="col-xs-5 col-sm-5 col-md-5 no-padding">
					                <input type="text" id="giftcard_code" name="" value="" class="form-control form-control-sm">
					             </div>   
					             <div class="col-xs-2 col-sm-2 col-md-2 no-padding">
					                <input type="text" id="giftcard_price" disabled value="$ 0" class="form-control form-control-sm">
					             </div>  
							</div>
							<div class="row form-group">
								<label class="col-xs-5 col-sm-5 col-md-5">Pay ($)</label>
					             <div class="col-xs-7 col-sm-7 col-md-7 no-padding">
					                <input type="text" id="giftcard_pay" value="0" class="form-control form-control-sm">
					             </div>   
							</div>
						</div>

						<div class="check-div action-div" style="display: none">
							<div class="row form-group">
								<label class="col-xs-4 col-sm-4 col-md-4">Check($)</label>
					             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
					                <input type="text" id="check" onkeypress="return isNumberKey(event)"  required="" name="" value="" class="form-control form-control-sm">
					             </div>   
					             <div class="col-xs-5 col-sm-5 col-md-5 no-padding">
					                <input type="text" id="number_check" placeholder="Number Check" class="form-control form-control-sm">
					             </div>  
							</div>
						</div>

						<div class="credit-div action-div" style="display: none">
							<div class="row form-group">
								<label class="col-xs-4 col-sm-4 col-md-4">Amount($)</label>
					             <div class="col-xs-3 col-sm-3 col-md-3 no-padding">
					                <input type="text" id="amount_credit" onkeypress="return isNumberKey(event)"  required="" name="" value="" class="form-control form-control-sm">
					             </div>   
					             <div class="col-xs-5 col-sm-5 col-md-5 no-padding">
					                <input type="text" id="card_number" placeholder="Card Number" class="form-control form-control-sm">
					             </div>  
							</div>
						</div>

						<div class="cash-div action-div" style="display: none">
							<div class="row form-group">
								<label class="col-xs-5 col-sm-5 col-md-5">Cash($)</label>
					             <div class="col-xs-7 col-sm-7 col-md-7 no-padding">
					                <input type="text" id="cash" required="" onkeypress="return isNumberKey(event)"  name="" value="" class="form-control form-control-sm">
					             </div>   
							</div>
						</div>

					</div>
					<div class="height-40p border-left" style="min-height: 250px;">
						<div class="action mt-2 button-item">
			                    <a class="btn btn-primary btn-custom btn-active tip-div disabled" href="#" >Tip</a>
			                    <a class="btn btn-primary btn-custom coupon-div disabled" id="coupon_button" href="#" >Coupon</a>
			                    <a class="btn btn-primary btn-custom giftcard-div disabled" id="giftcard_button" href="#" >Gift Card</a>
			                    <a class="btn btn-primary btn-custom credit-div disabled" href="#" id="credit_button">Credit</a>
			                    <a class="btn btn-primary btn-custom check-div disabled" href="#" id="check_button">Check</a>
			                    <a class="btn btn-primary btn-custom cash-div disabled" href="#" >Cash</a>
			                    <a class="btn btn-primary btn-custom disabled" href="#" >Discount</a>
			                    <a class="btn btn-primary btn-custom disabled" href="#" >Merge</a>
			                    <hr>
			                    <a class="btn btn-primary btn-custom disabled" id="print_button" href="javascript:void(0)" {{-- onclick="printDiv()" --}} >Print</a>
			            </div>
					</div>
				</div>
				<div class="row col-sm-3 scrollbar scroll-style-1 border-left detail_list"  id="payment_print" style="margin-left: 10px" >
					<div class="receipt-modal-dialog out-of-signature-box clearfix" style="display:block; width: 100%">
					   <div class="receipt-wrap receipt-print">
					      <div class="receipt-wrap" id="bill-need-clone" style="width:100%;">
					         <div style="width:100%;height:100%;font-size:16px;line-height:1.5;">
					            <!-- ngIf: false -->
					            <div style="width:100%;text-align:center;font-size:20px;font-weight: bold" class="ng-binding">Ticket #<span class="ticket_no_top"></span></div>
					            <div style="width:100%;text-align:center;font-size:18px;" class="ng-binding">DEG Sai Gon</div>
					            <div style="width:100%;text-align:center;font-size:17px;" class="ng-binding">HCM</div>
					            <div style="width:100%;text-align:center;font-size:17px;" class="ng-binding">(091) 887-3086</div>
					            <!-- ngIf: false -->
					            <div style="width: 100%;" class="ng-binding"> </div>
					            <!-- ngIf: false -->
					            <div class="ng-binding"></div>
					            <div class="time_payment_top"></div>
					            <hr>
					            <div class="service_list_ticket"></div>

					            <div style="width:100%;" ng-class="{'text-danger':action.price == 'tip','text-gray':action.price != 'tip'}" ng-if="billSelected.extra.totalTip != '' &amp;&amp; billSelected.extra.totalTip != 0" class="ng-scope text-gray">
					               <div class="tips"></div>
					               <div style="clear:both;display:block;"></div>
					            </div>
					            <hr style="border-top: 1px solid rgba(0,0,0,.1)">

					            <div class="giftcard_top"></div>
					            <div class="coupon_top"></div>
					            <div class="point_top"></div>

					               
					            <!-- ngIf: billSelected.tax.service_tax > 0 --><!-- ngIf: billSelected.tax.sale_tax > 0 --><!-- ngIf: billSelected.discountValue != 0 -->
					            <div style="width:100%;border-bottom:1px solid #c3c3c3;border-top:1px solid #c3c3c3;">
					               <div style="float:left;width:70%;" class="text-uppercase"><b>Total Charge</b></div>
					               <div style="float:right;width:30%;text-align:right;" class="ng-binding "><span class="total_charge">0</span></div>
					               <input type="hidden" id="total_charge_hidden" value="">
					               <input type="hidden" id="customer_id" value="">
					               <div style="clear:both;display:block;"></div>
					            </div>
					           
					            <div style="width:100%;border-bottom:1px solid #c3c3c3;border-top:1px solid #c3c3c3;">

					             <div class="credit_card_top"></div>
					             <div class="check"></div>
					             <div class="cash_total"></div>
					               <div style="float:left;width:70%;" class="text-uppercase"><b>Total Payment</b></div>
					               <div style="float:right;width:30%;text-align:right;" id="total_payment" class="ng-binding">$0</div>
					               <div style="clear:both;display:block;"></div>
					            </div>
					            <div style="width:100%;border-bottom:1px solid #c3c3c3;">
					               <div style="float:left;width:70%;" class="text-uppercase"><b>Cash back</b></div>
					               <div style="float:right;width:30%;text-align:right;" id="cash_back" class="ng-binding">$0</div>
					               <div style="clear:both;display:block;"></div>
					            </div>
					            <div style="width:100%;border-bottom:1px solid #c3c3c3;">
					               <div style="float:left;width:70%;" class="text-uppercase"><b>Balance / Change</b></div>
					               <div style="float:right;width:30%;text-align:right;" class="ng-binding "><span class="balance_change">0</span></div>
					               <div style="clear:both;display:block;"></div>
					            </div>
					            <div style="width:100%;font-size:13px" class="ng-binding time_pay"></div>
					            <div class="time_payment_middle"></div>
					            <div style="clear:both;display:block;"></div>
					            <div style="clear:both;display:block;"></div>
					            <!-- ngIf: printTicketTip == false -->
					            <div style="height: 100px;" ng-if="printTicketTip == false" class="ng-scope"></div>
					            <div style="float:left;width:70%;color: black" class="ticket_no"></div>
					            <div style="float:right;width:30%;text-align:right;color: gray" class="ng-binding "><span class="time_payment_bottom"></span></div>

					            <div style="clear:both;display:block;"></div>
					            <div class="service_staff_list" style="color: gray"></div>
					            <div style="clear:both;display:block;"></div>


					            <div class="coupon_bottom"></div>
					            <div class="giftcard_bottom"></div>
					            <div class="point_bottom"></div>
					            <div style="float:left;width:70%;color: gray" class="">Total</div>
					            <div style="float:right;width:30%;text-align:right;color: gray" class="ng-binding "><span class="total_price_bottom"></span></div>
					            <!-- end ngIf: printTicketTip == false -->
					         </div>
					      </div>
					   </div>
					</div>
				</div>
	        </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


