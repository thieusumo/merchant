<span id="add_edit_drink" style="display: none;">
<div class="x_title" id="add_edit_title">Edit Drink</div>
<div class="x_content">
    <form action="{{route('save-drink')}}" method="get" id="drink_form" class="form-horizontal form-label-left">                                      
    <div class="row">
       <label class="control-label col-md-2 col-sm-2 col-xs-12">Name</label>
       <div class="col-md-9 col-sm-9 col-xs-12">
        <input type="hidden" id="beverage_id" name="beverage_id" value="0">
         <input type='text' name="beverage_name" id="beverage_name" required class="form-control form-control-sm{{$errors->has('beverage_name')?'is-invalid':''}}"/>
         <span style="color: red">{{$errors->first('beverage_name')}}</span>
       </div>
    </div>
    <div class="row input-group-spaddon" style="padding-top:5px;">
       <label class="control-label col-md-2 col-sm-2 col-xs-12">Price</label>
       <div class="col-md-9 col-sm-9 col-xs-12">
          <div class="input-group">
              <span class="input-group-addon">$</span>                        
              <input type="text" required name="beverage_price" id="beverage_price" class="form-control form-control-sm{{$errors->has('beverage_price')?'is-invalid':''}}">
              <span style="color: red">{{$errors->first('beverage_price')}}</span>
          </div>
       </div>
     </div>
     <div class="row">
       <label class="control-label col-md-2 col-sm-2 col-xs-12">Description</label>
       <div class="col-md-9 col-sm-9 col-xs-12">
           <input type='text' id="beverage_description" name="beverage_description" class="form-control form-control-sm"/>
       </div>
     </div>      
     <div class="row form-actions" style="padding-top:5px;">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">&nbsp;</label>
        <div class="col-sm-6 col-md-6  form-group">
           <button class="btn btn-sm btn-primary" id="drink_submit" type="submit">SUBMIT</button>
           <button class="btn btn-sm btn-default" id="drink_reset" type="reset">ADD NEW</button>
        </div>            
    </div>      

</form>
</div>
</span>