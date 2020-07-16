<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PosService;

class ServiceController extends Controller
{      
	public function getServices(){
		$services = PosService::select('service_id','service_name','service_duration','service_price','cateservice_id','cateservice_name')
					->where('service_place_id',$this->getPlaceId())
					->join('pos_cateservice',function($joinCateservice){
						$joinCateservice->on('cateservice_id','service_cate_id')
						->on('cateservice_place_id','service_place_id');
					})
					->where('service_status',1)
					->where('enable_status',1)
					->where('cateservice_status',1)
					->get();

		if(count($services) == 0){
			 return response()->json(['status'=>1,'data'=>[]],200);
		}
		
		$arrCateService = [];

		foreach ($services as $value) {
			$arrCateService[] = [
				'cateservice_id' => $value->cateservice_id,
				'cateservice_name' => $value->cateservice_name,
			];
		}
		$arrCateService = array_unique($arrCateService, 0); 
		$arrCateService = array_values($arrCateService);

		foreach ($arrCateService as $keyCateService => $valueCateService) {
			foreach ($services as $valueServices) {
				if( $valueCateService['cateservice_id'] == $valueServices->cateservice_id ){

					$arrCateService[$keyCateService]['services'][] = [
						'servive_id' => $valueServices->service_id,
						'service_name' => $valueServices->service_name, 
						'service_duaration' => $valueServices->service_duration,
						'service_price' => $valueServices->service_price,
					];

				}
			}
		}

		return response()->json(['status'=>1,'data'=>$arrCateService],200);
	}
	/**
	 * get only list services
	 * @return json
	 */
	public function getListServies(){
		$servie = PosService::select('service_id','service_name')
							->where('service_place_id',$this->getPlaceId())
							->where('service_status',1)
							->get();
							
		return response()->json(['status'=>1,'data'=>$servie],200);
	}
}