<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\PosSmsSendEvent;
use Carbon\Carbon;
use DataTables;

class SmsController extends Controller
{
    const DAILY = 1;
    const WEEKLY = 2;
    const MONTHLY = 3;
    const QUARTERLY = 4;    
    const YEARLY = 5;   
    
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

        return view('report.sms_new');
    }
   
    public function getEventType(Request $request){
        // $request->date_order = '06/17/2019';

        $date_order =  format_date_db($request->date_order);
        $month_order = Carbon::parse($date_order)->format('m');
        $year_order = Carbon::parse($date_order)->format('Y');

        $event_sms_list = [];

        $event_list = SmsEventType::all();
        // $request->current_time_format = 'btnDaily';

        $color_arr = ['bg-blue-light','bg-gray-light','bg-blue-light','bg-gray-light'];

            // if($request->current_time_format == 'btnDaily'){

            //     $start_date = $date_order." 00:00:00";
            //     $end_date = $date_order." 23:59:59";

            // }
            // if($request->current_time_format == 'btnMonthly'){

            //     $start_date_count = new \DateTime($start_date);

            //     $end_date_count = new \DateTime($end_date);

            //     $count = $start_date_count->diff($end_date_count)->m + ($start_date_count->diff($end_date_count)->y*12);
            // }
            // if($request->current_time_format == 'btnWeekly'){

            //     $start_date_count = strtotime($start_date);

            //     $end_date_count = strtotime($end_date);

            //     $datediff = $end_date_count - $start_date_count;
            //     $count = ($datediff / (60 * 60 * 24))/7;
            // }

        foreach ($event_list as $key => $event) {


            if($request->current_time_format == 'btnDaily'){

                $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->where('sms_send_event_start_day',$date_order)
                            ->where('sms_send_event_status',1)
                            ->sum('sms_total');
            }
            if($request->current_time_format == 'btnWeekly'){

                 $ts = strtotime($date_order);
                // calculate the number of days since Monday
                $dow = date('w', $ts);

                $offset = $dow - 1;

                if ($offset < 0) {
                    $offset = 6;
                }
                // calculate timestamp for the Monday
                $ts = $ts - $offset * 86400;
                // loop from Monday till Sunday 
                for ($i = 0; $i < 7; $i++, $ts += 86400) {

                    $time_weekly[date('l', $ts)] = date('Y-m-d', $ts);
                }
                $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->where('sms_send_event_status',1)
                            ->whereBetween('sms_send_event_start_day',[ $time_weekly['Monday'],$time_weekly['Sunday'] ])
                            ->sum('sms_total');
            }

            if($request->current_time_format == 'btnMonthly'){
                $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->where('sms_send_event_status',1)
                            ->whereMonth('sms_send_event_start_day',$month_order )
                            ->whereYear('sms_send_event_start_day',$year_order)
                            ->sum('sms_total');
            }

            if($request->current_time_format == 'btnQuaterly'){

                $curQuarter = ceil($month_order/3);

                $month_of_quarter_arr = [
                    1 => [1,2,3],
                    2 => [4,5,6],
                    3 => [7,8,9],
                    4 => [10,11,12]
                ];
                $start_date = "01-" . $month_of_quarter_arr[$curQuarter][0] . "-" . $year_order;

                $start_time = strtotime($start_date);

                $end_time = strtotime("+3 month", $start_time);
                $end_date = date('Y-m-d',$end_time-86400);

                $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->where('sms_send_event_status',1)
                            ->whereBetween('sms_send_event_start_day',[ $start_date,$end_date ])
                            ->sum('sms_total');
            }
            if($request->current_time_format == 'btnYearly'){
                $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->where('sms_send_event_status',1)
                            ->whereYear('sms_send_event_start_day',$year_order)
                            ->sum('sms_total');
            }

            $event_sms_list[] = [
                'event_type_name' => $event,
                'sms_total' => $sms_total,
                'color' => $color_arr[$key-1],
                'type_id' => $key
            ];

        }
        return $event_sms_list;
    }
    public function getDataEvent(Request $request){
        $date_order = format_date_db($request->search_join_date);
        $date_type = $request->today;
        // $date_order = '01/17/2019';
        // $data_type = "btnQuaterly";
        $month_order = Carbon::parse($date_order)->format('m');
        $year_order = Carbon::parse($date_order)->format('Y');

        $event_list_sms = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId() )
                                        ->where('sms_send_event_status',1);
                                        // ->where('sms_send_event_type',3);
        
        if($date_type == "btnDaily"){
            $event_list_sms->where('sms_send_event_start_day',$date_order);
        }
        if($date_type == "btnWeekly"){
            // parse about any English textual datetime description into a Unix timestamp 
            $ts = strtotime($date_order);
            // calculate the number of days since Monday
            $dow = date('w', $ts);

            $offset = $dow - 1;

            if ($offset < 0) {
                $offset = 6;
            }
            // calculate timestamp for the Monday
            $ts = $ts - $offset * 86400;
            // loop from Monday till Sunday 
            for ($i = 0; $i < 7; $i++, $ts += 86400) {

                $time_weekly[date('l', $ts)] = date('Y-m-d', $ts);
            }
            $event_list_sms->whereBetween('sms_send_event_start_day',[ $time_weekly['Monday'],$time_weekly['Sunday'] ]);
        }
        if($date_type == "btnMonthly"){
            
            $event_list_sms->whereMonth('sms_send_event_start_day',$month_order)
                            ->whereYear('sms_send_event_start_day',$year_order)
                            ->get();
        }
        if($date_type == "btnQuaterly"){

            $curQuarter = ceil($month_order/3);

            $month_of_quarter_arr = [
                1 => [1,2,3],
                2 => [4,5,6],
                3 => [7,8,9],
                4 => [10,11,12]
            ];
            $start_date = "01-" . $month_of_quarter_arr[$curQuarter][0] . "-" . $year_order;

            $start_time = strtotime($start_date);

            $end_time = strtotime("+3 month", $start_time);
            $end_date = date('Y-m-d',$end_time-86400);

            $event_list_sms->whereBetween('sms_send_event_start_day',[ $start_date,$end_date ]);
        }
        if($date_type == "btnYearly"){
            $event_list_sms->whereYear('sms_send_event_start_day',$year_order);
        }
        if(isset($request->type_event_id) && $request->type_event_id != ""){
            $event_list_sms->where('sms_send_event_type',$request->type_event_id);
        }
        return DataTables::of($event_list_sms)
                        ->editColumn('sms_send_event_type',function($row){
                            $sms_event_type = SmsEventType::all();

                            return $sms_event_type[$row->sms_send_event_type];
                        })
                        ->addColumn('action',function($row){

                            return "<a class='btn btn-sm btn-secondary detail-event' type='".$row->sms_send_event_type."'  id='".$row->sms_send_event_id."'><i class='fa fa-list fa-lg'></i></a><a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-event' type='".$row->sms_send_event_type."' id='".$row->sms_send_event_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
    }
    public function calculateSms(Request $request){
        $event_id = $request->event_id;
        $type_id = $request->type_id;
        //return $request->all();

        if($type_id == 1)
            
            $url_api = "history?merchant_id=1&birthday_event_id=".$event_id;
        else
            $url_api = "history?merchant_id=1&storage_event_id=".$event_id;

        $url = "";
        $url = env("REVIEW_SMS_API_URL").$url_api.$url;
        //return $url;

        $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
        //$url="http://user.tag.com/api/v1/receiveTo";
        $client = new Client([
            // 'timeout'  => 5.0,            
        ]);
        $response = $client->get($url, array('headers' => $header));

        $resp=  (string)$response->getBody();

        $data_arr = json_decode($resp);

        $calculate = [];
        $success = 0;
        $fail = 0;

        //TOTAL SMS 
        $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                                    ->where('sms_send_event_id',$event_id)
                                    ->first()
                                    ->sms_total;

        $data_sum = [];

            foreach($data_arr->data as $data){

                if($data->status ==1) $success++;
                if($data->status ==0) $fail++;

                $calculate = [

                    'success' => $success,
                    'fail' => $fail,
                    'total' => $sms_total,
                    'balance' => $sms_total - $success
                ];
            }
        return $calculate;
    }
    public function eventDetail(Request $request){

        $event_id = $request->event_id;
        $type_id = $request->type_id;
        $data_sum = [];

        if($event_id != ""){

            if($type_id == 1)

                $url_api = "history?merchant_id=1&birthday_event_id=".$event_id;
            else
                $url_api = "history?merchant_id=1&storage_event_id=".$event_id;

            $url = env("REVIEW_SMS_API_URL").$url_api;
            //return $url;

            $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
            //$url="http://user.tag.com/api/v1/receiveTo";
            $client = new Client([
                // 'timeout'  => 5.0,            
            ]);
            $response = $client->get($url, array('headers' => $header));

            $resp=  (string)$response->getBody();
            // return $resp;

            $data_arr = json_decode($resp);

                foreach($data_arr->data as $data){
                    $data_sum[] = [
                        'phone' => $data->phone,
                        'content' => $data->content,
                        'date_time' => $data->updated_at,
                    ];

                }
        }else{
            $data_sum[] = [
                        'phone' => "",
                        'content' => "",
                        'date_time' => "",
                    ];
        }
        
        return Datatables::of($data_sum)
                           ->make(true);
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