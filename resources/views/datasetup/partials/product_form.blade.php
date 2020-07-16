<span id="add_edit_product" style="display: none">
<div id="product_title" class="x_title"> Add Product</div>
    <div class="x_content">
        <form action="{{route('add-product')}}" method="post" id="product_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
        @csrf                     
         <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Category</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
               <input type="text" id="supply_name" name="supply_name" class="form-control form-control-sm" value="">
               <input type="hidden" id="supply_id" name="supply_id">
           </div>
         </div>                   
            <div class="row" style="margin-bottom:10px;">
         <label class="control-label col-md-2 col-sm-2 col-xs-12">Image</label>
         <div class="col-md-9 col-sm-9 col-xs-12" style="overflow: hidden;">
            <div class="catalog-image-upload">
                   <div class="catalog-image-edit">
                       <input type='file' id="sn_image_input" name="sn_image_input" data-target="#sn_image" accept=".png, .jpg, .jpeg" />
                       <input type="hidden" id="sn_image_hidden"  name="sn_image_hidden">
                       <label for="sn_image_input"></label>
                   </div>
                   <div class="catalog-image-preview">
                       <img id="sn_image" src="" height="100%" />
                   </div>
               </div>
         </div>
       </div>   
        <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Name</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="hidden" id="sn_id" name="sn_id">
             <input type='text' id="sn_name" name="sn_name" required class="form-control form-control-sm{{ $errors->has('sn_name') ? ' is-invalid' : '' }}"/>
           </div>
        </div>
        <div class="row input-group-spaddon" style="padding-top:5px;">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Price</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
              <div class="input-group">
                  <span class="input-group-addon">$</span>                        
                  <input type="number" class="form-control form-control-sm" name="sn_price" id="sn_price" min="0" data-bind="value:sn_price">
              </div>
           </div>
         </div>
         <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Unit</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
               <input type="number" name="sn_unit" required id="sn_unit" class="form-control form-control-sm{{ $errors->has('sn_unit') ? ' is-invalid' : '' }}" placeholder="ml,gram,...">
           </div>
         </div>     
        <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Quantity</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
               <input type="number" id="sn_quantity" name="sn_quantity" class="form-control form-control-sm">
           </div>
         </div>  
          <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Capacity</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
               <input type="number" required id="sn_capacity" name="sn_capacity" class="form-control form-control-sm{{ $errors->has('sn_capacity') ? ' is-invalid' : '' }}">
           </div>      
         </div>    
             <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Discount</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
               <input type="number" id="sn_discount" name="sn_discount" class="form-control form-control-sm">
           </div>
         </div> 
        <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Point</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
             <input type='number' id="sn_point" name="sn_point" class="form-control form-control-sm"/>
           </div>
         </div> 
        <div class="row input-group-spaddon" style="padding-top:5px;">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Sale Tax</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
             <div class="input-group">
                  <span class="input-group-addon">%</span>                        
                  <input type="number" id="sn_sale_tax" name="sn_sale_tax" class="form-control form-control-sm">
              </div>
           </div>
         </div>  
        <div class="row">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Bonus</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
             <input type='number' id="sn_bonus" name="sn_bonus" class="form-control form-control-sm"/>
           </div>
         </div> 
         <div class="row input-group-spaddon" style="padding-top:5px;">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Start Date</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
                <div class='input-group date' id='productDateAdd'>
                    <input type='text' id="sn_datetime" name="sn_datetime" required class="form-control datepicker{{ $errors->has('sn_datetime') ? ' is-invalid' : '' }}" />
                    <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
           </div>
         </div>    
         <div class="row input-group-spaddon" style="padding-top:5px;">
           <label class="control-label col-md-2 col-sm-2 col-xs-12">Expiry Date</label>
           <div class="col-md-9 col-sm-9 col-xs-12">
                <div class='input-group date' id='productDateAdd'>
                    <input type='text' id="sn_dateexpired" name="sn_dateexpired" required class="form-control datepicker{{ $errors->has('sn_dateexpired') ? ' is-invalid' : '' }}" />
                    <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
           </div>
         </div>    
            
         <div class="row"  style="margin-top:5px;">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">&nbsp;</label>
            <div class="col-sm-6 col-md-6  form-group">
               <button class="btn btn-sm btn-primary" id="product_submit">SUBMIT</button>
               <button class="btn btn-sm btn-default" id="product_reset" type="reset">RESET</button>
            </div>            
        </div>      
    </form>
</div>
</span> 