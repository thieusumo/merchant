<?php

namespace App\Http\Controllers\SaleFinance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosBooking;
use App\Models\PosWorker;
use App\Models\PosService;
use App\Models\PosCateservice;
use App\Models\PosPlace;
use App\Models\PosCustomer;
use yajra\Datatables\Datatables;
use Carbon\Carbon;
use Session;
use Validator;
use DB;

use App\Http\Requests;

class TicketController extends Controller
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
     * Show the list of ticket appointment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('salefinance.tickets');
    }
    
    //GET TICKET LIST BY PLACE DATATABLE - BEGIN
     public function getBookingListByPlace(Request $request){

        $order_date = $request->order_date;
        $select_status = $request->select_status;
        $search_namephone = $request->search_namephone;
        
        $ticket_list = PosBooking::join("pos_customer",function($join){
                                            $join->on("pos_customer.customer_id","=","pos_booking.booking_customer_id")
                                                ->on("pos_customer.customer_place_id","=","pos_booking.booking_place_id");
                                        });
        if($order_date!="")
        {
            $order_date_arr = explode(' - ', $order_date);
            $ticket_list =$ticket_list->whereBetween('pos_booking.booking_time_selected', 
                                            array(format_date_db($order_date_arr[0]),format_date_db($order_date_arr[1])));
        }       
        if($select_status >="0")
        {
            $ticket_list =$ticket_list->where('pos_booking.booking_status',$select_status);
        }   

        if($search_namephone!="")
        {
            $ticket_list = $ticket_list->where(function($where) use ($search_namephone) {
                                $where->where('pos_customer.customer_phone','LIKE','%'.$search_namephone.'%')
                                ->orWhere('pos_customer.customer_fullname','LIKE','%'.$search_namephone.'%');
                              });
        }               
            
        $ticket_list = $ticket_list->where('pos_booking.booking_place_id',$this->getCurrentPlaceId())->get();
        //FORMAT COLUMN DATATABLE
        return Datatables::of($ticket_list)
            ->editColumn('booking_id',function($row){
                return  "<a href='".route('booking-view',$row->booking_id)."'>#".$row->booking_id." </a>";
            })
            ->editColumn('booking_date',function($row){
                return  format_date($row->booking_time_selected); 
            })
            ->editColumn('booking_time',function($row){
                return  gettime_by_datetime($row->booking_time_selected); 
            })
            ->editColumn('booking_type',function($row){
                if($row->booking_type == 1)
                {
                    $booking_type = "Welcome Guest";
                }elseif($row->booking_type == 2){

                    $booking_type = "Client Call";
                }elseif($row->booking_type == 3){
                    $booking_type = "Website";
                }else
                    $booking_type = "UNKNOWN";
                return  $booking_type; 
            })
            ->addColumn('duration', function($row){
                $lst_service = explode(",",$row->booking_lstservice);
                return PosService::whereIn('service_id', $lst_service)
                                ->where('service_place_id', $this->getCurrentPlaceId())
                                ->sum('service_duration');
            })
            ->addColumn('customer_name_phone', function($row){
                return $row->customer_fullname.'</br>(+'.$row->customer_country_code.')'.$row->customer_phone;
            })
            ->addColumn('rentstation_service', function($row){
                $worker = PosWorker::where('worker_id',$row->booking_worker_id)
                                        ->where('worker_place_id', $this->getCurrentPlaceId() )
                                        ->first();
                $lst_service = explode(",",$row->booking_lstservice);
                $Services = PosService::whereIn('service_id', $lst_service)
                                ->where('service_place_id', $this->getCurrentPlaceId())
                                ->get();
                                
                $service_list ="";
                foreach ($Services as $key => $value) {
                    $service_list.= ($worker ?($worker->worker_nickname):'[unknown]')." - ".$value->service_name."</br>";
                }
                return $service_list;
            })
            ->addColumn('status', function($row){
                return \GeneralHelper::convertBookingStatusHtml($row->booking_status);
            })
            ->addColumn('action', function($row){
                return " <a href='".route('edit-booking',$row->booking_id)."' class='edit-service btn btn-sm btn-secondary' ><i class='fa fa-pencil fa-lg'></i> </a>" ;
            })
            ->rawColumns(['booking_id','customer_name_phone','rentstation_service' ,'status','action'])
            ->make(true);
     }
    //GET TICKET LIST BY PLACE DATATABLE - END
    
    /**
     * Show the detail of ticket appointment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function view($id = 0)
    {
        $customer_item = PosCustomer::join("pos_booking",function($join){
                                                $join->on("pos_booking.booking_customer_id","=","pos_customer.customer_id")
                                                    ->on("pos_booking.booking_place_id","=","pos_customer.customer_place_id");
                                        })
                                    ->where('pos_customer.customer_place_id', $this->getCurrentPlaceId())
                                    ->where('pos_booking.booking_id', $id)
                                    ->first();
        if(isset($customer_item->booking_worker_id)){
            $worker = PosWorker::where('worker_id',$customer_item->booking_worker_id)
                                            ->where('worker_place_id', $this->getCurrentPlaceId() )
                                            ->first();
            $worker_nickname = $worker->worker_nickname;
        } else {
            $worker_nickname = '';
        }

        $lst_service = explode(",",$customer_item->booking_lstservice);
        $services = PosService::whereIn('service_id', $lst_service)
                        ->where('service_place_id', $this->getCurrentPlaceId())
                        ->get();

        $booking_status_html= $this->dropdown_status_html($customer_item->booking_status);

        return view('salefinance.ticket_detail',compact('customer_item' , 'id' ,'booking_status_html','worker_nickname','services'));
    }

    public function updateTicketStatus(Request $request)
    {
        $success = PosBooking::where('booking_id',$request->id)
                    ->where('booking_place_id',$this->getCurrentPlaceId())
                    ->update(['booking_status'=>$request->status_id]);
        return $this->dropdown_status_html($request->status_id);
        
        

    }

    public static function dropdown_status_html($seleted_id)
    {
        if($seleted_id == 0){
            return '<button data-toggle="dropdown" class="btn btn-round btn-primary dropdown-toggle btn-sm" type="button" aria-expanded="false">
                                <i class="fa fa-trash-o gray-dark" "></i>
                                <span style="padding: 0px 20px;">CANCEL</span><span class="caret">                            
                            </span></button>
                            <ul role="menu" name="dropdown_status" id="dropdown_status" class="dropdown-menu" style="min-width: 200px;">
                              <li class="border-top" value="1"><a  href="#"><i class="fa fa-ticket"></i>NEW APPOINTMENT</a></li>
                              <li class="border-top" value="2"><a  href="#"><i class="fa fa-check-circle-o blue" ></i> VERIFIED</a></li>
                              <li class="border-top" value="3"><a  href="#"><i class="fa fa-arrow-circle-down green" style="margin-right:10px;"></i> APPROVED</a></li>
                            </ul>';
        }elseif($seleted_id==1){
            return '<button data-toggle="dropdown" class="btn btn-round btn-primary dropdown-toggle btn-sm" type="button" aria-expanded="false">
                                <i class="fa fa-ticket"></i>
                                <span style="padding: 0px 10px">NEW APPOINTMENT</span><span class="caret">                            
                            </span></button>
                            <ul role="menu" name="dropdown_status" id="dropdown_status" class="dropdown-menu" style="min-width: 200px;">
                              <li class="border-top" value="0"><a  href="#"> <i class="fa fa-trash-o gray-dark" style="margin-right:10px;"></i> CANCEL</a></li>
                              <li class="border-top" value="2"><a  href="#"><i class="fa fa-check-circle-o blue" ></i> VERIFIED</a></li>
                              <li class="border-top" value="3"><a  href="#"><i class="fa fa-arrow-circle-down green" style="margin-right:10px;"></i> APPROVED</a></li>
                            </ul>';
        }elseif($seleted_id==2){
            return '
        <button data-toggle="dropdown" class="btn btn-round btn-primary dropdown-toggle btn-sm" type="button" aria-expanded="false">
                                <i class="fa fa-check-circle-o blue"></i>
                                <span style="padding: 0px 20px">VERIFIED</span> <span class="caret">                            
                            </span></button>
                            <ul role="menu" name="dropdown_status" id="dropdown_status" class="dropdown-menu" style="min-width: 200px">
                              <li class="border-top" value="0"><a  href="#"><i class="fa fa-trash-o gray-dark" style="margin-right:10px;"></i> CANCEL</a></li>
                              <li class="border-top" value="1"><a  href="#"><i class="fa fa-ticket"></i> NEW APPOINTMENT</a></li>
                              <li class="border-top" value="3"><a  href="#"><i class="fa fa-arrow-circle-down green" style="margin-right:10px;"></i> APPROVED</a></li>
                            </ul>  ';
        }elseif($seleted_id==3){
            return '
        <button data-toggle="dropdown" class="btn btn-round btn-primary dropdown-toggle btn-sm" type="button" aria-expanded="false">
                                <i class="fa fa-arrow-circle-down green"></i>
                                <span style="padding: 0px 20px">APPROVED</span> <span class="caret">                            
                            </span></button>
                            <ul role="menu" name="dropdown_status" id="dropdown_status" class="dropdown-menu" style="min-width: 200px">
                              <li class="border-top" value="0"><a  href="#"><i class="fa fa-trash-o gray-dark" style="margin-right:10px;"></i> CANCEL</a></li>
                              <li class="border-top" value="1"><a  href="#"><i class="fa fa-ticket"></i> NEW APPOINTMENT</a></li>
                              <li class="border-top" value="2"><a  href="#"><i class="fa fa-check-circle-o blue" ></i> VERIFIED</a></li>
                            </ul>  ';
        }
    }
    public function editBooking($id = 0){
        $booking_id = $id;

        $service_session = [];

        $booking_worker_id = "";

        $max_key = 0;

        $date_booking = "";

        $time_booking = "";

        $service_name_list = [];

        $worker_nickname = "";

        $customer_list = "";

        $time_booking_12h = "a";


        if($id > 0){
            $booking_list = PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                                        ->where('booking_id',$id)
                                        ->first();
            //GET INFO WORKER
            $booking_worker_id = $booking_list->booking_worker_id;
           
            $worker_nickname = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                          ->where('worker_id',$booking_worker_id)
                                          ->pluck('worker_nickname');
            //GET INFO CUSTOMER
            $booking_customer_id = $booking_list->booking_customer_id;

            $customer_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                          ->where('customer_id',$booking_customer_id)
                                          ->first();

            $booking_time_selected = $booking_list->booking_time_selected;

            $date_booking = format_date($booking_time_selected);

            $time_booking = gettime_by_datetime($booking_time_selected);

            $booking_lstservice = $booking_list->booking_lstservice;

            $booking_lstservice = explode(",", $booking_lstservice);

            foreach ($booking_lstservice as $key => $service) {

                 $service_session[$key] = [
                    'service_id' => $service,
                    'worker_id' => $booking_worker_id
                ];
                $service_name_arr = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                            ->where('service_id',$service)
                                            ->where('service_status',1)
                                            ->first();
                $service_name_list[] = $service_name_arr->service_name ?? '';
            }

            $max_key = max(array_keys($service_session));
        }
         Session::put('service_arr',$service_session);

        $cateservice_list = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                           ->select('cateservice_id','cateservice_name')
                           ->get();

        $service_list = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                    ->select('service_id','service_name','service_cate_id','service_price','service_duration')
                                    ->where('enable_status',1)
                                    ->where('service_status',1)
                                    ->get();

        $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                  ->where('enable_status',1)
                                  ->select('worker_id','worker_nickname')
                                  ->get();

        $date_action_list = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                  ->first()
                                  ->place_actiondate;

        $date_action_list = json_decode($date_action_list,true);

        return view('salefinance.booking',compact('cateservice_list','service_list','worker_list','date_action_list','service_session','booking_worker_id','max_key','date_booking','service_name_list','worker_nickname','time_booking','booking_id','customer_list','time_booking_12h'));
    }
    
    public function deleteBookingSession(Request $request)
    {
        $key = $request->id_hide;

        $service_arr = Session::get('service_arr');

        if(array_key_exists($key, $service_arr)){

            unset($service_arr[$key]);
        }
        Session::put('service_arr',$service_arr);

         return  Session::get('service_arr');
    }
    public function getBooking(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $date = format_date_db($request->date_booking);
        
        $dayOfWeek = \Carbon\Carbon::parse($request->date_booking)->shortEnglishDayOfWeek;

        if($dayOfWeek == "Thu")
            $dayOfWeek = "thur";
        $dayOfWeek = strtolower($dayOfWeek);

        $place_actiondate = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                      ->first()->place_actiondate;

        $place_actiondate = json_decode($place_actiondate,true);

        $time_opend =  $place_actiondate[$dayOfWeek]['start'];

        $time_closed =  $place_actiondate[$dayOfWeek]['end'];

        if($place_actiondate[$dayOfWeek]['closed'] == true || $date < $today){

            $open_close = "closed";

        }if($place_actiondate[$dayOfWeek]['closed'] == false){

            $open_close = "opend";

        }

        $time_morning = [];

        $time_afternoon = [];

        $time_night = [];

        $time_finish_morning = [];

        $time_finish_afternoon = [];

        $time_finish_night = [];

        //$booking_list = [];

        $service_duration_booking =  [];

        foreach (Session::get('service_arr') as $value){
           $service_duration_booking[] = PosService::where('service_place_id',$this->getCurrentPlaceId())
                        ->where('service_id',$value['service_id'])
                        ->first()
                        ->service_duration;
        }
        if( Session::get('service_id_clone') ) {
            foreach (Session::get('service_id_clone') as $key => $value) {
                $service_duration_booking[] = PosService::where('service_place_id',$this->getCurrentPlaceId())
                        ->where('service_id',$value)
                        ->first()
                        ->service_duration;
            }
        }

         $service_duration_booking = array_sum($service_duration_booking);
//return Session::get('service_arr');

        foreach (Session::get('service_arr') as $value) {

            if(isset($value['worker_id']))

                $worker_id = $value['worker_id'];
            else
                $worker_id = Session::get('worker_id');

            $service_id = $value['service_id'];

            $booking_list = PosBooking::join('pos_place',function($join){

                           $join->on('pos_booking.booking_place_id','pos_place.place_id');
                           })
                           ->where('pos_booking.booking_place_id',$this->getCurrentPlaceId())
                           ->where('pos_booking.booking_worker_id',$worker_id )
                           ->where('pos_booking.booking_time_selected','>=',$date." 00:00:00")
                           ->where('pos_booking.booking_time_selected','<=',$date." 23:59:59")
                           ->get();

           //return $booking_list;

            foreach ($booking_list as $booking) {

                $booking_lstservice = $booking->booking_lstservice;

                $lst_service_arr = explode(",", $booking_lstservice);

                    $service_duration = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                                ->whereIn('service_id',$lst_service_arr)
                                                ->sum('service_duration');

                    $time_booking = $booking->booking_time_selected;

                    if($time_booking >= $date." 00:00:00" && $time_booking <= $date." 11:59:59"){

                       $time_morning[] = [

                        'service_duration_booking' => gettime_by_datetime(\Carbon\Carbon::parse($time_booking)->subMinutes($service_duration_booking)),
                        'time_booking_morning' => gettime_by_datetime($time_booking),
                        'time_finish_morning' => gettime_by_datetime(\Carbon\Carbon::parse($time_booking)->addMinutes($service_duration)),
                        'date' => $date,
                       ];
                    }
                    if($time_booking >= $date." 12:00:00" && $time_booking <= $date." 16:59:59"){

                        $time_afternoon[] = [

                            'service_duration_booking' => gettime_by_datetime(\Carbon\Carbon::parse($time_booking)->subMinutes($service_duration_booking)),
                            'time_booking_afternoon' => gettime_by_datetime($time_booking),
                            'time_finish_afternoon' => gettime_by_datetime(\Carbon\Carbon::parse($time_booking)->addMinutes($service_duration)),
                            'date' => $date,
                        ];
                    }
                    if($time_booking >= $date." 17:00:00" && $time_booking <= $date." 23:59:59"){

                        $time_night[] = [

                            'service_duration_booking' => gettime_by_datetime(\Carbon\Carbon::parse($time_booking)->subMinutes($service_duration_booking)),
                            'time_booking_night' => gettime_by_datetime($time_booking),
                            'time_finish_night' => gettime_by_datetime(\Carbon\Carbon::parse($time_booking)->addMinutes($service_duration)),
                            'date' => $date,
                        ];
                    }
            }
         }
    
        $time = [
            'open_close' => $open_close,
            'time_morning' => $time_morning,
            'time_afternoon' => $time_afternoon,
            'time_night' => $time_night,
            'time_opend' => $time_opend,
            'time_closed' => $time_closed,
        ];
        return $time;
        
    }

    public function getServiceBooking(Request $request)
    {
        $id_stt = $request->id_stt;

        $service_arr = Session::get('service_arr');

        $service = ['service_id' => $request->service_id];
        if(!empty($service_arr)){

            foreach($service_arr as $key => $result)
            {
                $service_arr[$key] = $result;

                if($service != $result)
                {
                        if(isset($service_arr[$id_stt]))
                    {
                        if($key == $id_stt)
                        {
                            $service_arr[$key] = $service;
                        }
                    }
                    elseif(!isset($service_arr[$id_stt])){

                        $service_arr[$id_stt] = $service;
                    }
                }
            }
        }
        else{
             $service_arr[$id_stt] = $service;
            
        }
        Session::put('service_arr',$service_arr);

        return Session::get('service_arr');

    }

    public function addWorkerBooking(Request $request)
    {
        $worker_id = $request->worker_id;

        $service_worker_arr = Session::get('service_arr');

        foreach ($service_worker_arr as $key => $value) {
            $arr = [
                'service_id' => $value['service_id'],
                'worker_id' => $request->worker_id
            ];
            $service_worker_arr[$key] = $arr;
        }
        Session::put('service_arr',$service_worker_arr);

        return Session::get('service_arr');
    }
    public function checkCustomer(Request $request)
    {
        //CHECK CUSTOMER
        if($request->customer_info != "")
        {
            $customer_detail = $request->customer_detail;

            $customer_info = $request->customer_info;

            if($customer_detail == "customer_phone"  || $customer_detail == "giftcard_customer_phone" || $customer_detail == "referral_customer_phone" ){

                $sql = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                    ->where('customer_phone',$customer_info);
            }
            else
                $sql = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                    ->where('customer_email',$customer_info);
            
            $customer_check = $sql->count();

            if($customer_check != 0)
            {
                $customer_list = $sql->first();

                return $customer_list;
            }
        }
    }

    public function sendBooking(Request $request)
    {
        $date_time = $request->date_booking_hidden." ".$request->time_booking_hidden;

        $rule = [
            'customer_fullname' => 'required',
            'customer_phone' => 'required',
        ];
        $message = [
        'customer_fullname.required' => 'Enter Fullname, Please!',
        'customer_phone.required' => 'Enter Phone, Please!',
        ];

        $validator = Validator::make($request->all(),$rule,$message);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else{
            if($request->time_booking_hidden == "")
                return back()->with('message','Choose Time Booking, Please!');

            $customer_phone = $request->customer_phone;
            $customer_email = $request->customer_email;

            //CHECK CUSTOMER TO UPDATE
            $sql = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                               ->where(function($query) use ($customer_phone,$customer_email){
                                     $query->where('customer_phone',$customer_phone)
                                           ->orWhere('customer_email',$customer_email);
                               });

            $customer_check = $sql->count();

            //CHECK BOOKING TO EDIT
            $booking_sql = PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                                         ->where('booking_id',$request->booking_id);

            $booking_check = $booking_sql->count();

            $service_worker_arr = Session::get('service_arr');
            // dd($service_worker_arr);

            $service_booking = [];

            foreach ($service_worker_arr as $value) {

                $service_booking[] = $value['service_id'];

                if(isset($value['worker_id']))

                    $worker_id = $value['worker_id'];
                else
                    $worker_id = Session::get('worker_id');
            }
            $service_booking = implode(",", $service_booking);

            if($booking_check != 0){

                $booking_id = $request->booking_id;
                $booking_code = $booking_sql->first()->booking_code;
            }
            else{
                $count_booking = PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                                    ->whereDate('booking_time_selected',Carbon::today())
                                    ->count();

                $booking_id = PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                                      ->max('booking_id')+1;

                $booking_number = $count_booking + 1;
                $lenght_booking_id =  strlen($booking_number);
                switch ($lenght_booking_id) {
                    case 1: $booking_code = 'B000'.$booking_number; break;
                    case 2: $booking_code = 'B00'.$booking_number; break;
                    case 3: $booking_code = 'B0'.$booking_number; break;
                    case 4: $booking_code = 'B'.$booking_number; break;
                    default: break;
                }
            }
            if($customer_check != 0){

                $customer_id = $sql->first()->customer_id;
            }
            else{
                $customer_id = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                      ->max('customer_id')+1;
            }

            $arr = [
                'booking_id' => $booking_id,
                'booking_code' => $booking_code,
                'booking_place_id' => $this->getCurrentPlaceId(),
                'booking_customer_id' => $customer_id,
                'booking_lstservice' => $service_booking,
                'booking_worker_id' => $worker_id,
                'booking_ip' => $request->ip(),
                'booking_status' => 2, //confirm
                'booking_type' => $request->booking_type,
                'booking_time_selected' => \Carbon\Carbon::parse($date_time)->format('Y-m-d H:i:s'),
            ];
            //dd($arr);

            if($customer_check != 0){

                if($booking_check == 0){

                    $booking = PosBooking::create($arr);

                    $arr_booking_notifi = [
                            'place_id' => $this->getCurrentPlaceId(),
                            'booking_id' => $booking_id,
                            'checked' => 0
                        ];

                    if($booking){

                        $request->session()->flash('message_booking','Booking Success!');
                        return redirect()->route('schedule-index');
                    }else{
                        $request->session()->flash('message','Booking Error!');
                         return back();
                    }
                }elseif ($booking_check != 0) {
                    $booking = $booking_sql->update($arr);

                    if($booking){
                        $request->session()->flash('message_booking','Booking Edit Success!');
                        return redirect()->route('schedule-index');
                    }else{
                         $request->session()->flash('message','Booking Edit Error!');
                         return back();
                    }
                }
            }
            else{

                DB::beginTransaction();

                $idCustomer = PosCustomer::where('customer_place_id','=',$this->getCurrentPlaceId())->max('customer_id') +1;
                $arr_customer = [
                    'customer_id' => $idCustomer,
                    'customer_place_id' => $this->getCurrentPlaceId(),
                    'customer_fullname' => $request->customer_fullname,
                    'customer_phone' => $request->customer_phone,
                    'customer_email' => $request->customer_email,
                    'customer_gender' => $request->customer_gender,
                    'customer_status' => 1,
                ];

                $customer = PosCustomer::create($arr_customer);

                $booking = PosBooking::create($arr);

                if(!$customer || !$booking){
                    DB::callback();
                    $request->session()->flash('message','Booking Error!');
                    return back();
                }
                else{
                    DB::commit();
                    $request->session()->flash('message_booking','Booking Edit Success');
                    return redirect()->route('schedule-index');
                }
            }
        }
    }
    public function bookingFromSchedule($worker_id,$date_selected){

        $booking_id = 0;

        $service_session = [];

        $booking_worker_id = $worker_id;

        $max_key = 0;

        $date_booking = format_date($date_selected);

        $time_booking = Carbon::parse($date_selected)->format('H:i:s');

        $time_booking_12h = gettime_by_datetime($date_selected);

        $service_name_list = [];

        $worker_nickname = "";

        $customer_list = "";

        Session::put('service_arr',$service_session);

        $cateservice_list = PosCateservice::where('cateservice_place_id',$this->getCurrentPlaceId())
                           ->select('cateservice_id','cateservice_name')
                           ->get();

        $service_list = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                    ->select('service_id','service_name','service_cate_id','service_price','service_duration')
                                    ->get();

        $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                 ->where('enable_status',1)
                                  ->select('worker_id','worker_nickname')
                                  ->get();

        if($worker_id != ""){

            $worker_nickname = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                          ->where('worker_id',$worker_id)
                                          ->select('worker_nickname')
                                          ->first()
                                          ->worker_nickname;
        }
        $date_action_list = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                  ->first()
                                  ->place_actiondate;

        $date_action_list = json_decode($date_action_list,true);

        $booking_from_schedule = 'true';

        Session::put('worker_id',$worker_id);

        return view('salefinance.booking',compact('cateservice_list','service_list','worker_list','date_action_list','service_session','booking_worker_id','max_key','date_booking','service_name_list','worker_nickname','time_booking','booking_id','customer_list','booking_from_schedule','time_booking_12h'));
    }
    public function delete(Request $request){
        $booking_id = $request->booking_id;
        if($booking_id != ""){
            PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                        ->where('booking_id',$booking_id)
                        ->update(['booking_status'=>0]);
        }
    }
    public function bookingConfirm(Request $request){

        $booking_id = $request->booking_id;
        if($booking_id != ""){
            PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                      ->where('booking_id',$booking_id)
                      ->update(['booking_status'=>2]);
        }
    }
    public function workingConfirm(Request $request){

        $booking_id = $request->booking_id;
        if($booking_id != ""){
            PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                      ->where('booking_id',$booking_id)
                      ->update(['booking_status'=>3]);
        }
    }

    public function bookingClone($id = ""){
        $booking_from_clone = 'clone';
        $time_booking_12h = 'a';
        $max_key = 0;
        $worker_nickname = '';
        $time_booking = '';
        $customer_list = "";
        $booking_id = 0;
        $service_id_clone = [];
        $service_name_list = [];

        $booking_list = PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                        ->where('booking_id',$id)
                        ->first();

        $service_arr = explode(",", $booking_list->booking_lstservice);

        $service_list = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                    ->whereIn('service_id',$service_arr)
                                    ->where('service_status',1)
                                    ->get();

        foreach ($service_list as $key => $value) {
            $service_name_list[] = $value->service_name."(Duration: ".$value->service_duration."min)-Price: $".$value->service_price;
            $service_id_clone[] = $value->service_id;
        }
        Session::put('service_id_clone',$service_id_clone);

        $worker_id = $booking_list->booking_worker_id;

        Session::put('worker_id',$worker_id);

        $worker_nickname = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                    ->where('worker_id',$worker_id)
                                    ->first()
                                    ->worker_nickname;
        $customer_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                    ->where('customer_id',$booking_list->booking_customer_id)
                                    ->first();

        $date_booking = format_date($booking_list->booking_time_selected);

        $date_action_list = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                    ->first()
                                    ->place_actiondate;

        $date_action_list = json_decode($date_action_list,true);


        return view('salefinance.booking',compact('date_booking','booking_from_clone','date_action_list','booking_id','customer_list','time_booking','worker_nickname','max_key','time_booking_12h','service_name_list','worker_nickname','customer_list')); 

    }
}
