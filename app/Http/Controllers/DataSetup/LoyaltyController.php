<?php

namespace App\Http\Controllers\DataSetup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PosLoyalty;

class LoyaltyController extends Controller
{
    public function index()
    {	
    	$data['loyalty'] = PosLoyalty::where('loyalty_place_id',$this->getCurrentPlaceId())->first();

        if(isset($data['loyalty']) && !empty($data['loyalty']->loyalty_return_in_a_month)){

            $str = $data['loyalty']->loyalty_return_in_a_month;
            //check $str 
            if($str){
                $arr = explode(';', $str);
                $arr_returnInAMonth = [];
                foreach ($arr as $value) {
                    $arr_returnInAMonth[] = explode('-', $value);
                }
                $data['returnInAMonth'] = $arr_returnInAMonth;
            }         

        }

        if(isset($data['loyalty']) && !empty($data['loyalty']->loyalty_price_to_point)){
            $str = $data['loyalty']->loyalty_price_to_point;
            $arr = explode('-', $str);
            $data['priceToPoint_price'] = $arr[0];
            $data['priceToPoint_point'] = $arr[1];
        }

        if(isset($data['loyalty']) && !empty($data['loyalty']->loyalty_service_to_point)){
            $str = $data['loyalty']->loyalty_service_to_point;
            $arr = explode('-', $str);
            $data['serviceToPoint_service'] = $arr[0];
            $data['serviceToPoint_point'] = $arr[1];
        }

        if(isset($data['loyalty']) && !empty($data['loyalty']->loyalty_point_to_amount)){
            $str = $data['loyalty']->loyalty_point_to_amount;
            $arr = explode('-', $str);
            $data['pointToAmount_point'] = $arr[0];
            $data['pointToAmount_amount'] = $arr[1];
        }
        
    	return view('datasetup.loyalty',$data);
    }

    public function postLoyalty(Request $request){

    	$this->validate($request,[    		
    		'paying_by_cash' => 'required',
    		'referral_gift_card' => 'required',
    		'buying_gift_card' => 'required',
    		'new_customer' => 'required',
    		'vip_customer' => 'required',
    		'for_normal' => 'required',
    		'for_siver' => 'required',
    		'for_golden' => 'required',
    		'for_dimond' => 'required',
    		'vip_point' => 'required',
    	],[

    	]);

    	$times = $request->times;
    	$point = $request->point;
    	$return_in_a_month = '';

    	foreach ($request->times as $key => $value) {
            if($value != ''){
                $return_in_a_month .= $times[$key] .'-'.$point[$key].';';
            }    		
    	}

    	$return_in_a_month = substr($return_in_a_month,0,-1);
    	//ex: $return_in_a_month = "11-22;33-44;55-66;77-88";

    	$arr = [
    		'loyalty_place_id' => $this->getCurrentPlaceId(),
            'loyalty_price_to_point' => $request->priceToPoint_price."-".$request->priceToPoint_point,
            'loyalty_service_to_point' => $request->serviceToPoint_service."-".$request->serviceToPoint_point,
            'loyalty_point_to_amount' => $request->pointToAmount_point."-".$request->pointToAmount_amount,
    		'loyalty_paying_by_cash' => $request->paying_by_cash,
    		'loyalty_return_in_a_month' => $return_in_a_month,
    		'loyalty_referral_gift_card' => $request->referral_gift_card,
    		'loyalty_buying_gift_card' => $request->buying_gift_card,
    		'loyalty_new_customer' => $request->new_customer,
    		'loyalty_vip_customer' => $request->vip_customer,
    		'loyalty_for_normal' => $request->for_normal,
    		'loyalty_for_siver' => $request->for_siver,
    		'loyalty_for_golden' => $request->for_golden,
    		'loyalty_for_dimond' => $request->for_dimond,
    		'loyalty_vip_point' => $request->vip_point,
    	];
    	
    	$loyalty = PosLoyalty::where('loyalty_place_id',$this->getCurrentPlaceId())->first();
    	//check $loyalty
    	if($loyalty){
            // dd($arr);
    		$loyalty->update($arr);
    	} else {
    		PosLoyalty::insert($arr);
    	}    	

    	return back()->with('message','Update Loyalty Success!');
    }
}
