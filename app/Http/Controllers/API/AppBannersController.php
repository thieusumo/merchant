<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainAppBanners;
use Auth;

class AppBannersController extends Controller
{
	/**
	 * get app banners by appId
	 * @param  $request->appId
	 * @return json
	 */
	public function getAppBannersByAppId(Request $request){
		if($request->appId){
			$app = MainAppBanners::where('app_id',$request->appId)->get();

			return response()->json(['status'=>1,'data'=>$app],200);
		}
	}

}