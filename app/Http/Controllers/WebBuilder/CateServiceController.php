<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use Session;
use App\Models\PosCateservice;
use App\Models\PosPlace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CateServiceController extends Controller
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

        return view('webbuilder.cateservices');
    }
    
    public function edit(Request $request,$id=0) {
        if($id>0){
            $cateservice_item = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                ->where('cateservice_id',$id)
                                ->first();
            $cateservice_date = format_date($cateservice_item->cateservice_date);
            return view('webbuilder.cateservice_edit',compact('cateservice_item','id','cateservice_date'));
        } else {
            return view('webbuilder.cateservice_edit',compact('id'));
        }

    }

    public function getCateServices(Request $request)
    {
      $cateservice_item = PosCateservice::leftJoin('pos_user',function($join){
                        $join->on('pos_cateservice.created_by','=','pos_user.user_id')
                        ->on('pos_cateservice.cateservice_place_id','=','pos_user.user_place_id');
                    })
                    ->where('pos_cateservice.cateservice_place_id', $this->getCurrentPlaceId())
                    ->where('pos_cateservice.cateservice_status',1)
                    ->get();

        return Datatables::of($cateservice_item)
            ->editColumn('cateservice_name',function($row){
                return  "<a href='".route('cateservice',$row->cateservice_id)."'>".$row->cateservice_name." </a>";
            })
            ->editColumn('cateservice_description',function($row){
                $result=substr($row->cateservice_description,0,20);
                $dot="";
                if(strlen($row->cateservice_description)>20)
                {
                    $dot="...";
                }
                return $result."".$dot;
            })
            ->editColumn('cateservice_image',function($row){
                if(!empty($row->cateservice_image))
                return  "<img src=".config('app.url_file_view').$row->cateservice_image." width='100px' alt=''>  ";
                else
                    return "";
            })
            ->editColumn('updated_at',function($row){
                return  format_datetime($row->updated_at)." by ".$row->user_nickname; 
            })
             ->addColumn('action', function($row){
                return '<a href="'.route('cateservice',$row->cateservice_id).'" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>
                        <a href="#" class="delete-cateservice btn btn-sm btn-secondary" id="'.$row->cateservice_id.'"><i class="fa fa-trash-o"></i></a>';
            })
            ->rawColumns(['cateservice_name','cateservice_image' ,'action','cateservice_description'])
            ->make(true);
    }
    public function deleteCateService(Request $request)
    {
        $cateservice = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                        ->where('cateservice_id',$request->id)
                                        ->update([ 'cateservice_status'=> 0 ]);

        if($cateservice){
            return "Delete cateservice success";
        } else {
            return "Delete cateservice error";
        } 
            
    }  
    public function saveCateService(Request $request)
    {
        $cateservice_id = $request->cateservice_id;
        $cateservice_name = $request->cateservice_name;
        $image_path ="";
        
        /*if($cateservice_id >0){ // CHECK EXIST WHEN EDIT
            $check_exist = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                    ->where('cateservice_id','!=',$cateservice_id)
                                    ->where('cateservice_name',$cateservice_name)
                                    ->where('cateservice_status',1)
                                    ->count();
        }else{
            $check_exist = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                    ->where('cateservice_name',$cateservice_name)
                                    ->where('cateservice_status',1)
                                    ->count();
                                    // dd($check_exist);
        }*/
          $rules = [
                'cateservice_name' => 'required',
                'cateservice_image' => 'mimes:jpeg,jpg,png,gif|max:1024', // max 3000kb
                'cateservice_icon_image' => 'mimes:jpeg,jpg,png,gif|max:1024', // max 3000kb
                // 'cateservice_description' => 'required'
          ];
          $messages = [
            'cateservice_name.required' => "Please enter Full name",
            'cateservice_image.mimes' => 'Uploaded image is not in image format',
            'cateservice_image.max' => 'max size image 1Mb',
            // 'cateservice_description.required' => 'Please enter Description'
          ];
        $validator = Validator::make($request->all(), $rules, $messages);
        
        /*if($check_exist>0){ // PUSH ERROR WHEN EXIST CATESERVICE NAME
            $validator->after(function ($validator) {
                $validator->errors()->add('cateservice_name.exists', 'This name already exists.Please enter another name');
            });
        }*/
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if($request->hasFile('cateservice_image')){
                //insert image 
                $image_path= \App\Helpers\ImagesHelper::uploadImage($request->file('cateservice_image'),"cateservice",Session::get('place_ip_license'));

            }else{$image_path = $request->cateservice_image_old; }

            if($request->hasFile('cateservice_icon_image')){
                //insert image 
                $icon_image_path= \App\Helpers\ImagesHelper::uploadImage($request->file('cateservice_icon_image'),"cateservice",Session::get('place_ip_license'));

            }else{$icon_image_path = $request->cateservice_image_old; }

            $list_cateservice = PosCateservice::where('cateservice_place_id', $this->getCurrentPlaceId())->get();
            if($cateservice_id >0){
                //UPDATE CATESERVICE
                $PosCateservice = PosCateservice::where('cateservice_place_id','=',$this->getCurrentPlaceId())
                            ->where('cateservice_id',$cateservice_id)
                            ->update(['cateservice_name'=>$request->cateservice_name ,
                                    'cateservice_index'=>$request->cateservice_index?$request->cateservice_index:0,
                                    'cateservice_image'=>$image_path,
                                    'cateservice_icon_image'=>$icon_image_path,
                                    'cateservice_description'=>$request->cateservice_description?$request->cateservice_description:"",
                                ]);
                if($PosCateservice){
                    $request->session()->flash('message', 'Edit CateService Success!');
                }else{
                    $request->session()->flash('error', 'Edit CateService Error!');
                }   
                // return view('webbuilder.cateservices',compact('list_cateservice'));
                return redirect()->route("cateservices");
            }else{
                //CREATE CATESERVICE
                $idCateService = PosCateservice::where('cateservice_place_id','=',$this->getCurrentPlaceId())->max('cateservice_id') +1;
                $PosCateservice = new PosCateservice ;
                                $PosCateservice->cateservice_id = $idCateService;
                                $PosCateservice->cateservice_place_id = $this->getCurrentPlaceId();
                                $PosCateservice->cateservice_name = $request->cateservice_name;
                                $PosCateservice->cateservice_index = $request->cateservice_index?$request->cateservice_index:0;
                                $PosCateservice->cateservice_image = $image_path;
                                $PosCateservice->cateservice_icon_image = $icon_image_path;
                                $PosCateservice->cateservice_description = $request->cateservice_description?$request->cateservice_description:"";
                                $PosCateservice->cateservice_status = 1;
                                $PosCateservice->save();
                    if($PosCateservice){
                                $request->session()->flash('message', 'Insert CateService Success!');
                    } else {
                                $request->session()->flash('error', 'Insert CateService Error!');
                    }
                    
                    // return view('webbuilder.cateservices',compact('list_cateservice'));
                    return redirect()->route("cateservices");
            }
        }          
    }

}
