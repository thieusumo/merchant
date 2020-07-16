<span id="add_edit_cateservice" style="display: none">
<div class="x_title" id="cateservice_title"> Add Category Service</div>
<div class="x_content">
    <form action="{{route('save-cateservice')}}" method="get" id="cateservice_form"  class="form-horizontal form-label-left">
    @csrf
    <input type="hidden" name="cateservice_id" id="cateservice_id" value="0">                      
    <div class="row">
       <label class="control-label col-md-2 col-sm-2 col-xs-12">Name</label>
       <div class="col-md-9 col-sm-9 col-xs-12">
         <input type='text' id="cate_service_name" required name="cateservice_name" class="form-control form-control-sm{{$errors->has('cateservice_name')?'is-invalid':''}}"/>
       </div>
     </div>
     <div class="row">
       <label class="control-label col-md-2 col-sm-2 col-xs-12">Index</label>
       <div class="col-md-9 col-sm-9 col-xs-12">
           <input type='number' id="cate_service_index" required name="cateservice_index" class="form-control form-control-sm{{$errors->has('beverage_name')?'is-invalid':''}}"/>
       </div>
     </div>   
    <div class="row">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">&nbsp;</label>
        <div class="col-sm-6 col-md-6  form-group">
           <button class="btn btn-sm btn-primary" id="cateservice_submit" type="button">SUBMIT</button>
           <button class="btn btn-sm btn-default" id="cateservice_reset" type="reset" >Add New</button>
        </div>            
    </div>  
</form>
</div>
</span>