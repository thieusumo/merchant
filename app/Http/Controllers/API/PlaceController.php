<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PosPlace;

class PlaceController extends Controller
{        
	public function getActionDate(){
		$place = PosPlace::select('place_actiondate')
						->where('place_id',$this->getPlaceId())
						->first();

		$place_actiondate = json_decode($place->place_actiondate,true);

		$arr = [
			'mon' => $place_actiondate['mon'],
			'tue' => $place_actiondate['tue'],
			'wed' => $place_actiondate['wed'],
			'thu' => $place_actiondate['thur'],
			'fri' => $place_actiondate['fri'],
			'sat' => $place_actiondate['sat'],
			'sun' => $place_actiondate['sun'],
		];

		return response()->json(['status'=>1,'data'=>$arr],200);
	}
}