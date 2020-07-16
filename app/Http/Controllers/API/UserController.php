<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosUser;
use Auth;

class UserController extends Controller
{
	/**
	 * add device token one signal
	 * @param string $request->userId
	 */
	public function addDeviceToken(Request $request){
		return $this->saveDeviceToken($request->userId);
	}

	public function saveDeviceToken($deviceToken){
		if($deviceToken){
			$user = PosUser::where('user_place_id',$this->getPlaceId())
					->where('user_id',Auth::user()->user_id)
					->first();
			$checkDeviceToken = strpos($user->web_device_token,$deviceToken);

			if(!$checkDeviceToken){
				$user->web_device_token = $user->web_device_token.";".$deviceToken;
				$user->save();
			}

		return response()->json(['status'=>'1','msg'=>'success']);
		}
	}
}