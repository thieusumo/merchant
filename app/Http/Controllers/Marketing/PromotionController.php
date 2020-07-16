<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use yajra\Datatables\Datatables;
use App\Models\PosSubject;
use App\Models\PosPromotion;
use App\Models\PosService;
use App\Models\PosOrder;
use App\Models\PosTemplateType;
use App\Models\PosTemplate;
use App\Helpers\ImagesHelper;
use Exception;
use Session;
use Carbon\Carbon;

class PromotionController extends Controller
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
        return view('marketing.promotions');
    }
        
    public function view(){
        return view('marketing.partials.promotion_detail')->render(); 
    }
    
    public function add(){
        
        $serviceModel = new PosService;
        //$listServices = $serviceModel->getListByIds($this->getCurrentPlaceId());

        $idUserCurrent = Session::get('current_user_id');
        // dd($idUserCurrent);
        $listServices = PosService::join('pos_cateservice',function($join){
                                   $join->on('pos_service.service_place_id','pos_cateservice.cateservice_place_id')
                                   ->on('pos_service.service_cate_id','pos_cateservice.cateservice_id');
                                   })
                                   ->where('pos_service.service_place_id',$this->getCurrentPlaceId())
                                   ->where('pos_service.enable_status',1)
                                   ->select('pos_service.service_id','pos_service.service_name','pos_service.service_cate_id','pos_cateservice.cateservice_name','pos_cateservice.cateservice_id')
                                   ->get();
        // dd($listServices);
        $arr = [];
        foreach($listServices as  $service)
        {
            $arr[$service->cateservice_name][$service->service_id] = $service->service_name;
        }
        $ar[] = $arr;
        return view('marketing.promotion_add', compact('ar')); 
    }

    public function autoSetup_Add(){
        $data['templateType'] = PosTemplateType::where('template_type_status',1)->where('template_type_table_type',2)->get();
        
        return view('marketing.promotion_auto_add',$data); 
    }
   public function save(Request $request){

        DB::beginTransaction();
        
        try {
            //get all request params
            $params = $request->all();
            $placeId = $this->getCurrentPlaceId();
            // check promotion name is exist or not
            if (PosPromotion::where('promotion_place_id', '=', $placeId)
                    ->where('promotion_name', '=', $params["promotion_name"])->exists()) {
                // user found, return error name
                return response()->json([
                        'success' => false,
                        'messages' => 'This promotion name has been exist. Please re-check the promotion name again.'
                ]); 
             }
            if(isset($params['promotion_date_start']) && isset($params['promotion_date_end'])){
            $date_start = \Carbon\Carbon::parse($params['promotion_date_start'])->format('Y-m-d');
            $date_end = \Carbon\Carbon::parse($params['promotion_date_end'])->format('Y-m-d');
            $start = $request->promotion_time_start;
            $end = $request->promotion_time_end;

            $result = PosPromotion::where(function($query) use ($date_start, $date_end){
                    $query->where(function($query1) use ($date_start){
                        $query1->whereRaw('promotion_date_start <= \''.$date_start.'\'')
                            ->whereRaw('promotion_date_end >= \''.$date_start.'\'');     
                    })
                    ->orWhere(function($query2) use($date_end){
                        $query2->whereRaw('promotion_date_start <= \''.$date_end.'\'')
                            ->whereRaw('promotion_date_end >= \''.$date_end.'\'');       
                    })
                    ->orWhere(function($query3) use($date_start, $date_end){
                        $query3->whereRaw('promotion_date_start <= \''.$date_start.'\'')
                            ->whereRaw('promotion_date_end >= \''.$date_end.'\'');       
                    });
                })
                ->where('promotion_place_id', '=', $placeId)
                ->where('promotion_status', '=', 1)
                ->get();
            foreach($result as $k => $val){

                        if(( $start >= $val['promotion_time_start']&& $start <= $val['promotion_time_end']) 
                            ||  ($end >= $val['promotion_time_start']  && $end <= $val['promotion_time_end'])
                            ||  ($start >= $val['promotion_time_start']&& $end <=  $val['promotion_time_end']))
                            {
                            return response()->json([
                                'success'=>false,
                                'messages'=>'Can not make a promotion in the same time'
                            ]);
                }
            }
            }

            if(!isset($request->id_template)){
            $promotion_image =  "";
            if($request->owner_image==1){
                 if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $request->promotionImageBase64, $matches)) {
                    $imageType = $matches[1];
                    $imageData = base64_decode($matches[2]);
                    $image = imagecreatefromstring($imageData);
                    $filename = strtotime('now').'.png';
                    $promotion_path = "tmp-upload/canvas/";
                    $promotion_image = $filename;
                    if (!file_exists($promotion_path)) {
                        mkdir($promotion_path, 0777, true);
                    }
                    
                    $file_path_write = $promotion_path.$filename;
                    
                    if (!imagepng($image, $file_path_write)) {                    
                       throw new Exception('Could not save the image coupon.');
                    } else {
                        // dd($file_path_write );
                        $promotion_image = \App\Helpers\ImagesHelper::uploadImageCanvas($file_path_write,'promotions',$filename);
                    }
                } else {
                    throw new Exception('Invalid data url of image coupon.');
                }

            }
            else{
                // dd($request->fileUploadImageOwner);
                 $promotion_image =\App\Helpers\ImagesHelper::uploadImage($request->fileUploadImageOwner,'promotions',$placeId );
            }
            } else {
               $linkImage = PosTemplate::where('template_place_id',$this->getCurrentPlaceId())
                                            ->where('template_status',1)
                                            ->where('template_id',$request->id_template)
                                            ->first()->template_linkimage;
            }
            // save post data to table pos_promotion
            $promotionModel = new PosPromotion();                
            $promotionMaxId = intval($promotionModel->where('promotion_place_id', $placeId)->max('promotion_id')) + 1;
            $promotionModel->promotion_id = $promotionMaxId;
            $promotionModel->promotion_place_id = $placeId;            
            $promotionModel->promotion_name = $params['promotion_name'];
            if(isset($params['promotion_date_start'])){
                $promotionModel->promotion_date_start = format_date_db($params['promotion_date_start']);
            } else {
                $date = Carbon::now();
                $promotionModel->promotion_date_start = format_date_db($date->toDateTimeString());
            }
            $promotionModel->promotion_date_end = format_date_db($params['promotion_date_end']);

            //save in promotion auto
            $promotionModel->promotion_type = $params['promotion_discount_type'];
            
            //save in promotion custom        
            if($promotionModel->promotion_type == "$"){
                $promotionModel->promotion_type = 1; 
            } else if($promotionModel->promotion_type == "%"){
                $promotionModel->promotion_type = 0; 
            }
            

            $promotionModel->promotion_time_start = format_time24h($params['promotion_time_start']);
            $promotionModel->promotion_time_end = format_time24h($params['promotion_time_end']);

            if(!isset($request->id_template)){
                //save in promotion custom
                $promotionModel->promotion_image = $promotion_image;                
            } else {
                //save in promotion auto
                $promotionModel->promotion_image = $linkImage;               
            }

            if(isset($request->list_service)){
                //save in promotion auto
                $promotionModel->promotion_listservice_id = $request->list_service;
            } else {
                //save in promotion custom
                $promotion_listservice_id ="";
                if(isset($params['promotion_list_service'])){
                     foreach ($params['promotion_list_service'] as $value){
                        $promotion_listservice_id .=  $value.';';
                     }
                    $promotionModel->promotion_listservice_id = $promotion_listservice_id;
                }       
            }
            
            $promotionModel->promotion_discount = $params['promotion_discount'];
            $promotionModel->promotion_status = 1;
            $promotionModel->promotion_group =$request->promotion_group;
            
            $status = $promotionModel->save();
            if($status){ //  luu db success & then log action
                    $success = true;
                    $actionLog = '{"action": "insert", "table": "pos_promotion", "id": "'.$promotionMaxId.'"}';
                    $logAction = $this->actionLogTable($actionLog);
                    if(!$logAction){
                        throw new Exception('Cannot save log db.');                        
                    }
            }else{                    
                 throw new Exception('System error');
            }
            DB::commit();
            return response()->json([ 'success' => $success,'message' =>"Success", 'id'=>$promotionMaxId]);
            
        } catch (Exception $e) {
            \Log::info($e);
            DB::rollBack();
            return response()->json( array('success' => false, 'message' => 'Error: '.$e->getTraceAsString()) );            
        }               
         
    }
    public function changeStatus(Request $request){
        
        DB::beginTransaction();
        try{
            $promotionModel = new PosPromotion();
            $placeId = $this->getCurrentPlaceId();
            $promotionId = $request->get('id');
            
            $success = false;
            $promotion = $promotionModel
                    ->where('promotion_place_id', '=', $placeId)
                    ->where('promotion_id','=', $promotionId)
                    ->first();
            
            $promotionUpdate = $promotionModel->where('promotion_id', $promotionId)
                    ->where('promotion_place_id', $placeId)
                    ->update(['promotion_status'=>($promotion['promotion_status'] == 1?0:1)]);
            
            if($promotionUpdate){
                    $success = true;
                    $actionLog = '{"action": "update", "table": "pos_promotion", "id": "'.$promotionId.'"}';
                    $logAction = $this->actionLogTable($actionLog);
                    if(!$logAction){
                        throw new Exception('Cannot save log db.'); 
                    }
            }else{
                throw new Exception('Server error.');                 
            }
            DB::commit();            
            return response()->json([ 'success' => true, 'data' => $promotionId ]);
            
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([ 'success' => false, 'message' => $e->getMessage()]);
        }

    }
    
     /**
     * Ajax Get List of Template and return Json data
     * @return type
     */
    public function getTemplates(){
        $subjectModel = new PosSubject();
        $listCoupon = $subjectModel->selectRaw('sub_id as id, sub_name as name, sub_image as image')
                                ->where('sub_place_id','=',$this->getCurrentPlaceId())
                                ->get()
                                ->toArray();
        return response()->json([
                'success' => true,
                'data' => $listCoupon
        ]);
    }

    /**
     * Ajax 
     * @param   $request->id 
     * @return [json]
     */
    public function getPromotionAutoTemplates(Request $request){
        if(isset($request->id) && $request->id != ''){
            $promotionTemplates = PosTemplate::where('template_place_id',$this->getCurrentPlaceId())
                                            ->where('template_status',1)
                                            ->where('template_type_id',$request->id)
                                            ->where('template_table_type',2)
                                            ->get();
        } else {
            $promotionTemplates = PosTemplate::where('template_place_id',$this->getCurrentPlaceId())
                                            ->where('template_status',1)
                                            ->where('template_table_type',2)
                                            ->get();
        }

        $result = [
            'success' => true,
            'data' => $promotionTemplates
        ];
        return json_encode($result);
    }
    
    /**
     * Get Promotion List and render to Datatables
     * @param Request $request
     * @return type
     */
     public function getDataTables(Request $request){
         $promotionModel = PosPromotion::where('promotion_place_id', '=', $this->getCurrentPlaceId());         
         $search_status = $request->get('status');
         if($search_status != ''){
             $promotionModel->where('promotion_status',$search_status);
         }
         return Datatables::of($promotionModel)
            ->editColumn('promotion_image', function ($row) 
            {
                return '<a href="#"><img height="150px" src="'. config("app.url_file_view").'/'.$row->promotion_image.'" class="coupon-image"/></a>';
            })
            ->editColumn('date_range', function ($row){
                if($row->promotion_date_start == "0000-00-00"){
                    return "00/00/0000"." - ".format_date($row->promotion_date_end);
                } else {
                    return format_date($row->promotion_date_start)." - ".format_date($row->promotion_date_end);
                }
            })
            ->editColumn('time_range', function ($row){
               return sprintf('%s - %s ', $row->promotion_time_start, $row->promotion_time_end);
            })
            ->editColumn('promotion_discount', function ($row){
               return $row->promotion_type == 1 ?  ("$".$row->promotion_discount): ($row->promotion_discount."%");
            })
            ->editColumn('status', function ($row){
                return sprintf('<input type="checkbox" class="js-switch status" value="%s" %s/>',$row->promotion_id ,$row->promotion_status ==1?'checked="checked" ':'');             
            })    
            ->editColumn('promotion_popup_website', function ($row){
                return sprintf('<input type="checkbox" class="js-switch popup_website" value="%s" %s/>',$row->promotion_id ,$row->promotion_popup_website ==1?'checked="checked" ':'');             
            })         
             ->editColumn('services', function ($row){
               if(!empty($row->promotion_listservice_id)){                   
                   $serviceModel = new PosService;
                   $listServices = $serviceModel->getListByIds($this->getCurrentPlaceId(), $row->promotion_listservice_id);
                   if(count($listServices) == 1){
                       return array_shift($listServices);
                   }
                   $listServices = array_map(function($val) { return ' - '.$val;} , $listServices);
                   return implode('<br />', array_values($listServices));  
               }
               return '';               
            })
            ->editColumn('promotion_group', function ($row){
                if($row->promotion_group=="0")
                    return "Normal";
                if($row->promotion_group=="1")  
                    return "Happy hours";
                if($row->promotion_group=="2") 
                    return "Instant Day";
            })   
            ->editColumn('created_at', function ($row){
                return format_date($row->created_at);                  
            })            
             ->editColumn('action', function ($row){                    
               return sprintf('<a href="#" class="btn btn-sm btn-secondary delete" data-id="%s" ><i class="fa fa-trash"></i></a>', $row->promotion_id);
            })            
            ->rawColumns(['promotion_id', 'promotion_image' , 'promotion_name', 'date_range', 'time_range', 'promotion_discount', 'status' ,'services', 'created_at','action','promotion_popup_website'])
            ->make(true);
        
     }  
     /**
     * request post delete a coupon
     * @param Request $request
     */
    public function delete(Request $request){
        
        DB::beginTransaction();
        try{
            $promotionModel = new PosPromotion();
            $placeId = $this->getCurrentPlaceId();
            $promotionId = $request->get('id');
            $success = false;
            $promotion = $promotionModel->selectRaw('promotion_id')
                    ->where('promotion_place_id', '=', $placeId)
                    ->where('promotion_id','=', $promotionId)
                    ->first();
            
            $orderModel = new PosOrder();
            $countOrder = 0;
            if($promotion != null){
                $countOrder = $orderModel
                    ->where('order_promotion_id', '=', $promotionId)
                    ->where('order_place_id', '=', $placeId)
                    ->where('order_paid', '=', 1)
                    ->count();
            }
            if($countOrder > 0){
                throw new Exception('Cannot delete promotion used for payment');
            }
            
            $promotionDelete = $promotionModel->where('promotion_id', $promotionId)
                    ->where('promotion_place_id', $placeId)
                    ->delete();
            
            if($promotionDelete){
                    $success = true;
                    $actionLog = '{"action": "delete", "table": "pos_promotion", "id": "'.$promotionId.'"}';
                    $logAction = $this->actionLogTable($actionLog);
                    if(!$logAction){
                        throw new Exception('Cannot save log db.'); 
                    }
            }else{
                throw new Exception('Server error.');                 
            }
            DB::commit();            
            return response()->json([ 'success' => true, 'data' => $promotionId ]);
            
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([ 'success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function ajax_changePopupWebsite(Request $request){
        if($request->id){
            if($request->popup_website == 1){
                PosPromotion::where('promotion_place_id',$this->getCurrentPlaceId())
                                    ->update(['promotion_popup_website'=>'0']);
            }
            $PosPromotion = PosPromotion::where('promotion_id',$request->id)
                                            ->where('promotion_place_id',$this->getCurrentPlaceId())
                                            ->update(['promotion_popup_website'=>$request->popup_website]);          
            return "Update Promotion Popup Website Success!";
        }   
    }
}
