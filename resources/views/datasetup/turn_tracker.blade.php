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

   .btn-active{
   background-color: #ffdf00 !important;
   border: solid 1px #ffdf00 !important;
   }

   .card{
   border-radius: 0px;
   }

   .custom_card_header{
   border-top-left-radius:19px;
   border-top-right-radius:19px;
   padding: 8px;
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

</style>
@stop
@section('content')
   <div id="payservice" class="col-xs-12 col-md-12 no-padding" style="height: 427px;">     
      <div class="col-xs-6 col-md-6 no-padding full-height padding-top-5">
         <div id="list-services" class="height-50p scroll-view border-bottom list-services scroll-style-1">
         	<label  style="padding-left:20px">Category</label>
            <ul class="list-inline col-sm-12 listservices" style="width: 100%;margin: 0px;padding-left: 20px">
            	@foreach ($cateservice as $element)
               	<li class="list-inline-item block-service service " category-id="{{$element->cateservice_id}}" category-name="{{$element->cateservice_name}}"><a href="#">{{$element->cateservice_name}}</a></li>
               @endforeach      
            </ul>
         </div>
         <div id="list-staff" class="height-50p scroll-view list-staff scroll-style-1">
         	<label  style="padding-left:20px">Staff</label>
            <ul class="list-inline col-md-12 col-sm-12 row liststaffs text-center" style="width: 100%;margin: 0px;padding-left: 20px">
               @foreach ($worker as $element)    
               <div class="card staff-div staff" style="border: 2px solid #277a88;width: 24%;height: 140px;border-radius: 20px;padding:0px;margin:1px">
                  <div class="custom_card_header bg-primary" worker-id="{{$element->worker_id}}" worker-name="{{$element->worker_nickname}}">
                     <div class="text-center " style="color:#fff;"><b>{{$element->worker_nickname}}</b></div>
                  </div>
                  <div class="card-body custom_card_body">
                     <img style="width: 85px;height: 80px" src="{{!empty($element->worker_avatar) ? config('app.url_file_view').'/'.$element->worker_avatar : asset('images/user.png')}}" alt="{{$element->nickname}}">                     
                  </div>
               </div>
               @endforeach             
            </ul>
         </div>
      </div>
      <div class="col-xs-6 col-md-6 no-padding full-height padding-top-5">
         <div style="height: 70%" class=" scroll-view border-bottom section-order scroll-style-1">
            <table id="tableOrders" class="table table-striped table-bordered" style="width:100%;">
               <tbody class="list_worker_cateservice">
               {{-- 	@for ($i = 0; $i < 2; $i++)               	
                  <tr class="" id="0">
                     <td ><i class="fa fa-user-circle-o"></i><span class="td_worker">1221</span></td>
                     <td ><i class="fa fa-check-circle-o"></i><span class="td_category">faasdfsad</span></td>
                     <td  class="tr_order text-center select_turn col-12" >
						<button class="btn_custom active_btn_custom">0</button>
						<button class="btn_custom">0.5</button>
						<button class="btn_custom">1</button>
						<button class="btn_custom">1.5</button>
						<button class="btn_custom">2</button>
                     </td>
                     <td class="delete col-action text-center"><a href="#"><i class="fa fa-trash"></i></a></td>
                  </tr>   
                @endfor       --}}         
               </tbody>
            </table>
         </div>       
         <div style="height: 30%" class="text-center scroll-view section-summary scroll-style-1">          
            <div class="action">
               <div>
                  <a class="btn btn-primary save_turn_tracker" href="#">Save</a>               
                  <a class="btn btn-default reset" href="{{ route('turnTracker') }}">Reset</a>    
               </div> <hr>
            </div>
           
            <div class=""><h5>Turn Tracker Option</h5> <br>
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
            </div>
         </div>
      </div>
   </div>
@stop
@section('scripts')
	<script>
		function radioButton(){
			$('input.flat').iCheck({checkboxClass: 'icheckbox_flat-green',radioClass: 'iradio_flat-green'});
		}

		function classActive(param, number){			
			return parseFloat(param) == parseFloat(number) ? "active_btn_custom" : "";
		}

		function getListTurnTracker(){
			$.ajax({
				url:"{{ route('getListTurnTracker') }}",
				method:"get",
				dataType:"json",
				success:function(data){
					if(data.success){
						var html = '';						
						for(var i = 0; i < data.data.length; i++){
							html +='<tr class="" id="'+data.data[i].ws_id+'" worker-id="'+data.data[i].ws_worker_id+'"  cateservice-id="'+data.data[i].ws_cateservice_id+'" turn="'+data.data[i].ws_turn+'">';
			                html +='<td ><i class="fa fa-user-circle-o">&nbsp;</i><span class="td_worker">'+data.data[i].worker_nickname+'</span></td>';
			                html +='<td class="td_max_with"><i class="fa fa-check-circle-o">&nbsp;</i><span class="td_category">'+data.data[i].cateservice_name+'</span></td>';
			                html +='<td  class="tr_order text-center select_turn col-12" >';
							html +='<button class="btn_custom '+classActive(data.data[i].ws_turn,0)+' ">0</button>';
							html +='<button class="btn_custom '+classActive(data.data[i].ws_turn,0.5)+' ">0.5</button>';
							html +='<button class="btn_custom '+classActive(data.data[i].ws_turn,1)+' ">1</button>';
							html +='<button class="btn_custom '+classActive(data.data[i].ws_turn,1.5)+' ">1.5</button>';
							html +='<button class="btn_custom '+classActive(data.data[i].ws_turn,2)+' ">2</button>';
			                html +='</td>';
			                html +='<td class="delete col-action text-center"><a href="#"><i class="fa fa-trash"></i></a></td>';
			                html +='</tr>';
	              		}
	              		// console.log(html);
	                  $(".list_worker_cateservice").html(html);
					}
				}
			});
		}
		
		function appendToListWorkerService(param){
			var cateserviceId = $(param).attr('category-id');
			var cateserviceName = $(param).attr('category-name');
			var workerId = $(param).attr('worker-id');
			var workerName = $(param).attr('worker-name');
			//-- use when check exitst  
			var tempServiceId = cateserviceId;
			var tempWorkerId = workerId

			if(!cateserviceId){
				cateserviceId = '';
				cateserviceName = '';
				tempServiceId = $(".list_worker_cateservice").find("tr.selected").attr("cateservice-id");
			}
			if(!workerId){
				workerId = '';
				workerName = '';
				tempWorkerId = $(".list_worker_cateservice").find("tr.selected").attr("worker-id");
			}
			//exit funtion when this data already exists
			var checkExists = '';
			$(".list_worker_cateservice tr").each(function(){
				var cateservice = $(this).attr("cateservice-id");				
				var worker = $(this).attr("worker-id");		

				// console.log("cate1: "+tempServiceId + "cate2: "+cateservice + " && " + " worker1: "+tempWorkerId + " worker2: "+worker);
				if(parseInt(tempWorkerId) == parseInt(worker) && parseInt(cateservice) == parseInt(tempServiceId)){
					toastr.warning('Turn Tracker already exists!!');
					checkExists = true;
				}
			});
			if(checkExists == true) return;
			
			var checkSelected = $(".list_worker_cateservice").find("tr.selected");

			if(checkSelected.length == 0){
				// new
				var html = '<tr worker-id="'+workerId+'"  cateservice-id="'+cateserviceId+'" turn="0" >'
                     +'<td ><i class="fa fa-user-circle-o">&nbsp;</i><span class="td_worker">'+workerName+'</span></td>'
                     +'<td class="td_max_with"><i class="fa fa-check-circle-o">&nbsp;</i><span class="td_category">'+cateserviceName+'</span></td>'
                     +'<td  class="tr_order text-center select_turn col-12" >'
						+'<button class="btn_custom active_btn_custom">0</button>'
						+'<button class="btn_custom">0.5</button>'
						+'<button class="btn_custom">1</button>'
						+'<button class="btn_custom">1.5</button>'
						+'<button class="btn_custom">2</button>'
                     +'</td>'
                     +'<td class="delete col-action text-center"><a href="#"><i class="fa fa-trash"></i></a></td>'
                  +'</tr>';
                  // console.log(html);
				$(".list_worker_cateservice").append(html);
			} else {
				// update
				if(cateserviceId){					
					checkSelected.children().find(".td_category").text(cateserviceName);
					checkSelected.attr('cateservice-id',cateserviceId);
				} else if(workerId){
					checkSelected.children().find(".td_worker").text(workerName);
					checkSelected.attr('worker-id',workerId);
				}
				
			}
		}

		function getOptionTurnTracker(){
			$.ajax({
				url:"{{ route('getOptionTurnTracker') }}",
				method:"get",
				dataType:"json",
				success:function(data){
					if(data.success){
						$("input.optionTurn[value="+data.data.place_turn_option+"]").attr("checked",true);
						radioButton();
					}
				},
				error:function(){
					toastr.error("Failed to get turn tracker option!!");
				}
			});
		}

		$(document).ready(function(){
			getListTurnTracker();
			getOptionTurnTracker();	

			$(document).on('click','.select_turn button',function(){
				var turn = $(this).text();
				// console.log(turn);	
				$(this).parent().children().removeClass('active_btn_custom');
				$(this).addClass('active_btn_custom');
				$(this).parent().parent().attr('turn',turn);
			});

			$('#tableOrders').on('click','tr',function(){
				var strClass = $(this).attr('class');				
				if(strClass == 'selected'){
					$(this).removeClass('selected');
				} else {
					$(this).parent().children().removeClass('selected');
					$(this).addClass('selected');
				}				
			});

			$("li.service").on('click',function(e){
				e.preventDefault();
				appendToListWorkerService(this);
			});

			$("div.custom_card_header").on('click',function(e){
				e.preventDefault();
				appendToListWorkerService(this);
			});

			$(document).on('click','.delete',function(e){
				e.preventDefault();
				var parent = $(this).parent();
				if(parent.attr('id')){
					if(confirm("Are you sure you want to delete this item?")){
						$.ajax({
							url:"{{ route('deleteTurnTracker') }}",
							method:"post",
							data:{
								__token:"{{csrf_token()}}",
								id:parent.attr('id'),
							},
							dataType:"json",
							success:function(data){
								if(data.success){
									toastr.success('Deleted successfully!!',"Success!!");
									getListTurnTracker();
								}
							}, 
							error:function(){
								toastr.error('Error Delete!!',"Error!!");
							}
						});
					}
				} else {
					parent.remove();
				}
				
			});

			$(".reset").on('click',function(e){
				e.preventDefault();
				getListTurnTracker();
				toastr.success('The list has been reset!!')
			});

			$('.save_turn_tracker').on('click',function(e){
				e.preventDefault();
				var arrUpdate = [];
				var arrCreate = [];			
				var checkRequired = '';	
				$(".list_worker_cateservice tr").each(function(){					
					var id = $(this).attr('id');
					var workerId = $(this).attr('worker-id');
					var cateserviceId = $(this).attr('cateservice-id');
					var turn = $(this).attr('turn');
					if(workerId == '' || cateserviceId == ''){
						toastr.error('You have not set a service or staff!!','Error Save!!',)
						checkRequired = true;
					} else {
						if(id){
							arrUpdate.push({
								id:id,
								workerId:workerId,
								cateserviceId:cateserviceId,
								turn:turn,
							});
						} else {
							arrCreate.push({						
								workerId:workerId,
								cateserviceId:cateserviceId,
								turn:turn,
							});
						}
					}					
				});
				if(checkRequired == true) return;

				if(arrCreate.length > 0 || arrUpdate.length > 0){
					$.ajax({
						url:"{{ route('saveListTurnTracker') }}",
						method:"post",
						data:{
							_token:"{{csrf_token()}}",
							arrCreate,
							arrUpdate,
						},
						dataType:"json",
						success:function(data){
							if(data.success){
								toastr.success("Turn Tracker has been saved successfully!!","Success!!")
								getListTurnTracker();
							} else {
								toastr.error("Failed to save Turn Tracker!!","Error!!");
							}
						},
						error:function(){
							toastr.error("Failed to save Turn Tracker!!","Error!!");
						}
					});	
				}
			});

			$('input.optionTurn').on('ifChanged', function(e) {
			    if(e.target.checked == true){
			    	var valueOptionTurn = e.target.value;
			    	$.ajax({
			    		url:"{{ route('postOptionTurnTracker') }}",
			    		method:"post",
			    		data:{
			    			__token:"{{csrf_token()}}",
			    			valueOptionTurn,
			    		},
			    		dataType:"json",
			    		success:function(data){
			    			if(data.success){
			    				toastr.success("Turn tracker option has been changed successfully!!");
			    			} else {
			    				toastr.error("Failed to change turn tracker option!!");
			    			}
			    		},
			    		error:function(){
			    			toastr.error("Failed to change turn tracker option!!");
			    		}
			    	});
			    }			    
			});
			$(".custom_card_body").on('click',function(){
				var a = $(this).parent().children().first();
				$(a).trigger('click');
			});
		});

	</script>
@stop