@extends('layouts.master')
@section('title', 'Management | Turn Tracker')
@section('styles')
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">
<style type="text/css">
   #payservice .scroll-view {
   overflow-y: auto;
   }
   .full-height {
   border-right: 1px #dee2e6 solid;
   }
   .card-body p{
   line-height: 15px;
   margin-bottom: 8px;
   }

   .top_nav{height: 84px;}    
   table#client-datatable.dataTable tbody tr:hover {
   background-color: #0874e8;
   color: #fff;
   cursor: -webkit-grab; 
   cursor: grab;
   }   

   .card{
   border-radius: 0px;
   }

   .membership{
   /*border-top-left-radius:19px;*/
   /*border-top-right-radius:19px;*/
   /*padding-top: 8px;*/
   }
   .custom_card_body{
   border-bottom-left-radius:19px;
   border-bottom-right-radius:19px;
   }  
   .btn_custom{
   	border: 2px white solid;    
    background: #fff;
    width: 2em;
   }
   .btn_custom:hover{
   	background: #ffec67;
   }
   .active_btn_custom{
   	   background: #ffdf00;
   }
   #tableOrders td.col-action{
   	font-size: 2em;
   }
   #tableOrders tr td{
   	   padding: 5px 6px;
   	   font-size: 1.6em;
   	   width: 0px;
   	   vertical-align: middle;
   }
   .td_max_with{
   	max-width: 15em;
   }
   .radioOptionTurn{
   	padding: 0px 10px 0px 10px;
   }
   .staff{
   	cursor: pointer;
   }
   button{
   	margin: 2px;   	
   }
	li.block-service a.btn-active{
	   background: #0874e8;
	   color: #fff;
	}
	.middle{
		line-height:middle;
	}


</style>
@stop
@section('content')
   <div id="payservice" class="col-xs-12 col-md-12 no-padding" style="height: 427px;">     
      <div class="col-xs-9 col-md-9 no-padding full-height padding-top-5">
         <div id="list-membership" class="height-10p scroll-view list-membership scroll-style-1 border-bottom">
         	<label  style="padding-left:20px">Membership</label>
            <ul class="list-inline col-md-12 col-sm-12 row liststaffs " style="width: 100%;margin: 0px;padding-left: 20px;padding-top: 0px">
               @foreach ($membership as $element)
               	<li class="list-inline-item block-service membership col-md-3 row" membership-id="{{$element->membership_id}}" membership-name="{{$element->membership_name}}"><a href="#">{{$element->membership_name}}</a></li>
               @endforeach           
            </ul>
         </div>

         <div id="list-cateservices" class="height-30p scroll-view border-bottom list-cateservices scroll-style-1">
         	<label style="padding-left:20px">Category</label>
            <ul class="list-inline col-sm-12 listcateservices" style="width: 100%;margin: 0px;padding-left: 20px">
            	@foreach ($cateservice as $element)
               	<li class="list-inline-item block-service cateservice col-md-3 row" category-id="{{$element->cateservice_id}}""><a href="#">{{$element->cateservice_name}}</a></li>
               @endforeach      
            </ul>
         </div>

         <div id="list-services" class="height-50p scroll-view border-bottom list-services scroll-style-1">
         	<label style="padding-left:20px">Service</label>
            <ul class="list-inline col-sm-12 listservices" style="width: 100%;margin: 0px;padding-left: 20px">
            	{{-- @foreach ($cateservice as $element)
               	<li class="list-inline-item block-service service " category-id="{{$element->cateservice_id}}" category-name="{{$element->cateservice_name}}"><a href="#">{{$element->cateservice_name}}</a></li>
               @endforeach      --}} 
            </ul>
         </div>
         
      </div>
      <div class="col-xs-3 col-md-3 no-padding full-height padding-top-5">
         <div style="height: 60%" class=" scroll-view border-bottom section-order scroll-style-1">
            <table id="tableOrders" class="table table-striped table-bordered" style="width:100%;">
               <tbody class="list_service_membership">
               	{{-- @for ($i = 0; $i < 2; $i++)               	
                  <tr class="" id="0">
                     <td ><i class=""></i><span class="td_membership">1221</span></td>                     
                     <td class="delete col-action text-center"><a href="#"><i class="fa fa-trash"></i></a></td>
                  </tr>   
                @endfor   --}}             
               </tbody>
            </table>
         </div>       
         <div style="height: 40%" class="text-center scroll-view section-summary scroll-style-1"> 
         	<br> 
			<div class="">
            	<div class="border-bottom">
					<div class="col-12">
						<div class="form-group row">
							<label class="col-5" style="text-align: left;">Price:</label>						   
						    <input type="number" class="form-control col-7" id="price" placeholder="Price">
							
						</div>

						<div class="form-group row">
						    <label class="col-5" style="text-align: left;">Discount Percent(%):</label>
						    <input type="number" class="form-control col-7" id="percentDiscount" placeholder="Percent Discount">
						</div>

						<div class="form-group row">
						    <label class="col-5" style="text-align: left;">Time(month):</label>
						    <input type="number" class="form-control col-7" id="time" placeholder="Time">
						</div>
	                 </div>
            	</div>
            </div>

            <div class="action">
               <div>
                  <a class="btn btn-primary save" href="#">Save</a>               
                  <a class="btn btn-default reset" href="{{ route('turnTracker') }}">Reset</a>    
               </div> 
            </div>
           
         {{--    <div class=""><h5>Turn Tracker Option</h5> <br>
            	<div class="">
					<div class="radio col-12">
	                    <label class="radioOptionTurn">
	                      <input type="radio" class="flat optionTurn" value="1"  name="optionTurn">&nbsp;&nbsp;Checkin
	                    </label>	                 
	                    <label class="radioOptionTurn">
	                      <input type="radio" class="flat optionTurn" value="2"  name="optionTurn">&nbsp;&nbsp;Service
	                    </label>	                
	                    <label class="radioOptionTurn">
	                      <input type="radio" class="flat optionTurn" value="3"  name="optionTurn">&nbsp;&nbsp;Price
	                    </label>
	                 </div>
            	</div>
            </div> --}}
         </div>
      </div>
   </div>
@stop
@section('scripts')
	<script>
		function getServiceByCateServiceId(cateserviceId){
			$.ajax({
				url:"{{ route('getServiceByCateServiceId') }}",
				data:{cateserviceId},
				method:"get",
				dataType:"json",
				success:function(data){
					if(data.success){
						var html = '';
						for(var i = 0; i < data.service.length; i++){
							html += '<li class="list-inline-item block-service service col-md-3 row" service-id="'+data.service[i].service_id+'"><a href="#">'+data.service[i].service_name+'</a></li>'
						}
						$(".listservices").html(html);
					}
				}, 
				error:function(){
					toastr.error("Failed to get service!!");
				}
			});
		}

		function getMembershipDetailByMembershipId(membershipId){
			$.ajax({
				url:"{{ route('getMembershipDetailByMembershipId') }}",
				data:{membershipId},
				method:"get",
				dataType:"json",
				success:function(data){
					if(data.success){
						var html = '';
						for(var i = 0; i < data.service.length; i++){
							html += '<tr class="" id="'+data.membership_detail_id+'" membership-id="'+membershipId+'" service-id="'+data.service[i].service_id+'">'
                     				+'<td ><i class=""></i><span class="td_membership">'+data.service[i].service_name+'</span></td>'                     
                     				+'<td class="delete col-action text-center"><a href="#"><i class="fa fa-trash"></i></a></td>'
                  					+'</tr>'
						}
						$(".list_service_membership").html(html);
						$("#price").val(data.membership_detail_price);
						$("#percentDiscount").val(data.membership_detail_percent_discount);
						$("#time").val(data.membership_detail_time);
					} else {
						$(".list_service_membership").html('');
						$("#price").val('');
						$("#percentDiscount").val('');
						$("#time").val('');
					}	
				}, 
				error:function(){
					toastr.error("Failed to get membership detail!!");
				}
			});
		}	
		
		function appendToListService(param){
			var serviceId = $(param).attr('service-id');
			var serviceName = $(param).text();

			//exit funtion when this data already exists
			var checkExists = '';
			$(".list_service_membership tr").each(function(){
				var service = $(this).attr("service-id");
				if(parseInt(service) == parseInt(serviceId)){
					toastr.warning('Service already exists!!');
					checkExists = true;
				}
			});
			if(checkExists == true) return;
			
			var checkSelected = $(".membership ").find("a.btn-active");
			if(checkSelected.length > 0){
				// new
				var membershipId = checkSelected.parent().attr('membership-id');
				var html = '<tr class="" membership-id="'+membershipId+'" service-id="'+serviceId+'">'
                     				+'<td ><i class=""></i><span class="td_membership">'+serviceName+'</span></td>'                     
                     				+'<td class="delete col-action text-center"><a href="#"><i class="fa fa-trash"></i></a></td>'
                  			+'</tr>';
                  // console.log(html);
				$(".list_service_membership").append(html);
			} else {
				toastr.warning("You have not selected a membership!");
				return;
			}
		}

		function reset(){
			var membershipActive = $("li.membership").find('a.btn-active');
				membershipActive.parent().trigger('click');				
		}


		$(document).ready(function(){

			$(document).on('click','.membership',function(){				
				$(this).parent().parent().find(".btn-active").removeClass('btn-active');
				$(this).find("a").addClass('btn-active');
				var membershipId = $(this).attr('membership-id');
				getMembershipDetailByMembershipId(membershipId);
			});

			$(document).on('click','li.service',function(e){
				e.preventDefault();
				appendToListService(this);
			});

			$(document).on('click','.delete',function(e){
				e.preventDefault();
				var parent = $(this).parent().remove();	
			});

			$(".reset").on('click',function(e){
				e.preventDefault();
				reset();
				toastr.success('The list has been reset!!')				
			});

			$('.save').on('click',function(e){
				e.preventDefault();		
				var listService = '';	
				var membershipId = $(".membership ").find("a.btn-active").parent().attr('membership-id');
				var price = $("#price").val();
				var percentDiscount = $("#percentDiscount").val();
				var time = $("#time").val();

				$(".list_service_membership tr").each(function(){
					listService += $(this).attr('service-id') +';';					
				});

				if(listService){					
					$.ajax({
						url:"{{ route('saveMembershipDetail') }}",
						method:"post",
						data:{
							_token:"{{csrf_token()}}",
							membershipId,
							price,
							percentDiscount,
							time,
							listService,
						},
						dataType:"json",
						success:function(data){
							if(data.success){
								toastr.success("Membership has been saved successfully!!")
								reset();
							}
						},
						error:function(){
							toastr.error("Failed to save Membership!");
						}
					});	
				}
			});			

			$(".cateservice").on('click',function(e){
				e.preventDefault();
				var cateserviceId = $(this).attr('category-id');
				getServiceByCateServiceId(cateserviceId);
			});

		});
	</script>
@stop