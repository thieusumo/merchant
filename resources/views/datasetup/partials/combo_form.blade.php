<span id="add_edit_combo" style="display: none">
<div class="x_title"  id="title_combo"> Add Combo</div>
<div class="x_content">
    <form action="{{route('save-combo')}}" method="post" id="combo_form" class="form-horizontal form-label-left">
    @csrf                  
    <div class="row">
       <label class="control-label col-md-2 col-sm-2 col-xs-12">Name</label>
       <div class="col-md-9 col-sm-9 col-xs-12">
         <input type='text' id="package_name" name="package_name" required class="form-control form-control-sm{{ $errors->has('package_name') ? ' is-invalid' : '' }}"/>
         <input type='hidden' id="package_id" name="package_id" value="0" />
       </div>
    </div>
    <div class="row">
         <label class="control-label col-md-2 col-sm-2 col-xs-12">Items</label>
       <div class="col-md-9 col-sm-9 col-xs-12">
        <table class="table table-bordered jambo_table" id="tableComboDetail">
            <thead>
                <tr class="headings">
                    <th>Name</th>
                    <th width="50" class="text-center">Price($)</th>
                    <th width="50" class="text-center">Duration(h)</th>
                    <th width="50" class="text-center">Hold</th>
                    <th width="46" class="text-center"><a href="#" class="add-item"><i class="glyphicon glyphicon-plus-sign"></i></a></th>
                </tr>
            </thead>
            <tbody>   
            </tbody>      
            <tfoot>
                <tr>
                    <td class="text-center"><label class="control-label">Total</label></td>
                    <td><input type="text" class="form-control totaliprice text-right" readonly="readonly"/></td>
                    <td><input type="text" class="form-control totaliduration text-right" readonly="readonly"/></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
         </div>   
    </div>
    <div class="row" style="padding-top: 5px;">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">&nbsp;</label>
        <div class="col-sm-6 col-md-6  form-group">
           <button class="btn btn-sm btn-primary" type="submit">SUBMIT</button>
           <button class="btn btn-sm btn-default" id="combo_reset" type="reset" >ADD NEW</button>
        </div>            
    </div>      

</form>
</div>
</span>