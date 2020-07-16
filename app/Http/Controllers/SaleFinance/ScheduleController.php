<?php

namespace App\Http\Controllers\SaleFinance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelper;
use App\Models\PosPlace;
use App\Models\PosBooking;
use App\Models\PosBookingDetail;
use App\Models\PosWorker;
use App\Models\PosService;
use App\Models\PosCustomer;
use App\Models\PosOrder;
use Carbon\Carbon;
use Session;

class ScheduleController extends Controller
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
        $resource = [];
        $worke_id = [];
        $worker_image = [];


        // $eventColor = ['green','red','orange','yellow','pink','green','red','orange','yellow','pink','green','red','orange','yellow','pink','green','red','orange','yellow','pink'];

        // $place = PosPlace::find($this->getCurrentPlaceId());
        // $date_and_time_open = json_decode($place->place_actiondate, true);
        // $start_time="24:00";
        // $end_time="00:00";

        // foreach($date_and_time_open  as $value){
        //     //dd($value['start']);
        //     if($start_time > $value['start']){
        //         $start_time = $value['start'];
        //     }
        //     if($end_time < $value['end']){
        //         $end_time = $value['end'];
        //     }
        // }
        // $time_now = \Carbon\Carbon::now()->addHours(7)->toTimeString();
        // if($time_now > $start_time)
        //   $start_time = $time_now;
        // $start_time = round($start_time);
        // $end_time = round($end_time);

        $date_action_list = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                  ->first()
                                  ->place_actiondate;

        $date_action_list = json_decode($date_action_list,true);

        //SHOP CLOSE OR OPEND
        $dayOfWeek = \Carbon\Carbon::today()->shortEnglishDayOfWeek;

        if($dayOfWeek == "Thu")
            $dayOfWeek = "thur";
        $dayOfWeek = strtolower($dayOfWeek);

        $time_opend =  $date_action_list[$dayOfWeek]['start'];

        $time_closed =  $date_action_list[$dayOfWeek]['end'];

        if($date_action_list[$dayOfWeek]['closed'] == true){

            $open_close = "closed";

        }if($date_action_list[$dayOfWeek]['closed'] == false){

            $open_close = "opend";
        }
        //END CLOSE OR OPEND

        // event

        $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                  ->where('enable_status',1)
                                  ->where('worker_status',1)
                                  ->get();

        $worker_count =  $worker_list->count();
        $worker_id = [];

        foreach ($worker_list as $key => $worker) {
            $resource[] = [
                "id"=> $worker->worker_id,
                "title"=>$worker->worker_nickname,
                'image' => $worker->worker_avatar
             ];

             $worker_id[] = $worker->worker_id;
        }
        $booking_status_list = GeneralHelper::bookingStatusArray();

        return view('salefinance.schedule' , compact('worker_count','date_action_list','resource','worker_id','open_close','booking_status_list'));
    }
    /**
     * Call ajax use for fullCalendar
     * @param  $request->date || null
     * @return json
     */
    public function getListBooking(Request $request){
      // dd(Session::get('worker_array_session'));
        $event = [];        

        $start_time = "24:00";
        $end_time = "00:00";
        $now = Carbon::now();

        $place = PosPlace::find($this->getCurrentPlaceId());
        $date_and_time_open = json_decode($place->place_actiondate, true);          

        foreach($date_and_time_open  as $value){
            if($start_time > $value['start']){
                $start_time = $value['start'];
            }
            if($end_time < $value['end']){
                $end_time = $value['end'];
            }
        } 
        $time_now = \Carbon\Carbon::now()->addHours(7)->toTimeString();

        if($request->date){
          //not today
          $date = new Carbon($request->date);
          $start_date = $date->startOfDay();
          $end_date = $date->copy()->endOfDay();
        }

        if(!$request->date || format_date($request->date) == format_date($now)){
          //today
          $today = $now;
          $start_date = $today->copy()->startOfDay();
          $end_date = $today->copy()->endOfDay();   
          $start_time = $time_now;       
        }

        $start_time = round($start_time);
        $end_time = round($end_time);
        // echo $start_time."<br>".$end_time; die(); 
        $booking_list = PosBooking::leftjoin('pos_worker',function($join){
                                  $join->on('pos_booking.booking_place_id','pos_worker.worker_place_id')
                                  ->on('pos_booking.booking_worker_id','pos_worker.worker_id');
                                  })
                                  ->where('pos_booking.booking_place_id',$this->getCurrentPlaceId())
                                  ->whereBetween('booking_time_selected',[$start_date,$end_date])
                                  ->where('booking_status','!=',1)
                                  ->get();

        foreach ($booking_list as $booking) {          

        $customer_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                       ->where('customer_id',$booking->booking_customer_id)
                                       ->first();

        $customer_fullname = $customer_list->customer_fullname;

        if($booking->booking_status == 0)
          $color = '#d42423';
        elseif($booking->booking_status == 1)
          $color = '#edd70a';
        elseif($booking->booking_status == 2)
          $color = '#009fd5';
        elseif($booking->booking_status == 3)
          $color = '#307539';
        elseif($booking->booking_status == 4)
          $color = '#bbbdc4';          

          //check multy worker
        if(empty($booking->booking_worker_id)){
            $bookingDetail = PosBookingDetail::select('worker_id','booking_time')->distinct()->where('bookingdetail_place_id',$this->getCurrentPlaceId())
                                              ->where('booking_time',$booking->booking_time_selected)
                                              ->get();
            // echo ($bookingDetail);die();
            foreach ($bookingDetail as $value) {
              $service_duration = PosBookingDetail::select('service_duration')
                                              ->where('bookingdetail_place_id',$this->getCurrentPlaceId())
                                              ->where('booking_time',$booking->booking_time_selected)
                                              ->where('pos_booking_details.worker_id',$value->worker_id)
                                              ->join('pos_service',function($joinService){
                                                $joinService->on('service_place_id','bookingdetail_place_id')
                                                ->on('pos_service.service_id','pos_booking_details.service_id');
                                              })->sum('service_duration');

              $time_end = \Carbon\Carbon::parse($booking->booking_time_selected)->addMinutes($service_duration)->toDateTimeString();

              $event[] = [ 
                "id" => $booking->booking_id,
                "resourceId"=>$value->worker_id,
                "start" => $booking->booking_time_selected,
                "end" => $time_end,
                'title'=>$customer_fullname,
                'color'=> $color,
                'country_code' => $customer_list->customer_country_code,
                'phone'=> $customer_list->customer_phone,  
                'worker_id' =>  $value->worker_id,        
                'booking_time' =>  $value->booking_time,        
              ];
            }      
          } else {
              $lst_service_arr = explode(",", $booking->booking_lstservice);

              $service_duration = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                                ->whereIn('service_id',$lst_service_arr)
                                                ->sum('service_duration');

              $time_end = \Carbon\Carbon::parse($booking->booking_time_selected)->addMinutes($service_duration)->toDateTimeString();

              $event[] = [
                "id" => $booking->booking_id,
                "resourceId"=>$booking->worker_id,
                "start" => $booking->booking_time_selected,
                "end" => $time_end,
                'title'=>$customer_fullname,
                'color'=> $color,
                'country_code' => $customer_list->customer_country_code,
                'phone'=> $customer_list->customer_phone,            
              ];
          }
        }


        $worker_array_session = Session::get('worker_array_session');
        if(!empty($worker_array_session)){
          $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                    ->whereIn('worker_id',$worker_array_session)
                                    ->select('worker_nickname','worker_id','worker_avatar')
                                    ->where('enable_status',1)
                                    ->where('worker_status',1)
                                    ->get();   
        } else {
          $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                  ->where('enable_status',1)
                                  ->where('worker_status',1)
                                  ->get(); 
        }
        $resource = [];
        foreach ($worker_list as $key => $worker) {
              $resource[] = [
                'id' => $worker->worker_id,
                'title' => $worker->worker_nickname,
                'image' => $worker->worker_avatar
              ];
        }      

        $result = [
          'success' => true,
          'event' => $event,
          'start_time' => $start_time,
          'end_time' => $end_time,
          'resource' => $resource,
        ];

        return json_encode($result);
    }

    public function getScheduleByMonth(Request $request){

        $date = explode('-', $request->date);
        return $Schedule = PosBooking::join("pos_customer",function($join){
                                        $join->on("pos_booking.booking_customer_id","=","pos_customer.customer_id")
                                            ->on("pos_booking.booking_place_id","=","pos_customer.customer_place_id");
                                })
                                        ->join('pos_worker',function($join){
                                            $join->on('pos_worker.worker_id',"=","pos_booking.booking_worker_id")
                                            ->on('pos_worker.worker_place_id','=','pos_booking.booking_place_id');
                                        })
                                        ->where('pos_booking.booking_place_id',$this->getCurrentPlaceId())
                                        ->whereYear('pos_booking.booking_time_selected', '=', $date[0])
                                        ->whereMonth('pos_booking.booking_time_selected', '=', $date[1])
                                        ->get();
    }

    public function getServicesByBookingid(Request $request){

        $place_id = $this->getCurrentPlaceId();
        $result = array();
        $service_html ="";
        $total_price=0;
        $booking_item = PosBooking::leftjoin('pos_worker',function($join){
                                  $join->on('pos_booking.booking_place_id','pos_worker.worker_place_id')
                                  ->on('pos_booking.booking_worker_id','pos_worker.worker_id');
                                  })
                                  ->join('pos_customer',function($join){
                                    $join->on('pos_booking.booking_place_id','pos_customer.customer_place_id')
                                    ->on('pos_booking.booking_customer_id','pos_customer.customer_id');
                                  })
                                  ->where('booking_id',$request->id) // $request->id
                                  ->where('booking_place_id', $place_id)
                                  ->first(); 

        $booking_customer_id = $booking_item->booking_customer_id;

        $booking_first = PosBooking::where('booking_customer_id',$booking_customer_id) // $request->id
                                    ->where('booking_place_id', $place_id)
                                    ->where('booking_status',"!=",0)
                                    ->first();

        $booking_last = PosBooking::where('booking_customer_id',$booking_customer_id) // $request->id
                                    ->where('booking_place_id', $place_id)
                                    ->where('booking_status',"!=",0)
                                    ->latest()->first();

        $worker_id_last= $booking_last->booking_worker_id;

        $worker_nickname= PosWorker::where('worker_place_id', $place_id)
                                    ->where('worker_id',$worker_id_last)
                                    ->where('enable_status',1)
                                    ->where('worker_status',1)
                                    ->first();

        $booking_sql = PosBooking::where('booking_place_id',$place_id)
                                  ->where('booking_customer_id',$booking_customer_id)
                                  ->where('booking_status',"!=",0);


        $lst_service = explode(",",$booking_item->booking_lstservice);

        $services = PosService::whereIn('service_id', $lst_service)
                        ->where('service_place_id', $this->getCurrentPlaceId())
                        ->get();
        // $result['total'] = $services->sum('service_price');

        
        if($booking_item->booking_status == 0)
          $result['class_color'] = 'cancel';
        if($booking_item->booking_status == 1)
          $result['class_color'] = 'new_booking';
        if($booking_item->booking_status == 2)
          $result['class_color'] = 'confirm';
        if($booking_item->booking_status == 3)
          $result['class_color'] = 'working';
        if($booking_item->booking_status == 4)
          $result['class_color'] = 'paid';

        //check multy worker
        if($request->worker_id && $request->booking_time){
          // multy worker
          $bookingDetail = PosBookingDetail::select('worker_nickname','service_name','service_duration','service_price')
                                                      ->where('pos_booking_details.worker_id',$request->worker_id)
                                                      ->where('booking_time',$request->booking_time)
                                                      ->where('bookingdetail_place_id',$this->getCurrentPlaceId())
                                                      ->join('pos_worker',function($joinWorker){
                                                        $joinWorker->on('worker_place_id','bookingdetail_place_id')
                                                        ->on('pos_worker.worker_id','pos_booking_details.worker_id');
                                                      })
                                                      ->join('pos_service',function($joinService){
                                                        $joinService->on('service_place_id','bookingdetail_place_id')
                                                        ->on('pos_service.service_id','pos_booking_details.service_id');
                                                      })
                                                      ->get();
          foreach ($bookingDetail as $key => $value) {
            $service_html.="<p>+ ".$value->service_name."<span class='phone'>".$value->service_duration." min</span></p>";
            $total_price+=$value->service_price;
          }
        } else {
          // only one worker
          $lst_service = explode(",",$booking_item->booking_lstservice);

          $services = PosService::whereIn('service_id', $lst_service)
                          ->where('service_place_id', $this->getCurrentPlaceId())
                          ->get();
          
          foreach ($services as $key => $value) {
              $service_html.="<p>+ ".$value->service_name."<span class='phone'>".$value->service_duration." min</span></p>";
              $total_price+=$value->service_price;
          }
        }

        $result['customer_fullname']= $booking_item->customer_fullname;
        $result['customer_phone']= $booking_item->customer_phone;
        $result['customer_email']= $booking_item->customer_email;
        $result['customer_country_code']= $booking_item->customer_country_code;
        $result['worker_name_last'] = $worker_nickname->worker_nickname;
        $result['visit_count'] = $booking_sql->count();
        $result['total_spend']= PosOrder::where('order_place_id',$place_id)
                                ->where('order_status',1)
                                ->where('order_customer_id',$booking_customer_id)
                                ->sum('order_price');
        $result['first_visit'] = format_date($booking_first->booking_time_selected);
        $result['last_visit'] = format_date($booking_last->booking_time_selected);
        
        $result['status'] = \GeneralHelper::convertBookingStatusHtml($booking_item->booking_status);
        $result['status_number'] = $booking_item->booking_status;
        $result['service_html'] = $service_html;
        $result['total_price'] = $total_price;
        $result['booking_datetime'] = format_datetime($booking_item->booking_time_selected);
        $result['customer_description']= $booking_item->customer_note;
        $result['booking_note']= $booking_item->booking_note;   
        
        if($request->worker_id && $request->booking_time){
          // multy worker
          $result['staff_request'] = $bookingDetail->first()->worker_nickname; 
        } else {
          // only one worker
          $result['staff_request'] = $booking_item->worker_nickname;
        }

        return $result;   
    }
    public function getDetailSchedule(Request $request){

        $booking_lstservice = PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                                          ->where('booking_id',$request->booking_id)
                                          ->first()
                                          ->booking_lstservice;

        $booking_lstservice = explode(",", $booking_lstservice);

        $service_list_arr = [];

        $total_price = 0;

        foreach($booking_lstservice as $service){

            $service_list = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                        ->where('service_id',$service)
                                        ->first();

            $total_price += $service_list->service_price;

            $service_list = $service_list->service_name."( duration: ".$service_list->service_duration.")";

            $service_list_arr[] = $service_list;
        }

        $booking_list = PosBooking::join('pos_worker',function($join){
                                    $join->on('pos_booking.booking_place_id','pos_worker.worker_place_id')
                                    ->on('pos_booking.booking_worker_id','pos_worker.worker_id');
                                    })
                                    ->where('worker_place_id',$this->getCurrentPlaceId())
                                    ->where('booking_id',$request->booking_id)
                                    ->select('pos_worker.worker_nickname')
                                    ->first();

        $worker_nickname = $booking_list->worker_nickname;

        $reponse = [
            'service_list' => $service_list_arr,
            'worker_nickname' => $worker_nickname,
            'total_price' =>$total_price
        ];
        return $reponse;

    }
    public function getSchedule(Request $request){

        $event = [];
        $resource = [];

        $date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');

        $start_date = $date. " 00:00:00";

        $end_date = $date. " 23:59:59";

      //GET TIME START
      $place = PosPlace::find($this->getCurrentPlaceId());
        $date_and_time_open = json_decode($place->place_actiondate, true);
        $start_time="24:00";
        $end_time="00:00";

        foreach($date_and_time_open  as $value){
            //dd($value['start']);
            if($start_time > $value['start']){
                $start_time = $value['start'];
            }
            if($end_time < $value['end']){
                $end_time = $value['end'];
            }
        }
        $time_now = \Carbon\Carbon::now()->addHours(7)->toTimeString();
        $date_now = \Carbon\Carbon::now()->addHours(7)->toDateString();
        if($time_now > $start_time && $date_now == $date )
          $start_time = $time_now;
        $start_time = round($start_time);
      //END TIME START


        // $eventColor = ['green','red','orange','yellow','pink','green','red','orange','yellow','pink','green','red','orange','yellow','pink','green','red','orange','yellow','pink'];

        $booking_list = PosBooking::join('pos_worker',function($join){
                                  $join->on('pos_booking.booking_place_id','pos_worker.worker_place_id')
                                  ->on('pos_booking.booking_worker_id','pos_worker.worker_id');
                                  })
                                  ->where('pos_booking.booking_place_id',$this->getCurrentPlaceId())
                                  ->whereBetween('booking_time_selected',[$start_date,$end_date])
                                  ->get();
        //return $booking_list;
        foreach ($booking_list as $booking) {

            $booking_lstservice = $booking->booking_lstservice;

            $lst_service_arr = explode(",", $booking_lstservice);

            $service_duration = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                                ->whereIn('service_id',$lst_service_arr)
                                                ->sum('service_duration');
            if($service_duration == "" || $service_duration == 0){
              $service_duration = 15;
            }
         $time_end = \Carbon\Carbon::parse($booking->booking_time_selected)->addMinutes($service_duration)->toDateTimeString();
         //return $time_end;

         $customer_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                       ->where('customer_id',$booking->booking_customer_id)
                                       ->first();

        $customer_fullname = $customer_list->customer_fullname;

        if($booking->booking_status == 0)
          $color = '#d42423';
        elseif($booking->booking_status == 1)
          $color = '#edd70a';
        elseif($booking->booking_status == 2)
          $color = '#009fd5';
        elseif($booking->booking_status == 3)
          $color = '#307539';
        elseif($booking->booking_status == 4)
          $color = '#bbbdc4';

          $event[] = [
            "id" => $booking->booking_id,
            "resourceId"=>$booking->worker_id,
            "start" => $booking->booking_time_selected,
            'color' => $color,
            "end" => $time_end,
            'title'=> $customer_fullname,
            'country_code' => $customer_list->customer_country_code,
            'phone'=> $customer_list->customer_phone,
            ];
        }
        return $reponse = [
          'event'=>$event,
          'start_time'=>$start_time
        ];
    }
    /**
     * Call ajax set session for resource FullCalendar
     * @param  $request->id
     * @return array  $resource
     */
    public function getResource(Request $request){
        
      $resource = [];

      $id = $request->id;

      $worker_list = $request->worker_list;

      $worker_list_arr = explode(",", $worker_list);

      if (strpos($id, ',') !== false) { 

        $worker_array_session = $worker_list_arr;
      }else{

          $worker_array_session = Session::get('worker_array_session');
         
          if (($key = array_search($id, $worker_array_session)) !== false){

            unset($worker_array_session[$key]);
          }  
          else
            $worker_array_session[] = $id;
      }
      if( $worker_array_session == [] ){

        $worker_array_session = $worker_list_arr;
      }
      //REMOVE SPACE ELEMENT FROM ARRAY
      if (($key = array_search('', $worker_array_session)) !== false) {
        unset($worker_array_session[$key]);
      }
      //END REMOVE
      if( empty($worker_array_session) )
        $worker_array_session = $worker_list_arr;

      $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                  ->whereIn('worker_id',$worker_array_session)
                                  ->select('worker_nickname','worker_id','worker_avatar')
                                  ->where('enable_status',1)
                                  ->where('worker_status',1)
                                  ->get();

      Session::put('worker_array_session',$worker_array_session);

      foreach ($worker_list as $key => $worker) {
          $resource[] = [
            'id' => $worker->worker_id,
            'title' => $worker->worker_nickname,
            'image' => $worker->worker_avatar
          ];
        }
 
        return $resource;
    }
}
