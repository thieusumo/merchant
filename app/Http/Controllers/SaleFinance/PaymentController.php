<?php

namespace App\Http\Controllers\SaleFinance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PosCustomer;
use App\Models\PosService;
use App\Models\PosWorker;
use App\Models\PosBooking;
use App\Models\PosCheckin; 
use App\Models\PosCustomertag;
use App\Models\PosPromotion;
use App\Models\PosCoupon;
use App\Models\PosGiftcode;
use App\Models\PosOrder;
use App\Models\PosPlace;
use App\Models\PosSupplyNail;
use App\Models\PosLoyalty;
use App\Models\PosCateservice;
use App\Models\PosOrderdetail;
use App\Models\PosOrderSupplyNail;
use App\Models\PosUser;
use App\Models\PosCustomerRating;
use App\Models\PosGiftcodeHistory;
use App\Models\PosWorkerCateservice;
use App\Models\PosBookingDetail;
use App\Models\PosCustomerLoyalty;
use App\Models\PosCustomerMembershipHistory;
use yajra\Datatables\Datatables;
use Carbon\Carbon;
use Session;
use Validator;
use DB;
use Auth;
use Response;

class PaymentController extends Controller
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
    public function checkout($id = null)
    {
        $place_id = $this->getCurrentPlaceId();
        $today = Carbon::today();

        //GET LIST PRODUCT
        $product_list = PosSupplyNail::where('sn_place_id',$place_id)
                                    ->where('sn_dateexpired',">=",$today)
                                    ->where('sn_status',1)
                                    ->where('sn_quantity','!=',null)
                                    ->get();
        $list_service = "";
        $customer_info = "";
        
        //GET ORDER LIST SESSION
        if (Session::has('order_list_payment')){
            $order_list = Session::get('order_list_payment');
        }else{$order_list ="";}

        //GET TICKET LIST SESSION
        if (Session::has('ticket_list_payment')){
            $ticket_list = Session::get('ticket_list_payment');
        }else{$ticket_list ="";}

        //GET EXTRA SESSION
        if (Session::has('extra_payment')){
            $extra_payment = Session::get('extra_payment');
        }else{$extra_payment =0 ;}

        //PAY A TICKET
        $click_pay = "";
        $number_of_ticket = 0;
        $booking_code = "";
        $booking_customer_id = "";
        $service_arr = []; 

        if($id != null){
            //CHECK TICKET EXIST
            $exist_ticket = PosBooking::select('booking_id')
                            ->where('booking_place_id',$this->getCurrentPlaceId())
                            ->where('booking_id',$id)
                            ->whereDate('booking_time_selected',Carbon::today())
                            ->count();
            if($exist_ticket == 0)
                return redirect()->route('schedule-index')->with('message','Ticket not exist. Check again');
            else{
                $check_ticket = PosBooking::where('booking_place_id',$this->getCurrentPlaceId())
                                            ->whereDate('booking_time_selected',Carbon::today())
                                            ->where('booking_id',$id)
                                            ->first()
                                            ->booking_status;
                if($check_ticket != 3){
                 return redirect()->route('schedule-index')->with('message','Can pay this ticket now. Check again!');
                }
            }

            $get_ticket_list = PosBooking::select('booking_id','booking_code','booking_customer_id')
                            ->where('booking_place_id',$place_id)
                            ->whereDate('booking_time_selected',$today)
                            ->where('booking_status',3)
                            ->get();
            //GET NUMBER TICKET FOR PAYMENT
            foreach ($get_ticket_list as $key => $value) {
                if($value->booking_id == $id){
                    $number_of_ticket = $key;
                    $booking_code = $value->booking_code;
                    $booking_customer_id = $value->booking_customer_id;
                    $current_customer = $value->booking_customer_id;
                }
            }
            $click_pay = "click";
            //GET SERVICE LIST OF CURRENT TICKET
            // $ticket = PosBooking::where('booking_place_id',$place_id)
            //                     ->where('booking_id',$id)
            //                     ->first();

             $ticket = PosBooking::leftjoin('pos_worker',function($join){
                                    $join->on('pos_booking.booking_place_id','pos_worker.worker_place_id')
                                    ->on('pos_booking.booking_worker_id','pos_worker.worker_id');
                                })
                                ->where('pos_booking.booking_place_id',$this->getCurrentPlaceId())
                                ->whereDate('pos_booking.booking_time_selected',Carbon::today())
                                ->where('booking_id',$id)
                                ->select('pos_booking.booking_code','pos_booking.booking_parent','pos_booking.booking_combine','pos_booking.booking_time_selected','pos_booking.booking_lstservice','pos_booking.booking_worker_id','pos_worker.worker_nickname','pos_booking.booking_customer_id')
                                ->get();             
            $service_arr = self::getListTicketToday($ticket[0],$place_id);
        }

        elseif(Session::has('current_customer_payment') && Session::get('current_customer_payment') >0){
            $current_customer = Session::get('current_customer_payment');

            $customer_info = PosCustomer::join('pos_customertag',function($join){
                                            $join->on('pos_customer.customer_customertag_id','=','pos_customertag.customertag_id')->on('pos_customer.customer_place_id','=','pos_customertag.customertag_place_id');
                                        })
                                    ->where('pos_customer.customer_id',$current_customer)
                                    ->where('pos_customer.customer_place_id',$this->getCurrentPlaceId())
                                    ->first();
            $customer_info->customer_gender = \GeneralHelper::convertGender($customer_info->customer_gender);
            $customer_info->customer_birthdate = format_date($customer_info->customer_birthdate);

        }
        
        else
            {$current_customer =0;}

        $list_customertag = PosCustomertag::where('customertag_place_id', $this->getCurrentPlaceId())
                                            ->get();
        $place_info = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                ->where('place_status',1)->first();

        $service_arr = json_encode($service_arr);
        //SET VAR FOR BUY GITCARD OUTSIDE
        $check = "";
        //GET MEMBERSHIP
        $membership_list = DB::table('pos_membership_detail')
                                    ->join('pos_membership',function($join){
                                    $join->on('pos_membership_detail.membership_detail_membership_id','pos_membership.membership_id');
                                    })
                                    ->where('pos_membership_detail.membership_detail_place_id',$this->getCurrentPlaceId())
                                    ->where('membership_detail_status',1)
                                    ->select('membership_name','membership_detail_price','membership_id')
                                    ->get();
        return view('salefinance.payment',compact('customer_info','current_customer',
                                                    'order_list','list_customertag','extra_payment','ticket_list','list_service','place_info','product_list','check','click_pay','number_of_ticket','booking_code','booking_customer_id','service_arr','membership_list'));
    }
        
    public function getCateServices(){
        return PosCateService::where('cateservice_place_id',$this->getCurrentPlaceId())
                                ->where('cateservice_status',1)
                                ->get();
    }

    public function putSessionPayment(Request $request){
            Session::put($request->action, $request->data);
        return $request->data;   
    }
    /**
     * GET SESSION PAYMENT
     *  
     */
    public function getSessionPayment(Request $request){
        if (Session::has('order_list_payment')){
            $data = Session::get('order_list_payment');
            return response()->json(['success' => 1 , 'session' => $data ]); 
        }else{
            return response()->json(['success' => 0 , 'session' => "" ]); 
        }   
    }

    public function clearSessionPayment(){
        Session::forget('extra_payment');
        Session::forget('current_customer_payment');
        Session::forget('order_list_payment');
    }

    public function getStaffs($selected_cateservice_id = 0){
        $data = [];

        $worker_sql = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                ->where('worker_checkin','!=',0);
        $worker_turn_sum = $worker_sql->sum('worker_turn');
        if($worker_turn_sum == 0)
            $data = $worker_sql->orderBy('worker_turn','asc')->get();
        else{
            $worker = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                    ->where('worker_status',1)
                                    ->orderBy('worker_turn','asc')
                                    ->get()
                                    ->toArray();

            $listCheckin = PosCheckin::selectRaw('checkin_worker_id as worker_id, checkin_type as type')
                ->join(\DB::Raw('(select max(checkin_id) as id from pos_checkin 
                                where checkin_place_id = '.$this->getCurrentPlaceId().' and 
                                Date(checkin_datetime) = \''.get_nowDate('Y-m-d').'\'
                                group by checkin_worker_id 
                                ) checkinTable '),function($join){
                    $join->on('checkinTable.id','=','pos_checkin.checkin_id');
                })
                ->where('checkin_place_id','=',$this->getCurrentPlaceId())
                ->whereRaw('Date(checkin_datetime) = \''.get_nowDate('Y-m-d').'\'')
                ->orderBy('checkin_id','DESC')
                ->get()
                ->toArray();
            foreach ($worker as $key => $value) {
                //$value['checkin'] = false;
                foreach ($listCheckin as $val) {
                    if($value['worker_id'] == $val['worker_id'] && intval($val['type']) == 1){
                        //$value['checkin'] = true;
                        array_push($data, $worker[$key]);
                    }
                }
            }
        }
        return $data;
    }

    public function getServices(Request $request){
        $promotion = PosPromotion::where('promotion_date_start','<=',get_nowDate('Y-m-d'))
                                    ->where('promotion_date_end','>=',get_nowDate('Y-m-d'))
                                    ->whereTime('promotion_time_start','<=',get_nowDate('h:i'))
                                    ->whereTime('promotion_time_end','>=',get_nowDate('h:i'))
                                    ->where('promotion_place_id',$this->getCurrentPlaceId())
                                    ->first();
        $list_service = PosService::where('service_place_id',$this->getCurrentPlaceId())
                            ->where('service_cate_id',$request->id)
                            ->where('service_status',1)
                            ->get();

        if(!empty($promotion)){
            $promotion_services = explode(';', $promotion->promotion_listservice_id);
            //dd($promotion_services);
            foreach ($list_service as $key => $value) {
                if(in_array($value['service_id'], $promotion_services)){
                    $list_service[$key]->promotion_discount = $promotion->promotion_discount;
                    $list_service[$key]->promotion_type = $promotion->promotion_type;
                }  
                else{
                    $list_service[$key]->promotion_discount = '';
                    $list_service[$key]->promotion_type = '';
                }
            }
        }
        else{
            foreach ($list_service as $key => $value) {
                $list_service[$key]->promotion_discount = '';
                $list_service[$key]->promotion_type = '';
            }
        }
        return $list_service;
    }


    /**
     *  New Customer in Payment
     *  @param customer_fullname
     *  @param customer_phone
     *  @param customer_email
     *  @param customer_dateofbirth
     *  @param customer_gender
     *  @param customer_address
     *  @return true/false
     */
    public function saveCustomerInPayment(Request $request)
    {
        $check_exist = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                    ->where('customer_phone', $request->customer_phone)->first();

        $rules = [
            'customer_fullname' => 'required',
            'customer_email' => 'required',
            'customer_dateofbirth' => 'required',
            'customertag_id' => 'required',
            'customer_address' => 'required'
        ];
        $messages = [
            'customer_fullname.required' => "Please enter Full name",
            'customer_phone.required' => 'Please enter phone number',
            'customer_phone.number' => 'Phone is a number',
            'customer_phone.exists' => 'Phone number is exist, Please check again!',
            'customer_dateofbirth.required' => 'Please select Date of birth',
            'customertag_id.required' => 'Please select Group',
            'customer_address.required' => 'Please enter Address'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if(isset($check_exist)){ // PUSH ERROR WHEN EXIST PHONE NUMBER
            $validator->after(function ($validator) {
                $validator->errors()->add('customer_phone', 'Phone number is exist, Please check again!');
            });
        }

        if ($validator->fails()) {
            return response()->json(['status'=>"errors",
                'errors' => $validator->errors()
            ]);
        } 
        else
        {
            $idCustomer = PosCustomer::where('customer_place_id','=',$this->getCurrentPlaceId())->max('customer_id') +1;
            $PosCustomer = new PosCustomer ;
                    $PosCustomer->customer_id = $idCustomer;
                    $PosCustomer->customer_place_id = $this->getCurrentPlaceId();
                    $PosCustomer->customer_history = "";
                    $PosCustomer->customer_fullname = $request->customer_fullname;
                    $PosCustomer->customer_phone = $request->customer_phone;
                    $PosCustomer->customer_country_code = $request->country_code;
                    $PosCustomer->customer_email = $request->customer_email;
                    $PosCustomer->customer_gender = $request->gender;
                    $PosCustomer->customer_birthdate = format_date_db($request->customer_dateofbirth);
                    $PosCustomer->customer_customertag_id = $request->customertag_id;
                    $PosCustomer->customer_address = $request->customer_address;
                    $PosCustomer->customer_status = 1;
                    $PosCustomer->save();

            if($PosCustomer)
                    return response()->json(['status'=>"success" ,'customer_id'=>$PosCustomer->customer_id]);
        }
    }

    /**
    *  Get Promotions
    *  @param mytime
    *  @param date
    *  @param time
    *  @param promtion
    *  @return true/false
    */

    public function getPromotion(){
        $mytime = Carbon::now();
        // $mytime->setTimezone('Asia/Phnom_Penh');
        $date = $mytime->toDateString();
        $time = $mytime->toTimeString();

        $promtion = PosPromotion::where('promotion_place_id','=',$this->getCurrentPlaceId())
                                ->whereRaw('? between promotion_date_start and promotion_date_end', [$date])
                                ->whereTime('promotion_time_start','<=',$time)
                                ->whereTime('promotion_time_end','>=',$time)
                                ->get();
        return $promtion;
    }


    /**
    *  Get Booking List
    *  @param mytime
    *  @param date
    *  @param time
    *  @param promtion
    *  @return true/false
    */

    public function getBookingList(){

        $today = now();

        $start_day = $today->copy()->startOfDay();

        $end_day = $today->copy()->endOfDay();

        $booking = PosBooking::leftJoin('pos_customer',function($join){
                                    $join->on('booking_place_id','=','customer_place_id')
                                    ->on('customer_id','=','booking_customer_id');
                                })
                                ->where('booking_place_id','=',$this->getCurrentPlaceId())
                                ->whereBetween('booking_time_selected',[$start_day,$end_day])
                                ->where('booking_status','!=',4)
                                ->where('booking_status','!=',0)
                                ->select('booking_id','booking_time_selected','booking_status' , 'customer_fullname');
        return Datatables::of($booking)
            ->editColumn('booking_time_selected',function($row){
                return format_datetime($row->booking_time_selected)  ;
            })
            ->editColumn('booking_status',function($row){
                return \GeneralHelper::convertBookingStatusHtml($row->booking_status);
            })
            ->rawColumns(['booking_status'])
            ->make(true);

    }
    public function getBookingFromPayment(Request $request){

        $customer_list = PosBooking::join('pos_customer',function($join){
                                     $join->on('pos_booking.booking_place_id','pos_customer.customer_place_id')
                                     ->on('pos_booking.booking_customer_id','pos_customer.customer_id');
                                    })
                                    ->where('booking_place_id',$this->getCurrentPlaceId())
                                    ->where('booking_id',$request->id)
                                    ->first();

        $customertag_list = PosCustomertag::where('customertag_place_id',$this->getCurrentPlaceId())
                                                ->where('customertag_id',$customer_list->customer_customertag_id)
                                                ->select('customertag_name')
                                                ->first();

        $service_arr = explode(",", $customer_list->booking_lstservice);


        $promotion = PosPromotion::where('promotion_date_start','<=',get_nowDate('Y-m-d'))
                                        ->where('promotion_date_end','>=',get_nowDate('Y-m-d'))
                                        ->whereTime('promotion_time_start','<=',get_nowDate('h:i'))
                                        ->whereTime('promotion_time_end','>=',get_nowDate('h:i'))
                                        ->where('promotion_place_id',$this->getCurrentPlaceId())
                                        ->first();

        $list_service = PosService::where('service_place_id',$this->getCurrentPlaceId())
                                        ->whereIn('service_id',$service_arr)
                                        ->get();

        $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                ->where('worker_id',$customer_list->booking_worker_id)
                                ->select('worker_nickname','worker_id')
                                ->first(); 

        if(!empty($promotion)){
            $promotion_services = explode(';', $promotion->promotion_listservice_id);
            //dd($promotion_services);
            foreach ($list_service as $key => $value) {
                if(in_array($value['service_id'], $promotion_services)){
                    $list_service[$key]->promotion_discount = $promotion->promotion_discount;
                    $list_service[$key]->promotion_type = $promotion->promotion_type;
                    $list_service[$key]->staff_name = $worker_list->worker_nickname;
                    $list_service[$key]->staff_id = $worker_list->worker_id;
                }  
                else{
                    $list_service[$key]->promotion_discount = '';
                    $list_service[$key]->promotion_type = '';
                    $list_service[$key]->staff_name = $worker_list->worker_nickname;
                    $list_service[$key]->staff_id = $worker_list->worker_id;
                }
            }
        }
        else{
            foreach ($list_service as $key => $value) {
                $list_service[$key]->promotion_discount = '';
                $list_service[$key]->promotion_type = '';
                $list_service[$key]->staff_name = $worker_list->worker_nickname;
                $list_service[$key]->staff_id = $worker_list->worker_id;
            }
        }

        $customer_list->list_service = $list_service;

        $customer_list->customertag_name = $customertag_list->customertag_name;

        $customer_list->customer_birthdate = format_date($customer_list->customer_birthdate);

        return $customer_list;
    }

    public function getPointFromCoupon(Request $request){

        // service_list_payment:[{"staff_id":"3","staff_name":"leone","service_id":"447","service_name":"Polish Change(feet) Color","service_price":"7","promotion_discount":null,"promotion_type":null}

        // return $request->all();

        $coupon_sql = PosCoupon::where('coupon_place_id',$this->getCurrentPlaceId())
                                ->where('coupon_code',$request->coupon_code);
        //CHECK EXIST COUPON CODE
        $count = $coupon_sql->count();

        $data = [];

        if($count == 0){

            return $data = [
                    "coupon_cash" =>  0,
                    "message"=>"Can't found this coupon code!"
                ];

        }else{

            $today = today();

            $coupon_list = $coupon_sql->where('coupon_startdate','<=',$today)
                                      ->where('coupon_deadline','>=',$today)
                                      ->first();
            if($coupon_list != ""){

                $coupon_type = $coupon_list->coupon_type;
                $coupon_list_service = $coupon_list->coupon_list_service;
                $service_list_payment = $request->service_list_payment;

                if($coupon_list_service  != NULL){
                    $list_service = explode(";", $coupon_list_service);
                    $service_price = 0;

                    foreach ($list_service as $key => $service) {
                        foreach ($service_list_payment as $key_payment => $service_payment) {
                            if($service_payment['service_id'] == $service){
                                $service_price += $service_payment['service_price'];
                            }
                        }
                    }
                    if( $service_price == 0){
                        if(count($service_list_payment) ==1)
                            return $data = [
                                "coupon_cash" =>  0,
                                "message"=>"This coupon does not include this service!"
                            ];
                        else
                            return $data = [
                                "coupon_cash" =>  0,
                                "message"=>"This coupon does not include these services!"
                            ];
                    }else{
                        if($coupon_type == 0){

                            $coupon_cash = $coupon_list->coupon_discount*$service_price/100;

                        }else{

                            $coupon_cash = $coupon_list->coupon_discount;
                        }
                        return $data = [
                            'coupon_cash' => $coupon_cash,
                        ];
                    }
                }else{
                    return $data = [
                        "coupon_cash" =>  0,
                        "message"=>"This coupon does not include this service!"
                    ];
                }

            }else{
                return $data = [
                        "coupon_cash" =>  0,
                        "message"=>"This coupon does not include today!"
                    ];
            }
        }
    }
    public function getPointFromPayment(Request $request){

       $customer_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                            ->where('customer_id',$request->customer_id)
                                            ->first();

        $point_total = $customer_list->customer_point_total;

        return $point_total;
    }
    public function getGiftcardCode(Request $request){

        $giftcode_sql = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                    ->where('giftcode_code',$request->giftcard_code);
        //CHECK EXIST COUPON CODE
        $count = $giftcode_sql->count();

        $data = [];

        $order_giftcard_amount = [];

        if($count == 0){

            return $data = [
                    "giftcard_price" =>  0,
                    "message"=>"Can't found this giftcard code!"
                ];

        }else{

            $today = today();

            $giftcode_list = $giftcode_sql->first();
            
                $giftcard_price = $giftcode_list->giftcode_balance;

                return $data = [
                    'giftcard_price' => $giftcard_price,
                ];
        }
    }
    public function getCustomerInfoPayment(Request $request){

        $result = [];
        $customer_id = $request->customer_id;
        $place_id = $this->getCurrentPlaceId();

        $customer_info = PosCustomer::where('customer_place_id',$place_id)
                                    ->where('customer_id',$customer_id)
                                    ->first();

        $result['customer_fullname'] = $customer_info->customer_fullname;
        $result['customer_email'] = $customer_info->customer_email;
        $result['customer_phone'] = $customer_info->customer_phone;
        $result['description'] = $customer_info->customer_note;
        $result['customer_point_total'] = $customer_info->customer_point_total;
        $result['customer_point'] = $customer_info->customer_point;

        $loyalty_list = PosLoyalty::where('loyalty_place_id',$place_id)
                                    ->first();
        $point_convert_to_amount = explode("-",$loyalty_list->loyalty_point_to_amount);
        //CONVERT POINT TO AMOUNT
        $result['total_amount_after_convert'] = round($point_convert_to_amount[1]/$point_convert_to_amount[0]*$result['customer_point_total'],2);
        $result['balance_amount_after_convert'] = round($point_convert_to_amount[1]/$point_convert_to_amount[0]*$result['customer_point'],2);
        
        $booking_info = PosBooking::where('booking_place_id',$place_id)
                                    ->where('booking_status',4)
                                    ->where('booking_customer_id',$customer_id);
        
        $result['order_count'] = $booking_info->count();

        if( $result['order_count'] != 0 ){

            $result['first_visit'] = format_date($booking_info->first()->booking_time_selected);
            $result['last_visit'] = format_date($booking_info->latest()->first()->booking_time_selected);
            $last_staff_id = $booking_info->latest()->first()->booking_worker_id;

            if($last_staff_id == ""){
                $last_staff_id = PosBooking::join('pos_booking_details',function($join){
                    $join->on('pos_booking.booking_place_id','pos_booking_details.bookingdetail_place_id')
                    ->on('pos_booking.booking_code','pos_booking_details.booking_code');
                })
                ->where('pos_booking.booking_place_id',$place_id)
                ->where('pos_booking.booking_customer_id',$customer_id)
                ->where('pos_booking.booking_status',4)
                ->latest()
                ->first()
                ->worker_id;
            }
            $result['last_staff_name'] = PosWorker::where('worker_place_id',$place_id)
                                        ->where('worker_id',$last_staff_id)
                                        ->first()
                                        ->worker_nickname;

            $result['total_price'] = PosOrder::where('order_place_id',$place_id)
                                ->where('order_customer_id',$customer_id)
                                ->where('order_status',1)
                                ->sum('order_price');
        }
        else{

            $result['first_visit'] = "";
            $result['last_visit'] = "";
            $last_staff_id = "";
            $result['last_staff_name'] = "";
            $result['total_price'] = "";
        }
        //GET RATING
        $rating = 0;
        $customer_rating = PosCustomerRating::where('cr_place_id',$this->getCurrentPlaceId())
                            ->where('cr_email',$customer_info->customer_email)
                            ->orWhere('cr_phone',$customer_info->customer_phone)
                            ->latest()
                            ->first();

        if( $customer_rating != "" && $customer_rating->cr_rating != "")
            $rating = $customer_rating->cr_rating;
        $result['rating'] = $rating;
        //GET MEMBERSHIP
        $customer_membership_id = $customer_info->customer_membership_id;
        if( $customer_membership_id == 0 )
            $result['membership'] = 'No Membership';
        if( $customer_membership_id == 1 )
            $result['membership'] = 'Normal Membership';
        if( $customer_membership_id == 2 )
            $result['membership'] = 'Silver Membership';
        if( $customer_membership_id == 3 )
            $result['membership'] = 'Golden Membership';
        if( $customer_membership_id == 4 )
            $result['membership'] = 'Dimond Membership';

        if(!empty($result))
            return $result;
        else
            return 0;

    }
    public function buyGifcard(Request $request){
        $rule = [
            'customer_fullname' => 'required',
            'customer_phone' => 'required',
            'giftcode_price' => 'required',
            'giftcode_sale_date' => 'required',
        ];
        $message = [
        'customer_fullname.required' => 'Enter Fullname, Please!',
        'customer_phone.required' => 'Enter Phone, Please!',
        'giftcode_price.required' => 'Enter Giftcode, Please!',
        'giftcode_sale_date.required' => 'Enter Giftcode Amount, Please!',
        ];

        $validator = Validator::make($request->all(),$rule,$message);

        if($validator->fails()){
            return Response::json(array(
                'success' => 'errors',
                'message' => $validator->getMessageBag()->toArray()

            ), 400);
        }
        else{
                $customer_phone = $request->customer_phone;

                $customer_email = $request->customer_email;

                //CHECK CUSTOMER TO UPDATE
                $sql = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                   ->where(function($query) use ($customer_phone,$customer_email){
                                         $query->where('customer_phone',$customer_phone)
                                               ->orWhere('customer_email',$customer_email);
                                   });

                $customer_check = $sql->count();

                if($customer_check != 0){

                    $customer_id = $sql->first()->customer_id;
                }
                else{
                    $customer_id = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                          ->max('customer_id')+1;
                }
                DB::beginTransaction();
                //GET USER BUY GIFTCARD
                $user_current = Session::get('user_current');
                //SET GIFTCODE_TYPE FOR HISTORY GIFTCODE SAVE
                if(isset($request->giftcode_redemption))
                    $giftcode_type = 1;
                elseif(isset($request->giftcode_bonus_point))
                    $giftcode_type = 2;
                else
                    $giftcode_type = 3;

                $customer = "q";
                //IF HAS NOT CUSTOMER ALREADY -> INSERT CUSTOMER + INSER GIFTCODE
                if($customer_check == 0){

                    $customer_arr = [
                        'customer_id' => $customer_id,
                        'customer_place_id' => $this->getCurrentPlaceId(),
                        'customer_fullname' => $request->customer_fullname,
                        'customer_phone' => $request->customer_phone,
                        'customer_email' => $request->customer_email,
                        'customer_gender' => 2,
                        'customer_status' => 1,
                    ];

                    $customer = PosCustomer::create($customer_arr);

                    //INSERT GIFTCODE TO GIFTCARD
                    foreach ($request->giftcode_code as $key => $giftcode_code) {

                        $giftcard_arr =[
                            'giftcode_id' => PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())->max('giftcode_id')+1,
                            'giftcode_place_id' => $this->getCurrentPlaceId(),
                            'giftcode_code' => $giftcode_code,
                            'giftcode_price' => $request->giftcode_price,
                            'giftcode_balance' => $request->giftcode_price,
                            'giftcode_customer_id' => $customer_id,
                            'giftcode_sale_date' => format_date_db($request->giftcode_sale_date),
                            'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                            'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,
                            'giftcode_status' => 1,
                        ];
                        $giftcard = PosGiftcode::create($giftcard_arr);

                        //SAVE INFORATION HISTORY FOR GIFTCODE
                        $giftcode_arr = [
                            'place_id' => $this->getCurrentPlaceId(),
                            'giftcode_code' => $giftcode_code,
                            'giftcode_use' => $request->giftcode_price,
                            'created_by' => $user_current,
                            'updated_by' => $user_current,
                            'giftcode_type' => $giftcode_type,
                            'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                            'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,

                        ];
                        PosGiftcodeHistory::create($giftcode_arr);

                        $giftcode_history_use = $request->giftcode_price;
                        $giftcode_code = $giftcode_code;
                    }
                    //ELSE CUSTOMER EXIST-> UPDATE OR INSERT GIFTCARD
                }else{
                    $giftcode_sql = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                        ->where('giftcode_customer_id',$customer_id)
                                        ->where('giftcode_bonus_point',NULL);
                    $giftcode_count = $giftcode_sql->count();

                    //IF REFERRAL GIFTCARD
                    if( isset($request->giftcode_bonus_point) ){
                        foreach ($request->giftcode_code as $key => $giftcode_code) {
                            $giftcard_arr =[
                                'giftcode_id' => PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())->max('giftcode_id')+1,
                                'giftcode_place_id' => $this->getCurrentPlaceId(),
                                'giftcode_code' => $giftcode_code,
                                'giftcode_price' => $request->giftcode_price,
                                'giftcode_balance' => $request->giftcode_price,
                                'giftcode_customer_id' => $customer_id,
                                'giftcode_sale_date' => format_date_db($request->giftcode_sale_date),
                                'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                                'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,
                                'giftcode_status' => 1,
                            ];
                            $giftcard = PosGiftcode::create($giftcard_arr);
                            //SAVE HISTORY GIFTCODE
                            $giftcode_arr = [
                            'place_id' => $this->getCurrentPlaceId(),
                            'giftcode_code' => $giftcode_code,
                            'giftcode_use' => $request->giftcode_price,
                            'created_by' => $user_current,
                            'updated_by' => $user_current,
                            'giftcode_type' => $giftcode_type,
                            'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                            'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,

                            ];
                            PosGiftcodeHistory::create($giftcode_arr);
                        }
                    }
                    //IF EXIST GIFTCARD -> UPDATE GIFTCARD
                    elseif( !$request->giftcode_redemption && $giftcode_count > 0){
                        
                        $giftcode_code_database = $giftcode_sql->latest()->first()->giftcode_code;

                         foreach ($request->giftcode_code as $key => $giftcode_code) {
                            //IF GIFTCARD CODE NOT EXACTLY-> RETURN BACK
                            if($giftcode_code_database !== $giftcode_code){
                                return Response::json(array(
                                    'success' => false,
                                    'message' => 'This giftcard code not exactly!'

                                ), 400);
                            //ELSE IF GIFTCARD CODE EXACTLY -> UPDATE
                            }else{
                                $giftcode_infor = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                                            ->where('giftcode_code',$giftcode_code)
                                                            ->first();
                                $giftcode_balance = $giftcode_infor->giftcode_balance;
                                $giftcode_price = $giftcode_infor->giftcode_price;
                                $giftcard = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                            ->where('giftcode_code',$giftcode_code)
                                            ->update([
                                                'giftcode_price' => floatval($request->giftcode_price)+floatval($giftcode_price),
                                                'giftcode_balance' => floatval($request->giftcode_price)+floatval($giftcode_balance),
                                                'giftcode_sale_date' => format_date_db($request->giftcode_sale_date),
                                                'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                                                'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,
                                                'giftcode_status' => 1,
                                            ]);
                                //SAVE HISTORY GIFTCODE
                                $giftcode_arr = [
                                'place_id' => $this->getCurrentPlaceId(),
                                'giftcode_code' => $giftcode_code,
                                'giftcode_use' => $request->giftcode_price,
                                'created_by' => $user_current,
                                'updated_by' => $user_current,
                                'giftcode_type' => $giftcode_type,
                                'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                                'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,

                                ];
                                PosGiftcodeHistory::create($giftcode_arr);
                            }
                        }
                    }
                    //IF NOT EXIST GIFTCARD -> INSERT GIFTCARD
                    else{
                        foreach ($request->giftcode_code as $key => $giftcode_code) {
                            $giftcard_arr =[
                                'giftcode_id' => PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())->max('giftcode_id')+1,
                                'giftcode_place_id' => $this->getCurrentPlaceId(),
                                'giftcode_code' => $giftcode_code,
                                'giftcode_price' => $request->giftcode_price,
                                'giftcode_balance' => $request->giftcode_price,
                                'giftcode_customer_id' => $customer_id,
                                'giftcode_sale_date' => format_date_db($request->giftcode_sale_date),
                                'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                                'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,
                                'giftcode_status' => 1,
                            ];
                            $giftcard = PosGiftcode::create($giftcard_arr);
                            //SAVE HISTORY GIFTCODE
                            $giftcode_arr = [
                            'place_id' => $this->getCurrentPlaceId(),
                            'giftcode_code' => $giftcode_code,
                            'giftcode_use' => $request->giftcode_price,
                            'created_by' => $user_current,
                            'updated_by' => $user_current,
                            'giftcode_type' => $giftcode_type,
                            'giftcode_redemption' => $request->giftcode_redemption ?? NULL,
                            'giftcode_bonus_point' => $request->giftcode_bonus_point ?? NULL,

                            ];
                            PosGiftcodeHistory::create($giftcode_arr);
                        }
                    }
                }
                    if(!$customer || !$giftcard || !$giftcode_arr){
                        DB::callback();
                        return Response::json(array(
                            'success' => false,
                            'message' => 'Get Giftcard Error. Check again!'
                        ), 400);
                    }
                    else{
                        DB::commit();
                        $giftcode_balance = 0;
                        if(isset($request->giftcode_redemption)){
                            $giftcode_balance = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                        ->where('giftcode_code',$giftcode_code)
                                        ->first()
                                        ->giftcode_balance;
                        }
                        return Response::json(array(
                            'success' => true,
                            'giftcode_code' => $giftcode_code,
                            'giftcode_balance' => $giftcode_balance,
                            'message' => 'Get Giftcard Success!'
                        ), 200);
                    }
        }
    }
    public function getProduct(Request $request){

        $product = PosSupplyNail::where('sn_place_id',$this->getCurrentPlaceId())
                                ->where('sn_id',$request->product_id)
                                ->where('sn_status',1)
                                ->first();
        return $product;
    }
    public function convertUserPointToAmount(Request $request){

        $ratio_point_to_reward = PosLoyalty::where('loyalty_place_id',$this->getCurrentPlaceId())
                                            ->first()
                                            ->loyalty_point_to_amount;

        $ratio = explode("-",$ratio_point_to_reward);

        $reward_after_convert = round($ratio[1]/$ratio[0]*$request->user_point);
        
        return $reward_after_convert;
    }
    public function checkPassForDeleteTicket(Request $request){
        $user_check = PosUser::where('user_place_id',$this->getCurrentPlaceId())
                                ->where('user_pin',$request->pass_for_delete)
                                ->first();
        if( isset($user_check) ){
            Session::put('user_current',$user_check->user_id);
            return '1';
        }
        else{
            return '0';
        }
    }
    public function saveTicketToDatabase(Request $request){

        $total_point_earn = 0;
        $place_id = $this->getCurrentPlaceId();
        $today = Carbon::today();
        $payment_list = $request->payment_list;

        DB::beginTransaction();
        //IF UPDATE TICKET, DONT DO THIS
        if( $request->correct_ticket == ""){
                //UPDATE STATUS TICKET IN POS BOOKING
                $ticket_arr = [];
                if(is_array($payment_list['ticket_combine'])){
                    $ticket_arr = $payment_list['ticket_combine'];
                    $ticket_arr[] = $payment_list['ticket_no'];
                }else{
                    $ticket_arr[] = $payment_list['ticket_no'];
                }
                PosBooking::where('booking_place_id',$place_id)
                            ->whereDate('booking_time_selected',$today)
                            ->whereIn('booking_code',$ticket_arr)
                            ->update(['booking_status'=>4]);

                //IF TICKET NOT CANCEL, WILL CALCULATE POINT
                $loyalty_list = PosLoyalty::where('loyalty_place_id',$this->getCurrentPlaceId())
                                        ->first();
                //CONVERT PRICE TO POINT
                if( $loyalty_list->loyalty_price_to_point != ""){

                    $convert_price_to_point = explode('-',$loyalty_list->loyalty_price_to_point);
                    $total_charge = $payment_list['total_price'];
                    $price_to_point = floatval($total_charge) * $convert_price_to_point[1] / $convert_price_to_point[0];
                    $total_point_earn += $price_to_point;
                }
                //CONVERT SERVICE TO POINT
                if( $loyalty_list->loyalty_service_to_point != ""){
                    $convert_service_to_point = explode('-', $loyalty_list->loyalty_service_to_point);
                    $service_to_point = count($request->service_list_payment) * $convert_service_to_point[1] / $convert_service_to_point[0];
                    $total_point_earn += $service_to_point;
                }
                // REWARD POINT WHEN PAYING CASH
                if( $payment_list['cash'] && $payment_list['cash'] > 0  ){
                    $reward_poin_paying_cash = $loyalty_list->loyalty_paying_by_cash;
                    $total_point_earn += $reward_poin_paying_cash;
                }
                //CHECK TIME RETURN
                $month_order = Carbon::parse($payment_list['date'])->format('m');
                $time_return = PosOrder::where('order_place_id',$this->getCurrentPlaceId())
                                        ->where('order_customer_id',$payment_list['customer_id'])
                                        ->whereMonth('order_datetime_payment',$month_order)
                                        ->where('order_status',1)
                                        ->count();

                //REWARD POINT WHEN TIME RETURN CURRENT MONTH
                if( $loyalty_list->loyalty_return_in_a_month != "" && $time_return > 0){

                    $loyalty_time_point_list = explode(';', $loyalty_list->loyalty_return_in_a_month );

                    if( $time_return < count($loyalty_time_point_list) )

                        $loyalty_time_point = explode('-', $loyalty_time_point_list[$time_return-1]);

                    if( $time_return >= count($loyalty_time_point_list) )

                        $loyalty_time_point = explode('-', $loyalty_time_point_list[count($loyalty_time_point_list)-1]);

                    $reward_point_return_time = $loyalty_time_point[1];

                    $total_point_earn += $reward_point_return_time;
                }
                //REWARD POINT FOR NEW CUSTOMER OR VIP
                    //NEW CUSTOMER
                    if( $time_return == 0){
                        $point_for_customer = $loyalty_list->loyalty_new_customer;
                        $total_point_earn += $point_for_customer;
                    }
                    //VIP CUSTOMER
                    $customer_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                                ->where('customer_id',$payment_list['customer_id'])
                                                ->where('customer_status',1)
                                                ->first();

                    $customer_point_total = $customer_list->customer_point_total;

                    if( $customer_point_total >= $loyalty_list->loyalty_vip_point )

                        $total_point_earn += $loyalty_list->loyalty_vip_customer;

                //POINT WHEN BUY GIFTCARD OR PRODUCT
                    if( isset($payment_list['point_earn']) )
                        $total_point_earn += $payment_list['point_earn'];

                    //UPDATE GIFTCARD
                    $giftcard_balance = $payment_list['giftcard_price'] - floatval($payment_list['giftcard_pay']);
                    PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                    ->where('giftcode_code',$payment_list['giftcard_code'])
                                    ->update(['giftcode_balance' => $giftcard_balance]);

                    //GET POINT FORM GIFTCARD
                    $giftcard_list = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                                ->where('giftcode_code',$payment_list['giftcard_code'])
                                                ->first();

                    if( $giftcard_list != ""){

                        $giftcode_redemption = $giftcard_list->giftcode_redemption;
                        $giftcode_price = $giftcard_list->giftcode_price;
                        $giftcode_bonus_point = $giftcard_list->giftcode_bonus_point;

                        if( $giftcode_redemption >0 && $giftcode_redemption != null ){
                            $giftcode_point_earn = $request->giftcard_pay * $giftcode_redemption / $giftcode_price;
                        }
                        if( $giftcode_bonus_point >0 && $giftcode_bonus_point != null ){
                            $giftcode_point_earn = $request->giftcard_pay * $giftcode_bonus_point / $giftcode_price;
                        }
                        //IF CURRENT CUSTOMER AND CUSTOMER'S GIFTCARD ARE THE SAME
                        if( $giftcard_list->giftcode_customer_id ==  $payment_list['customer_id'])
                            $total_point_earn += $giftcode_point_earn;

                        //ELSE
                        $other_customer_sql = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                    ->where('customer_id',$giftcard_list->giftcode_customer_id);

                        $other_customer_list = $other_customer_sql->first();

                        $other_point_total = $other_customer_list->customer_point_total;
                        $other_point = $other_customer_list->customer_point;
                        //UPDATE POINT CUSTOMER DATABASE
                        $other_customer_sql->update([
                                               'customer_point_total' => $other_point_total + $giftcode_point_earn,
                                               'customer_point' => $other_point + $giftcode_point_earn
                                            ]);
                    }
                    //GET POINT FOR MEMBERSHIP
                    $membership_id = $customer_list->customer_membership_id;
                    if( $membership_id == 1 )
                        $total_point_earn += $loyalty_list->loyalty_for_normal;
                    if( $membership_id == 2 )
                        $total_point_earn += $loyalty_list->loyalty_for_siver;
                    if( $membership_id == 3 )
                        $total_point_earn += $loyalty_list->loyalty_for_golden;
                    if( $membership_id == 4 )
                        $total_point_earn += $loyalty_list->loyalty_for_dimond;

                    //UPDATE  POINT TO CUSTOMER
                    $customer_point_balance = $customer_list->customer_point - floatval($payment_list['use_point']);
                    $customer_point_total = $total_point_earn + $customer_list->customer_point_total;
                    $customer_point = $total_point_earn + $customer_point_balance;

                    PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                    ->where('customer_id',$payment_list['customer_id'])
                                    ->update([
                                    'customer_point_total' => $customer_point_total,
                                    'customer_point_use' =>  $customer_point_balance,
                                    'customer_point' => $customer_point,
                                    'customer_lastest_order' => $payment_list['date']
                                        ]);
                    //UPDATE POINT TO POS CUSTOMER LOYALTY
                    $cl_id_max = PosCustomerLoyalty::where('cl_place_id',$place_id)
                                                    ->max('cl_id');
                    $cl_arr = [
                        'cl_id' => $cl_id_max+1,
                        'cl_customer_id' => $payment_list['customer_id'],
                        'cl_place_id' => $place_id,
                        'cl_score'=> $total_point_earn,
                        'cl_used' => $payment_list['use_point'],
                        'cl_status' => 1
                    ];
                    PosCustomerLoyalty::create($cl_arr);
        }
        if( $request->correct_ticket == ""){
            $order_id = PosOrder::where('order_place_id',$this->getCurrentPlaceId())->max('order_id')+1;
        }else
            $order_id = PosOrder::where('order_place_id',$this->getCurrentPlaceId())
                                ->whereDate('order_datetime_payment',$today)
                                ->where('order_bill',$payment_list['ticket_no'])
                                ->first()
                                ->order_id;
        //SET TIP FOR STAFF
        $tip_list = $request->tip_list;
        $staff_list_payment = $request->staff_list_payment;
        $tip = [];

        if( is_array($staff_list_payment) && count($staff_list_payment) != 0 ){

            foreach ($staff_list_payment as $key => $staff) {

                if( is_array($tip_list) &&  count($tip_list) != 0)
                    $tip[$staff] = $tip_list[$key];
                else
                    $tip[$staff] = 0;
            }
        }
        //SET ORDER PAYMENT MENTHOD
        if( $payment_list['giftcard_pay'] != 0 )
            $order_payment_method = 3;
        elseif( $payment_list['credit_amount'] != 0 )
            $order_payment_method = 2;
        else
            $order_payment_method = 0;
        //SAVE TICKET 
        $payment_arr = [
            'order_id' => $order_id,
            'order_place_id' =>$this->getCurrentPlaceId(),
            'order_customer_id' => $payment_list['customer_id'],
            'order_promotion_id' => 0,
            'order_booking_id' => null,
            'order_image' => '',
            'order_bill' => $payment_list['booking_code'],
            'order_price' => $payment_list['total_charge'],
            'order_receipt' => $payment_list['total_payment'],
            'order_payback' => $payment_list['cash_back'],
            'order_promotion_discount' => 0,
            'order_coupon_discount' => $payment_list['coupon_amount'],
            'order_coupon_code' => $payment_list['coupon_code'],
            'order_giftcard_code' => $payment_list['giftcard_code'],
            'order_giftcard_amount' => $payment_list['giftcard_pay'],
            'order_card_number' => $payment_list['credit_number'],
            'order_card_amount' => $payment_list['credit_amount'],
            'order_cash_amount' => $payment_list['cash'],
            'order_use_point' => $payment_list['use_point'],
            'order_discount_point' => $payment_list['discount_amount'],
            'order_beverage_id' => 0,
            'order_beverage_price' => 0,
            'order_payment_method' => $order_payment_method,
            'order_transaction' => '1233',
            'order_datetime_payment' => format_date_db( $payment_list['date'])." ".$payment_list['time'],
            'order_customer_type' => 0,
            'order_sent' => 0,
            'order_merge_id' => '',
            'order_paid' => 0,
            'order_discount' => $payment_list['discount_amount'],
            'order_status' => 1,
            'order_membership_discount' => $payment_list['membership_point'],
            'order_debit_amount' => $payment_list['debit_amount'],
            'order_debit_number' => $payment_list['debit_number']
        ];
        if( $request->correct_ticket == "")
            $payment_save = PosOrder::create($payment_arr);
        else
            $payment_update = PosOrder::where('order_place_id',$this->getCurrentPlaceId())
                                        ->whereDate('order_datetime_payment',Carbon::today())
                                        ->where('order_id',$order_id)
                                        ->update([
            'order_customer_id' => $payment_list['customer_id'],
            'order_promotion_id' => 0,
            'order_booking_id' => null,
            'order_image' => '',
            'order_bill' => $payment_list['booking_code'],
            'order_price' => $payment_list['total_charge'],
            'order_receipt' => $payment_list['total_payment'],
            'order_payback' => $payment_list['cash_back'],
            'order_promotion_discount' => 0,
            'order_coupon_discount' => $payment_list['coupon_amount'],
            'order_coupon_code' => $payment_list['coupon_code'],
            'order_giftcard_code' => $payment_list['giftcard_code'],
            'order_giftcard_amount' => $payment_list['giftcard_pay'],
            'order_card_number' => $payment_list['credit_number'],
            'order_card_amount' => $payment_list['credit_amount'],
            'order_cash_amount' => $payment_list['cash'],
            'order_use_point' => $payment_list['use_point'],
            'order_discount_point' => $payment_list['discount_amount'],
            'order_beverage_id' => 0,
            'order_beverage_price' => 0,
            'order_payment_method' => $order_payment_method,
            'order_transaction' => '1233',
            'order_datetime_payment' => format_date_db( $payment_list['date'])." ".$payment_list['time'],
            'order_customer_type' => 0,
            'order_sent' => 0,
            'order_merge_id' => '',
            'order_paid' => 0,
            'order_discount' => $payment_list['discount_amount'],
            'order_status' => 1,
            'order_membership_discount' => $payment_list['membership_point'],
            'order_debit_amount' => $payment_list['debit_amount'],
            'order_debit_number' => $payment_list['debit_number']
                                        ]);
        //SAVE DETAIL TICKET
        if($request->correct_ticket != ""){
            $service_list_payment = $request->service_list_payment;
            foreach ($service_list_payment as $key => $value) {
                foreach ($value as $service) {
                    $orderdetail_update = PosOrderdetail::where('orderdetail_place_id',$this->getCurrentPlaceId())
                                    ->whereDate('orderdetail_datetime',Carbon::today())
                                    ->where('orderdetail_order_id',$order_id)
                                    ->where('orderdetail_worker_id',$service['worker_id'])
                                    ->where('orderdetail_service_id',$service['service_id'])
                                    ->update(['orderdetail_tip' => $tip[$key]]);
                }
            }
            if(!$payment_update || !$orderdetail_update){
                DB::callback();
                return 0;
            }else{
                DB::commit();
                return 1;
            }
        }
        else{
            $orderdetail_id = PosOrderdetail::where('orderdetail_place_id',$this->getCurrentPlaceId())->max('orderdetail_id')+1;

            $service_list_payment = $request->service_list_payment;
            $detailorder_arr = [];

            foreach ($service_list_payment as $key => $value) {
                foreach ($value as $service) {
                    $detailorder_arr[] = [
                        'orderdetail_id' => $orderdetail_id,
                        'orderdetail_place_id' => $this->getCurrentPlaceId(),
                        'orderdetail_order_id' => $order_id,
                        'orderdetail_worker_id' => $service['worker_id'],
                        'orderdetail_service_id'=> $service['service_id'],
                        'orderdetail_price' => $service['service_price'],
                        'orderdetail_datetime' => format_date_db($payment_list['date'])." ".$payment_list['time'],
                        'orderdetail_status' => 1,
                        'orderdetail_tip' => $tip[$key],
                    ];
                    $orderdetail_id++;
                }
            }
            $detailorder_save = PosOrderdetail::insert($detailorder_arr);
            //SAVE PRODUCT
            $os_id = PosOrderSupplyNail::where('os_place_id',$this->getCurrentPlaceId())->max('os_id')+1;
            $product_list = $request->product_list;
            $product_arr = [];

            if( is_array($product_list) && count($product_list) != 0 ){
                foreach ($product_list as $key => $value) {
                    $product_arr[] = [
                        'os_id' => $os_id,
                        'os_place_id' => $this->getCurrentPlaceId(),
                        'os_order_id' => $order_id,
                        'os_supply_nail_id' => $value['product_id'],
                        'os_price' => $value['product_price'],
                        'os_name' => $value['product_name'],
                        'os_quantity' => $value['product_amount'],
                        'os_discount' => $value['product_discount'],
                        'os_sale_tax' => $value['product_sale_tax'],
                        'os_status' => 1,
                        'os_type_discount' => $value['product_type_discount'],
                    ];
                    $os_id++;
                }
                $product_save = PosOrderSupplyNail::insert($product_arr);
            }

            if( !$payment_save || !$detailorder_save){
                DB::callback();
                return 0;
            }else{
                DB::commit();
                return 1;
            }
        }
    }
    public function getPointGiftcard(Request $request){

        $giftcode_point_earn = 0;

        $giftcard_list = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                    ->where('giftcode_code',$request->giftcard_code)
                                    ->first();

        if( $giftcard_list != 0){

            $giftcode_redemption = $giftcard_list->giftcode_redemption;
            $giftcode_price = $giftcard_list->giftcode_price;
            $giftcode_bonus_point = $giftcard_list->giftcode_bonus_point;

            if( $giftcode_redemption >0 && $giftcode_redemption != null ){
                $giftcode_point_earn = $request->giftcard_pay * $giftcode_redemption / $giftcode_price;
            }
            if( $giftcode_bonus_point >0 && $giftcode_bonus_point != null ){
                $giftcode_point_earn = $request->giftcard_pay * $giftcode_bonus_point / $giftcode_price;
            }
        }
        return $giftcode_point_earn;
    }
    public function getGiftcodeCustomer(Request $request){

        $customer_fullname = "";
        $customer_phone = "";
        $customer_email = "";
        $giftcode_balance = "";

        $giftcode_sql = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                    ->where('giftcode_code',$request->giftcode);
        $giftcode_count =  $giftcode_sql->count();

        if($giftcode_count > 0){
            $giftcode_list = $giftcode_sql->first();
            $giftcode_balance = $giftcode_list->giftcode_balance;
            $giftcode_customer_id = $giftcode_list->giftcode_customer_id;
            $customer_list = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                        ->where('customer_id',$giftcode_customer_id)
                                        ->first();

            $customer_fullname = $customer_list->customer_fullname;
            $customer_phone = $customer_list->customer_phone;
            $customer_email = $customer_list->customer_email;

            return Response::json(array(
                            'success' => true,
                            'giftcode_balance' => $giftcode_balance,
                            'customer_fullname' => $customer_fullname,
                            'customer_phone' => $customer_phone,
                            'customer_email' => $customer_email,
                        ), 200);
        }else
            return Response::json(array(
                    'success' => false,
                    'message' => 'This giftcard code has not exist already!'
                ), 400);
    }
    public function getCustomerForEditTicket(Request $request){
        $customer_list = PosCustomer::join('pos_customertag',function($join){
                                        $join->on('pos_customer.customer_place_id','pos_customertag.customertag_place_id')
                                        ->on('pos_customer.customer_customertag_id','pos_customertag.customertag_id');
                                        })
                                        ->where('pos_customer.customer_place_id',$this->getCurrentPlaceId())
                                        ->where('pos_customer.customer_id',$request->customer_id)
                                        ->select('pos_customer.customer_id','pos_customer.customer_fullname','pos_customer.customer_phone','pos_customer.customer_email','pos_customer.customer_gender','pos_customer.customer_birthdate','pos_customertag.customertag_name')
                                        ->first();
        if(isset($customer_list)){

            if($customer_list->customer_gender == 1)
                $customer_gender = 'Male';
            elseif($customer_list->customer_gender == 2)
                $customer_gender = 'Female';
            else
                $customer_gender = 'Child';

            $customer_arr = [
                'customer_id' => $customer_list->customer_id,
                'customer_fullname' => $customer_list->customer_fullname,
                'customer_phone' => $customer_list->customer_phone,
                'customer_email' => $customer_list->customer_email,
                'customer_gender' => $customer_gender,
                'customer_birthdate' => format_date($customer_list->customer_birthdate),
                'customertag_name' => $customer_list->customertag_name,
            ];
            return Response::json($customer_arr,200);
        }
        else
            return Response::json([
                'message' => "Can't get customer information!"
            ],400);
    }
    public function buyGiftcardOutSide($id = null){
        //GET LIST PRODUCT
        $product_list = PosSupplyNail::where('sn_place_id',$this->getCurrentPlaceId())
                                    ->where('sn_dateexpired',">=",today())
                                    ->where('sn_status',1)
                                    ->where('sn_quantity','!=',null)
                                    ->get();
        $list_service = "";
        $customer_info = "";
        
        //GET ORDER LIST SESSION
        if (Session::has('order_list_payment')){
            $order_list = Session::get('order_list_payment');
        }else{$order_list ="";}

        //GET TICKET LIST SESSION
        if (Session::has('ticket_list_payment')){
            $ticket_list = Session::get('ticket_list_payment');
        }else{$ticket_list ="";}

        //GET EXTRA SESSION
        if (Session::has('extra_payment')){
            $extra_payment = Session::get('extra_payment');
        }else{$extra_payment =0 ;}
         //CHECK PAY A TICKET
        $click_pay = "";
        $number_of_ticket = 0;
        $booking_code = "";
        $booking_customer_id = "";
        $service_arr = "";

        if($id != null){
            $get_ticket_list = PosBooking::select('booking_id','booking_code','booking_customer_id')
                            ->where('booking_place_id',$this->getCurrentPlaceId())
                            ->whereDate('booking_time_selected',Carbon::today())
                            ->where('booking_status',3)
                            ->get();
            //GET NUMBER TICKET FOR PAYMENT
            foreach ($get_ticket_list as $key => $value) {
                if($value->booking_id == $id){
                    $number_of_ticket = $key;
                    $booking_code = $value->booking_code;
                    $booking_customer_id = $value->booking_customer_id;
                    $current_customer = $value->booking_customer_id;
                }
            }
            $click_pay = "click";
        }
        elseif(Session::has('current_customer_payment') && Session::get('current_customer_payment') >0){
            $current_customer = Session::get('current_customer_payment');

            $customer_info = PosCustomer::join('pos_customertag',function($join){
                                            $join->on('pos_customer.customer_customertag_id','=','pos_customertag.customertag_id')->on('pos_customer.customer_place_id','=','pos_customertag.customertag_place_id');
                                        })
                                    ->where('pos_customer.customer_id',$current_customer)
                                    ->where('pos_customer.customer_place_id',$this->getCurrentPlaceId())
                                    ->first();
            $customer_info->customer_gender = \GeneralHelper::convertGender($customer_info->customer_gender);
            $customer_info->customer_birthdate = format_date($customer_info->customer_birthdate);

        }
        
        else
            {$current_customer =0;}

        $list_customertag = PosCustomertag::where('customertag_place_id', $this->getCurrentPlaceId())
                                            ->get();
        $place_info = PosPlace::where('place_id',$this->getCurrentPlaceId())
                                ->where('place_status',1)->first();
        $check = 1;
        $membership_list = DB::table('pos_membership_detail')
                                    ->join('pos_membership',function($join){
                                    $join->on('pos_membership_detail.membership_detail_membership_id','pos_membership.membership_id');
                                    })
                                    ->where('pos_membership_detail.membership_detail_place_id',$this->getCurrentPlaceId())
                                    ->where('membership_detail_status',1)
                                    ->select('membership_name','membership_detail_price','membership_id')
                                    ->get();

        return view('salefinance.payment',compact('customer_info','current_customer',
                                                    'order_list','list_customertag','extra_payment','ticket_list','list_service','place_info','product_list','check','click_pay','number_of_ticket','booking_code','booking_customer_id','service_arr','membership_list'));
    }
    public function checkCorrectTicket(Request $request){
        try{
            $ticket_list = PosOrder::where('order_place_id',$this->getCurrentPlaceId())
                                ->whereDate('order_datetime_payment',Carbon::today())
                                ->where('order_bill',$request->ticket_no)
                                ->where('order_status',1)
                                ->first();
            return Response::json([$ticket_list],200);
        }catch( \Exception $e){
            \Log::info($e);
            return Response::json(['message'=>'Get detail ticket error!'],400);
        }
    }
    public function checkCorrectTicketToday(Request $request)
    {
        $place_id = $this->getCurrentPlaceId();
        $today = Carbon::today();
        $order_arr = [];

        $order_list = PosOrder::where('order_place_id',$place_id)
                                ->whereDate('order_datetime_payment',$today)
                                ->where('order_status',1)
                                ->get();

        foreach ($order_list as $key => $order) {

            $orderdetail_list = PosOrderdetail::join('pos_worker',function($join){
                                $join->on('pos_orderdetail.orderdetail_place_id','pos_worker.worker_place_id')
                                ->on('pos_orderdetail.orderdetail_worker_id','pos_worker.worker_id');
                            })
                                ->join('pos_service',function($join){
                                    $join->on('pos_orderdetail.orderdetail_place_id','pos_service.service_place_id')
                                    ->on('pos_orderdetail.orderdetail_service_id','pos_service.service_id');
                                })
                                ->where('pos_orderdetail.orderdetail_place_id',$place_id)
                                ->where('pos_orderdetail.orderdetail_order_id',$order->order_id)
                                ->select('pos_orderdetail.orderdetail_service_id','pos_orderdetail.orderdetail_price','pos_orderdetail.orderdetail_tip','pos_orderdetail.orderdetail_worker_id','pos_worker.worker_nickname','pos_service.service_name')
                                ->get();
            // GET SERVICE LIST
            $service_list = [];
            $tips = [];
            foreach ($orderdetail_list as $orderdetail) {
                $service_list[$orderdetail->worker_nickname][] = [
                    'service_name' => $orderdetail->service_name,
                    'service_price' => $orderdetail->orderdetail_price,
                    'service_id' => $orderdetail->orderdetail_service_id,
                    'worker_id' => $orderdetail->orderdetail_worker_id,
                ];
                $tips[$orderdetail->worker_nickname] = $orderdetail->orderdetail_tip;

            }
            //GET TIP LIST
            $tip_list = [];
            foreach ($tips as $key => $value) {
                $tip_list[] = $value;
            }
            //TOTAL TIP
            $tip = array_sum($tip_list);
            //GET PRODUCT
            $product_list = [];
            $product_arr = PosOrderSupplyNail::where('os_place_id',$place_id)
                            ->where('os_order_id',$order->order_id)
                            ->select('os_name','os_quantity','os_price')
                            ->get();

            foreach ($product_arr as $key => $product) {
                $product_list[] = [
                    'product_name' => $product->os_name,
                    'product_amount' => $product->os_quantity,
                    'product_price' => $product->os_price,
                ];
            }

            $order_arr[] = [
                    'ticket_no' => $order->order_bill,
                    'time' => gettime_by_datetime($order->order_datetime_payment),
                    'date' => format_date($order->order_datetime_payment),
                    'booking_code' => $order->order_bill,
                    'order_list' => $service_list,
                    'customer_id' => $order->order_customer_id,
                    'ticket_combine' => null,
                    'number' => $key,
                    'booking_id' => $order->order_bill,
                    'balance_point' => 0,
                    'point_cash' => 0,
                    'coupon_code' => $order->order_coupon_code,
                    'coupon_amount' => $order->order_coupon_discount,
                    'giftcard_code' => $order->order_giftcard_code,
                    'giftcard_amount' => $order->order_giftcard_amount,
                    'cash' => $order->order_cash_amount,
                    'cash_back' => $order->order_payback,
                    'credit_number' => $order->order_card_number,
                    'credit_amount' => $order->order_card_amount,
                    'debit_number' => null,
                    'debit_amount' => 0,
                    'product_list' => $product_list,
                    'service_list' => $service_list,
                    'discount_amount' => $order->order_discount_point,
                    'giftcard_pay' => $order->order_giftcard_amount,
                    'use_amount' => $order->order_use_point,
                    'use_point' => $order->order_use_point,
                    'tip' => $tip,
                    'staff_list_payment' => $service_list,
                    'tip_list' => $tip_list,
                    'total_price' => $order->order_price,
                    'discount_station' => "",
                    'giftcard_pay' => $order->order_giftcard_amount,
                    'total_payment' => $order->order_receipt,
                    'check' => 0,
                    'point_earn' => 0,
                    'total_point' => 0
                ];
        }
        return $order_arr;
    }
    public function addTurnWithService(Request $request){
        $order_list = $request->order_list;
        $place_id = $this->getCurrentPlaceId();
        //GET TURN OPTION
        $turn_option = PosPlace::where('place_id',$this->getCurrentPlaceId())
                               ->where('place_status',1)
                               ->first()
                               ->place_turn_option;
        //ADD TURN WITH SERVICE
        if($turn_option == 2){
            foreach ($order_list as $key => $value) {
                $cate_id = PosService::where('service_place_id',$place_id)
                                         ->where('service_id',$value['service_id'])
                                         // ->where('service_status',1)
                                         ->first()
                                         ->service_cate_id;

                $turn_list = PosWorkerCateservice::where('ws_place_id',$place_id)
                                                   ->where('ws_cateservice_id',$cate_id)
                                                   ->where('ws_worker_id',$value['staff_id'])
                                                   ->where('ws_status',1)
                                                   ->first();
                // return $turn_list;
                if(isset($turn_list)){
                    $worker_sql = PosWorker::where('worker_place_id',$place_id)
                                            ->where('worker_id',$value['staff_id']);

                    $worker_turn = $worker_sql->select('worker_turn')->first()->worker_turn;
                    $turn_with_cateservice = $turn_list->ws_turn;

                    if($turn_with_cateservice > 0)
                        $worker_sql->update(['worker_turn'=>$worker_turn+$turn_with_cateservice]);
                }
            }
        //ADD TURN WITH CHECKIN
        }elseif($turn_option ==1){
            foreach ($order_list as $key => $value) {
                $worker_turn = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                        ->where('worker_id',$value['staff_id'])
                                        ->first()
                                        ->worker_turn;
                $worker_sql = PosWorker::where('worker_place_id',$place_id)
                                        ->where('worker_id',$value['staff_id'])
                                        ->update(['worker_turn'=>$worker_turn+1]);
            }
        //ADD TURN WITH PRICE
        }else{
            $service_price = [];
            foreach ($order_list as $key => $value) {
                $service_price[$value['staff_id']][] = $value['service_price'];
            }
            foreach ($service_price as $key => $value) {
                $worker_info = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                         ->where('worker_id',$key);
                $worker_turn = $worker_info->first()->worker_turn;
                $turn_add_with_price = array_sum($value)/100;
                $worker_info->update(['worker_turn'=>$worker_turn+$turn_add_with_price]);
            }
        }
    }
    public function checkTicket(Request $request){

        $now = Carbon::now()->format('Y-m-d H:i:s');
        $today = Carbon::today();
        $place_id = $this->getCurrentPlaceId();
        $order_list = $request->order_list;
        $staff_arr = [];
        $service_list = [];
        $service_worker_array = [];
        $booking_staff = [];

        foreach($order_list as $key => $value) {
            $staff_arr[] = $value['staff_id'];
            $service_list[] = $value['service_id'];
        }
        $staff_arr = array_unique($staff_arr); 
        $worker_count = count($staff_arr);       
        //GET BOOKING TODAY
        $booking_sql = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today);
        
        $booking_list = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today)
                                    ->whereIn('booking_worker_id',$staff_arr)
                                    ->where('booking_status','!=',0)
                                    ->get();
        if($booking_list->count() != 0){
            foreach ($booking_list as $key => $value) {
                $booking_staff[] = $value;
            }
        }
        $bookingdetail_list = PosBookingDetail::where('bookingdetail_place_id',$place_id)
                                    ->whereDate('booking_time',$today)
                                    ->whereIn('worker_id',$staff_arr)
                                    ->get();

        if($bookingdetail_list->count() != 0){
            foreach ($bookingdetail_list as $key => $value) {
                $booking_staff[] = $value;
            }
        }
        $booking_staff_count = count($booking_staff);

        $count_booking = $booking_sql->count();

        if($booking_staff_count == 0 ){
            //SAVE TICKET TO DATABASE
            $booking_id = PosBooking::where('booking_place_id',$place_id)
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
            $booking_lstservice = implode(",", $service_list);
            $worker_id = implode(",", $staff_arr);
            //IF HAS 1 WORKER->INSERT SERVICE TO POS_BOOKING, IF WORKER MORE THAN 2-> INSERT SERVICE TO DETAIL BOOKING
            DB::beginTransaction();
            //INSERT BOOKING
            $booking_arr = [
                'booking_id' => $booking_id,
                'booking_place_id' => $place_id,
                'booking_customer_id' => $request->customer_id,
                'booking_lstservice' => $worker_count==1?$booking_lstservice:NULL,
                'booking_time_selected' => $now,
                'booking_worker_id' => $worker_count==1?$worker_id:NULL,
                'booking_ip' => request()->ip(),
                'booking_status' => 3, //WORKING FOR PAYMENT
                'booking_type' => 1,
                'booking_code' => $booking_code,
            ];
            $booking_save = PosBooking::create($booking_arr);

            if($worker_count > 1){
                //INSERT SERVICE WORKER TO pos_booking_details]
                foreach ($order_list as $key => $value) {
                    $service_worker_array[] = [
                        'bookingdetail_place_id' => $place_id,
                        'booking_code' => $booking_code,
                        'service_id' => $value['service_id'],
                        'worker_id' => $value['staff_id'],
                        'booking_time' => $now,
                    ];
                }
                $booking_save_detail = PosBookingDetail::insert($service_worker_array);

                if(!$booking_save || !$booking_save_detail){
                    DB::callback();
                    return Response::json(['message' => 'Error'],400);
                }else{
                    DB::commit();
                   return 1;//CAN BOOKING
                }
            }
            if(!$booking_save){
                    DB::callback();
                    return Response::json(['message' => 'Error'],400);
                }else{
                    DB::commit();
                   return 1;//CAN BOOKING
                }
        }else{
            $check = 0;

            foreach ($booking_staff as $key => $booking) {

                if($booking->booking_lstservice != null)
                    $service_id = $booking->booking_lstservice;
                if($booking->service_id != null)
                    $service_id = $booking->service_id;

                $service_duration = PosService::where('service_place_id',$place_id)
                           ->where('service_status',1)
                           ->where('service_id',$service_id)
                           ->first()
                           ->service_duration;
                $service_finish = Carbon::parse($booking->booking_time_selected)->addMinutes($service_duration);

                //CURRENT TIME FOR BOOKING FINISH
                $service_duration_booking = PosService::where('service_place_id',$place_id)
                           ->where('service_status',1)
                           ->whereIn('service_id',$service_list)
                           ->sum('service_duration');
                $service_booking_finish = Carbon::parse($now)->addMinutes($service_duration_booking);
                // return $service_list;

                //0: CAN NOT BOOKING, 1: CAN BOOKING
                if($service_booking_finish < $booking->booking_time_selected || $now > $service_finish){
                    $check++;
                }
            }
            if(count($booking_staff) == $check){
                //SAVE TICKET TO DATABASE
                $booking_id = PosBooking::where('booking_place_id',$place_id)
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
                $booking_lstservice = implode(",", $service_list);
                $worker_id = implode(",", $staff_arr);
                //IF HAS 1 WORKER->INSERT SERVICE TO POS_BOOKING, IF WORKER MORE THAN 2-> INSERT SERVICE TO DETAIL BOOKING
                DB::beginTransaction();
                //INSERT BOOKING
                $booking_arr = [
                    'booking_id' => $booking_id,
                    'booking_place_id' => $place_id,
                    'booking_customer_id' => $request->customer_id,
                    'booking_lstservice' => $worker_count==1?$booking_lstservice:NULL,
                    'booking_time_selected' => $now,
                    'booking_worker_id' => $worker_count==1?$worker_id:NULL,
                    'booking_ip' => request()->ip(),
                    'booking_status' => 3, //WORKING FOR PAYMENT
                    'booking_type' => 1,
                    'booking_code' => $booking_code,
                ];
                $booking_save = PosBooking::create($booking_arr);

                if($worker_count > 1){
                    //INSERT SERVICE WORKER TO pos_booking_details]
                    foreach ($order_list as $key => $value) {
                        $service_worker_array[] = [
                            'bookingdetail_place_id' => $place_id,
                            'booking_code' => $booking_code,
                            'service_id' => $value['service_id'],
                            'worker_id' => $value['staff_id'],
                            'booking_time' => $now,
                        ];
                    }
                    $booking_save_detail = PosBookingDetail::insert($service_worker_array);

                    if(!$booking_save || !$booking_save_detail){
                        DB::callback();
                        return Response::json(['message' => 'Error'],400);
                    }else{
                        DB::commit();
                       return 1;//CAN BOOKING
                    }
                }
                if(!$booking_save){
                    DB::callback();
                    return Response::json(['message' => 'Error'],400);
                }else{
                    DB::commit();
                   return 1;//CAN BOOKING
                }
            }else
                return 0; // CAN NOT BOOKING
        }
    }
    public function getTicketToday(Request $request){
        $booking_arr = [];

        $booking_list = PosBooking::leftjoin('pos_worker',function($join){
                                    $join->on('pos_booking.booking_place_id','pos_worker.worker_place_id')
                                    ->on('pos_booking.booking_worker_id','pos_worker.worker_id');
                                })
                                ->where('pos_booking.booking_place_id',$this->getCurrentPlaceId())
                                ->whereDate('pos_booking.booking_time_selected',Carbon::today())
                                ->where('pos_booking.booking_parent',null)
                                ->where('pos_booking.booking_status',3)
                                ->select('pos_booking.booking_code','pos_booking.booking_parent','pos_booking.booking_combine','pos_booking.booking_time_selected','pos_booking.booking_lstservice','pos_booking.booking_worker_id','pos_worker.worker_nickname','pos_booking.booking_customer_id')
                                ->get();
        if(count($booking_list) != 0 ){

            $place_id = $this->getCurrentPlaceId();
            
            foreach ($booking_list as $key => $booking){

                $service_arr = [];
                $service_arr = self::getListTicketToday($booking,$place_id);
                $booking_arr[] = [
                    'ticket_no' => $booking->booking_code,
                    'time' => gettime_by_datetime($booking->booking_time_selected),
                    'date' => format_date($booking->booking_time_selected),
                    'booking_code' => $booking->booking_code,
                    'order_list' => $service_arr,
                    'customer_id' => $booking->booking_customer_id,
                    'ticket_combine' => $booking->booking_combine==null?null:explode(";",$booking->booking_combine),
                    'number' => $key,
                    'booking_id' => $booking->booking_code,
                    'reason_delete' => "empty",
                    'payment' => 0,
                    'balance_point' => 0,
                    'point_cash' => 0,
                    'coupon_code' => null,
                    'coupon_amount' => 0,
                    'coupon_balance' => 0,
                    'giftcard_code' => null,
                    'giftcard_amount' => 0,
                    'giftcard_balance' => 0,
                    'cash' => 0,
                    'cash_back' => 0,
                    'credit_number' => null,
                    'credit_amount' => 0,
                    'debit_number' => null,
                    'debit_amount' => 0,
                    'product_list' => [],
                    'service_list' => [],
                    'discount_amount' => 0,
                    'giftcard_price' => 0,
                    'giftcard_pay' => 0,
                    'total_point' => 0,
                    'use_amount' => 0,
                    'point_cash' => 0,
                    'point_earn' => 0,
                    'use_point' => 0,
                    'use_amount' => 0,
                    'tip' => 0,
                    'staff_list_payment' => [],
                    'tip_list' => [],
                    'balance_amount_after_convert' => 0,
                    'total_price' => 0,
                    'discount_station' => "",
                    'giftcard_pay' => 0,
                    'total_payment' => 0,
                    'check' => 0,
                    'membership_point' => 0
                ];
            }
        }
        return $booking_arr;
    }
    public static function getListTicketToday($booking,$place_id){
        
            $booking_lstservice = $booking->booking_lstservice;

            if($booking_lstservice != null){

                $booking_service_arr = explode(",", $booking_lstservice);
                $service_list = PosService::where('service_place_id',$place_id)
                                            ->whereIn('service_id',$booking_service_arr)
                                            ->where('service_status',1)
                                            ->get();

                foreach ($service_list as $key => $service) {

                    $service_arr[$booking->worker_nickname][] = [
                        'service_name' => $service->service_name,
                        'service_price' => $service->service_price,
                        'service_id' => $service->service_id,
                        'worker_id' => $booking->booking_worker_id,
                    ];
                }
            }else{
                $service_list = PosBookingDetail::join('pos_service',function($join){
                                    $join->on('pos_booking_details.bookingdetail_place_id','pos_service.service_place_id')
                                    ->on('pos_booking_details.service_id','pos_service.service_id');
                                })
                                ->join('pos_worker',function($join){
                                    $join->on('pos_booking_details.bookingdetail_place_id','pos_worker.worker_place_id')
                                    ->on('pos_booking_details.worker_id','pos_worker.worker_id');
                                })
                                ->whereDate('pos_booking_details.booking_time',Carbon::today())
                                ->where('pos_booking_details.bookingdetail_place_id',$place_id)
                                ->where('pos_booking_details.booking_code',$booking->booking_code)
                                ->select('pos_service.service_id','pos_service.service_name','pos_service.service_price','pos_booking_details.worker_id','pos_worker.worker_nickname','pos_service.service_id')
                                ->get();

                foreach ($service_list as $service) {

                    $service_arr[$service->worker_nickname][] = [
                        'service_name' => $service->service_name,
                        'service_price' => $service->service_price,
                        'service_id' => $service->service_id,
                        'worker_id' => $service->worker_id,
                    ];
                }
            }
            //IF THIS TICKET COMBINE OTHER
            if($booking->booking_combine != NULL){

                $code_combine_list = explode(";", $booking->booking_combine);

                $booking_combine_list = PosBooking::leftjoin('pos_worker',function($join){
                                        $join->on('pos_booking.booking_place_id','pos_worker.worker_place_id')
                                        ->on('pos_booking.booking_worker_id','pos_worker.worker_id');
                                        })
                                        ->where('pos_booking.booking_place_id',$place_id)
                                       ->whereDate('pos_booking.booking_time_selected',Carbon::today())
                                       ->whereIn('pos_booking.booking_code',$code_combine_list)
                                       ->select('pos_booking.booking_code','pos_booking.booking_combine','pos_booking.booking_time_selected','pos_booking.booking_lstservice','pos_booking.booking_worker_id','pos_worker.worker_nickname')
                                       ->get();

                foreach ($booking_combine_list as $key => $booking_combine){

                    $service_combine_arr = self::getListTicketToday($booking_combine,$place_id);

                    foreach ($service_combine_arr as $key => $value) {
                        $service_arr[$key][] = $value[0];
                    }
                }
            }
        return $service_arr;
    }
    public function saveTicketCombine(Request $request){

        $ticket_current = $request->ticket_current;
        $ticket_combine_array = $request->ticket_combine_array;
        $place_id = $this->getCurrentPlaceId();
        $today = Carbon::today();
        $ticket_combine_list = implode(";", $ticket_combine_array);

        DB::beginTransaction();
        //UPDATE CURRENT TICKET
        $update_booking_current = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today)
                                    ->where('booking_code',$ticket_current)
                                    ->update(['booking_combine' => $ticket_combine_list]);
        //UPDATE COMBINED TICKET
        $update_combine_arr = PosBooking::where('booking_place_id',$place_id)
                                ->whereDate('booking_time_selected',$today)
                                ->whereIn('booking_code',$ticket_combine_array)
                                ->update(['booking_parent' => $ticket_current]);

        if( !$update_booking_current || !$update_combine_arr ){
            DB::callback();
            return Response::json([null],400);
        }else{
            DB::commit();
            return 1;
        }
    }
    public function splitTicketWithStaff(Request $request){

        $today = Carbon::today();
        $place_id = $this->getCurrentPlaceId();
        $ticket_no = $request->ticket_no;
        $sub_name_ticket = ['A','B','C','D','E','F','G','H','I','K','L','M','N'];
        $booking_arr = [];

        $booking_detail_list = PosBookingDetail::where('bookingdetail_place_id',$place_id)
                                                ->where('booking_code',$ticket_no)
                                                ->whereDate('booking_time',$today)
                                                ->get();

        $booking_id_max = PosBooking::where('booking_place_id',$place_id)
                                    ->max('booking_id')+1;
        //INSERT SPLIT TICKET TO DATABASE
        foreach ($booking_detail_list as $key => $value) {
            $booking_arr[] = [
                'booking_id' => $booking_id_max,
                'booking_place_id' => $place_id,
                'booking_customer_id' => $request->customer_id,
                'booking_lstservice' => $value['service_id'],
                'booking_time_selected' => $value['booking_time'],
                'booking_worker_id' => $value['worker_id'],
                'booking_ip' => request()->ip(),
                'booking_status' => 3, //WORKING FOR PAYMENT
                'booking_type' => 1,
                'booking_code' => $value['booking_code'].$sub_name_ticket[$key],
            ];
            $booking_id_max++;
        };
        DB::beginTransaction();

        $booking_insert = PosBooking::insert($booking_arr);
        //DETELE TICKET CURRENT
        $booking_delete = PosBooking::where('booking_place_id',$place_id)
                        ->whereDate('booking_time_selected',$today)
                        ->where('booking_code',$ticket_no)
                        ->update([
                            'booking_status'=>0,
                            'booking_reason' => 'this ticket has been split',
                        ]);
        if(!$booking_insert || !$booking_delete){
            DB::callback();
            return Response::json([null],400);
        }else{
            DB::commit();
            return 1;
        }
    }
    public function splitTicket(Request $request){

        $place_id = $this->getCurrentPlaceId();
        $ticket_no = $request->ticket_no;
        $ticket_combine_arr = $request->ticket_combine;
        $today = Carbon::today();

        //ADD CURRENT TICKET TO TICKET COMBINE ARRAY
        $ticket_combine_arr[] = $ticket_no;

        DB::beginTransaction();
        $booking_update = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today)
                                    ->update([
                                        'booking_combine' => NULL,
                                        'booking_parent' => NULL
                                    ]);
        if(!$booking_update){
            DB::callback();
            return Response::json([null],400);
        }else{
            DB::commit();
            return 1;
        }
    }
    public function voidTicket(Request $request){

        $ticket_no = $request->ticket_no;
        $place_id = $this->getCurrentPlaceId();
        $booking_reason = $request->booking_reason;
        $today =Carbon::today();

        $booking_update = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today)
                                    ->where('booking_code',$ticket_no)
                                    ->update([
                                        'booking_status' => 0,
                                        'booking_reason' => $booking_reason
                                    ]);
        if(!$booking_update)
            return Response::json([null],400);
        else
            return 1;
    }
    public function updateTicketPayment(Request $request){

        $place_id = $this->getCurrentPlaceId();
        $today = Carbon::today();
        $booking_code = $request->booking_code;
        $order_list = $request->order_list;
        $staff_arr = [];
        $service_list = [];
        $service_worker_array = [];
        $booking_staff = [];

        foreach($order_list as $key => $value) {
            $staff_arr[] = $value['staff_id'];
            $service_list[] = $value['service_id'];
        }
        $staff_arr = array_unique($staff_arr);
        $worker_count = count($staff_arr);

        //GET BOOKING TIME OF THIS TICKET
        $booking_time = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today)
                                    ->where('booking_code',$booking_code)
                                    ->where('booking_status','!=',0)
                                    ->first()
                                    ->booking_time_selected;
        // //GET OLD TIME BOOKING
        $booking_list = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today)
                                    ->whereIn('booking_worker_id',$staff_arr)
                                    ->where('booking_code','!=',$booking_code)
                                    ->where('booking_status','!=',0)
                                    ->get();
        if($booking_list->count() != 0){
            foreach ($booking_list as $key => $value) {
                $booking_staff[] = $value;
            }
        }
        $bookingdetail_list = PosBookingDetail::where('bookingdetail_place_id',$place_id)
                                    ->whereDate('booking_time',$today)
                                    ->whereIn('worker_id',$staff_arr)
                                    ->where('booking_code','!=',$booking_code)
                                    ->get();

        if($bookingdetail_list->count() != 0){
            foreach ($bookingdetail_list as $key => $value) {
                $booking_staff[] = $value;
            }
        }
        $booking_staff_count = count($booking_staff);

        if($booking_staff_count == 0 ){

            $booking_lstservice = implode(",", $service_list);
            $worker_id = implode(",", $staff_arr);
            //UPDATE TICKET TO DATABASE
            DB::beginTransaction();
            //IF HAS 1 WORKER->INSERT SERVICE TO POS_BOOKING, IF WORKER MORE THAN 2-> UPDATE SERVICE TO DETAIL BOOKING
            if($worker_count ==1){
                $booking_update = PosBooking::where('booking_place_id',$place_id)
                                ->whereDate('booking_time_selected',$today)
                                ->where('booking_code',$booking_code)
                                ->update([
                                    'booking_lstservice' => $booking_lstservice,
                                    'booking_worker_id' => $worker_id
                                ]);
            }else{
                $booking_update = PosBooking::where('booking_place_id',$place_id)
                                    ->whereDate('booking_time_selected',$today)
                                    ->where('booking_code',$booking_code)
                                    ->update([
                                        'booking_lstservice' => NULL,
                                        'booking_worker_id' => NULL
                                    ]);
                //DELETE OLD BOOKING DETAIL OF THIS TICKET
                $booking_delete = PosBookingDetail::where('bookingdetail_place_id',$place_id)
                                    ->whereDate('booking_time',$today)
                                    ->where('booking_code',$booking_code)
                                    ->delete();
                //INSERT NEW LIST SERVICE WORKER TO POS BOOKING DETAIL
                foreach ($order_list as $key => $value) {
                    $service_worker_array[] = [
                        'bookingdetail_place_id' => $place_id,
                        'booking_code' => $booking_code,
                        'service_id' => $value['service_id'],
                        'worker_id' => $value['staff_id'],
                        'booking_time' => $booking_time,
                    ];
                }
                $booking_save_detail = PosBookingDetail::insert($service_worker_array);

                if(!$booking_update || !$booking_save_detail || !$booking_delete){
                    DB::callback();
                    return Response::json(['message' => 'Error'],400);
                }else{
                    DB::commit();
                   return 1;//CAN BOOKING
                }
            }
            if(!$booking_update){
                    DB::callback();
                    return Response::json(['message' => 'Error'],400);
                }else{
                    DB::commit();
                   return 1;//CAN BOOKING
                }
        }else{
            $check = 0;

            foreach ($booking_staff as $key => $booking) {

                if(isset($booking->booking_lstservice) && $booking->booking_lstservice != null)
                    $service_id = $booking->booking_lstservice;
                if(isset($booking->service_id) && $booking->service_id != null)
                    $service_id = $booking->service_id;

                $service_duration = PosService::where('service_place_id',$this->getCurrentPlaceId())
                           ->where('service_status',1)
                           ->where('service_id',$service_id)
                           ->first()
                           ->service_duration;
                $service_finish = Carbon::parse($booking->booking_time_selected)->addMinutes($service_duration);

                //CURRENT TIME FOR BOOKING FINISH
                $service_duration_booking = PosService::where('service_place_id',$this->getCurrentPlaceId())
                           ->where('service_status',1)
                           ->whereIn('service_id',$service_list)
                           ->sum('service_duration');
                $service_booking_finish = Carbon::parse($booking_time)->addMinutes($service_duration_booking);
                // return $service_list;

                //0: CAN NOT BOOKING, 1: CAN BOOKING
                if($service_booking_finish < $booking->booking_time_selected || $booking_time > $service_finish){
                    $check++;
                }
            }
            if($booking_staff->count() == $check){
                $booking_lstservice = implode(",", $service_list);
                $worker_id = implode(",", $staff_arr);
                //UPDATE TICKET TO DATABASE
                DB::beginTransaction();
                //IF HAS 1 WORKER->INSERT SERVICE TO POS_BOOKING, IF WORKER MORE THAN 2-> INSERT SERVICE TO DETAIL BOOKING
                if($worker_count == 1){
                    $booking_update = PosBooking::where('booking_place_id',$place_id)
                                                ->whereDate('booking_time_selected',$today)
                                                ->where('booking_code',$booking_code)
                                                ->update([
                                                    'booking_lstservice' => $booking_lstservice,
                                                    'booking_worker_id' => $worker_id
                                                ]);
                }else{
                    $booking_update = PosBooking::where('booking_place_id',$place_id)
                                                ->whereDate('booking_time_selected',$today)
                                                ->where('booking_code',$booking_code)
                                                ->update([
                                                    'booking_lstservice' => NULL,
                                                    'booking_worker_id' => NULL
                                                ]);
                    //DELETE OLD OLDBOOKING DETAIL
                    $booking_delete = PosBookingDetail::where('bookingdetail_place_id',$place_id)
                                                ->whereDate('booking_time',$today)
                                                ->where('booking_code',$booking_code)
                                                ->delete();
                    //INSERT SERVICE WORKER TO POS BOOKING DETAIL
                    foreach ($order_list as $key => $value) {
                        $service_worker_array[] = [
                            'bookingdetail_place_id' => $place_id,
                            'booking_code' => $booking_code,
                            'service_id' => $value['service_id'],
                            'worker_id' => $value['staff_id'],
                            'booking_time' => $booking_time,
                        ];
                    }
                    $booking_save_detail = PosBookingDetail::insert($service_worker_array);

                    if(!$booking_update || !$booking_save_detail || !$booking_delete){
                        DB::callback();
                        return Response::json(['message' => 'Error'],400);
                    }else{
                        DB::commit();
                       return 1;//CAN BOOKING
                    }
                }
                if(!$booking_update){
                    DB::callback();
                    return Response::json(['message' => 'Error'],400);
                }else{
                    DB::commit();
                   return 1;//CAN BOOKING
                }
            }else
                return 0; // CAN NOT BOOKING
        }
    }
    public function getMembershipPoint(Request $request){

        $place_id = $this->getCurrentPlaceId();
        $customer_id = $request->customer_id;
        $order_list = $request->order_list;
        $membership_point = 0;
        $today = Carbon::today();

        if(!is_array($order_list))
        $order_list = json_decode($order_list,TRUE);

        //CHECK CUSTOMER BUY MEMBERSHIP
        $check_customer = PosCustomer::where('customer_place_id',$place_id)
                                        ->where('customer_id',$customer_id)
                                        ->first()
                                        ->customer_membership_id;
        if($check_customer == 0){
            return $membership_point = 0;

        }else{

            $customer_membership = DB::table('pos_customer_membership_history')->join('pos_membership_detail',function($join){
                                $join->on('pos_customer_membership_history.cm_place_id','pos_membership_detail.membership_detail_place_id')
                                ->on('pos_customer_membership_history.cm_membership_id','pos_membership_detail.membership_detail_membership_id');
                                })
                                ->where('pos_customer_membership_history.cm_place_id',$place_id)
                                ->where('pos_customer_membership_history.cm_customer_id',$customer_id)
                                ->where('pos_membership_detail.membership_detail_status',1)
                                ->select('pos_membership_detail.membership_detail_listservice','pos_membership_detail.membership_detail_price','pos_membership_detail.membership_detail_percent_discount','cm_time_buy','membership_detail_time')
                                ->orderBy('cm_time_buy','desc')
                                ->take(1)
                                ->skip(0)
                                ->get();
            if($customer_membership->count()){

                $expired_day = Carbon::parse($customer_membership[0]->cm_time_buy)->addMonths($customer_membership[0]->membership_detail_time);

                if($today > $expired_day){

                    return $membership_point = 0;

                }else{
                    $membership_service_list = explode(";",$customer_membership[0]->membership_detail_listservice);
                    $percent_discount = $customer_membership[0]->membership_detail_percent_discount;

                    foreach ($order_list as $value) {

                        foreach ($value as $key => $value_service) {

                            $service_id = strval($value_service['service_id']);

                            if( in_array($service_id,$membership_service_list) ){
                                $membership_point += $value_service['service_price']*$percent_discount/100;
                            }
                        }
                    }
                }
            }
        }
        return $membership_point;
    }
    public function buyMembership(Request $request){

        $order_list = $request->order_list;
        $place_id = $this->getCurrentPlaceId();

        $rule = [
            'customer_fullname' => 'required',
            'customer_phone' => 'required',
            'membership_id' => 'required',
            'payment_method' => 'required'
        ];
        $message = [
        'customer_fullname.required' => 'Enter Fullname, Please!',
        'customer_phone.required' => 'Enter Phone, Please!',
        'membership_id.required' => 'Choose Membership, Please!',
        'payment_method.required' => 'Choose Payment Method, Please!',
        ];

        $validator = Validator::make($request->all(),$rule,$message);

        if($validator->fails()){
            return Response::json(array(
                'success' => 'errors',
                'message' => $validator->getMessageBag()->toArray()

            ), 400);
        }else{
            $customer_phone = $request->customer_phone;

            $customer_email = $request->customer_email;

            //CHECK CUSTOMER TO UPDATE
            $sql = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                               ->where(function($query) use ($customer_phone,$customer_email){
                                     $query->where('customer_phone',$customer_phone)
                                           ->orWhere('customer_email',$customer_email);
                               });

            $customer_check = $sql->count();

            if($customer_check != 0){

                $customer_id = $sql->first()->customer_id;
            }
            else{
                $customer_id = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                      ->max('customer_id')+1;
            }
            DB::beginTransaction();
            //GET USER BUY GIFTCARD
            $user_current = Session::get('user_current');
            //INSERT MEMBERSHIP TO CUSTOMER MEMBERSHIP TABLE
            $membership_arr = [
                'cm_place_id' => $this->getCurrentPlaceId(),
                'cm_membership_id' => $request->membership_id,
                'cm_customer_id' => $customer_id,
                'cm_payment_method' => $request->payment_method
            ];
            $membership = PosCustomerMembershipHistory::create($membership_arr);

            //IF HAS NOT CUSTOMER ALREADY -> INSERT CUSTOMER
            if($customer_check == 0){

                $customer_arr = [
                    'customer_id' => $customer_id,
                    'customer_place_id' => $this->getCurrentPlaceId(),
                    'customer_fullname' => $request->customer_fullname,
                    'customer_phone' => $request->customer_phone,
                    'customer_email' => $request->customer_email,
                    'customer_gender' => 2,
                    'customer_status' => 1,
                ];

                $customer = PosCustomer::create($customer_arr);

                if(!$membership || !$customer){
                    DB::callback();
                    return Response::json(array(
                        'success' => false,
                        'message' => 'Buy Membership Error. Check again!'
                    ), 400);
                }else{

                    $membership_point = 0;

                    $membership_point = self::checkMembership($order_list,$customer_id,$place_id);
                    }
                    DB::commit();
                    return Response::json(array(
                        'message' => 'Buy Membership Success!',
                        'membership_point' => $membership_point,
                        'customer_id' => $customer_id
                    ),200);
                }
            }
            if(!$membership){
                DB::callback();
                return Response::json(array(
                    'success' => false,
                    'message' => 'Buy Membership Error. Check again!'
                ), 400);
            }else{
                $membership_point = 0;
                $membership_point = self::checkMembership($order_list,$customer_id,$place_id);
                DB::commit();
                return Response::json(array(
                    'message' => 'Buy Membership Success!',
                    'membership_point' => $membership_point,
                    'customer_id' => $customer_id
                ),200);
            }
        }
    public static function checkMembership($order_list,$customer_id,$place_id){

        $today = Carbon::today();
        $membership_point = 0;

        if(count($order_list) != 0){
            $check_customer = PosCustomer::where('customer_place_id',$place_id)
                                            ->where('customer_id',$customer_id)
                                            ->first()
                                            ->customer_membership_id;
            if($check_customer == 0){
                return $membership_point = 0;

            }else{
                $customer_membership = DB::table('pos_customer_membership_history')->join('pos_membership_detail',function($join){
                                    $join->on('pos_customer_membership_history.cm_place_id','pos_membership_detail.membership_detail_place_id')
                                    ->on('pos_customer_membership_history.cm_membership_id','pos_membership_detail.membership_detail_membership_id');
                                    })
                                    ->where('pos_customer_membership_history.cm_place_id',$place_id)
                                    ->where('pos_customer_membership_history.cm_customer_id',$customer_id)
                                    ->where('pos_membership_detail.membership_detail_status',1)
                                    ->select('pos_membership_detail.membership_detail_listservice','pos_membership_detail.membership_detail_price','pos_membership_detail.membership_detail_percent_discount','cm_time_buy','membership_detail_time')
                                    ->orderBy('cm_time_buy','desc')
                                    ->take(1)
                                    ->skip(0)
                                    ->get();
                if($customer_membership->count()){

                    $expired_day = Carbon::parse($customer_membership[0]->cm_time_buy)->addMonths($customer_membership[0]->membership_detail_time);

                    if($today > $expired_day){

                        return $membership_point = 0;

                    }else{
                        $membership_service_list = explode(";",$customer_membership[0]->membership_detail_listservice);
                        $percent_discount = $customer_membership[0]->membership_detail_percent_discount;

                        foreach ($order_list as $value) {

                            foreach ($value as $key => $value_service) {

                                $service_id = strval($value_service['service_id']);

                                if( in_array($service_id,$membership_service_list) ){
                                    $membership_point += $value_service['service_price']*$percent_discount/100;
                                }
                            }
                        }
                    }
                }
            }
            return $membership_point;
        }
    }
    public function getListServiceMembership(Request $request){

        $membership_id = $request->membership_id;
        $place_id = $this->getCurrentPlaceId();

        $service_list = DB::table('pos_membership_detail')->where('membership_detail_place_id',$place_id)
                        ->where('membership_detail_membership_id',$membership_id)
                        ->first()
                        ->membership_detail_listservice;

        $service_arr = explode(";", $service_list);

        $service_name_list = PosService::where('service_place_id',$place_id)
                        ->whereIn('service_id',$service_arr)
                        ->select('service_name','service_price','service_duration')
                        ->get();
        return $service_name_list;
    }
}