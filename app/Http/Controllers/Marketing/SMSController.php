<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Requests;
use App\Models\PosSmsContentTemplate;
use App\Models\PosSmsContentTemplateDefault;
use App\Models\PosPlace;
use yajra\Datatables\Datatables;
use App\Models\PosCoupon;
use App\Models\PosPromotion;
use App\Models\PosSmsGroupReceivers;
use App\Models\PosCustomer;
use App\Models\PosSmsGroupReceiversDetail;
use App\Models\PosMessageTemplate;
use App\Models\PosSmsSendEvent;
use DB;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Client;
use App\Models\PosCustomertag;
use Validator;
use App\Helpers\SmsHelper;

class SMSController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');          
       // dd(SmsHelper::getCientWithBirthday(7,$this->getCurrentPlaceId()));
        // dd($this->listClientGroup());
        // die();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $event_id_list = PosSmsSendEvent::select('sms_send_event_id')
                                        ->where('sms_send_event_status',1)
                                        ->get();

        $event_list = SmsEventType::all();

        foreach ($event_id_list as $value) {

            $url = "";
            $api_url = 'reports?';
            $merchant_id = '1';
            $url = env("REVIEW_SMS_API_URL").$api_url.'merchant_id='.$merchant_id.'&event_id='.$value['sms_send_event_id'];

            $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
            //$url="http://user.tag.com/api/v1/receiveTo";
            $client = new Client([
                // 'timeout'  => 5.0,            
            ]);
            $response = $client->get($url, array('headers' => $header));

            $resp=  (string)$response->getBody();

            $data_arr = json_decode($resp);
            //return $data_arr->total;

            if($data_arr->data != []){

                foreach($data_arr->data as $data){
                   PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                                    ->where('sms_send_event_id',$value['sms_send_event_id'])
                                    ->update([
                                        'sms_fail'=> $data->fail,
                                        'sms_success' => $data->success
                                    ]);
                }
            }
        }
        $event_sms_list = [];
        $color_arr = ['red','green','blue','green'];
        $today = Carbon::now()->format('Y-m-d');

        foreach ($event_list as $key => $event) {

            $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->where('sms_send_event_start_day',$today)
                            ->where('sms_send_event_status',1)
                            ->sum('sms_total');

            $event_sms_list[] = [
                'event_type_name' => $event,
                'sms_total' => $sms_total,
                'color' => $color_arr[$key-1]
            ];
        }
       
        return view('marketing.sms.summary',compact('event_list','event_sms_list'));
    }

    public function getEvent(Request $request){

        $event_type_id = $request->event_type_id;
        $search_join_date = $request->search_join_date;
        $date_order = explode("-", $search_join_date);

        //birthday = "http://apirv.datallys.com/api/v1/birthday?merchant_id=1"
         //http://apirv.datallys.com/api/v1/events?merchant_id=1
        if($event_type_id == 1 )

            $url_api = "birthday";

        if($event_type_id > 1 || $event_type_id == "")
            $url_api = "events";

        $url = "";
        $url = env("REVIEW_SMS_API_URL").$url_api.'?merchant_id=1'.$url;

        $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
        //$url="http://user.tag.com/api/v1/receiveTo";
        $client = new Client([
            // 'timeout'  => 5.0,            
        ]);
        $response = $client->get($url, array('headers' => $header));
        $resp=  (string)$response->getBody();
        //return $resp;
        $data_sum = [];
        
            $data_arr = json_decode($resp);

            foreach($data_arr->data as $data){
                 
                 if($event_type_id == 1 ){

                    $data_event = [
                        'content' => $data->content,
                        'date_time' => format_date_d_m_y($data->start)." ".$data->timesend,
                        'send_to' => $data->totalSend,
                        'event_type' => "Birthday"
                    ];

                    if($search_join_date != ""){
                    $date_start = \Carbon\Carbon::parse($data->start)->format('m/d/Y');
                    //return $date_start;
                        if($date_start >= $date_order[0] ){

                                $data_sum[] = $data_event;
                        }
                    }else{
                        $data_sum[] = $data_event;
                    }

                }if($event_type_id > 1 ){

                $recieve_list = unserialize($data->recieve);
                $content = $data->content;

                foreach ($recieve_list as $recieve) {
                    if (strpos($content, '{p1}') !== false && $recieve['{p1}']) {
                        $content = str_replace('{p1}', $recieve['{p1}'] , $content);
                    }
                    if (strpos($content, '{p2}') !== false && $recieve['{p2}']) {
                        $content = str_replace('{p2}', $recieve['{p2}'] , $content);
                    }

                    $type_event = SmsEventType::all();

                    $data_event = [
                        'content' => $content,
                        'date_time' => format_date_d_m_y($data->start)." ".$data->timesend,
                        'send_to' => $recieve['phone'],
                        'event_type' => $type_event[$event_type_id]
                    ];
                    if($data->type_event == $event_type_id){
                        if($search_join_date != ""){

                            $date_start = \Carbon\Carbon::parse($data->start)->format('m/d/Y');
                            //return $date_start;

                            if($date_start >= $date_order[0] ){

                                    $data_sum[] = $data_event;
                            }
                        }else{
                            $data_sum[] = $data_event;
                        }
                    }
 
                }
            }
                
            }

         return Datatables::of($data_sum)
                ->make(true);
    }
    
    public function sendSMS()
    {
        $data = [
            'listEventType' => SmsEventType::all()
        ];
        $data['content_template'] = PosSmsContentTemplate::select('sms_content_template_id','template_title')->where('sms_content_template_place_id',$this->getCurrentPlaceId())->get();
        $data['group_receiver'] = PosSmsGroupReceivers::select('sms_group_receivers_id','sms_group_receivers_group_name')->where('sms_group_receivers_place_id',$this->getCurrentPlaceId())->where('sms_group_receivers_status',1)->get();
        /*$return = $this->PushApiSMS();
        dd($return);*/
        return view('marketing.sms.send_sms', $data);
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
                                'name' => 'extra',
                                'contents' => $input['add_receiver'],
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

    public function sendEmail()
    {
        return view('marketing.sms.send_email');
    }
    
    public function bookingPayment(){
        $data['content_template'] = PosSmsContentTemplate::select('sms_content_template_id','template_title')
                                    ->where('sms_content_template_place_id',$this->getCurrentPlaceId())->get();
        
        $PosMessageTemplate = PosMessageTemplate::select('mt_name','mt_type','created_at','remind_before')->where('mt_place_id',$this->getCurrentPlaceId())->get();
        
        $data['name_booking'] = '';
        $data['name_appointment'] = '';
        $data['name_payment'] = '';        
        $data['remind_before'] = '';
        
        foreach ($PosMessageTemplate as  $value) {
            if($value->mt_type == 1){ 
                $data['name_booking'] = $value->mt_name;
            }
            if($value->mt_type == 2){ 
                $data['name_appointment'] = $value->mt_name;                
                $data['remind_before'] = $value->remind_before;              
            }
            if($value->mt_type == 4){ 
                $data['name_payment'] = $value->mt_name;
            }
        }

        return view('marketing.sms.booking_payment',$data);
    }
    public function listSMS(){
        return view('marketing.sms.list_sms');
    }
    
    public function listTemplate(){       
        return view('marketing.sms.content_template');
    }
    public function smsAccountSummary(){
        return view('marketing.sms');
    }
    
    public function listGroupReceiver(){
        return view('marketing.sms.group_receiver');
    }
    
    public function viewSMS(){
        return view('marketing.sms.partials.view_sms')->render(); 
    }
    
    public function changeStatus(Request $request){

        if($request->checked == "checked")

            $sms_send_event_enable = 0;
        else
            $sms_send_event_enable = 1;

        $update = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                                ->where('sms_send_event_id',$request->sms_event_id)
                                ->update(['sms_send_event_enable'=>$sms_send_event_enable]);
    }
    
    public function addContentTemplate(){
         $data['PosPlace'] = PosPlace::select("place_name","place_phone","place_email","place_address")->where('place_id',$this->getCurrentPlaceId())->first();
         // echo $data['PosPlace']; die();
        return view('marketing.sms.content_template_add',$data);
    }
    public function editContentTemplate($id){
        $data['edit'] = PosSmsContentTemplate::where('sms_content_template_place_id',$this->getCurrentPlaceId())->where('sms_content_template_id',$id)->first();

        $data['PosPlace'] = PosPlace::select("place_name","place_phone","place_email","place_address")->where('place_id',$this->getCurrentPlaceId())->first();

        if(!$data['edit']) abort(404);
        
        return view('marketing.sms.content_template_add',compact("data","id"));
    }
    public function addGroupReceiver(){

        $customertag_list = PosCustomertag::where('customertag_place_id',$this->getCurrentPlaceId())
                                        ->get();

        return view('marketing.sms.group_receiver_add',compact('customertag_list'));
    }
    public function editGroupReceiver(){

        return view('marketing.sms.group_receiver_add');
    }
    //post form view('marketing.sms.content_template_add') 
    public function post_addOrEditContentTemplate(Request $request){
        $validate = Validator::make($request->all(),[
            'templateTitle'=>'required',
            'smsContentTemplate'=>'required',
        ],[

        ]);
        $error_array = [];
        $success_output = '';

        if($validate->fails()){
            foreach($validate->message()->getMessages() as  $message) {
             $error_array[] = $message;   
            }
        }else{
                if($request->id){
                        $arr = [
                            'template_title'=>$request->templateTitle,
                            'sms_content_template'=>$request->smsContentTemplate,
                        ];
                    $posSms = PosSmsContentTemplate::where('sms_content_template_place_id',$this->getCurrentPlaceId())->where('sms_content_template_id',$request->id)->update($arr);                     

                    $success_output = 'Edit Content Template Success!';
                } else {
                $sms_content_template_id = PosSmsContentTemplate::where('sms_content_template_place_id',$this->getCurrentPlaceId())->max('sms_content_template_id')+1;

                $posSms = new PosSmsContentTemplate;
                $posSms->sms_content_template_id = $sms_content_template_id;
                $posSms->sms_content_template_place_id = $this->getCurrentPlaceId();
                $posSms->template_title = $request->templateTitle;
                $posSms->sms_content_template = $request->smsContentTemplate;
                $posSms->save();
                $success_output = "Add Content Template Success!";
            }   
        }

        $output = array(
            'error' => $error_array,
            'success' => $success_output,
        );

        return json_encode($output);
        
    }

    //post form view('marketing.sms.content_template_add') 
    public function post_editContentTemplate(Request $request,$id){
        $this->validate($request,[
            'templateTitle'=>'required',
            'smsContentTemplate'=>'required',
        ],[

        ]);

        $arr = [
            'template_title'=>$request->templateTitle,
            'sms_content_template'=>$request->smsContentTemplate,
        ];

        $posSms = PosSmsContentTemplate::where('sms_content_template_place_id',$this->getCurrentPlaceId())->where('sms_content_template_id',$id)->update($arr);     
        

        return back()->with('message','Edit Content Template Success!');
    }
    // post form view('marketing.sms.group_receiver_add');
    public function post_addGroupReceiver(Request $request){
        
        $this->validate($request,[
            'groupName'=>'required',
        ],[

        ]);
        $sms_group_receivers_id = PosSmsGroupReceivers::where('sms_group_receivers_place_id',$this->getCurrentPlaceId())->max('sms_group_receivers_id')+1;
        
        $sms_group_receivers = new PosSmsGroupReceivers;
        $sms_group_receivers->sms_group_receivers_id = $sms_group_receivers_id;
        $sms_group_receivers->sms_group_receivers_place_id = $this->getCurrentPlaceId();
        $sms_group_receivers->sms_group_receivers_group_name = $request->groupName;
        
 

        //list contact phones 
            if($request->checkbox_client){
                
                $customer = PosCustomer::select('customer_place_id as sms_group_receivers_detail_place_id','customer_fullname as sms_group_receivers_detail_name','customer_phone as sms_group_receivers_detail_phone','customer_birthdate as sms_group_receivers_detail_dob')
                                ->wherein('customer_id',$request->checkbox_client)
                                ->where('customer_place_id',$this->getCurrentPlaceId())->get()->toarray();

                $sms_group_receivers_detail_id = PosSmsGroupReceiversDetail::where('sms_group_receivers_detail_place_id',$this->getCurrentPlaceId())->max('sms_group_receivers_detail_id')+1;
                
                foreach ($customer as $key =>$c) {
                    $customer[$key]['sms_group_receivers_detail_group_receivers_id'] = $sms_group_receivers_id;
                    $customer[$key]['sms_group_receivers_detail_id'] = $sms_group_receivers_detail_id;
                    $sms_group_receivers_detail_id++;
                }                                                 

                $sms_group_receivers_detail = PosSmsGroupReceiversDetail::insert($customer);
                $sms_group_receivers->sms_group_receivers_type = "In Place";    
            } //--
            else if($request->import_ListConteactPhones){
                $file = strtolower($request->import_ListConteactPhones->getClientOriginalExtension());
                
                if($file != "xls" && $file != 'xlsx')
                    return back()->with('message',"Error file *.$file!");

                $data = Excel::load($request->import_ListConteactPhones)->get();
                
                if($data->count() == 0)
                    return back()->with('message','Error empty file!');

                $sms_group_receivers_detail_id = PosSmsGroupReceiversDetail::where('sms_group_receivers_detail_place_id',$this->getCurrentPlaceId())->max('sms_group_receivers_detail_id')+1;

                $arr = [];

                foreach ($data as $key =>$c) {                    
                    $arr[$key]['sms_group_receivers_detail_id'] = $sms_group_receivers_detail_id;
                    $arr[$key]['sms_group_receivers_detail_place_id'] = $this->getCurrentPlaceId();
                    $arr[$key]['sms_group_receivers_detail_group_receivers_id'] = $sms_group_receivers_id;
                    $arr[$key]['sms_group_receivers_detail_name'] = $c->name;
                    $arr[$key]['sms_group_receivers_detail_phone'] = $c->phone;
                    $arr[$key]['sms_group_receivers_detail_dob'] = $c->birthday;
                    $sms_group_receivers_detail_id++;
                }  
                DB::beginTransaction();
                try {                    
                    $sms_group_receivers_detail = PosSmsGroupReceiversDetail::insert($arr);
                    $sms_group_receivers->sms_group_receivers_type = "Import";
                    DB::commit();
                } catch (\Exception $e) {
                DB::rollback();
                    return back()->with('message','Error template file!');
                }
            } else return back()->with('message','Error Check List Contact Phones!');
        //--
        $sms_group_receivers->save();
        return back()->with('message','Add Group Receivers Success!');

    }
    //load dataTables coupon_code
    public function loadDataTables_coupon_code(){
        $coupon_code = PosCoupon::select('coupon_code','coupon_discount')->where('coupon_place_id',$this->getCurrentPlaceId());
        return Datatables::of($coupon_code)
            ->make(true);
        
    }
    //load dataTables promotion link
    public function loadDataTables_promotion_link(){
        $promotion_link = PosPromotion::select('promotion_name','promotion_discount')->where('promotion_place_id',$this->getCurrentPlaceId());
        return Datatables::of($promotion_link)
            ->make(true);
        
    }
    // load dataTables content template
    public function DataTables_content_template(){
        $content_template = PosSmsContentTemplate::select('user_nickname','sms_content_template_id','template_title','sms_content_template','pos_sms_content_template.updated_at')
        ->join('pos_user',function($join){
            $join->on('pos_user.user_id','pos_sms_content_template.updated_by')
                ->on('pos_user.user_place_id','pos_sms_content_template.sms_content_template_place_id');
        })
        ->where('sms_content_template_place_id',$this->getCurrentPlaceId());

        $contentTemplateDefault = PosSmsContentTemplateDefault::select('created_at','sms_content_template_id','template_title','sms_content_template','updated_at')
                        ->where('status',1)->unionAll($content_template)->get();

        // echo $contentTemplateDefault; die();

        return Datatables::of($content_template)
            ->editColumn('template_title',function($content_template){
                return '<a href="'.route("editSmsTemplate",$content_template->sms_content_template_id).'" class="view-template">'.$content_template->template_title.'</a>';
            })
            ->editColumn('updated_at', function($content_template){
                return format_datetime($content_template->updated_at)." by ".$content_template->user_nickname;
            })
            ->rawColumns(['template_title'])
            ->make(true);
    }
    // load dataTables group receivers
    public function Datatables_group_receivers(){
        
        $group_receiver = PosSmsGroupReceivers::select(DB::raw('count(sms_group_receivers_id) as count'),'sms_group_receivers_id','sms_group_receivers_group_name','pos_sms_group_receivers.updated_at','user_nickname','sms_group_receivers_type')
                            ->join('pos_user',function($join){
                                $join->on('pos_user.user_id','pos_sms_group_receivers.updated_by')
                                    ->on('pos_user.user_place_id','pos_sms_group_receivers.sms_group_receivers_place_id');
                            })
                            ->join('pos_sms_group_receivers_detail',function($join){
                                $join->on('pos_sms_group_receivers_detail.sms_group_receivers_detail_group_receivers_id','pos_sms_group_receivers.sms_group_receivers_id')
                                ->on('pos_sms_group_receivers_detail.sms_group_receivers_detail_place_id','pos_sms_group_receivers.sms_group_receivers_place_id');
                            })
                            ->groupBy('pos_sms_group_receivers_detail.sms_group_receivers_detail_group_receivers_id')

                            ->where('sms_group_receivers_place_id',$this->getCurrentPlaceId())
                            ->where('sms_group_receivers_status',1);

                            
        return Datatables::of($group_receiver)
        ->addColumn('action',function($group_receiver){
            return '<a class="btn btn-sm btn-secondary  delete" href="#" data="'.$group_receiver->sms_group_receivers_id.'" data-place="'.$group_receiver->sms_group_receivers_place_id.'"><i class="fa fa-trash"></i></a>';
        })
        ->addColumn('total_user',function($group_receiver){             
            return '<a href="#" class="view-group-receivers" data="'.$group_receiver->sms_group_receivers_id.'">'.$group_receiver->count.'</a>';
        })
        ->editColumn('updated_at',function($group_receiver){
            return format_datetime($group_receiver->updated_at).' by '.$group_receiver->user_nickname;
        })
        ->rawColumns(['action','total_user'])
        ->make(true);
    }
    // load dataTables group receivers add
    public function Datatables_group_receivers_add(Request $request){
        // load Datatables Clients
        $search_customer_date = $request->search_customer_date;
        $client_group = $request->client_group;

        $client_list = PosCustomer::join('pos_customertag',function($join){
                                    $join->on('pos_customer.customer_place_id','pos_customertag.customertag_place_id')
                                    ->on('pos_customer.customer_customertag_id','pos_customertag.customertag_id');
                                    })
                                    ->where('pos_customer.customer_place_id',$this->getCurrentPlaceId());

        if($search_customer_date != ""){

            $date = explode("-", $search_customer_date);
            $client_list->whereBetween("customer_birthdate",[format_date_db($date[0]),format_date_db($date[1])]);
        }
        if($client_group != ""){
            $client_list->where('customer_customertag_id',$client_group);
        }
        $client_list->select('pos_customer.customer_id','pos_customer.customer_phone','pos_customer.customer_fullname','pos_customer.customer_birthdate','pos_customertag.customertag_name','pos_customertag.customertag_id')
            ->get();

        return Datatables::of($client_list)
        ->editColumn('customer_birthdate',function($client){
            return format_date($client->customer_birthdate);
        })
        ->addColumn('checkbox',function($client){
            return $client->customer_id;
        })
        ->rawColumns(['checkbox'])
        ->make(true);

    }
    // ajax delete_GroupReceiver 
    public function delete_GroupReceiver(Request $request){
        $arr = [
            'sms_group_receivers_status'=>0,
        ];

        $group_receiver = PosSmsGroupReceivers::where('sms_group_receivers_place_id',$this->getCurrentPlaceId())
                                        ->where('sms_group_receivers_id',$request->data)
                                        ->update($arr);
        
        if($group_receiver)
        return "Group Receivers Deleted!";
        else return "Error";
    }
    //url: /sms/greceiver_detail/datatable | load dataTables group receivers_detail
    public function Datatables_group_receivers_detail(){
        $receivers_detail = PosSmsGroupReceiversDetail::where('sms_group_receivers_detail_place_id',$this->getCurrentPlaceId());

        return Datatables::of($receivers_detail)
        ->editColumn('sms_group_receivers_detail_dob',function($receivers_detail){
            return format_date($receivers_detail->sms_group_receivers_detail_dob);
        })
        ->make(true);
    }
    //
    public function download_templatefile(){        
            if(file_exists('add_group_receivers_template.xlsx')){
            return response()->download('add_group_receivers_template.xlsx');            
            }
            else 
            return "Error download template";     
    }
    //post url /marketing/sms/bps 
    public function post_bookingPayment(Request $request){

        $PosMessageTemplate_id = PosMessageTemplate::where('mt_place_id',$this->getCurrentPlaceId())->max('mt_id')+1;
        //------- booking_website
        if($request->booking_website){
            $this->Setup_Booking_Payment(1,$PosMessageTemplate_id,$request->booking_website,$request->event_type);
            $PosMessageTemplate_id++;
        }
        //-------- remind_appointment
        if($request->remind_appointment){            
            $this->Setup_Booking_Payment(2,$PosMessageTemplate_id,$request->remind_appointment,$request->event_type);
            $PosMessageTemplate_id++;
        }
        //-------- payment_service
        if($request->payment_service){
            $this->Setup_Booking_Payment(4,$PosMessageTemplate_id,$request->payment_service,$request->event_type);
            $PosMessageTemplate_id++;
        }
        //--------
        return back()->with('message',"Setup Booking & Payment Success!");
    }
    //function insert or update Setup Booking & Payment
    private function Setup_Booking_Payment($type, $PosMessageTemplate_id, $request, $event_type){
        $PosMessageTemplate = PosMessageTemplate::where('mt_place_id',$this->getCurrentPlaceId())->where('mt_type',$type)->first();

            $content_template = PosSmsContentTemplate::where('sms_content_template_place_id',$this->getCurrentPlaceId())
                                                        ->where('sms_content_template_id',$request)->first();

            if($PosMessageTemplate){
                if($type == 2){ 
                    $arr = [
                        'mt_name'=>$content_template->template_title,
                        'mt_description'=>$content_template->sms_content_template,
                        'remind_before' =>$event_type,
                    ];
                }else{
                    $arr = [
                        'mt_name'=>$content_template->template_title,
                        'mt_description'=>$content_template->sms_content_template,
                    ];
                }
                PosMessageTemplate::where('mt_place_id',$this->getCurrentPlaceId())->where('mt_type',$type)->update($arr);
            }else{
                $PosMessageTemplate = new PosMessageTemplate;
                $PosMessageTemplate->mt_id = $PosMessageTemplate_id;
                $PosMessageTemplate->mt_place_id = $this->getCurrentPlaceId();
                $PosMessageTemplate->mt_name = $content_template->template_title;
                $PosMessageTemplate->mt_description = $content_template->sms_content_template;
                $PosMessageTemplate->mt_type = $type;
                if($type == 2){  
                    $PosMessageTemplate->remind_before = $event_type;
                }
                $PosMessageTemplate->save();                
            }
    }
    //-----------
    //url /sms/bps/ajax | ajax load content template 
    public function ajax_bookingPayment(Request $request){ 
        $ajax_bookingPayment = PosSmsContentTemplate::select('sms_content_template')
                                ->where('sms_content_template_place_id',$this->getCurrentPlaceId())
                                ->where('sms_content_template_id',$request->id)->first();

        if($ajax_bookingPayment)
        return $ajax_bookingPayment->sms_content_template;
        else return '';
    }
    //post | CREATE SEND SMS EVENT
    public function post_sendSMS(Request $request){
        //return $request->all();

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

            $group_receiver_list_sql = PosSmsGroupReceiversDetail::where('sms_group_receivers_detail_place_id',$this->getCurrentPlaceId())
                ->where('sms_group_receivers_detail_group_receivers_id',$group_receiver_id);

            $group_receiver_list = $group_receiver_list_sql->count();

            $receiver_list = $group_receiver_list_sql->get();

            foreach ($receiver_list as $key => $receiver) {
                $receiver_total[] = [
                    'name' => $receiver->sms_group_receivers_detail_name,
                    'birthday' => $receiver->sms_group_receivers_detail_dob,
                    'phone' => $receiver->sms_group_receivers_detail_phone
                ];
            }
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


        if($request->hasFile('list_receiver')){

        $path = $request->file('list_receiver')->getRealPath();

        $data = \Excel::load($path)->toArray();

                if(!empty($data)){

                    $arr = [];

                    foreach($data as $key => $value){
                            
                                $receiver_total[] = [
                                    'name' =>$value['name'],
                                    'phone'=>$value['phone'],
                                    'birthday'=>$value['birthday'],
                                ];
                                $arr[] = $value['phone'];
                            }

                            $pos_sms_send_event->upload_list_receiver = implode(";",$arr);

                            $upload_list_receiver = $key+1 ;
                }else{
                    $pos_sms_send_event->upload_list_receiver = "";
                    $upload_list_receiver = 0;
                    $request->session()->flash("message","Upload List Receiver Empty!");
                    return back();
                }
        }
        else
        {
            $pos_sms_send_event->upload_list_receiver = "";
            $upload_list_receiver = 0;
        }
        //GET REPEAT SEND SMS
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


        $pos_sms_send_event->save();


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

        return back()->with('message',$result['messages']);
    }
    // data SMS Management
    public function Datatable_SMS_Management(Request $request){ 
        $pos_sms_send_event = PosSmsSendEvent::select('sms_send_event_enable','sms_send_event_id','sms_send_event_title','sms_send_event_type','sms_send_event_start_day','sms_send_event_start_time','sms_send_event_end_date','sms_send_event_status','pos_sms_send_event.updated_at','pos_user.user_nickname','sms_total')
                    ->where('sms_send_event_place_id',$this->getCurrentPlaceId())
                    ->where('sms_send_event_status',1)
                            ->join('pos_user',function($join){
                              $join->on('pos_user.user_id','pos_sms_send_event.updated_by')
                                    ->on('pos_user.user_place_id','pos_sms_send_event.sms_send_event_place_id');
                                            });

        if(isset($request->search_join_date) && $request->search_join_date != ""){

            $date_order = explode("-",$request->search_join_date);

            $pos_sms_send_event->whereBetween('sms_send_event_start_day',[format_date_db($date_order[0]),format_date_db($date_order[1])]);
        }
        if(isset($request->today) && $request->today != ""){
            $today = Carbon::now()->format('Y-m-d');
            $pos_sms_send_event->where('sms_send_event_start_day',$today);
        }

        return Datatables::of($pos_sms_send_event)

        ->addColumn('last_update',function($pos_sms_send_event){ 

            return $pos_sms_send_event->updated_at. " by ".$pos_sms_send_event->user_nickname;
        })
        ->editColumn('sms_send_event_type',function($pos_sms_send_event){

            $sms_event_type = SmsEventType::all();

            return $sms_event_type[$pos_sms_send_event->sms_send_event_type];
        })
        ->editColumn('sms_send_event_enable',function($pos_sms_send_event){

            $checked = "";
            if($pos_sms_send_event->sms_send_event_enable == 1)
                $checked = "checked";
            
            return '<input type="checkbox" id="'.$pos_sms_send_event->sms_send_event_id.'" class="js-switch"'.$checked.' name="sms_status"/>';
        })
        ->addColumn('action',function($pos_sms_send_event){

            return "<a class='btn btn-sm btn-secondary detail-event' type='".$pos_sms_send_event->sms_send_event_type."'  id='".$pos_sms_send_event->sms_send_event_id."'><i class='fa fa-list fa-lg'></i></a><a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-event' type='".$pos_sms_send_event->sms_send_event_type."' id='".$pos_sms_send_event->sms_send_event_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
        })
        ->rawColumns(['sms_send_event_enable','action'])
        ->make(true);
    }
     public function deleteEvent(Request $request){

        //REMOVE IN API
        if($request->sms_event_id == 1){

            $url_api = "birthday?merchant_id=1";
        }else
            $url_api = "events?merchant_id=1";

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

        foreach ($data_arr->data as $data) {

            if($data->event_id == $request->sms_event_id){

                $url = env("REVIEW_SMS_API_URL").$url_api."$id=".$data->id.$url;
                //return $url;

                $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
                //$url="http://user.tag.com/api/v1/receiveTo";
                $client = new Client([
                    // 'timeout'  => 5.0,            
                ]);
                $response = $client->get($url, array('headers' => $header));

                $client->delete($url, array('headers' => $header));
            }
        }
        //return $resp;
        //END REMOVE API

        //REMOVE IN DATABASE
        $remove = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                         ->where('sms_send_event_id',$request->sms_event_id)
                         ->update(['sms_send_event_status'=>0]);


        if($remove)
            return response()->json([ 'success' => true]);
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
        $total = 0;

        $data_sum = [];

            foreach($data_arr->data as $data){

                if($data->status ==1) $success++;
                if($data->status ==0) $fail++;
                $total++;

                $calculate = [

                    'success' => $success,
                    'fail' => $fail,
                    'total' => $total
                ];
            }
        return $calculate;
    }
    public function getReciever(Request $request){

        $search_join_date = $request->search_join_date;

        $receiver_list = PosSmsGroupReceiversDetail::join('pos_sms_send_event',function($join){
                        $join->on('pos_sms_group_receivers_detail.sms_group_receivers_detail_place_id','pos_sms_send_event.sms_send_event_place_id')
                        ->on('pos_sms_group_receivers_detail.sms_group_receivers_detail_group_receivers_id','pos_sms_send_event.sms_send_event_id');
                        })
                        ->where('pos_sms_group_receivers_detail.sms_group_receivers_detail_place_id',$this->getCurrentPlaceId());
                        
        if($search_join_date != ""){

            $date_order = explode("-", $search_join_date);

            $receiver_list->whereBetween('sms_send_event_start_day',[format_date_db($date_order[0]),format_date_db($date_order[1])]);
        }
            $receiver_list->select('pos_sms_group_receivers_detail.*','pos_sms_send_event.sms_send_event_start_day','pos_sms_send_event.sms_send_event_start_time');

        return Datatables::of($receiver_list)
                        ->editColumn('content',function($row){
                            return "";
                        })
                         ->make(true);
    }
    public function getEventType(Request$request){

        $count_month = Carbon::now()->format('m');
        $current_year = Carbon::now()->format('Y');
        $today = Carbon::now()->format('Y-m-d');

        $date_order = explode("-",$request->date_order);
                
        $start_date = Carbon::parse($date_order[0])->format('Y-m-d');
        $end_date = Carbon::parse($date_order[1])->format('Y-m-d');

        $event_list = SmsEventType::all();

        $event_sms_list = [];

        $color_arr = ['red','green','blue','green'];

            if($request->current_time_format == 'daily'){

                $start_date_count = strtotime($start_date);
                $end_date_count = strtotime($end_date);

                $datediff = $end_date_count - $start_date_count;
                $count = $datediff / (60 * 60 * 24);

            }
            if($request->current_time_format == 'monthly'){

                $start_date_count = new \DateTime($start_date);

                $end_date_count = new \DateTime($end_date);

                $count = $start_date_count->diff($end_date_count)->m + ($start_date_count->diff($end_date_count)->y*12);
            }
            if($request->current_time_format == 'weekly'){

                $start_date_count = strtotime($start_date);

                $end_date_count = strtotime($end_date);

                $datediff = $end_date_count - $start_date_count;
                $count = ($datediff / (60 * 60 * 24))/7;
            }

        foreach ($event_list as $key => $event) {


            if($request->current_time_format == 'today'){

                $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->where('sms_send_event_start_day',$today)
                            ->where('sms_send_event_status',1)
                            ->sum('sms_total');
                $count = 1;
            }
            if($request->current_time_format != 'today')

                $sms_total = PosSmsSendEvent::where('sms_send_event_place_id',$this->getCurrentPlaceId())
                            ->where('sms_send_event_type',$key)
                            ->whereBetween('sms_send_event_start_day',[$start_date,$end_date])
                            ->where('sms_send_event_status',1)
                            ->sum('sms_total');

            
            if($count == 0)
                $sms_total_result = 0;
            else
                $sms_total_result = round($sms_total/$count,2);

            $event_sms_list[] = [
                'event_type_name' => $event,
                'sms_total' => $sms_total_result,
                'color' => $color_arr[$key-1]
            ];

        }
        return $event_sms_list;
    }

    //------Sms Setting
    public function smsSetting(){
        $data['PosPlace'] = PosPlace::select("place_name","place_phone","place_email","place_address")->where('place_id',$this->getCurrentPlaceId())->first();        
        
        $data['listClientGroup'] = $this->listClientGroup();
        
        // echo ($this->getCustomerIdByArrayQuery(SmsHelper::typeCustomer($this->getCurrentPlaceId(),'Vip')));
        //die();
        return view('marketing.sms.sms_setting',$data);
    }

    public function ajax_DataTableSmsSetting(){
        $content_template = PosSmsContentTemplate::select('user_nickname','sms_content_template_id','template_title','sms_content_template','pos_sms_content_template.updated_at')
        ->join('pos_user',function($join){
            $join->on('pos_user.user_id','pos_sms_content_template.updated_by')
                ->on('pos_user.user_place_id','pos_sms_content_template.sms_content_template_place_id');
        })
        ->where('sms_content_template_place_id',$this->getCurrentPlaceId())->get()->toArray();

        $contentTemplateDefault = PosSmsContentTemplateDefault::select('sms_content_template_id','template_title','sms_content_template','updated_at')
                        ->where('status',1)->get()->toArray();
        $data = array_merge($contentTemplateDefault,$content_template);

        return json_encode($data);
    }

    public function get_SmsContentTemplate(Request $request){
        $posContent = PosSmsContentTemplate::select('template_title','sms_content_template')
                                            ->where('sms_content_template_place_id',$this->getCurrentPlaceId())
                                            ->where('sms_content_template_id',$request->id)
                                            ->first();
        if($posContent){
            return json_encode($posContent);
        }
    }

    private function listClientGroup(){
        $NEW = SmsHelper::typeCustomer($this->getCurrentPlaceId(),'New');
        $ROYAL = SmsHelper::typeCustomer($this->getCurrentPlaceId(),'Royal');
        $VIP = SmsHelper::typeCustomer($this->getCurrentPlaceId(),'Vip');
        $NORMAL_MEMBERSHIP = SmsHelper::membership($this->getCurrentPlaceId(),'Normal Membership');
        $SILVER_MEMBERSHIP = SmsHelper::membership($this->getCurrentPlaceId(),'Silver Membership');
        $GOLDEN_MEMBERSHIP = SmsHelper::membership($this->getCurrentPlaceId(),'Golden Membership');
        $DIMOND_MEMBERSHIP = SmsHelper::membership($this->getCurrentPlaceId(),'Dimond Membership');        
        $REMINDER_7_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),7);
        $REMINDER_15_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),15);
        $REMINDER_21_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),21);
        $REMINDER_30_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),30);
        $REMINDER_60_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),60);
        $REMINDER_90_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),90);
        $REMINDER_180_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),180);
        $REMINDER_365_DAYS = SmsHelper::remider($this->getCurrentPlaceId(),365);
        $BIRTHDAY_JANUARY = SmsHelper::getCientWithBirthday(1,$this->getCurrentPlaceId());
        $BIRTHDAY_FEBRUARY = SmsHelper::getCientWithBirthday(2,$this->getCurrentPlaceId());
        $BIRTHDAY_MARCH = SmsHelper::getCientWithBirthday(3,$this->getCurrentPlaceId());
        $BIRTHDAY_APRIL = SmsHelper::getCientWithBirthday(4,$this->getCurrentPlaceId());
        $BIRTHDAY_MAY = SmsHelper::getCientWithBirthday(5,$this->getCurrentPlaceId());
        $BIRTHDAY_JUNE = SmsHelper::getCientWithBirthday(6,$this->getCurrentPlaceId());
        $BIRTHDAY_JULY = SmsHelper::getCientWithBirthday(7,$this->getCurrentPlaceId());
        $BIRTHDAY_AUGUST = SmsHelper::getCientWithBirthday(8,$this->getCurrentPlaceId());
        $BIRTHDAY_SEPTEMBER = SmsHelper::getCientWithBirthday(9,$this->getCurrentPlaceId());
        $BIRTHDAY_OCTOBER = SmsHelper::getCientWithBirthday(10,$this->getCurrentPlaceId());
        $BIRTHDAY_NOVEMBER = SmsHelper::getCientWithBirthday(11,$this->getCurrentPlaceId());
        $BIRTHDAY_DECEMBER = SmsHelper::getCientWithBirthday(12,$this->getCurrentPlaceId());

        $listClientGroup = [
            [
                'group_name'=>'NEW',
                'total_user'=>$NEW->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($NEW),
            ],
            [
                'group_name'=>'ROYAL',
                'total_user'=>$ROYAL->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($ROYAL),
            ],
            [
                'group_name'=>'VIP',
                'total_user'=>$VIP->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($VIP),
            ],
            [
                'group_name'=>'NORMAL MEMBERSHIP',
                'total_user'=>$NORMAL_MEMBERSHIP->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($NORMAL_MEMBERSHIP),
            ],
            [
                'group_name'=>'SILVER MEMBERSHIP',
                'total_user'=>$SILVER_MEMBERSHIP->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($SILVER_MEMBERSHIP),
            ],
            [
                'group_name'=>'GOLDEN MEMBERSHIP',
                'total_user'=>$GOLDEN_MEMBERSHIP->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($GOLDEN_MEMBERSHIP),
            ],
            [
                'group_name'=>'DIMOND MEMBERSHIP',
                'total_user'=>$DIMOND_MEMBERSHIP->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($DIMOND_MEMBERSHIP),
            ],            
            [
                'group_name'=>'REMINDER 7 DAYS',
                'total_user'=>$REMINDER_7_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_7_DAYS),
            ],
            [
                'group_name'=>'REMINDER 15 DAYS',
                'total_user'=>$REMINDER_15_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_15_DAYS),
            ],
            [
                'group_name'=>'REMINDER 21 DAYS',
                'total_user'=>$REMINDER_21_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_21_DAYS),
            ],
            [
                'group_name'=>'REMINDER 30 DAYS',
                'total_user'=>$REMINDER_30_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_30_DAYS),
            ],
            [
                'group_name'=>'REMINDER 60 DAYS',
                'total_user'=>$REMINDER_60_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_60_DAYS),
            ],
            [
                'group_name'=>'REMINDER 90 DAYS',
                'total_user'=>$REMINDER_90_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_90_DAYS),
            ],
            [
                'group_name'=>'REMINDER 180 DAYS',
                'total_user'=>$REMINDER_180_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_180_DAYS),
            ],
            [
                'group_name'=>'REMINDER 365 DAYS',
                'total_user'=>$REMINDER_365_DAYS->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($REMINDER_365_DAYS),
            ],
            [
                'group_name'=>'BIRTHDAY JANUARY',
                'total_user'=>$BIRTHDAY_JANUARY->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_JANUARY),
            ],
            [
                'group_name'=>'BIRTHDAY FEBRUARY',
                'total_user'=>$BIRTHDAY_FEBRUARY->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_FEBRUARY),
            ],
            [
                'group_name'=>'BIRTHDAY MARCH',
                'total_user'=>$BIRTHDAY_MARCH->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_MARCH),
            ],
            [
                'group_name'=>'BIRTHDAY APRIL',
                'total_user'=>$BIRTHDAY_APRIL->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_APRIL),
            ],
            [
                'group_name'=>'BIRTHDAY MAY',
                'total_user'=>$BIRTHDAY_MAY->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_MAY),
            ],
            [
                'group_name'=>'BIRTHDAY JUNE',
                'total_user'=>$BIRTHDAY_JUNE->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_JUNE),
            ],
            [
                'group_name'=>'BIRTHDAY JULY',
                'total_user'=>$BIRTHDAY_JULY->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_JULY),
            ],
            [
                'group_name'=>'BIRTHDAY AUGUST',
                'total_user'=>$BIRTHDAY_AUGUST->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_AUGUST),
            ],
            [
                'group_name'=>'BIRTHDAY SEPTEMBER',
                'total_user'=>$BIRTHDAY_SEPTEMBER->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_SEPTEMBER),
            ],
            [
                'group_name'=>'BIRTHDAY OCTOBER',
                'total_user'=>$BIRTHDAY_OCTOBER->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_OCTOBER),
            ],
            [
                'group_name'=>'BIRTHDAY NOVEMBER',
                'total_user'=>$BIRTHDAY_NOVEMBER->count(),
                'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_NOVEMBER),
            ],
            [
                'group_name'=>'BIRTHDAY DECEMBER',
                'total_user'=>$BIRTHDAY_DECEMBER->count(),
                 'list_id'=>$this->getCustomerIdByArrayQuery($BIRTHDAY_DECEMBER),
            ],


        ];

        return $listClientGroup;
    }

    private function getCustomerIdByArrayQuery($arrQuery){
        //return ex stringCustomerId: 1,2,3,4
        $stringCustomerId = '';
        foreach ($arrQuery as $value) {
            $stringCustomerId .= $value->customer_id.','; 
        }
        $stringCustomerId = substr($stringCustomerId, 0,-1);

        return $stringCustomerId;
    }

    public function ajax_getCustomerByStringCustomerId(Request $request){
        if($request->string_id){
            $arrId = explode(',', $request->string_id);
            $customer = PosCustomer::select('customer_id','customer_fullname','customer_phone')
                                ->where('customer_place_id',$this->getCurrentPlaceId())
                                ->whereIn('customer_id',$arrId)
                                ->get();
            return json_encode($customer);
        }            
    }
    
    
} //


//--
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
