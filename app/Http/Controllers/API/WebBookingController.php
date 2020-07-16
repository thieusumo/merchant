<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PosPromotion;
use App\Models\PosService;
use App\Models\PosPlace;
use App\Models\PosCateservice;
use App\Models\PosCustomerWeb;
use App\Models\PosBooking;
use App\Models\PosPlaceConfiguration;
use App\Helpers\NotificationHelper;
use App\Helpers\MailHelper;

class WebBookingController extends Controller {

    public function listPromotion(Request $request) {

        /* REQUEST FORM DATA example
          toDay: 08-30-2019
          license: 837ec5754f503cfaaee0929fd48974e7
         */

        $promotionModel = new PosPromotion();
        $serviceModel = new PosService();
        $dataAll = $request->all();

        $remote_license = $request->get("license");
        //$remote_license = "837ec5754f503cfaaee0929fd48974e7"; // for test
        $stringWhere = '(promotion_date_start >= CURDATE() OR promotion_date_end >= CURDATE())';
        if (isset($dataAll['date'])) {
            $date = date_create_from_format("m-d-Y", $dataAll['date']);
            $stringWhere = '(promotion_date_start >= ' . $date->format("Y-m-d") . ' OR promotion_date_end >= ' . $date->format("Y-m-d") . ')';
        }
        $query = $promotionModel->selectRaw("pos_promotion.*, IF(promotion_type = 0, '%', '$') as symbol")
                ->join('pos_place', function($join) use($remote_license) {
                    $join->on('pos_place.place_id', '=', 'pos_promotion.promotion_place_id')
                    ->where('pos_place.place_ip_license', '=', $remote_license)
                    ->where('pos_place.place_status', '=', 1);
                })
                ->where('promotion_status', 1)
                ->whereRaw($stringWhere)
                ->orderBy('promotion_date_end', 'DESC');

        $promotion = $query->get();
        foreach ($promotion as $key => $value) {
            if ($value->promotion_listservice_id != '') {
                $condition = explode(";", $value->promotion_listservice_id);
                $service = $serviceModel->where('service_place_id', $value->promotion_place_id)->whereIn('service_id', $condition)->get();
                $service = $this->__dismissKeysListData($service->toArray(), 'service_');
                $value->promotion_lstServices = $service;
            }
            $value->image = ENV("URL_FILE_VIEW") . $value->promotion_image;
            if ($value->promotion_time_end != '')
                $value->promotion_time_end = explode(";", $value->promotion_time_end);
            if ($value->promotion_time_start != '')
                $value->promotion_time_start = explode(";", $value->promotion_time_start);
        }
        $data = $this->__dismissKeysListData($promotion->toArray(), 'promotion_');

        return response()->json([
                    'success' => true,
                    'data' => $data
        ]);
    }

    public function listServiceByCate(Request $request) {
        /* REQUEST FORM DATA         
          license: 837ec5754f503cfaaee0929fd48974e7
         */
        $serviceModel = new PosService();
        $placeModel = new PosPlace();

        $remote_license = $request->get("license");
        //$remote_license = "837ec5754f503cfaaee0929fd48974e7"; // for test
 
        $placeData = $placeModel->selectRaw('place_id AS id, place_timezone AS timezone, place_phone AS phone, 
			place_actiondate AS actiondate, place_actiondate_option AS actiondate_option, place_email as email, place_customer_id as customer_id,
			place_name as name, place_website as website, hide_service_price')
                ->where('place_ip_license', '=', $remote_license)
                ->where('place_status', '=', 1)
                ->first();
        $placeId = "";

        if ($placeData != null) {
            $placeId = $placeData->toArray()['id'];
        } else {
            return response()->json([
                        'success' => false,
                        'msg' => 'The place not found'
            ]);
        }

        $cateServiceModel = new PosCateservice();
        $records = $cateServiceModel->selectRaw('cateservice_id AS id, cateservice_name AS cate')
                        ->where('cateservice_name', '!=', '')
                        ->where('cateservice_place_id', '=', $placeId)
                        ->where('cateservice_status', '=', 1)
                        ->get()->toArray();
        $lstService = $serviceModel->selectRaw('service_id AS id, service_name AS name, service_cate_id AS cate_id,
			service_duration AS duration, service_price AS price')
                        ->where('service_place_id', '=', $placeId)
                        ->where('service_status', '=', 1)
                        ->where('booking_online_status', '=', 1)
                        ->where('enable_status', '=', 1)
                        ->get()->toArray();
        foreach ($records as $key => $value) {
            $lst_services = array();
            foreach ($lstService as $k => $val) {
                if ($value['id'] == $val['cate_id']) {
                    array_push($lst_services, $val);
                }
            }
            $records[$key]['lst_services'] = json_encode($lst_services);
        }
        foreach ($records as $key => $value) {
            if ($value['lst_services'] == '[]')
                unset($records[$key]);
        }

        $records = array_values($records);
        $lstServiceCate = array();
        foreach ($lstService as $key => $value) {
            $flag = true;
            foreach ($records as $k => $val) {
                if ($value['cate_id'] == $val['id']) {
                    $flag = false;
                    break;
                }
            }
            if ($flag)
                array_push($lstServiceCate, $value);
        }
        if (count($lstServiceCate) != 0) {
            $cateItem = array();
            $cateItem['cate'] = 'Other';
            $cateItem['lst_services'] = json_encode($lstServiceCate);
            array_push($records, $cateItem);
        }
        unset($placeData['id']);
        return response()->json([
            'success' => true,
            'data' => $records,
            'place' => $placeData,
            'timezone' => $placeData['place_timezone']
        ]);
    }
/**
 * booking online from website nails
 *    FORM DATA REQUEST
        license: aff1621254f7c1be92f64550478c56e6
        customer[phone]: 32834628342
        customer[name]: asdjada
        customer[email]: test@tagio.com
        customer[gender]: 2
        dateTimeSel: 2019/08/30 14:10
        lstServiceSel[]: 78
        lstServiceSel[]: 80
        booking_note:         
 * @param Request $request
 * @return type
 * @throws Exception
 */
    public function bookingOrderWeb(Request $request) { 
        // dd($request->all());
//        for test     
       // $request->merge([
       //     'license' => "837ec5754f503cfaaee0929fd48974e7",
       //     'dateTimeSel' => "2019/08/30 14:10",
       //     'lstServiceSel' => ["78", "80"],
       //     'booking_note' => 'may be late for traffic jam',
       //     'customer' => ['phone' => "32834628342", 'name' => "linh test", 'email' => "test@gmail.com","gender"=>'2'],
       // ]); 
        
        // check place license 
        $placeData = $this->__getPlace($request->get('license'));
        if (empty($placeData)) {
            return response()->json([
                        "success" => false,
                        "code" => "20100", // 20100 is error code for insert booking order
                        "msg" => "The place not found"
            ],400);
        }
        
        try {            
            DB::beginTransaction();
                        
            // get customer info( create new customer if not found)
            $customer = $this->__getCustomer($request, $placeData['place_id']);
            
            $bookingModel = new PosBooking();
            
            $datetimeSel = date_create_from_format("Y/m/d H:i", $request->get('dateTimeSel'));

            $lastId = $bookingModel->where('booking_place_id', $placeData['place_id'])->max('booking_id');
            $bookingModel->booking_id = intval($lastId) + 1;
            $bookingModel->booking_place_id = $placeData['place_id'];
            $bookingModel->booking_customer_id = $customer['customer_id'];
            $bookingModel->booking_ip = $_SERVER['REMOTE_ADDR'];
            $bookingModel->booking_lstservice = implode(",", $request->get('lstServiceSel'));
            $bookingModel->booking_time_selected = $datetimeSel;            
            $bookingModel->booking_status = 1; //value 0 deleted, value 1 pending verify code, value 2 verified code not approve, value 3 approved
            $bookingModel->booking_type = 3; //NULL: UNKNOWN , 1:Welcome Guest , 2:Client Call, 3: Website
            $bookingModel->booking_note = $request->get('booking_note');
            
            if (!$bookingModel->save()) {
                throw new Exception('Cannot insert booking', 20100);
            }
            //----------------------------------------------------------------------------------
            //TODO 1: send notification 
            // get notification messaage
            $lstNocationSocket = $this->__getNotificationByName(array('booking_insert'), $placeData['place_id']);

            NotificationHelper::send("New Booking","You have a new booking on website!",route('booking-view',$bookingModel->booking_id),$placeData['place_id']);
            
            //TODO 2: send sms confirm to customer
            
            //TODO 3: send email confirm to customer and send email notification to the place
            $listService = $this->getService($request->get('lstServiceSel'),$placeData['place_id']);

            $strService = '';
            foreach ($listService as $value) {
                $strService .= "<br> - Name: ".$value->service_name." price($): ".$value->service_price.", duaration(minutes): ".$value->service_duration;
            }
            $body = "Dear ".$request->customer['name']
            ."<br>You've successfully booker your appointment at ".format_datetime($request->dateTimeSel)
            ."<br>List service: "
            .$strService
            ."<br>Thanks!";

            MailHelper::send("Data Eglobal",$request->customer['email'],"Congratulations",$body);
            //----------------------------------------------------------------------------------
            DB::commit();
            return response()->json([
                  'success' => 1,                  
                  'msg' => 'Booking appointment success',
                  'booking' => $bookingModel,
                  'customer' => $customer,
            ]);
         
        } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => 0,
                    "code" => "20100", // 20100 is error code for insert booking order
                    'msg' => $e->getMessage() . ' line ' . $e->getLine()
                ]);
        }

    }

    private function __dismissKeysListData($array, $string = null) {
        $results = array();
        foreach ($array as $key => $d) {
            foreach ($d as $field => $value) {
                if ($field != 'created_at' && $field != 'updated_at')
                    $row[str_replace($string, "", $field)] = $value;
            }
            array_push($results, $row);
        }
        return $results;
    }

    private function __getNotificationByName($lstName, $placeId) {
        $configurationModel = new PosPlaceConfiguration();

        $dataConfiguration = $configurationModel->selectRaw('pc_name as name, pc_value')
                        ->where('pc_place_id', '=', $placeId)
                        ->where('pc_status', '=', 1)
                        ->whereIn('pc_name', $lstName)->get()->toArray();
        $lstData = array();
        foreach ($dataConfiguration as $value) {
            $lstData[$value['name']] = $value['pc_value'];
        }
        return $lstData;
    }
    /**
     * return customer model ( create if not exits)
     * @param type $request
     * @param type $place_id
     * @return customer id
     */
    private function __getCustomer($request, $place_id){

        $customerModel = new PosCustomerWeb();
        
        $customerRequest = $request->get('customer');

        $customer = $customerModel->selectRaw('customer_id, customer_fullname, customer_phone, customer_email')           
            ->where('customer_phone', '=', $customerRequest['phone'])
            ->where('customer_place_id', '=', $place_id)
            ->where('customer_status', '=', 1)
            ->first();
            // echo ($customer); die();
         if (empty($customer)) {     
            $lastId = $customerModel->where('customer_place_id', $place_id)->max('customer_id');
            $customerModel->customer_id = intval($lastId) + 1;
            $customerModel->customer_email = $customerRequest['email'];
            $customerModel->customer_phone = $customerRequest['phone'];
            $customerModel->customer_fullname = $customerRequest['name'];
            $customerModel->customer_gender = $customerRequest['gender'];
            // $customerModel->customer_birthdate = date('Y').'-01-01';
            $customerModel->customer_status = 1;
            $customerModel->customer_customertag_id = 0;
            $customerModel->customer_place_id = $place_id;
            if (!$customerModel->save()) {
                throw new \Exception('Cannot save customer',20100);
            }            
            $customer = $customerModel;
         }
         
         return $customer;
    }
    private function __getPlace($license){        
        $placeModel = new PosPlace();
        $placeData = $placeModel->selectRaw('place_id, place_timezone, place_email, place_phone, cs_service_id, cs_date_expire, hide_service_price')
                ->join('main_customer', function($join) {
                    $join->on('main_customer.customer_id', '=', 'pos_place.place_customer_id')
                    ->where('main_customer.customer_status', '=', 1);
                })
                ->leftJoin('main_customer_service', function($join) {
                    $join->on('main_customer_service.cs_customer_id', '=', 'main_customer.customer_id')
                    ->where('main_customer_service.cs_service_id', '=', 2)
                    ->where('main_customer_service.cs_status', '=', 1);
                })
                ->where('place_ip_license', '=', $license)
                ->where('place_status', '=', 1)
                ->first();

        return $placeData;
    }

    public function getService($arrServiceId,$placeId){
        $service = PosService::select('service_name','service_duration','service_price')
                            ->where('service_place_id',$placeId)
                            ->whereIn('service_id',$arrServiceId)
                            ->where('service_status',1)
                            ->where('enable_status',1)
                            ->get();
        return $service;
    }


    // public function __sendNotification($token, $title)
    // {
    //     $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    //     $token=$token;

    //     $notification = [
    //         'title' => $title,
    //         'sound' => true,
    //     ];
        
    //     $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

    //     $fcmNotification = [
    //         //'registration_ids' => $tokenList, //multple token array
    //         'to'        => $token, //single token
    //         'notification' => $notification,
    //         'data' => $extraNotificationData
    //     ];

    //     $headers = [
    //         'Authorization: key=Legacy server key',
    //         'Content-Type: application/json'
    //     ];


    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    //     $result = curl_exec($ch);
    //     curl_close($ch);

    //     return true;
    // }

}
