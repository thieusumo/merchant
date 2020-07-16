<?php

namespace App\Http\Controllers\DataSetup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosCateservice;
use yajra\Datatables\Datatables;
use App\Models\PosService;
use App\Models\PosPackage;
use App\Models\PosPackageDetail;
use App\Models\PosBeverage;
use App\Models\PosSupplyNail;
use App\Models\PosSupply;
use App\Helpers\ImagesHelper;
use App\Models\PosPlace;
use DB;
use Validator;


class ServiceController extends Controller
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
        $cateservice_list = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())->get();

        return view('datasetup.services',compact('cateservice_list'));
    }
    public function getService(Request $request)
    {
        $search_cate = $request->search_cate;
        $search_cate_parent = $request->search_cate_parent;

        $service_list = PosService::join('pos_cateservice',function($join){
                                     $join->on('pos_service.service_place_id','pos_cateservice.cateservice_place_id')
                                          ->on("pos_service.service_cate_id","=","pos_cateservice.cateservice_id");
                                     })
                                     ->join("pos_user",function($join1){

                                    $join1->on("pos_service.updated_by","=","pos_user.user_id")
                                          ->on("pos_service.service_place_id","=","pos_user.user_place_id");
                                    })
                                    ->where('pos_service.service_place_id', $this->getCurrentPlaceId())
                                    ->where('service_status',1);
        if($search_cate > 0){

            $service_list->where('pos_service.service_cate_id',$search_cate);
        }
        $service_list->select('pos_service.*' ,'pos_cateservice.cateservice_name','pos_cateservice.cateservice_index','pos_user.user_nickname')
            ->get();

        return Datatables::of($service_list)

            ->editColumn('updated_at',function($row){
               return format_datetime($row->updated_at)." by ".$row->user_nickname;
            })
            ->addColumn('action', function($row){
                return " <a href='".route('data-setup')."' class='edit-service btn btn-sm btn-secondary' ><i class='fa fa-pencil fa-lg'></i> </a> <a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-service' id='".$row->service_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getCombo()
    {
        $package_list = PosPackage::join('pos_user',function($join){
                                       $join->on('pos_package.package_place_id','pos_user.user_place_id')
                                       ->on("pos_package.updated_by","=","pos_user.user_id");
                                    })
                                     ->where('pos_package.package_place_id',$this->getCurrentPlaceId())
                                     ->select('pos_package.*','pos_user.user_nickname')
                                     ->where('package_status',1);

        return Datatables::of($package_list)

        ->editColumn('updated_at',function($row){

               return format_datetime($row->updated_at)." by ".$row->user_nickname;
            })
            ->addColumn('action', function($row){

                return " <a href='' class='edit-combo btn btn-sm btn-secondary' ><i class='fa fa-pencil fa-lg'></i> </a> <a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-combo' id='".$row->package_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
            })

            ->rawColumns(['action'])
            ->make(true);
    }
    public function deleteCombo(Request $request)
    {
        PosPackage::where('package_place_id',$this->getCurrentPlaceId())
                    ->where('package_id',$request->id)
                    ->update(['package_status'=>0]);
    }
    public function getDrink(Request $request)
    {
        $beverage_list = PosBeverage::join('pos_user',function($join){
                                       $join->on('pos_beverage.beverage_place_id','pos_user.user_place_id')
                                       ->on("pos_beverage.updated_by","=","pos_user.user_id");
                                    })
                                     ->where('pos_beverage.beverage_place_id',$this->getCurrentPlaceId())
                                     ->select('pos_beverage.*','pos_user.user_nickname')
                                     ->where('beverage_status',1);

        return Datatables::of($beverage_list)

        ->editColumn('updated_at',function($row){

               return format_datetime($row->updated_at)." by ".$row->user_nickname;
            })
            ->addColumn('action', function($row){

                return " <a href='".route('data-setup')."' class='edit-drink btn btn-sm btn-secondary' ><i class='fa fa-pencil fa-lg'></i> </a> <a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-drink' id='".$row->beverage_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function deleteDrink(Request $request)
    {
        PosBeverage::where('beverage_place_id',$this->getCurrentPlaceId())
                    ->where('beverage_id',$request->id)
                    ->update(['beverage_status'=>0]);
    }
    public function getProduct()
    {
        $product_list = PosSupplyNail::join('pos_user',function($join){

                                       $join->on('pos_supply_nail.sn_place_id','pos_user.user_place_id')
                                       ->on("pos_supply_nail.updated_by","=","pos_user.user_id");
                                    })
                                     ->where('pos_supply_nail.sn_place_id',$this->getCurrentPlaceId())
                                     ->select('pos_supply_nail.*','pos_user.user_nickname')
                                     ->where('sn_status',1);

        return Datatables::of($product_list)

            ->editColumn('updated_at',function($row){

               return format_datetime($row->updated_at)." by ".$row->user_nickname;
            })
            ->addColumn('action', function($row){

                return " <a href='".route('data-setup')."' class='edit-drink btn btn-sm btn-secondary' ><i class='fa fa-pencil fa-lg'></i> </a> <a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-product' id='".$row->sn_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function deleteProduct(Request $request)
    {
        PosSupplyNail::where('sn_place_id',$this->getCurrentPlaceId())
                    ->where('sn_id',$request->id)
                    ->update(['sn_status'=>0]);
    }

    public function setup(){

        $cateservice_list = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                            ->where('cateservice_status',1)->get();

        $service_list = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                    ->where('service_status',1)->get();

        $supply_list = PosSupply::where('supply_place_id',$this->getCurrentPlaceId())
                                ->where('supply_status',1)->get();

        $product_list = PosSupplyNail::join('pos_supply',function($join){
                                       $join->on('pos_supply_nail.sn_place_id','pos_supply.supply_place_id')
                                       ->on('pos_supply_nail.sn_supply_id','pos_supply.supply_id');       
                                       })
                                       ->where('pos_supply_nail.sn_place_id',$this->getCurrentPlaceId())
                                       ->where('sn_status',1)->get();

        $combo_list = PosPackage::where('package_place_id',$this->getCurrentPlaceId())
                                ->where('package_status',1)->get();

        $combo_detail_list = PosPackageDetail::where('packagedetail_place_id',$this->getCurrentPlaceId())
                                ->where('packagedetail_status',1)->get();

        $drink_list = PosBeverage::where('beverage_place_id',$this->getCurrentPlaceId())
                                   ->where('beverage_status',1)
                                   ->get();

         return view('datasetup.service_setup',compact('cateservice_list','service_list','product_list','supply_list','combo_list','combo_detail_list','drink_list'));
    }

    public function saveCateService(Request $request)
    {
        $cateservice_id = $request->cateservice_id;
        $cateservice_name = $request->cateservice_name;
        
        if($cateservice_id >0){ // CHECK EXIST WHEN EDIT
            $check_exist = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                    ->where('cateservice_id','!=',$cateservice_id)
                                    ->where('cateservice_name',"'".$cateservice_name."'")
                                    ->where('cateservice_status',1)
                                    ->count();
        }else{
            $check_exist = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                    ->where('cateservice_name',"'".$cateservice_name."'")
                                    ->where('cateservice_status',1)
                                    ->count();
        }
          $rules = [
                'cateservice_name' => 'required',
                'cateservice_index' => 'required',
          ];
          $messages = [
            'cateservice_name.required' => "Please enter Full name",
            'cateservice_index.required' => 'Please enter Index',
          ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($check_exist>0){ // PUSH ERROR WHEN EXIST CATESERVICE NAME
            $validator->after(function ($validator) {
                $validator->errors()->add('cateservice_name.exists', 'Cate Service already exist, Please check again!');
            });
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            $list_cateservice = PosCateservice::where('cateservice_place_id', $this->getCurrentPlaceId())->get();
            if($cateservice_id >0){
                //UPDATE CATESERVICE
                $PosCateservice = PosCateservice::where('cateservice_place_id','=',$this->getCurrentPlaceId())
                            ->where('cateservice_id',$cateservice_id)
                            ->update(['cateservice_name'=>$request->cateservice_name ,
                                    'cateservice_index'=>$request->cateservice_index,
                                ]);
                if($PosCateservice){
                    $request->session()->flash('message','Edit Cate Success');
                }else{
                    $request->session()->flash('message','Edit Cate Error');
                }
                return back();
            }else{
                //CREATE CATESERVICE
                $idCateService = PosCateservice::where('cateservice_place_id','=',$this->getCurrentPlaceId())->max('cateservice_id') +1;

                $PosCateservice = new PosCateservice ;
                                $PosCateservice->cateservice_id = $idCateService;
                                $PosCateservice->cateservice_place_id = $this->getCurrentPlaceId();
                                $PosCateservice->cateservice_name = $request->cateservice_name;
                                $PosCateservice->cateservice_index = $request->cateservice_index;
                                $PosCateservice->cateservice_status = 1;
                                $PosCateservice->save();

                    if($PosCateservice){

                        $request->session()->flash('message','Insert Cate Success');
                    } else {
                        $request->session()->flash('message','Insert Cate Error');
                    }
                    return back();
            }
        }          
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
    public function addProduct(Request $request)
    {
        //dd($request->all());
        $sn_id = $request->sn_id;
        
        if($sn_id >0){ // CHECK EXIST WHEN EDIT
            $check_exist = PosSupplyNail::where('sn_place_id',$this->getCurrentPlaceId())
                                        ->where('sn_name', $request->sn_name)
                                        ->where('sn_supply_id', $request->sn_supply_id)
                                        ->where('sn_id','!=',$sn_id)
                                        ->first();
        }else //CHECK EXIST WHEN ADD NEW
        {
            $check_exist = PosSupplyNail::where('sn_place_id',$this->getCurrentPlaceId())
                                        ->where('sn_name', $request->sn_name)
                                        ->where('sn_supply_id', $request->sn_supply_id)
                                        ->first();
        }
        
        $rules = [
            'sn_name' => 'required',
            'sn_capacity' => 'required',
            'sn_unit' => 'required',
            'sn_datetime' => 'required',
            'sn_dateexpired' => 'required',
            'sn_image'=>'mimes:jpeg,jpg,png,gif|max:2000'
        ];
        $messages = [
            'sn_name.required' => "Please enter product name",
            'sn_capacity.required' => 'Please enter capacity',
            'sn_unit.required' => 'Please enter unit product',
            'sn_datetime.required' => 'Please enter date start',
            'sn_dateexpired.required' => 'Please enter date expire',
            'sn_image.mimes' => 'Please choose an image',
            'sn_image.max' => 'image limited 2MB'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if(isset($check_exist)){ // PUSH ERROR WHEN EXIST PHONE NUMBER
            $validator->after(function ($validator) {
                $validator->errors()->add('sn_name.exists', 'Product already exist, Please check again!');
            });
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        else
        {
            //CHECK IS EDIT
            $supply_list = PosSupply::where('supply_place_id', $this->getCurrentPlaceId())->get();
            if($sn_id >0){
                $pos_supply_nail = PosSupplyNail::where('sn_place_id','=',$this->getCurrentPlaceId())
                            ->where('sn_id',$sn_id)
                            ->update(['sn_name'=>$request->sn_name ,
                                    'sn_price'=>$request->sn_price,
                                    'sn_quantity'=>$request->sn_quantity,
                                    'sn_capacity'=>$request->sn_capacity,
                                    'sn_sale_tax'=>$request->sn_sale_tax,
                                    'sn_unit'=>$request->sn_unit,
                                    'sn_discount'=>$request->sn_discount,
                                    'sn_bonus'=>$request->sn_bonus,
                                    'sn_point'=>$request->sn_point,
                                    'sn_image'=>($request->sn_image_input!='')?ImagesHelper::uploadImage($request->sn_image_input,'product',$this->getCurrentPlaceIpLicense()):$request->sn_image_hidden,
                                    'sn_datetime'=>format_date_db($request->sn_datetime),
                                    'sn_dateexpired'=>format_date_db($request->sn_dateexpired),
                                ]);
                $pos_supply = PosSupply::where('supply_place_id',$this->getCurrentPlaceId())
                                         ->where('supply_id',$request->supply_id)
                                         ->update(['supply_name'=>$request->supply_name]);
                if($pos_supply_nail && $pos_supply)
                        $request->session()->flash('message', 'Edit Product Success!');
                else    $request->session()->flash('message', 'Edit Product Error!');
                return back();

            }else //IS ADD NEW
            {
                $sn_supply_id = PosSupply::where('supply_place_id','=',$this->getCurrentPlaceId())->max('supply_id') +1;
                $pos_supply_nail= new PosSupplyNail ;
                        $pos_supply_nail->sn_name = $request->sn_name;
                        $pos_supply_nail->sn_place_id = $this->getCurrentPlaceId();
                        $pos_supply_nail->sn_price = $request->sn_price;
                        $pos_supply_nail->sn_quantity = $request->customer_fullname;
                        $pos_supply_nail->sn_capacity = $request->sn_capacity;
                        $pos_supply_nail->sn_sale_tax = $request->sn_sale_tax;
                        $pos_supply_nail->sn_unit = $request->sn_unit;
                        $pos_supply_nail->sn_discount = $request->sn_discount;
                        $pos_supply_nail->sn_bonus = $request->sn_bonus;
                        $pos_supply_nail->sn_point = $request->sn_point;
                        $pos_supply_nail->sn_image=($request->sn_image!='')?(ImagesHelper::uploadImage($request->image)):$request->sn_image_hidden;
                        $pos_supply_nail->sn_datetime = format_date_db($request->sn_datetime);
                        $pos_supply_nail->sn_dateexpired = format_date_db($request->sn_dateexpired);
                        $pos_supply_nail->sn_supply_id = $sn_supply_id;
                        $pos_supply_nail->sn_status = 1;
                        $pos_supply_nail->save();
                if($pos_supply_nail)
                        $request->session()->flash('message', 'Insert Product Success!');
                else    $request->session()->flash('message', 'Edit Product Error!');
                
                return back();
            }
        }   
    }
    public function getComboDetail(Request $request)
    {
        $combo = PosPackage::where('package_place_id',$this->getCurrentPlaceId())
                    ->where('package_id',$request->id)
                    ->first();

        $combo_detail_list = PosPackageDetail::where('packagedetail_place_id',$this->getCurrentPlaceId())
                                               ->where('packagedetail_status',1)
                                               ->get();
        $combo_array = explode(';',$combo->package_listservice_id);

        $packagedetail_body = [];

        $packagedetail = [];

            foreach($combo_array as  $key => $value)
                {
                    $combo_detail_collection = collect($combo_detail_list);

                    $combo_detail = $combo_detail_collection->where('packagedetail_id',$value);

                    foreach($combo_detail as $detail)
                    {
                        $packagedetail[] = $detail->packagedetail_name;
                        $packagedetail[] = $detail->packagedetail_price;
                        $packagedetail[] = $detail->packagedetail_duration;
                        $packagedetail[] = $detail->packagedetail_price_hold;
                        $packagedetail[] = $detail->packagedetail_id;
                    }
                    $packagedetail_body[] = $packagedetail;
                    unset($packagedetail);
                }
        return $packagedetail_body;
    }
    
    public function saveCombo(Request $request)
    {
        $package_id = $request->package_id;
        if(!($request->packagedetail_name)||!($request->packagedetail_price)||!($request->packagedetail_hold)||!($request->packagedetail_duration))
        {
            $request->session()->flash('message', 'Items nots empty!');
            return back();
        }
        $packagedetail_id_array = [];

        foreach($request->packagedetail_id as $key => $detail)
        {
            $packagedetail_id_array[] =  $detail;
        }
        $package_listservice_id = implode(";", $packagedetail_id_array);

        if($package_id >0){ // CHECK EXIST WHEN EDIT
            $check_exist = PosPackage::where('package_place_id',$this->getCurrentPlaceId())
                                        ->where('package_id','!=',$package_id)
                                        ->where('package_name',$request->package_name)
                                        ->first();
        }else //CHECK EXIST WHEN ADD NEW
        {
            $check_exist = PosPackage::where('package_place_id',$this->getCurrentPlaceId())
                                        ->where('package_name', $request->package_name)
                                        ->orWhere('package_listservice_id',$package_listservice_id)
                                        ->first();
        }
        $rules = [
            'package_name' => 'required',
        ];
        $messages = [
            'package_name.required' => "Please enter combo name",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if(isset($check_exist)){ 
        // PUSH ERROR WHEN EXIST PHONE NUMBER
            $validator->after(function ($validator) {
                $validator->errors()->add('package_name.exists', 'Name this Combo already exist, Please check again!');
            });
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        else
        {
            //CHECK IS EDIT
            //$supply_list = PosSupply::where('supply_place_id', $this->getCurrentPlaceId())->get();
            foreach($request->packagedetail_id as $key => $detail_id)
                {
                    $packagedetail = PosPackageDetail::where('packagedetail_place_id',$this->getCurrentPlaceId())
                                      ->where('packagedetail_id',$detail_id);

                    if($packagedetail->count()==1)
                    {
                        $pos_detailpackage = $packagedetail->update([
                                        "packagedetail_name"=>$request->packagedetail_name[$key],
                                        "packagedetail_price"=>$request->packagedetail_price[$key],
                                        "packagedetail_duration"=>$request->packagedetail_duration[$key],
                                        "packagedetail_price_hold"=>$request->packagedetail_price_hold[$key],
                                      ]);
                    }
                    else
                    {
                        $packagedetail_id = PosPackageDetail::where('packagedetail_place_id',$this->getCurrentPlaceId())->max('packagedetail_id')+1;

                        $pos_detailpackage = new PosPackageDetail;
                        $pos_detailpackage->packagedetail_id = $packagedetail_id;
                        $pos_detailpackage->packagedetail_name = $request->packagedetail_name[$key];
                        $pos_detailpackage->packagedetail_price = $request->packagedetail_price[$key];
                        $pos_detailpackage->packagedetail_duration = $request->packagedetail_duration[$key];
                        $pos_detailpackage->packagedetail_price_hold = $request->packagedetail_hold[$key];
                        $pos_detailpackage->packagedetail_place_id = $this->getCurrentPlaceId();
                        $pos_detailpackage->packagedetail_status = 1;
                        $pos_detailpackage->save();

                        $packagedetail_id_array[] = $packagedetail_id;
                    }
                }
                
                    foreach (array_keys($packagedetail_id_array, '0') as $key) {
                        unset($packagedetail_id_array[$key]);
                    }
                    $package_listservice_insert = implode(";", $packagedetail_id_array);

            if($package_id >0){
                $pos_package = PosPackage::where('package_place_id','=',$this->getCurrentPlaceId())

                            ->where('package_id',$package_id)

                            ->update(['package_name'=>$request->package_name ,
                                    'package_price'=>array_sum($request->packagedetail_price),
                                    'package_duration'=>array_sum($request->packagedetail_duration),
                                    'package_price_hold'=>array_sum($request->packagedetail_hold),
                                    'package_listservice_id'=>$package_listservice_insert,
                                ]);
                if($pos_package && $pos_detailpackage)
                {
                        $request->session()->flash('message', 'Edit  Success!');
                }
                else    
                    {
                        $request->session()->flash('message', 'Edit Product Error!');
                    }
                return back();

            }else //IS ADD NEW
            {
                $pos_package_id = PosPackage::where('package_place_id','=',$this->getCurrentPlaceId())->max('package_id') +1;
                $pos_package= new PosPackage ;
                $pos_package->package_id = $pos_package_id;
                $pos_package->package_name = $request->package_name;
                $pos_package->package_place_id = $this->getCurrentPlaceId();
                $pos_package->package_listservice_id = $package_listservice_insert;
                $pos_package->package_price = array_sum($request->packagedetail_price);
                $pos_package->package_price_hold = array_sum($request->packagedetail_hold);
                $pos_package->package_duration = array_sum($request->packagedetail_duration);
                $pos_package->package_status = 1;
                $pos_package->save();

                if($pos_package && $pos_detailpackage)
                {
                    $request->session()->flash('message', 'Insert Combo Success!');
                }
                else
                {
                    $request->session()->flash('message', 'Edit Combo Error!');
                }
                return back();
            }
        }   
    }
    public function deleteComboItem(Request $request)
    {
        DB::beginTransaction();

            $packagedetail = PosPackageDetail::where('packagedetail_place_id',$this->getCurrentPlaceId())
                             ->where('packagedetail_id',$request->packagedetail_id)
                             ->update(['packagedetail_status'=>0]);

            $package_list = PosPackage::where('package_place_id',$this->getCurrentPlaceId())
                            ->where('package_id',$request->combo_id)
                            ->first()->package_listservice_id;

            $package_list = str_replace(";",",",$package_list);

            $package_list = explode(",",$package_list);

            foreach (array_keys($package_list, $request->id) as $key) {
                            unset($package_list[$key]);
                        }
            $package_list = implode(";", $package_list);

            $package_update = PosPackage::where('package_place_id',$this->getCurrentPlaceId())
                            ->where('package_id',$request->combo_id)
                            ->update(['package_listservice_id'=>$package_list]);

        if(!$packagedetail && !$package_update)
        {
            DB::rollback();
            return "Delete Item Combo Error!";
        }
        else
        {
            DB::commit();
            return "Delete Item Combo Success!";
        }
    }
    public function saveDrink(Request $request)
    {
        //dd($request->all());
        $beverage_id = $request->beverage_id;
        
        if($beverage_id >0){ // CHECK EXIST WHEN EDIT
            $check_exist = PosBeverage::where('beverage_place_id',$this->getCurrentPlaceId())
                                        ->where('beverage_name', $request->beverage_name)
                                        ->where('beverage_id','!=',$beverage_id)
                                        ->first();
        }else //CHECK EXIST WHEN ADD NEW
        {
            $check_exist = PosBeverage::where('beverage_place_id',$this->getCurrentPlaceId())
                                        ->where('beverage_name', $request->beverage_name)
                                        ->first();
        }
        
        $rules = [
            'beverage_name' => 'required',
            'beverage_price' => 'required|numeric',
        ];
        $messages = [
            'beverage_name.required' => "Please enter drink name",
            'beverage_price.required' => 'Please enter price drink',
            'beverage_price.numeric' => 'Please enter number on price field',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if(isset($check_exist)){ // PUSH ERROR WHEN EXIST PHONE NUMBER
            $validator->after(function ($validator) {
                $validator->errors()->add('beverage_name.exists', 'Drink name already exist, Please check again!');
            });
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        else
        {
            //CHECK IS EDIT
            if($beverage_id >0){
                $pos_beverage = PosBeverage::where('beverage_place_id','=',$this->getCurrentPlaceId())
                            ->where('beverage_id',$beverage_id)
                            ->update(['beverage_name'=>$request->beverage_name ,
                                    'beverage_price'=>$request->beverage_price,
                                    'beverage_description'=>$request->beverage_description,
                                ]);
                if($pos_beverage)
                        $request->session()->flash('message', 'Edit Drink Success!');
                else    $request->session()->flash('message', 'Edit Drink Error!');
                return back();

            }else //IS ADD NEW
            {
                $pos_beverage= new PosBeverage ;
                    $pos_beverage->beverage_name = $request->beverage_name;
                    $pos_beverage->beverage_place_id = $this->getCurrentPlaceId();
                    $pos_beverage->beverage_price = $request->beverage_price;
                    $pos_beverage->beverage_description = $request->beverage_description;
                    $pos_beverage->beverage_status = 1;

                    $pos_beverage->save();

                if($pos_beverage)

                        $request->session()->flash('message', 'Insert Drink Success!');
                else    $request->session()->flash('message', 'Edit Drink Error!');
                
                return back();
            }
        }   
    }
    public function deleteService(Request $request)
    {
        $service_id = $request->id;

        if($service_id)
        {
            PosService::where('service_place_id',$this->getCurrentPlaceId())
                                ->where('service_id',$service_id)
                                ->update(['service_status'=>0]);
            return "Delete Service Success!";
        }
        else
            return "Delete Service Error!";
        
    }
    public function saveService(Request $request)
    {
        $cateservice_list = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())->get();

       // dd($request->all());

        $service_id = $request->service_id;

        $check_exist = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                       ->where('service_id',$service_id)
                                       ->count();
        $rule = [
            'service_name' =>'required',
            'service_price' =>'required|numeric',
            'service_duration' =>'required|numeric',
        ];

        $message = [
            'service_name.required' => 'Please enter Name Service',
            'service_price.required' => 'Please enter Price Service',
            'service_price.numeric' => 'Please enter number',
            'service_duration.numeric' => 'Please enter number',
            'service_price_repair.required' => 'Please enter Price Repair Service',
            'service_duration.required' => 'Please enter Cate Duration Service',
        ];

        $validator = Validator::make($request->all(),$rule,$message);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else
        {

            if($check_exist == 0 )
            {
                $idService = PosService::where('service_place_id',$this->getCurrentPlaceId())->max('service_id')+1;
            }else
            {
                $idService = $service_id;
            }
            $arr = [
                    'service_id' => $idService,
                    'service_place_id' => $this->getCurrentPlaceId(),
                    'service_cate_id'=>$request->cateservice_id,
                    'service_name'=>$request->service_name,
                    'service_duration'=>$request->service_duration,
                    'service_price'=>$request->service_price,
                    'service_price_hold'=>$request->service_price_hold,
                    'service_status'=>1,
                    'service_turn'=>0,
                    'service_updown'=>0,
                    'service_tax'=>$request->service_tax,
                ];

            if($check_exist == 0)
            {
                $service_list = PosService::create($arr);

                if($service_list)

                    $request->session()->flash('message','Insert Service Success');
                else
                    $request->session()->flash('message','Insert Insert Error');
            }elseif($check_exist ==1)
            {

                $service_list = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                            ->where('service_id',$service_id)
                                            ->update($arr);
                if($service_list)

                    $request->session()->flash('message','Edit Service Success');
                else
                    $request->session()->flash('message','Edit Insert Error');
            }
            return back();
        }
    }
    public function import() {
        return view('datasetup.partials.import');
    }
    public function importServices(Request $request)
    {
        
        if($request->hasFile('fileImport')){
            $path = $request->file('fileImport')->getRealPath();
            $begin_row = $request->begin_row;
            $end_row = $request->end_row;
            $update_exist = $request->check_update_exist;
            $update_count = 0;
            $insert_count = 0;

            \DB::beginTransaction();

            try{
                $data = \Excel::load($path)->toArray();
                //dd($data);

                if(!empty($data)){

                    foreach($data as $key => $value){
                        //dd($value);


                        if( $key >= $begin_row && $key <= $end_row){

                            $check_cateservice = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())->where('cateservice_name',$value['cateservice_name'])->count();

                            if($check_cateservice == 0)
                            {
                                $idCateservice = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())->max('cateservice_id')+1;

                                $arr = [
                                    'cateservice_place_id'=> $this->getCurrentPlaceId(),
                                    'cateservice_name' =>$value['cateservice_name'],
                                    'cateservice_id' => $idCateservice,
                                    'cateservice_image'=>$value['cateservice_image'],
                                    'cateservice_index'=>1
                                ];
                                //dd($arr);
                                PosCateservice::create($arr);

                            }
                            // CHECK EXIST SERVICE

                            $check_exist = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                                       ->where('service_name',$value['service_name'])->count();


                            $cate_service = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                                                            ->where('cateservice_name',$value['cateservice_name'])->first();
                                                    

                            //Nếu chưa tồn tại service thì SERVICE_ID sẽ bằng max(SERVICE_ID)+1
                            if($check_exist ==  0){

                                $idService = PosService::where('service_place_id',$this->getCurrentPlaceId())->max('service_id')+1;

                            } else 
                            {
                                $service_id = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                                       ->where('service_name',$value['service_name'])->first();
                                                       $idService = $service_id->service_id;
                                                       
                            }
                            //dd($idService);

                            if($value['service_image'] != ""){

                                $place_ip_license = PosPlace::where('place_id',$this->getCurrentPlaceId())->first()->place_ip_license;

                                $pathImage = '/images/'.$place_ip_license.'/website/service/';

                                $service_image = $pathImage.$value['service_image'];
                            }
                            else $service_image = "";

                            $arr_service = [
                                'service_id' => $idService,
                                'service_place_id' => $this->getCurrentPlaceId(),
                                'service_cate_id'=>$cate_service->cateservice_id,
                                'service_tag'=>$value['service_tag'],
                                'service_name'=>$value['service_name'],
                                'service_short_name'=>$value['service_short_name'],
                                'service_duration'=>$value['service_duration'],
                                'service_price'=>$value['service_price'],
                                'service_price_extra'=>$value['service_price_extra'],
                                'service_price_repair'=>$value['service_price_repair'],

                                'service_updown'=>($value['service_updown']!="")?$value['service_updown']:0 ,
                                'service_image'=>$service_image,
                                'service_description'=>$value['service_description'],
                                'service_descript_website'=>$value['service_description_website']?$value['service_description_website']:"",
                                'booking_online_status'=>($value['booking_online_status']=="")?1:$value['booking_online_status'],
                                'service_turn'=>($value['service_turn']=="")?0:$value['service_turn'],
                                'service_tax'=>$value['service_tax'],

                            ];
                            //dd($arr_service);
                            //Nếu chưa tồn tại thì create, không thì update
                            if($check_exist == 0 ){

                                $a = PosService::create($arr_service);

                                $insert_count++;
                            }else
                            {
                                if($update_exist == "on")
                                {   $service_id = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                                       ->where('service_name',$value['service_name'])->first()->service_id;

                                    PosService::where('service_place_id',$this->getCurrentPlaceId())
                                                ->where('service_id',$service_id)
                                                ->update($arr_service);

                                    $update_count++;
                                }
                            }
                        }
                    }
                    \DB::commit();
                    $request->session()->flash('message','Import File Success , update:'.$update_count.'row, inserted:'.$insert_count.'row');
                }
                else{
                    $request->session()->flash('error','Import File Not Data');
                }
            } catch(\Exception $e){

                return $e->getMessage();
                $request->session()->flash('error','Import File Error is Error! Please  check import again!');
            }
        }
        else
        {
            $request->session()->flash('error','Please choose file import.');
            
        }
        return back();
    }
    public function exportService()
    {
        // $data = PosService::join('pos_cateservice',function($join){
        //                    $join->on('pos_service.service_cate_id','pos_cateservice.cateservice_id')
        //                          ->on('pos_service.service_place_id','pos_cateservice.cateservice_place_id');
        //                    })
        //                    ->join('pos_user',function($join_user){
        //                    $join_user->on('pos_service.updated_by','pos_user.user_id')
        //                          ->on('pos_service.service_place_id','pos_user.user_place_id');
        //                    })
        //                    ->where('service_place_id',$this->getCurrentPlaceId())
        //                    ->select('cateservice_image','cateservice_name','service_tag','service_name','service_short_name','service_duration','service_price','service_price_extra','service_price_repair','service_updown','service_image','service_description','service_descript_website','booking_online_status','service_turn','service_tax','user_nickname')
        //                    ->orderBy('cateservice_name','asc')
        //                    ->first();
        $date = format_date(now());
        return \Excel::create('service_table_'.$date,function($excel) {

            $excel ->sheet('Service Table', function ($sheet) 
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('Cateservice Image');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('Cateservice Name');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Service Tag');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('Service Name');   });
                $sheet->cell('E1', function($cell) {$cell->setValue('Service Short Name');   });
                $sheet->cell('F1', function($cell) {$cell->setValue('Service Duration');   });
                $sheet->cell('G1', function($cell) {$cell->setValue('Service Price');   });
                $sheet->cell('H1', function($cell) {$cell->setValue('Service Price Extra');   });
                $sheet->cell('I1', function($cell) {$cell->setValue('Service Price Repair');   });
                $sheet->cell('J1', function($cell) {$cell->setValue('Service Updown');   });
                $sheet->cell('K1', function($cell) {$cell->setValue('Service Image');   });
                $sheet->cell('L1', function($cell) {$cell->setValue('Service Description');   });
                $sheet->cell('M1', function($cell) {$cell->setValue('Service Description Website');   });
                $sheet->cell('N1', function($cell) {$cell->setValue('Booking Online Status');   });
                $sheet->cell('O1', function($cell) {$cell->setValue('Service Turn');   });
                $sheet->cell('P1', function($cell) {$cell->setValue('Service Tax');   });
                // $sheet->cell('Q1', function($cell) {$cell->setValue('By');   });

                
                        // $sheet->cell('A2', 'ex: '.$data->cateservice_image); 
                        // $sheet->cell('B2', 'ex: '.$data->cateservice_name); 
                        // $sheet->cell('C2', 'ex: '.$data->service_tag);
                        // $sheet->cell('D2', 'ex: '.$data->service_name);
                        // $sheet->cell('E2', 'ex: '.$data->service_short_name);
                        // $sheet->cell('F2', 'ex: '.$data->service_duration);
                        // $sheet->cell('G2', 'ex: '.$data->service_price);
                        // $sheet->cell('H2', 'ex: '.$data->service_price_extra);
                        // $sheet->cell('I2', 'ex: '.$data->service_price_repair);
                        // $sheet->cell('J2', 'ex: '.$data->service_updown);
                        // $sheet->cell('K2', 'ex: '.$data->service_image);
                        // $sheet->cell('L2', 'ex: '.$data->service_description);
                        // $sheet->cell('M2', 'ex: '.$data->service_descript_website);
                        // $sheet->cell('N2', 'ex: '.$data->booking_online_status);
                        // $sheet->cell('O2', 'ex: '.$data->service_turn);
                        // $sheet->cell('P2', 'ex: '.$data->service_tax); 
                        // $sheet->cell('Q2', 'ex: '.$data->user_nickname); 
                
            });
        })->download("xlsx");
    }

    
    
}

