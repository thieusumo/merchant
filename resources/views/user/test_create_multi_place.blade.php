@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Sales & Finances | Booking & Payment Services')
@section('styles')
@stop
@section('content')

<div class="col-md-12">
	<div class="col-md-4 offset-md-4">
		<div class="col-md-12" style="height: 50px"></div>
		<div class="form-group col-md-12">
		    <label class="col-md-4" for=quantity>How many place do you wannt create? </label>
		    <div class="col-md-8">
		    	<input type="number" class="form-control" name="" value="" id="quantity" >
		    </div>
		</div>
		<div class="form-group  col-md-12 ">
		    <input type="button" id="submit" class="btn btn-primary float-right" name="" value="Create">
		</div>
		<div class="notifi_create">
			
		</div>
	</div>
</div>

@stop
@section('scripts')
<script>
	jQuery(document).ready(function($) {

		$("#submit").click(function(){

		    var quantity = $("#quantity").val();

		    quantity = parseInt(quantity);

		    if( quantity > 0){
		    	$.ajax({
			    	url: '{{route('post-create-multi-place')}}',
			    	type: 'POST',
			    	dataType: 'html',
			    	data: {quantity: quantity, _token: '{{csrf_token()}}'},
			    })
			    .done(function(data) {

			    	data = JSON.parse(data);
			    	if(quantity == 1)
			    		$(".notifi_create").html(`
			    		<div class="col-md-12" style="border: 1px solid red;border-radius: 20px;padding: 20px">
							<p>You just create <span id="show_quantity text-danger">`+quantity+`</span> Place.</p>
							<p>Have id <span id="number_to">`+data.to+`</span></p>
							<p>You can check with "user_name": "user_id_place", "user_phone": "84111111id_place", "user_password": "abc123" </p>
							<p >Ex: <span class="text-danger">user_640, 0111111640, abc123</span></p>
							<a href="{{ route('logout') }}" title=""><button class="btn btn-primary btn-sm" type="button">Login Now</button></a> 
						</div>`);

			    	else
			    	    $(".notifi_create").html(`
				    		<div class="col-md-12" style="border: 1px solid red;border-radius: 20px;padding: 20px">
								<p>You just create <span id="show_quantity text-danger">`+quantity+`</span> Places.</p>
								<p>Have id from <span class="number_from">`+data.from+`</span> to <span id="number_to">`+data.to+`</span></p>
								<p>You can check with "user_name": "user_id_place", "user_phone": "84111111id_place", "user_password": "abc123" </p>
								<p >Ex: <span class="text-danger">user_640, 0111111640, abc123</span></p>
								<a href="{{ route('logout') }}" title=""><button class="btn btn-primary btn-sm" type="button">Login Now</button></a> 
							</div>`);

			    	console.log(data);
			    })
			    .fail(function() {
			    	console.log("error");
			    });
		    }
	    });
	});
	
</script>
@stop