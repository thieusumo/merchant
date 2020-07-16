<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use yajra\Datatables\Datatables;
use App\Models\PosSubject;
use App\Models\PosCoupon;
use App\Models\PosService;
use App\Models\PosOrder;
use App\Models\PosSmsContentTemplateDefault;
use App\Models\PosCustomer;
use App\Models\PosSmsSendEvent;
use App\Models\PosSmsContentTemplate;
use App\Models\PosPlace;
use App\Models\PosTemplate;
use App\Models\PosTemplateType;
use App\Helpers\ImagesHelper;
use App\Helpers\SmsHelper;
use Carbon\Carbon;
use Exception;
use Carbon\CarbonPeriod;
use GuzzleHttp\Client;
use Session;

class CouponController extends Controller
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
     * Show the Coupons page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $group_list = SmsHelper::groupClient();
        // $client_list = SmsHelper::getCientWithBirthday(2,15);
        // $client_list = SmsHelper::membership(15,2,4);
        // return Carbon::now()->subDays(365);
        // $client_list = SmsHelper::remider(15,2);
        // return $client_list;
        $listEventType = SmsEventType::all();

        $template_list_default = PosSmsContentTemplateDefault::where('status',1)
                                ->select('sms_content_template_id','template_title')
                                ->get();
        return view('marketing.coupons',compact('template_list_default','group_list','listEventType'));
    }
    
    /**
     * Show the Add Coupon page.
     * @return type
     */
    public function add()
    {        
        $listServices = PosService::join('pos_cateservice',function($join){
                                   $join->on('pos_service.service_place_id','pos_cateservice.cateservice_place_id')
                                   ->on('pos_service.service_cate_id','pos_cateservice.cateservice_id');
                                   })
                                   ->where('pos_service.service_place_id',$this->getCurrentPlaceId())
                                   ->where('pos_service.enable_status',1)
                                   ->select('pos_service.service_id','pos_service.service_name','pos_service.service_cate_id','pos_cateservice.cateservice_name','pos_cateservice.cateservice_id')
                                   ->get();
        $arr = [];
        foreach($listServices as  $service)
        {
            $arr[$service->cateservice_name][$service->service_id] = $service->service_name;
        }
        $ar[] = $arr;

        //<----
        $placeId = $this->getCurrentPlaceId();
        // $serviceModel = new PosService;
        // $listServices = $serviceModel->getListByIds($placeId);
        $couponCode = $this->__randomCouponCode($placeId);
        return view('marketing.coupon_add',compact("listServices","ar",'couponCode'));
    }

    public function autoSetup_Add(){
        $templateType = PosTemplateType::where('template_type_status',1)->where('template_type_table_type',1)->get();
        $placeId = $this->getCurrentPlaceId();
        $couponCode = $this->__randomCouponCode($placeId);
        
        return view('marketing.coupon_auto_add',compact('couponCode','templateType'));
    }

    /**
     * submit post to save coupon
     * @param Request $request
     * @return type
     * @throws \Exception
     */
    public function save(Request $request){
        // return $request->all();
        // check id coupon template
        $linkImage = '';
        if($request->id_template){
            $linkImage = PosTemplate::where('template_place_id',$this->getCurrentPlaceId())
                                            ->where('template_status',1)
                                            ->where('template_id',$request->id_template)
                                            ->first()->template_linkimage;
        }

        DB::beginTransaction();
        
        try {
            //get all request params
            $params = $request->all();
            //check coupon code empty or not
            if(!isset($params['coupon_code']) || empty($params['coupon_code'])){
                throw new Exception('Not found data coupon');                    
            }
            
            $placeId = $this->getCurrentPlaceId();
            if(!isset($request->id_template)){
                // save image to server
                $coupon_linkimage =  "";
                if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $request->couponImageBase64, $matches)) {
                    $imageType = $matches[1];
                    $imageData = base64_decode($matches[2]);
                    $image = imagecreatefromstring($imageData);
                    $filename = strtotime('now').'.png';
                    $coupon_path = "tmp-upload/canvas/";
                    // $coupons_image = $filename;

                    if (!file_exists($coupon_path)) {
                        mkdir($coupon_path, 0777, true);
                    }
                    
                    $file_path_write = $coupon_path.$filename;
                    if (!imagepng($image, $file_path_write)) {                    
                       throw new Exception('Could not save the image coupon.');
                    } else {
                        $coupon_linkimage = \App\Helpers\ImagesHelper::uploadImageCanvas($file_path_write,'coupons',$filename);
                    }
                } else {
                    throw new Exception('Invalid data url of image coupon.');
                }
            }
            
             // check coupon code is exist or not
            // mac du random nhung vi tranh TH co khi trung code nen kiem tra cho an toan
            if (PosCoupon::where('coupon_place_id', '=', $placeId)
                    ->where('coupon_code', '=', $params["coupon_code"])->exists()) {
                // user found, return error & new coupon
                return response()->json([
                        'success' => false,
                        'messages' => 'This coupon code has been exist. Please re-check the coupon code has just created.',
                        'coupon_code' => $this->__randomCouponCode($placeId)
                ]); 
             }
           
            // save post data to table pos_coupon 
            $couponModel = new PosCoupon();                
            $couponMaxId = intval($couponModel->where('coupon_place_id', $placeId)->max('coupon_id')) + 1;
            $couponModel->coupon_id = $couponMaxId;
            $couponModel->coupon_place_id = $placeId;   
            if(isset($params['coupon_date_end'])){
                $couponModel->coupon_deadline = format_date_db($params['coupon_date_end']);
            }        
            $couponModel->coupon_code = $params['coupon_code'];
            
            //save in coupon auto
            $couponModel->coupon_type = $params['coupon_discount_type'];
            
            //save in coupon custom        
            if($couponModel->coupon_type == "$"){
                $couponModel->coupon_type = 1; 
            } else if($couponModel->coupon_type == "%"){
                $couponModel->coupon_type = 0; 
            }


            if(isset($params['coupon_date_start'])){
            $couponModel->coupon_startdate = format_date_db($params['coupon_date_start']);  
            } else {
                $date = Carbon::now();
                $couponModel->coupon_startdate = format_date_db($date->toDateTimeString());
            }
            $couponModel->coupon_title = $params['coupon_title'];
            if(isset($params['sub_id'])){
                $couponModel->coupon_sub_id = $params['sub_id'];
            }
            
            //check  id coupon
            if(!isset($request->id_template)){
                $couponModel->coupon_linkimage = $coupon_linkimage;
                $couponModel->coupon_short_linkimage = $coupon_linkimage;
            } else {
                $couponModel->coupon_linkimage = $linkImage;
                $couponModel->coupon_short_linkimage = $linkImage;
            }
            if(isset($request->coupon_list_service)){
                // $couponModel->coupon_list_service = $params['coupon_list_service'];
                $str = implode(";", $params['coupon_list_service']);
                $couponModel->coupon_list_service = $str;
                // return $str;
            }         
            if(isset($request->list_service)){
                $couponModel->coupon_list_service = $request->list_service;
            } 
            if(isset($params['coupon_quantity'])){
                $couponModel->coupon_quantity_limit = $params['coupon_quantity'];
            }            

            $couponModel->coupon_discount = $params['coupon_discount'];
            $couponModel->coupon_status = 1;
            
            $status = $couponModel->save();


            if($status == 1){ //  luu db success & then log action
                    $success = true;
                    $actionLog = '{"action": "insert", "table": "pos_coupon", "id": "'.$couponMaxId.'"}';
                    $logAction = $this->actionLogTable($actionLog);
                    if(!$logAction){
                        throw new Exception('Cannot save log db.');                        
                    }
            }else{                    
                 throw new Exception('System error');
            }
            DB::commit();
            return response()->json([ 'success' => $success, 'id'=>$couponMaxId]);
            
        } catch (Exception $e) {
            \Log::info($e);
            DB::rollBack();
            return response()->json( array('success' => false, 'message' => 'Error: '.$e->getTraceAsString()) );            
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
                                ->whereIn('sub_type',array(0,1))
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
    public function getCouponAutoTemplates(Request $request){
        if(isset($request->id) && $request->id != ''){
            $couponTemplates = PosTemplate::where('template_place_id',$this->getCurrentPlaceId())
                                            ->where('template_status',1)
                                            ->where('template_type_id',$request->id)
                                            ->where('template_table_type',1)
                                            ->get();
        } else {
            $couponTemplates = PosTemplate::where('template_place_id',$this->getCurrentPlaceId())
                                            ->where('template_status',1)
                                            ->where('template_table_type',1)
                                            ->get();
        }

        $result = [
            'success' => true,
            'data' => $couponTemplates
        ];
        return json_encode($result);
    }
    /**
     * Ajax get coupon template by id
     * @param  Request $request [input ex: $request->listId "1;2;3"]
     * @return json
     */
    public function getServicesByListId(Request $request){
        if($request->listId){
            $arrServiceId = explode(";", $request->listId);
// dd($arrServiceId);
            $listService = PosService::select('service_id','service_name')
                                 ->where('service_place_id',$this->getCurrentPlaceId())
                                 ->whereIn('service_id',$arrServiceId)
                                 ->where('service_status',1)
                                 ->get();

            $result = [
                'success' => true,
                'data' => $listService,
            ];
            return json_encode($result);
        }
    }
        
    /**
     * request post delete a coupon
     * @param Request $request
     */
    public function delete(Request $request){
        
        DB::beginTransaction();
        try{
            $couponModel = new PosCoupon();
            $placeId = $this->getCurrentPlaceId();
            $couponId = $request->get('id');
            $success = false;
            $coupon = $couponModel->selectRaw('coupon_code')
                    ->where('coupon_place_id', '=', $placeId)
                    ->where('coupon_id','=', $couponId)
                    ->where('coupon_status', '=', 1)
                    ->first();
            $orderModel = new PosOrder();
            $countOrder = 0;
            if($coupon != null){
                $countOrder = $orderModel
                    ->where('order_coupon_code', '=', $coupon['coupon_code'])
                    ->where('order_place_id', '=', $placeId)
                    ->where('order_paid', '=', 1)
                    ->count();
            }
            if($countOrder > 0){
                throw new Exception('Cannot delete coupon used for payment');
            }
            
            $couponDelete = $couponModel->where('coupon_id', $couponId)
                    ->where('coupon_place_id', $placeId)
                    ->update(['coupon_status'=>0]);
            
            if($couponDelete == 1){
                    $success = true;
                    $actionLog = '{"action": "delete", "table": "pos_coupon", "id": "'.$couponId.'"}';
                    $logAction = $this->actionLogTable($actionLog);
                    if(!$logAction){
                        throw new Exception('Cannot save log db.'); 
                    }
            }else{
                throw new Exception('Server error.');                 
            }
            DB::commit();            
            return response()->json([ 'success' => true, 'data' => $coupon ]);
            
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([ 'success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Get Coupon List and render to Datatables
     * @param Request $request
     * @return type
     */
     public function getDataTables(Request $request){
         
         $couponModel = PosCoupon::where('coupon_place_id', '=', $this->getCurrentPlaceId())
                 ->where('coupon_status', '=', 1);
         $couponModel->selectRaw('coupon_id, coupon_linkimage, coupon_code, coupon_discount, coupon_type, IFNULL(coupon_quantity_limit, 0) as quantity, (IFNULL(coupon_quantity_limit, 0)-IFNULL(coupon_quantity_use, 0)) as balance , coupon_startdate, coupon_deadline, coupon_list_service, created_at');
         $couponModel->get(); 
         return Datatables::of($couponModel)
            ->editColumn('coupon_linkimage', function ($row) 
            {
                return '<a href="#"><img src="'. config("app.url_file_view").'/'.$row->coupon_linkimage.'" class="coupon-image"/></a>';
            })
            ->editColumn('date_start', function ($row){
                if(empty($row->coupon_startdate)){
                    return format_date($row->coupon_startdate);
                } else {
                    return '';
                }                                  
            })
            ->editColumn('date_end', function ($row){
               return format_date($row->coupon_deadline);                  
            })
            ->editColumn('coupon_discount', function ($row){
               return $row->coupon_type == 1 ?  ("$".$row->coupon_discount): ($row->coupon_discount."%");
            })

             ->editColumn('services', function ($row){
                if(!empty($row->coupon_list_service)){                   
                   $serviceModel = new PosService;
                   $listServices = $serviceModel->getListByIds($this->getCurrentPlaceId(), $row->coupon_list_service);
                   if(count($listServices) == 1){
                       return array_shift($listServices);
                   }
                   $listServices = array_map(function($val) { return ' - '.$val;} , $listServices);
                   return implode('<br />', array_values($listServices));  
                }
               return '';               
            })
             ->editColumn('action', function ($row){                    
               return '<a href="'.route('get-send-sms-coupon',$row->coupon_id).'" class="btn btn-sm btn-secondary send-sms" >SMS</a>
                       <a href="#" title="Send SMS" class="btn btn-sm btn-secondary delete" data-id="'.$row->coupon_id.'" ><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($row){
                return format_date($row->created_at);                  
            })            
            ->rawColumns(['coupon_linkimage','action','services'])
            ->make(true);
        
     }  
     public function getContentTemplateBooking(Request $request){
        $result = "";
        if($request->id != ""){
            $result = PosSmsContentTemplateDefault::where('sms_content_template_id',$request->id)
                                        ->select('sms_content_template')
                                        ->first()
                                        ->sms_content_template;
        }
        return $result;
    }
    public function sendSmsCoupon(Request $request){
        //return $request->all();
        $place_id = $this->getCurrentPlaceId();
        
        $this->validate($request,[
            'event_name'=>'required',
            'event_type'=>'required',
            'content_template'=>'required',
            'start_date'=>'required',
            'start_time'=>'required',
            'repeat'=>'required',
            // 'send_before'=>'required',
            // 'repeat_year_day'=>'required',
            // 'repeat_year_month'=>'required',
            // 'repeat_month_day'=>'required',
            // 'repeat_weekly'=>'required',
            // 'end_date'=>'required',
            
        ],[

        ]);

        $sms_send_event_id = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())->max('sms_send_event_id')+1;

        $pos_sms_send_event = new PosSmsSendEvent; 
        $pos_sms_send_event->sms_send_event_id = $sms_send_event_id;
        $pos_sms_send_event->sms_send_event_place_id = $this->getCurrentPlaceId();
        $pos_sms_send_event->sms_send_event_title = $request->event_name;
        $pos_sms_send_event->sms_send_event_type = $request->event_type;
        $pos_sms_send_event->sms_send_event_template_id = $request->content_template;
        $pos_sms_send_event->sms_send_event_start_day = $request->start_date;
        $pos_sms_send_event->sms_send_event_start_time = $request->start_time;
        $pos_sms_send_event->sms_send_event_end_date = $request->end_date;
        $pos_sms_send_event->sms_send_event_repeat_type = $request->repeat; 
        $pos_sms_send_event->sms_send_event_enable = 1;        
        

        //check repeat

        if($request->repeat == "no"){ 
            if($request->send_before != ""){
                $pos_sms_send_event->send_before_days = $request->send_before;
            }
            else return back()->with('message','Choose before date, please!');
        }
        else{
            //check end date
            if($request->end_date)
                $pos_sms_send_event->sms_send_event_end_date = $request->end_date;
            else return back()->with('message','Choose End Date, please!');
            //check repeat on 
            if($request->repeat == "w"){
                $sms_send_event_repeat_on = '';
                //check repeat_weekly
                if(!$request->repeat_weekly) return back()->with('message','Choose repeat time date, please!');

                foreach ($request->repeat_weekly as $r_k) {
                    $sms_send_event_repeat_on = $sms_send_event_repeat_on.$r_k.';';                    
                }  
                $sms_send_event_repeat_on = substr($sms_send_event_repeat_on,0,-1);

                $pos_sms_send_event->sms_send_event_repeat_on = $sms_send_event_repeat_on;
            } else if($request->repeat == "m"){ 
                $pos_sms_send_event->sms_send_event_repeat_on = $request->repeat_month_day;
            } else if($request->repeat == "y"){ 
                $pos_sms_send_event->sms_send_event_repeat_on = $request->repeat_year_day.";".$request->repeat_year_month;
            }

        } 
        //check Send to
        if($request->checkbox_group_receiver){            
            if($request->group_receiver)
                $pos_sms_send_event->group_receiver_id = $request->group_receiver;
        }

        if($request->checkbox_add_receiver){             
            if($request->add_receiver)
                $pos_sms_send_event->add_more_phone = $request->add_receiver;
        }

        //CALCULATE SMS
 
        $start_date = $request->start_date;
        $start_time = $request->start_time;
        $end_date = $request->end_date;
        $repeat = $request->repeat;
        $group_receiver_id = $request->group_receiver;
        $add_receiver = $request->add_receiver;
        $list_receiver = $request->list_receiver;
        $repeat_month_day = $request->repeat_month_day;
        $repeat_year_month = $request->repeat_year_month;
        $repeat_year_day = $request->repeat_year_day;

        $receiver_total = [];

        if($group_receiver_id != ""){

            $customer_id_arr = [];

            if($group_receiver_id == 0){

                $customer_order = PosOrder::where('order_place_id',$place_id)
                                            ->where('order_status',1)
                                            ->groupBy('order_customer_id')
                                            ->select('order_customer_id')
                                            ->get();
                foreach ($customer_order as $key => $value) {
                    $customer_id_arr[] = $value->order_customer_id;
                }

                $client_list = PosCustomer::where('customer_place_id',$place_id)
                                            ->whereIn('customer_id',$customer_id_arr)
                                            ->where('customer_status',1)
                                            ->get();
                
            }
            //New Client
            if($group_receiver_id == 1){

                $client_list = SmsHelper::typeCustomer($place_id,'New');
            }
            //Royal Client
            if($group_receiver_id == 1){

                $client_list = SmsHelper::typeCustomer($place_id,'Royal');
            }
            //VIP Client
            if($group_receiver_id == 2){

                $client_list = SmsHelper::typeCustomer($place_id,'Vip');
            }
            //NORMAL MEMBERSHIP
             if($group_receiver_id == 3){

                $client_list = SmsHelper::membership($place_id,'Normal Membership');
            }
            //GET RECIEVER LIST WITH MEMBERSHIP

            //SILVER MEMBERSHIP
            if($group_receiver_id == 4){

                $client_list = SmsHelper::membership($place_id,'Silver Membership');
            }
            //MEMBERSHIP GOLDEN
            if($group_receiver_id == 5){

                $client_list = SmsHelper::membership($place_id,'Golden Membership');
            }
            //MEMBERSHIP DIAMOND
            if($group_receiver_id == 6){

                $client_list = SmsHelper::membership($place_id,'Dimond Membership');
            }
            //NO MEMBERSHIP
            if($group_receiver_id == 6){
                $client_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                            ->where('customer_membership_id',0)
                                            ->where('customer_status',1)
                                            ->get();
            }
        //END GET WITH MEMBERSHIP

        //GET RECEIVER LIST WITH REMINDER
            //REMINDER 7DAYS
            if($group_receiver_id == 8){

                $client_list = SmsHelper::remider($place_id,7);
            }
             //REMINDER 14DAYS
            if($group_receiver_id == 9){

                $client_list = SmsHelper::remider($place_id,14);
            }
            // REMIDER 21 DAYS
            if($group_receiver_id == 10){

                $client_list = SmsHelper::remider($place_id,21);
            }
            // REMIDER 30 DAYS
            if($group_receiver_id ==11){

                $client_list = SmsHelper::remider($place_id,30);
            }
            // REMIDER 60 DAYS
            if($group_receiver_id == 12){

                $client_list = SmsHelper::remider($place_id,60);
            }
            // REMIDER 90 DAYS
            if($group_receiver_id == 13){

                $client_list = SmsHelper::remider($place_id,90);
            }
            // REMIDER 180 DAYS
            if($group_receiver_id == 14){

                $client_list = SmsHelper::remider($place_id,180);
            }
            // REMIDER 365 DAYS
            if($group_receiver_id == 15){

                $client_list = SmsHelper::remider($place_id,365);
            }
        //END GET WITH REMINDER
        //GET RECEIVER WITH BIRTHDAY
            //BIRTHDAY JANUARY
            if($group_receiver_id == 16){

                $client_list = SmsHelper::getCientWithBirthday(1,$place_id);
            }
            //BIRTHDAY FEBRUARY
            if($group_receiver_id == 17){

                $client_list = SmsHelper::getCientWithBirthday(2,$place_id);
            }
            //BIRTHDAY MARCH
            if($group_receiver_id == 18){

                $client_list = SmsHelper::getCientWithBirthday(3,$place_id);
            }
            //BIRTHDAY APRIL
            if($group_receiver_id == 19){

                $client_list = SmsHelper::getCientWithBirthday(4,$place_id);
            }
             //BIRTHDAY MAY
            if($group_receiver_id == 20){

                $client_list = SmsHelper::getCientWithBirthday(5,$place_id);
            }
            //BIRTHDAY JUNE
            if($group_receiver_id == 21){

                $client_list = SmsHelper::getCientWithBirthday(6,$place_id);
            }
            //BIRTHDAY JULY
            if($group_receiver_id == 22){

                $client_list = SmsHelper::getCientWithBirthday(7,$place_id);
            }
            //BIRTHDAY AUGUST
            if($group_receiver_id == 23){

                $client_list = SmsHelper::getCientWithBirthday(8,$place_id);
            }
            //BIRTHDAY SEPTEMBER
            if($group_receiver_id == 24){

                $client_list = SmsHelper::getCientWithBirthday(9,$place_id);
            }
            //BIRTHDAY OCTOBER
            if($group_receiver_id == 25){

                $client_list = SmsHelper::getCientWithBirthday(10,$place_id);
            }
            //BIRTHDAY NOVEMBER
            if($group_receiver_id == 26){

                $client_list = SmsHelper::getCientWithBirthday(11,$place_id);
            }
            //BIRTHDAY DECEMBER
            if($group_receiver_id == 27){

                $client_list = SmsHelper::getCientWithBirthday(12,$place_id);
            }
        //END GET WITH BIRTHDAY

            foreach ($client_list as $key => $value) {
                    $receiver_total[] = [
                        'name' => $value->customer_fullname,
                        'birthday' => $value->customer_birthdate,
                        'phone' => $value->customer_phone
                    ];
                }
            $group_receiver_list = $client_list->count();

        }
        else
            $group_receiver_list = 0;

        if($add_receiver != ""){

            $add_receiver = 1;

            $receiver_total[] = [

                'name' => "",
                'birthday' => "",
                'phone' => $add_receiver
            ];
        }else
            $add_receiver = 0;

        //CHECK RECEIVER LIST. IF EMPTY RETURN BACK
        if(count($receiver_total) == 0){
            return back()->with('message','Receiver List Empty. Choose again, Please!');
        }
        //END CHECK RECEIVER LIST

        $pos_sms_send_event->upload_list_receiver = "";
        $upload_list_receiver = 0;

        if($request->event_type != 1){
            if($repeat != "no"){

                $repeat_list = 0;
                
                $start_date = Carbon::parse($start_date)->format('Y-m-d');
                $end_date = Carbon::parse($end_date)->format('Y-m-d');

                $period = CarbonPeriod::create($start_date,$end_date);

                    // Iterate over the period
                    foreach ($period as $date) {

                        if($repeat == "w"){

                            $dateOfWeek = Carbon::parse($date)->dayOfWeek;

                            foreach($request->repeat_weekly as $repeat_weekly){

                                //return $repeat_weekly;

                                if($repeat_weekly == $dateOfWeek){

                                    $repeat_list ++;
                                }
                            }
                        }
                        if($repeat == "m"){

                            $date_of_month = Carbon::parse($date)->format('d');

                            if($date_of_month == $repeat_month_day){

                                $repeat_list++;
                            }
                        }
                        if($repeat == "y"){

                            $day_of_year = Carbon::parse($date)->format('d');
                            $month_of_year = Carbon::parse($date)->format('m');

                            if($repeat_year_day == $day_of_year && $repeat_year_month == $month_of_year){

                                $repeat_list++;
                            }

                        }
                    }
                $sms_total = ($group_receiver_list + $add_receiver + $upload_list_receiver) * $repeat_list;

                $time_now = Carbon::now()->addHours(7)->toTimeString();
                $date_now = Carbon::now()->addHours(7)->toDateString();

                $date_time_now = Carbon::parse($date_now." ".gettime_by_datetime($time_now));

                $date_time_send = Carbon::parse($date_now." ".$start_time);

                if($start_date == $date_now && $date_time_now > $date_time_send ){

                    $sms_total = $sms_total - 1;

                } 
            }
            if($repeat == "no")

                $sms_total = $group_receiver_list + $add_receiver + $upload_list_receiver;
        }
        else
            $sms_total = $group_receiver_list + $upload_list_receiver;
        //END GET REPEAT SMS
        $pos_sms_send_event->sms_total = $sms_total;

        //CHECK TOTAL SMS BALANCE
        $balance_sms = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                ->first()
                                ->place_total_sms;
        if( $balance_sms == "" || $sms_total > $balance_sms )
            return redirect()->route('list_reviews')->with('message','You do not have enough sms for send. Check again!');

        $date = now()->format('Y_m_d');

        if($request->event_type == 1){

            $file_name = "receiver_sms_list_birthday_".$date;

            \Excel::create($file_name,function($excel) use ($receiver_total,$request){

                $excel ->sheet($request->event_name, function ($sheet) use ($receiver_total)
                {
                    $sheet->cell('A1', function($cell) {$cell->setValue('phone');   });
                    $sheet->cell('B1', function($cell) {$cell->setValue('birthday');   });
                    $sheet->cell('C1', function($cell) {$cell->setValue('{p2}');   });

                    if (!empty($receiver_total)) {
                        foreach ($receiver_total as $key => $value) {
                            $i= $key+2;
                            if($value['phone'] != ""){
                                $sheet->cell('A'.$i, $value['phone']);
                                $sheet->cell('B'.$i, $value['birthday']);
                                $sheet->cell('C'.$i, $value['name']); 
                            }
                        }
                    }
                });
            })->store('xlsx', false, true);
        }else{
            $file_name = "receiver_sms_list_".$date;

            \Excel::create($file_name,function($excel) use ($receiver_total,$request){

                $excel ->sheet($request->event_name, function ($sheet) use ($receiver_total)
                {
                    $sheet->cell('A1', function($cell) {$cell->setValue('phone');   });
                    $sheet->cell('B1', function($cell) {$cell->setValue('birthday');   });
                    $sheet->cell('C1', function($cell) {$cell->setValue('{p1}');   });

                    if (!empty($receiver_total)) {
                        foreach ($receiver_total as $key => $value) {
                            $i= $key+2;
                            if($value['phone'] != ""){
                                $sheet->cell('A'.$i, $value['phone']);
                                $sheet->cell('B'.$i, $value['birthday']);
                                $sheet->cell('C'.$i, $value['name']);
                            }
                        }
                    }
                });
            })->store('xlsx', false, true);
        }
        //END MAKE EXCEL

        //GET INFORMATION FOR SEND SMS
        $input = $request->all();

        $file_url = storage_path('exports/'.$file_name.".xlsx");

        $sms_content_template = PosSmsContentTemplate::where('sms_content_template_place_id',$this->getCurrentPlaceId())
                                                      ->where('sms_content_template_id',$request->content_template)
                                                      ->first()
                                                      ->sms_content_template;
        $input['sms_content_template'] = $sms_content_template;
        $input['event_id'] = $sms_send_event_id;

        $result = $this->PushApiSMS($input,$file_url);

        $result = json_decode($result,true);


        if( $result['status'] == 1)
            $pos_sms_send_event->save();

        return back()->with('message',$result['messages']);
    }
    /**
     * random coupon code
     * @param int $placeId
     * @return string coupon code( 8 characters long include alphanumeric)
     */
    private function __randomCouponCode($placeId){
        return 'c'.$placeId.substr(uniqid(),8);
    }
    private function PushApiSMS($input,$file_url,$url = ""){
        if($input['event_type'] == 1)
            $url_event = 'birthday';
        else
            $url_event = 'pushsms';

        $url = env("REVIEW_SMS_API_URL").$url_event.$url;

        $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
        //$url="http://user.tag.com/api/v1/receiveTo";
        $client = new Client([
            // 'timeout'  => 5.0,            
        ]);
        $date_before = "";

        if($input['repeat'] == "no"){
            $repeat = '0';
            $repeat_on = "";
            $date_before = "";
        }
        elseif ($input['repeat'] == "w") {
            $repeat = '1';
            $repeat_on = implode(",", $input['repeat_weekly']);
        }
        elseif ($input['repeat'] == "m") {
            $repeat = '2';
            $repeat_on = $input['repeat_month_day'];
        }
        elseif ($input['repeat'] == "y") {
            $repeat = '3';
            $repeat_on = $input['repeat_year_day'].','.$input['repeat_year_month'];
        }
        $sms_content_template = str_replace("name","p1",$input['sms_content_template']);

        if($input['event_type'] == 1){

            $date_before = $input['send_before'];
            $repeat = '0';
            $repeat_on = "";
            $date_end = "";
            $sms_content_template = str_replace("name","p2",$input['sms_content_template']);

        }else
            $date_end = format_date_d_m_y($input['end_date']);
            //return $url;

        $response = $client->request('POST', $url ,[
                    'multipart' => [
                            [
                                'name' => 'content',
                                'contents' => $sms_content_template,
                            ],
                            [
                                'name' => 'event_id',
                                'contents' => $input['event_id'],
                            ],
                            [
                                'name' => 'title',
                                'contents' => $input['event_name'],
                            ],
                            [
                                'name' => 'type_event',
                                'contents' => $input['event_type'],
                            ],
                            [
                                'name' => 'merchant_id',
                                'contents' => '1',
                            ],
                            [
                                'name' => 'start',
                                'contents' => format_date_d_m_y($input['start_date']),
                            ],
                            [
                                'name' => 'end',
                                'contents' => $date_end,
                            ],
                            [
                                'name' => 'repeat',
                                'contents' => $repeat,
                            ],
                            [
                                'name' => 'repeat_on',
                                'contents' => $repeat_on,
                            ],
                            [
                                'name' => 'date_before',
                                'contents' => $date_before,

                            ],

                            [
                                'name' => 'timesend',
                                'contents' => format_time24h($input['start_time']),
                            ],
                            [
                                'name'     => 'upfile',
                                'contents' => fopen($file_url, 'r')
                            ],
                        
                    ],
                    'headers' => [
                        'Authorization' => 'Bearer ' . env("REVIEW_SMS_API_KEY"),
                                ],

                    
                ]);
                
        //$response = $client->put($url, array('headers' => $header));
        // Call external API
        // $response = $client->post("http://d29u17ylf1ylz9.cloudfront.net/phuler-v4/index.html", ['form_params' => $smsData]);
        //$response = $client->get("http://d29u17ylf1ylz9.cloudfront.net/phuler-v4/index.html");
        // Check whether API call was successfull or not...
        //$zonerStatusCode = $response->getStatusCode();
        $resp =  (string)$response->getBody();
        //echo $resp;
        return $resp;

    }
    public function getSendSmsCoupon($id = null ){
        // dd($id);
        $coupon_title = "";

        if($id != null)
        
            $coupon_title = PosCoupon::where('coupon_place_id',$this->getCurrentPlaceId())
                            ->where('coupon_id',$id)
                            ->where('coupon_status',1)
                            ->first()
                            ->coupon_title;

        $group_list = SmsHelper::groupClient();
        // dd($group_list);

        $listEventType = SmsEventType::all();

        $template_list_default = PosSmsContentTemplateDefault::where('status',1)
                                ->select('sms_content_template_id','template_title')
                                ->get();

    //     $data['BIRTHDAY_JANUARY'] = SmsHelper::getCientWithBirthday(1,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_FEBRUARY'] = SmsHelper::getCientWithBirthday(2,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_MARCH'] = SmsHelper::getCientWithBirthday(3,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_APRIL'] = SmsHelper::getCientWithBirthday(4,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_MAY'] = SmsHelper::getCientWithBirthday(5,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_JUNE'] = SmsHelper::getCientWithBirthday(6,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_JULY'] = SmsHelper::getCientWithBirthday(7,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_AUGUST'] = SmsHelper::getCientWithBirthday(8,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_SEPTEMBER'] = SmsHelper::getCientWithBirthday(9,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_OCTOBER'] = SmsHelper::getCientWithBirthday(10,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_NOVEMBER'] = SmsHelper::getCientWithBirthday(11,$this->getCurrentPlaceId())->count();
    //     $data['BIRTHDAY_DECEMBER'] = SmsHelper::getCientWithBirthday(12,$this->getCurrentPlaceId())->count();
    //                             // dd($listEventType);
    //     return view('marketing.sms.send_sms',compact('listEventType','template_list_default','group_list','coupon_title'),$data);
    // }

        $data1=[];




        $data1[0]['name']='NEW';
        $data1[0]['NEW'] = SmsHelper::typeCustomer($this->getCurrentPlaceId(),'New')->count();

        $data1[1]['ROYAL'] = SmsHelper::typeCustomer($this->getCurrentPlaceId(),'Royal')->count();
        $data1[1]['name']='ROYAL';

        $data1[2]['VIP'] = SmsHelper::typeCustomer($this->getCurrentPlaceId(),'Vip')->count();
        $data1[2]['name']='VIP';

        $data1[3]['NORMAL_MEMBERSHIP'] = SmsHelper::membership($this->getCurrentPlaceId(),'Normal Membership')->count();
        $data1[3]['name']='NORMAL_MEMBERSHIP';

        $data1[4]['SILVER_MEMBERSHIP']= SmsHelper::membership($this->getCurrentPlaceId(),'Silver Membership')->count();
        $data1[4]['name']='SILVER_MEMBERSHIP';

        $data1[5]['GOLDEN_MEMBERSHIP'] = SmsHelper::membership($this->getCurrentPlaceId(),'Golden Membership')->count();
        $data1[5]['name']='GOLDEN_MEMBERSHIP';

        $data1[6]['DIMOND_MEMBERSHIP'] = SmsHelper::membership($this->getCurrentPlaceId(),'Dimond Membership')->count();
        $data1[6]['name']='DIMOND_MEMBERSHIP';




        $data1[7]['NO_MEMBERSHIP'] = 0;
        $data1[7]['name']='NO_MEMBERSHIP';
        
        $data1[8]['name']='REMINDER_7_DAYS';
        $data1[8]['REMINDER_7_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),7)->count();

        $data1[9]['REMINDER_15_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),15)->count();
        $data1[9]['name']='REMINDER_15_DAYS';

        $data1[10]['REMINDER_21_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),21)->count();
        $data1[10]['name']='REMINDER_21_DAYS';

        $data1[11]['REMINDER_30_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),30)->count();
        $data1[11]['name']='REMINDER_30_DAYS';

        $data1[12]['REMINDER_60_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),60)->count();
        $data1[12]['name']='REMINDER_60_DAYS';

        $data1[13]['REMINDER_90_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),90)->count();
        $data1[13]['name']='REMINDER_90_DAYS';

        $data1[14]['REMINDER_180_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),180)->count();
        $data1[14]['name']='REMINDER_180_DAYS';

        $data1[15]['REMINDER_365_DAYS'] = SmsHelper::remider($this->getCurrentPlaceId(),365)->count();
        $data1[15]['name']='REMINDER_365_DAYS';




        $data1[16]['name']='BIRTHDAY_JANUARY';
        $data1[16]['BIRTHDAY_JANUARY'] = SmsHelper::getCientWithBirthday(1,$this->getCurrentPlaceId())->count();
        
        $data1[17]['BIRTHDAY_FEBRUARY'] = SmsHelper::getCientWithBirthday(2,$this->getCurrentPlaceId())->count();
        $data1[17]['name']='BIRTHDAY_FEBRUARY';
        
        $data1[18]['BIRTHDAY_MARCH'] = SmsHelper::getCientWithBirthday(3,$this->getCurrentPlaceId())->count();
        $data1[18]['name']='BIRTHDAY_MARCH';
        
        $data1[19]['BIRTHDAY_APRIL'] = SmsHelper::getCientWithBirthday(4,$this->getCurrentPlaceId())->count();
        $data1[19]['name']='BIRTHDAY_APRIL';
        
        $data1[20]['BIRTHDAY_MAY'] = SmsHelper::getCientWithBirthday(5,$this->getCurrentPlaceId())->count();
        $data1[20]['name']='BIRTHDAY_MAY';
        
        $data1[21]['BIRTHDAY_JUNE'] = SmsHelper::getCientWithBirthday(6,$this->getCurrentPlaceId())->count();
        $data1[21]['name']='BIRTHDAY_JUNE';
        
        $data1[22]['BIRTHDAY_JULY'] = SmsHelper::getCientWithBirthday(7,$this->getCurrentPlaceId())->count();
        $data1[22]['name']='BIRTHDAY_JULY';
        
        $data1[23]['BIRTHDAY_AUGUST'] = SmsHelper::getCientWithBirthday(8,$this->getCurrentPlaceId())->count();
        $data1[23]['name']='BIRTHDAY_AUGUST';
        
        $data1[24]['BIRTHDAY_SEPTEMBER'] = SmsHelper::getCientWithBirthday(9,$this->getCurrentPlaceId())->count();
        $data1[24]['name']='BIRTHDAY_SEPTEMBER';
        
        $data1[25]['BIRTHDAY_OCTOBER'] = SmsHelper::getCientWithBirthday(10,$this->getCurrentPlaceId())->count();
        $data1[25]['name']='BIRTHDAY_OCTOBER';
        
        $data1[26]['BIRTHDAY_NOVEMBER'] = SmsHelper::getCientWithBirthday(11,$this->getCurrentPlaceId())->count();
        $data1[26]['name']='BIRTHDAY_NOVEMBER';
        
        $data1[27]['BIRTHDAY_DECEMBER'] = SmsHelper::getCientWithBirthday(12,$this->getCurrentPlaceId())->count();
        $data1[27]['name']='BIRTHDAY_DECEMBER';
        
                                // dd($data1);
        return view('marketing.sms.send_sms',compact('listEventType','template_list_default','group_list','coupon_title','data1','data2','data3'));
    }

}
class SmsEventType{
    const HAPPY_BIRTHDAY = 1;
    const REMINDER = 2;
    const CUSTOMER_SERVICE = 3;
    const ADS_CAPMAIGN = 4;    
    public static function all(){
        return [
            self::HAPPY_BIRTHDAY => 'Happy Birthday',
            self::REMINDER => 'Reminder',
            self::CUSTOMER_SERVICE => 'Customer Service',
            self::ADS_CAPMAIGN => 'Ads Campaign',
        ];
    }     
    public static function find($id = 0){
        $list = self::all();
        return isset($list[$id])?$list[$id]:0;
    }
}
