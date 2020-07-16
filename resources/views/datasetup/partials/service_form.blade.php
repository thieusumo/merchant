<span id="add_edit_service" style="display: none">
<div class="x_title" id="service_title" >Edit Service</div>
    <div class="x_content">
        <form action="{{route('save-service-mana')}}" method="get" id="service_form" class="form-horizontal form-label-left">                      
         <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Category</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
               <select class="form-control form-control-sm" id="cateservice_id" name="cateservice_id">
                @foreach($cateservice_list as $cateservice)
                   <option value="{{$cateservice->cateservice_id}}">{{$cateservice->cateservice_name}}</option>
                @endforeach
               </select>
           </div>
         </div>   
        <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Name</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="hidden" name="service_id" id="service_id" value="0">
             <input type='text' id="service_name" required name="service_name" class="form-control form-control-sm{{$errors->has('service_name')?'is-invalid':''}}"/>
           </div>
        </div>
        <div class="row input-group-spaddon" style="padding-top:5px;">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Price</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
              <div class="input-group">
                  <span class="input-group-addon">$</span>                        
                  <input type="number" id="service_price" required name="service_price" class="form-control form-control-sm{{$errors->has('service_price')?'is-invalid':''}}">
              </div>
           </div>
         </div>
         <div class="row input-group-spaddon" style="padding-top:5px;">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Duration</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
              <div class="input-group">
                  <span class="input-group-addon">hours</span>                        
                  <input type="number" id="service_duration" required name="service_duration" class="form-control form-control-sm{{$errors->has('service_duration')?'is-invalid':''}}">
              </div>
           </div>
         </div>    
        <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Hold</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
             <input type='number' id="service_price_hold" name="service_price_hold" class="form-control form-control-sm"/>
           </div>
         </div> 
         <div class="row input-group-spaddon" style="padding-top:5px;">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Tax</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
             <div class="input-group">
                  <span class="input-group-addon">%</span>                        
                  <input type="number" id="service_tax" name="service_tax" class="form-control form-control-sm">
              </div>
           </div>
         </div>     
         <div class="row" style="padding-top: 10px;">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">&nbsp;</label>
            <div class="col-sm-6 col-md-6  form-group">
               <button class="btn btn-sm btn-primary" id="service_submit" type="submit" >SUBMIT</button>
               <button class="btn btn-sm btn-default" id="service_reset" type="reset">RESET</button>
            </div>            
        </div>      

    </form>
</div>
</span>      