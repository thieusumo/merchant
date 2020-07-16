<div class="imgtemplate-gallery full-height">
<div class="text-center">
    <h6 class="title border-bottom">Templates</h6>
</div>
@if (isset($templateType))
	<select id="templateType" class="form-control">
		<option value="">- ALL -</option>
		@foreach ($templateType as $element)
			<option value="{{$element->template_type_id}}">{{$element->template_type_name}}</option>
		@endforeach		
	</select>
	@endif
<div class="itgbody">
	
    <ul class="gallery list-unstyled" id="listImageTemplate">       
    </ul>
</div> 
@if (!isset($templateType))
<div class="itgfooter">
    <div class="upload-btn-wrapper">
        <button class="btn btn-sm btn-secondary" id="btnUploadImage">Upload Image</button>
        <input type="file" name="fileUploadImageTemplate" id="fileUploadImageTemplate"  accept="image/*"/>
    </div>
</div>
@endif
</div>