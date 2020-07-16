<div class="scrollbar div-payment div-product col-md-12" style="display: none;overflow-y: hidden;height: 750px">
	@foreach($product_list as $key => $product)
		<div class="col-md-2 product-element text-center" style="padding: 4px" id="{{$product->sn_id}}" product_name="{{ $product->sn_name }}">
			<div class="" style="width: 100%;height: 120px">
				<img src="{{$product->sn_image?config('app.url_file_view').$product->sn_image:asset('images/bottle.png') }}" style="width: 100%;height: 100%">
			</div>
			<div class="row col-md-12" style="background-color: #959a9e;margin:0px 2px;height: 60px;padding-top: 3px;color:#fff">
				<div class="text-center" title="{{ucfirst($product->sn_name) }}"><b>{{ GeneralHelper::shortString($product->sn_name) }}</b></div>
				<div class="">
					<div class="col-md-12" style="padding:0px">
						<div class="col-md-3"><b>${{ round($product->sn_price,2) }}</b></div>
						<span class="col-md-2 offset-md-1 sub_product btn-product" id="{{ $product->sn_id}}">-</span>
						<input type="text" class="col-md-3 product_amount text-center" quantity="{{$product->sn_quantity}}" onkeypress="return isNumberKey(event)"  id="{{$product->sn_id}}" value="0" placeholder="" style="padding: 0px">
						<span class="col-md-2 add_product btn-product" id="{{ $product->sn_id}}">+</span>
					</div>
				</div>
			</div>
		</div>
	@endforeach
	<div class="col-md-12" style="position: absolute;bottom: 0px;right: 30px">
		<button type="button" id="close_product" class="btn btn-danger float-right">Finish</button>
	</div>
</div>