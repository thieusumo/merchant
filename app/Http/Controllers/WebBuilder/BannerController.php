<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use App\Models\PosBanner;
use App\Models\PosPlace;
use Validator;
use Session;

class BannerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	$list_banner = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())->get();
        return view('webbuilder.banners',compact('list_banner'));
    }
    
    public function getBanner(Request $request)
    {
    	$ba_item = PosBanner::join('pos_user',function($join){
    		$join->on('pos_banner.created_by','=','pos_user.user_id')->on('pos_banner.ba_place_id','=','pos_user.user_place_id');
    	})
    	->where('pos_banner.ba_place_id',$this->getCurrentPlaceId())
        ->where('ba_status',1)
        ->select('pos_banner.*',"pos_user.user_id","pos_user.user_place_id","pos_user.user_places_id","pos_user.user_default_place_id","pos_user.user_usergroup_id","pos_user.user_main_customer_id","pos_user.user_permission","pos_user.user_nickname","pos_user.user_phone","pos_user.user_email","pos_user.user_password","pos_user.user_fullname","pos_user.user_avatar","pos_user.user_status","pos_user.user_token","pos_user.remember_token","pos_user.created_at","pos_user.updated_at","pos_user.created_by","pos_user.updated_by","pos_user.updated_at","pos_user.user_login_time")
        ->get();
        // dd($ba_item);

    	return Datatables::of($ba_item)
    			->editColumn('ba_name',function($row){
    				return "<a href='".route('banner',$row->ba_id)."'>".$row->ba_name."</a>";
    			})
    			->editColumn('ba_image',function($row){
                    if(!empty($row->ba_image))
    				    return "<img src=".config('app.url_file_view').$row->ba_image." width =100px alt=''>  ";
                    else
                        return "";
    			})
    			->addColumn('enable_status',function($row){
    				$checked= "";
                    if ($row->enable_status==1) {
                        $checked = 'checked';
                    }
    				return "<input type='checkbox' id='".$row->ba_id."' class='js-switch' ".$checked." />";
    			})
    			->editColumn('updated_at',function($row){
    				return format_datetime($row->updated_at)." by ".$row->user_nickname;
    			})
    			->addColumn('action', function($row){
                return '<a href="'.route('banner',$row->ba_id).'"  class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>
                        <a href="#" class="delete-banner btn btn-sm btn-secondary" id="'.$row->ba_id.'"><i class="fa fa-trash-o"></i></a>';
            	})
    			->rawColumns(['ba_name','ba_image','enable_status','action','ba_descript'])
    			->make(true);
    }  

    public function changeBannerStatus(Request $request)
    {
    	$checked = $request->checked;

        $id = $request->id;
        // return $ba_id;
        $enable_status = 0;
        if($checked == "checked"){
            $enable_status = 1;
        }
        // return $enable_status;
     	PosBanner::where('ba_place_id',$this->getCurrentPlaceId())
                ->where('ba_id',$id)
                ->update(['enable_status'=>$enable_status]);

        return "Update Status Success!";
    }
    
     public function edit(Request $request, $id=0) {
     	$list_banner = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())->get();
     		if ($id>0) {
     			$ba_item = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())
     								->where('ba_id',$id)
     								->first();
     	$banner_date = format_date($ba_item->banner_date);
     			return view('webbuilder.banner_edit',compact('list_banner','ba_item','id','banner_date'));
     		}else{
     			return view('webbuilder.banner_edit',compact('list_banner','id'));
     		}
    }

    public function postBanner(Request $request)
    {
    	$ba_id = $request->ba_id;
    	$ba_name = $request->ba_name;
    	// if($ba_id>0){// CHECK EXIST WHEN EDIT
    	// 	$check_exist = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())
    	// 							->where('ba_id','!=',$ba_id)
    	// 							->where('ba_name',$ba_name)
    	// 							->where('ba_status',1)
    	// 							->count();
    	// } else{
    	// 	$check_exist = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())
    	// 							->where('ba_name',$ba_name)
    	// 							->where('ba_status',1)
    	// 							->count();
    	// }
    	$rules = [
    		'ba_name'		=> 'required',
            'ba_image'      => 'mimes:jpeg,jpg,png,gif|max:1024'
    	];
    	$messages = [
    		'ba_name.required'		=> 'Please enter Banner Name',
    		// 'ba_descript.required'	=> 'Please enter Description',
            'ba_image.mimes'        => 'Image must be .jpeg,.jpg,.png,.gif',
            'ba_image.max'          => 'The Maximun image 1M' 
    	];
    	$validator = Validator::make($request->all(),$rules,$messages);
    	// if ($check_exist>0) {
    	// 	// $validator->after(function($validator){
    	// 	// 	$validator->error()->add('ba_name.exists','This banner name already exists.Please enter another name');
    	// 	// });
     //        $request->session()->flash('error','This banner name already exists.Please enter another name');
     //        return redirect()->back();

    	// }
    	if ($validator->fails()) {
    		return redirect()->back()->withErrors($validator)->withInput();
    	}else{
            if ($request->hasFile('ba_image')) {
                $ipPlaceLicense = Session::get('place_ip_license');
                $images = \App\Helpers\ImagesHelper::uploadImage($request->file('ba_image'),"banner",$ipPlaceLicense);
               /* dd($request->file('ba_image'));*/
            }else{
                $images = $request->ba_image_old;
            }

    		$list_banner = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())->get();
    		if ($ba_id>0) {//UPDATE BANNER
                
                //dd($request->ipPlaceLicense);
    			 $PosBanner = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())
    									->where('ba_id',$ba_id)
    									->update([
    										'ba_name'		=> $request->ba_name,
    										'ba_index'		=> $request->ba_index,
    										'ba_descript'	=> $request->ba_descript,
                                            'ba_image'      => $images,
                                            'enable_status' => 1,	
    									]);

    			if ($PosBanner) {
    				$request->session()->flash('message','Edit Banner Success');
    			}else{
    				$request->session()->flash('error','Edit Banner Error');
    			}
    			// return view('webbuilder.banners',compact('list_banner'));
                return redirect()->route('banners');
    		}else{ // ADD BANNER
    			$idPosBanner = PosBanner::where('ba_place_id','=',$this->getCurrentPlaceId())->max('ba_id')+1;
                $ipPlaceLicense = PosPlace::where('place_ip_license',$this->getCurrentPlaceId())->get();
                //dd($ipPlaceLicense);
    			$PosBanner = new PosBanner;
    					$PosBanner->ba_id 			= $idPosBanner;
    					$PosBanner->ba_place_id		= $this->getCurrentPlaceId();
    					$PosBanner->ba_name 		= $request->ba_name;
    					$PosBanner->ba_index 		= $request->ba_index;
    					$PosBanner->ba_descript 	= $request->ba_descript;
    					$PosBanner->ba_image		= $images;
                        $PosBanner->enable_status   = 1;
    					$PosBanner->ba_status		= 1;
    					$PosBanner->save();
                
    			if ($PosBanner) {
    				$request->session()->flash('message','Insert Banner Success');
    			}else{
    				$request->session()->flash('error','Insert Banner Error');
    			}
    			// return view('webbuilder.banners',compact('list_banner'));
                return redirect()->route('banners');
    		}
    	}
    }

    public function destroyBanner(Request $request)
    {
    	$banner = PosBanner::where('ba_place_id',$this->getCurrentPlaceId())
    						->where('ba_id',$request->id)
    						->update(['ba_status'=>0]);
    		if ($banner) {
    			return "Delete Banner Success";
    		}else{
    			return "Delete Banner Error";
    		}
    }




}
