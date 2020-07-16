<?php

namespace App\Http\Controllers\DataSetup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PosCateservice;
use App\Models\PosService;
use App\Models\PosMembership;
use App\Models\PosMembershipDetail;

class MembershipController extends Controller
{
    public function index(){
        $data['cateservice'] = PosCateservice::select('cateservice_id','cateservice_name')
                                            ->where('cateservice_place_id',$this->getCurrentPlaceId()) 
                                            ->where('cateservice_status',1)
                                            ->get();
                                            
        $data['membership'] = PosMembership::all();

        return view('datasetup.membership',$data);
    }
    /**
     * Ajax save Membership Detail
     * @param  int $request->membershipId 
     * @param  int $request->membershipDetailId 
     * @param  string $request->listService [ex: 1;2;3;]
     * @return json 
     */
    public function save(Request $request){
    	if($request->membershipId){
    		$arrUpdate = [
    			'membership_detail_listservice'=>$request->listService,
    			'membership_detail_price' => $request->price,
    			'membership_detail_percent_discount' => $request->percentDiscount,
    			'membership_detail_time' => $request->time,
    		];

    		$membershipDetail = PosMembershipDetail::where('membership_detail_membership_id',$request->membershipId)
    							->where('membership_detail_place_id',$this->getCurrentPlaceId())
    							->where('membership_detail_status',1)
    							->first();
    		if($membershipDetail){
    			$membershipDetail->update($arrUpdate);
    		} else {
    			$membershipDetailId = PosMembershipDetail::where('membership_detail_place_id',$this->getCurrentPlaceId())->max('membership_detail_id')+1;

    			$arrUpdate['membership_detail_id'] = $membershipDetailId;
    			$arrUpdate['membership_detail_place_id'] = $this->getCurrentPlaceId();
    			$arrUpdate['membership_detail_membership_id'] = $request->membershipId;
    			// dd($arrUpdate);
    			PosMembershipDetail::create($arrUpdate);
    		}

    		return json_encode(['success'=>true]);
    	}
    }
    /**
     * Call ajax 
     * @param  int 		$request->cateservice_id
     * @return json 	
     */
    public function getServiceByCateServiceId(Request $request){
    	if($request->cateserviceId){
    		$service = PosService::select('service_id','service_name')
    							->where('service_place_id',$this->getCurrentPlaceId())
    							->where('service_cate_id',$request->cateserviceId)
    							->where('service_status',1)
    							->where('enable_status',1)
    							->get();
    		$result = [
    			'success' => true,
    			'service' => $service,
    		];
    		return json_encode($result);
    	}
    }
    /**
     * call ajax
     * @param  int 		$request->membershipId
     * @return json
     */
    public function getMembershipDetailByMembershipId(Request $request){
    	if($request->membershipId){
    		$membershipDetail = PosMembershipDetail::select('membership_detail_id','membership_detail_listservice','membership_detail_price','membership_detail_percent_discount','membership_detail_time')
    											->where('membership_detail_place_id',$this->getCurrentPlaceId())
    											->where('membership_detail_membership_id',$request->membershipId)
    											->where('membership_detail_status',1)
    											->first();

    		if(!$membershipDetail) {
    			// not exist
    			return json_encode(['error' => true]);	
    		}	
    							
    		$listService = explode(';', $membershipDetail->membership_detail_listservice);

    		$service = PosService::select('service_id','service_name')
    								->where('service_place_id',$this->getCurrentPlaceId())
    								->whereIn('service_id',$listService)
    								->where('service_status',1)
    								->where('enable_status',1)
    								->get();
    	
    		$result = [
    			'success' => true,
    			'membership_detail_id' => $membershipDetail->membership_detail_id,
    			'membership_detail_price' => $membershipDetail->membership_detail_price,
    			'membership_detail_percent_discount' => $membershipDetail->membership_detail_percent_discount,
    			'membership_detail_time' => $membershipDetail->membership_detail_time,
    			'service' => $service,
    		];
    		return json_encode($result);
    	}
    }    
   
}
