<div class="paymentinfo"> 
<div class="row">
    <h4 class="col-xs-12">Billing Information</h4>           
</div> 
<div class="ln_solid" style="margin-top:0px;"></div> 
<div class="row">
    <div class="col-md-6" style="padding-right:20px;">      
        <div class="row">
                <label class="col-md-4">First Name</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" name="first_name"/>
                </div>          
        </div>
         <div class="row">
                <label class="col-md-4">Email</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" name="email"/>
                </div>   
        </div>
        <div class="row">
                <label class="col-md-4">Street Address</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" name="street_address"/>
                </div> 
        </div>
       <div class="row">
                <label class="col-md-4">State</label>
                <div class="col-md-8 input-group">
                   {{--  <select class="form-control form-control-sm" name="state" required="" >
                       <option value="">Choose State</option>
                   </select> --}}
                   <input type='text' class="form-control form-control-sm" name="state"/>
                 </div> 
        </div>
        <div class="row">
                <label class="col-md-4">Country</label>
                <div class="col-md-8 input-group">
                    {{-- <select class="form-control form-control-sm" name="state" required="" >
                       <option value="">Choose Country</option>
                   </select> --}}
                   <input type='text' class="form-control form-control-sm" name="country"/>
                 </div> 
        </div>
    </div>
    <div class="col-md-6"> 
        <div class="row">
                <label class="col-md-4">Last Name</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" name="last_name"/>
                </div>          
        </div>
         <div class="row">
                <label class="col-md-4">Phone</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" name="phone"/>
                </div>   
        </div>
        
         <div class="row">
                <label class="col-md-4">City</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" name="city"/>
                 </div> 
        </div>
      
        <div class="row">
                <label class="col-md-4">Zip</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" style="max-width:80px;" maxlength="7" name="zip"/>
                 </div> 
        </div>
        
    </div>
</div>    
<div class="row">
    <h4 class="col-xs-12">Payment Information</h4>       
</div> 
    
<div class="ln_solid" style="margin-top:0px;"></div> 
<div class="row">
    <div class="col-md-6" style="padding-right:20px;">
        <div class="row">  
            <label class="col-md-4">Card Type</label>
            <div class="col-md-8 input-group">
                <select class="form-control form-control-sm">
                        <option value=""> -- Card Type -- </option>
                        <option value="1">Visa</option>
                        <option value="2">Master</option>
                        <option value="3">Discover</option>
                        <option value="4">American Express</option>
                    </select>
             </div>      
        </div> 
        <div class="row">
                <label class="col-md-4">Card Number</label>
                <div class="col-md-8 input-group">
                    <input type='number' class="form-control form-control-sm" required name="card_number" />
                </div>    
      
        </div>
         <div class="row">
                <label class="col-md-4">Name On card</label>
                <div class="col-md-8 input-group">
                    <input type='text' class="form-control form-control-sm" name="name_on_card"/>
                </div>          
        </div>
    </div>
    <div class="col-md-6">        
         <div class="row">
                <label class="col-md-4">CCV</label>
                <div class="col-md-8 input-group">
                    <input type='number' class="form-control form-control-sm" required maxlength="3" style="max-width:60px;" name="ccv"/>
                </div>   
        </div>
         <div class="row">
                <label class="col-md-4">Expiration Date</label>
                <div class="col-md-8 input-group">
                    <select class="form-control form-control-sm" name="month" style="margin-right:10px;">
                    <option value="">Month</option>
                </select>
                <select class="form-control form-control-sm" name="year">
                    <option value="">Year</option>
                </select>
                </div>  
        </div>
    </div>
</div>
 <div class="row">  
        <label class="col-sm-3 col-md-2">Notes</label>
        <div class="col-sm-10 col-md-10 input-group">
            <textarea id="note" class="form-control" name="note"></textarea>
         </div>      
 </div>
<div class="ln_solid" style="margin-top:0px;margin-bottom:10px;"></div>
  <div class="row">  
        <label class="col-sm-3 col-md-2">&nbsp;</label>
        <div class="col-sm-10 col-md-10 input-group">
            <button class="btn btn-sm btn-primary" >SUBMIT</button>
            <button class="btn btn-sm btn-default" type="button">CANCEL</button>
         </div>      
 </div> 
</div>         
    