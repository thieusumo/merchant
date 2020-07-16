<div class="paymentinfo"> 
<div class="row">
    <input id="customer_id" name="customer_id" type='hidden' class="form-control form-control-sm" required />
    <div class="col-md-6">  
        <div class="row">
                <label class="col-md-4">Client Name</label>
                <div class="col-md-8 input-group">
                    <input id="customer_fullname" name="customer_fullname" type='text' class="form-control form-control-sm" required="required" />
                    <div class="text-danger feedback">{{ $errors->first('customer_id')}}</div>  
                </div>                
        </div>

    </div>
     <div class="col-xs-1">  
          <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#selectClientModal">Search Client</button>
     </div>
</div>    
<div class="row">
    <div class="col-md-6">              
         <div class="row">
                <label class="col-md-4">Client Phone</label>
                <div class="col-md-8 input-group">
                    <input required="required" id="customer_phone" name="customer_phone" type='number' class="form-control form-control-sm" />
                </div>   
        </div>
        <div class="row">
                <label class="col-md-4">Street Address</label>
                <div class="col-md-8 input-group">
                    <input required="required" id="customer_address" name="customer_address" type='text' class="form-control form-control-sm"  />
                </div> 
        </div>
       <div class="row">
                <label class="col-md-4">State</label>
                <div class="col-md-8 input-group">
                    <input id="customer_state" name="customer_state" type='text' class="form-control form-control-sm"  required="required"/>
                    {{-- <select class="form-control form-control-sm" name="state" required="" >
                       <option value="">Choose State</option>
                   </select> --}}
                 </div> 
        </div>
        <div class="row">
                <label class="col-md-4">Country</label>
                <div class="col-md-8 input-group">
                    <input required="required" id="customer_country" type='text' name="customer_country" class="form-control form-control-sm" />
                    {{-- <select class="form-control form-control-sm" name="state" required="" >
                       <option value="">Choose Country</option>
                   </select> --}}
                 </div> 
        </div>
    </div>
    <div class="col-md-6" style="padding-left:10px;"> 
         <div class="row">
                <label class="col-md-4">Client Email</label>
                <div class="col-md-8 input-group">
                    <input id="customer_email" name="customer_email" type='Email' class="form-control form-control-sm"  required="required"/>
                </div>   
        </div>
        
         <div class="row">
                <label class="col-md-4">City</label>
                <div class="col-md-8 input-group">
                    <input required="required" id="customer_city" name="customer_city" type='text' class="form-control form-control-sm" />
                 </div> 
        </div>
      
        <div class="row">
                <label class="col-md-4">Zip</label>
                <div class="col-md-8 input-group">
                    <input required="required" id="customer_zip" name="customer_zip" type='text' class="form-control form-control-sm" style="max-width:80px;" maxlength="7" />
                 </div> 
        </div>
        
    </div>
</div>    
<div class="ln_solid" style="margin-top:0px;"></div> 
<div class="row ">
    <label class="col-md-2">Payment Type</label>
    <div class="col-md-8 input-group">
        <div class="radio">
            <label>
              <input type="radio" id="radio_offer" class="pay_type flat icheckstyle" id="giftCardPaymentType" name="giftCardPaymentType" value="3" checked="checked">&nbsp;Offer
            </label>
        </div>
        <div class="radio" style="margin-left:10px;">
            <label>
              <input type="radio" id="radio_cash" class="pay_type flat icheckstyle" id="giftCardPaymentType" name="giftCardPaymentType" value="0" >&nbsp;Cash
            </label>
        </div>
        <div class="radio" style="margin-left:10px;">
            <label>
              <input type="radio"  id="radio_merchant" class="pay_type flat icheckstyle" id="giftCardPaymentType" name="giftCardPaymentType" value="1" >&nbsp;Merchant
            </label>
          </div>
        <div class="radio" style="margin-left:10px;">
            <label>
              <input type="radio"  id="radio_authorize" class="pay_type flat icheckstyle" id="giftCardPaymentType" name="giftCardPaymentType" value="2" >&nbsp;Authorize.net
            </label>
          </div>
    </div>                             
</div>  
<div class="ln_solid" style="margin-top:0px;"></div>
<div class="row show_authorize" style="display: none;">
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
                    <input type='number' name="card_number" class="form-control form-control-sm"/>
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
                    <input type='number' name="ccv" class="form-control form-control-sm" maxlength="3" ="999" style="max-width:80px;" onKeyPress="if(this.value.length==3) return false;" min="0" >
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
                <input type="text" id="exporation_date_card" data-format="YYYY-MM" data-template="YYYY MM" name="exporation_date_card">
            </div>  
        </div>
    </div>
</div>
</div>         
    