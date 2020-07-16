<?php

namespace App\Http\Controllers\DataSetup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosCustomer;
use App\Models\PosCustomertag;
use App\Models\PosBooking;
use App\Models\PosService;
use App\Models\PosWorker;
use App\Models\PosOrder;
use App\Models\PosLoyalty;
use App\Models\PosMembership;
use yajra\Datatables\Datatables;
use Session;
use Validator;
use Illuminate\Validation\Rule;
use App\Exports\PosCustomerExport;
use App\Imports\PosCustomerImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use app\Helpers\GeneralHelper;
use Carbon\Carbon;

class CustomerController extends Controller
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

        $list_customertag = PosCustomertag::where('customertag_place_id', $this->getCurrentPlaceId())->get();
        return view('customer.customers',compact('list_customertag'));
    }
    
    public function view($id){
        $data['customer_item'] = PosCustomer::leftjoin("pos_customertag",function($join){
                                                $join->on("pos_customer.customer_customertag_id","=","pos_customertag.customertag_id")
                                                    ->on("pos_customer.customer_place_id","=","pos_customertag.customertag_place_id");
                                        })
                                    ->leftjoin('pos_order',function($join){
                                        $join->on("pos_customer.customer_id","=","pos_order.order_customer_id");
                                    })
                                    ->leftjoin('pos_membership','pos_membership.membership_id','pos_customer.customer_membership_id')
                                    ->orderBy('pos_update','desc')
                                    ->where('pos_customer.customer_place_id', $this->getCurrentPlaceId())
                                    ->where('pos_customer.customer_id', $id)
                                    ->select('pos_customer.*' ,'pos_customertag.customertag_name','pos_order.updated_at as pos_update','membership_name')
                                    ->first();
              // echo $data['customer_item']; die();                    
        // check id
        if(!isset($data['customer_item'])){
            abort(404);
        }

        $first_visit = PosOrder::select('order_datetime_payment')
                                ->where('order_customer_id',$id)
                                ->where('order_place_id',$this->getCurrentPlaceId())
                                ->first();
                        
        $data['last_visit']= PosOrder::select('pos_order.order_datetime_payment','pos_worker.worker_nickname')
                                ->join('pos_orderdetail',function($joinOrderDetail){
                                    $joinOrderDetail->on('pos_orderdetail.orderdetail_place_id','pos_order.order_place_id')
                                    ->on('pos_orderdetail.orderdetail_order_id','pos_order.order_id');
                                })
                                ->join('pos_worker',function($joinWorker){
                                    $joinWorker->on('pos_worker.worker_place_id','pos_orderdetail.orderdetail_place_id')
                                    ->on('pos_worker.worker_id','pos_orderdetail.orderdetail_worker_id');
                                })
                                ->where('order_customer_id',$id)
                                ->where('order_place_id',$this->getCurrentPlaceId())
                                ->orderBy('order_id','desc')
                                ->first();  
                            
        $count_visit = PosOrder::select('order_price')
                                ->where('order_customer_id',$id)
                                ->where('order_place_id',$this->getCurrentPlaceId())
                                ->get();    
        $data['rating'] = PosCustomer::select('cr_rating','cr_description')
                            ->join('pos_customer_rating',function($joinRating){
                            $joinRating->on('pos_customer_rating.cr_place_id','pos_customer.customer_place_id')
                            ->on('pos_customer.customer_phone','pos_customer_rating.cr_phone');
                            })
                            ->where('customer_place_id', $this->getCurrentPlaceId())
                            ->where('customer_id', $id)
                            ->where('cr_status',1)
                            ->orderBy('cr_id','desc')
                            ->first();

        $loyalty = PosLoyalty::select('loyalty_point_to_amount')->where('loyalty_place_id',$this->getCurrentPlaceId())->first();
        
        $data['count_visit'] = $count_visit->count();
        $data['total_spend'] = $count_visit->sum('order_price');
        // check $first_visit
        if($first_visit){
            //$data['first_visit'] = $first_visit->order_datetime_payment;
        }else $data['first_visit'] = '';
        
        // $last_visit = $last_visit->order_datetime_payment;
        // $worker_nickname = $last_visit->worker_nickname;
        $data['id'] = $id;

        if($data['customer_item']){
           $customer_point_total = $data['customer_item']->customer_point_total; 
        } else  $customer_point_total = 0;
        
        //get poin to amount
        $loyalty_point_to_amount = $loyalty->loyalty_point_to_amount;
        if($loyalty_point_to_amount){
            $arr = explode('-', $loyalty_point_to_amount);            
            $data['rewardEarnedValue'] = $customer_point_total/($arr[0]/$arr[1]);
        }

        if($data['customer_item']->customer_customertag_id === 0){
            $data['client_type'] = "New";
        } else {
            $data['client_type'] = $data['customer_item']->customertag_name;
        }
        return view('customer.customer_info' , $data);
    }

    //GET TICKET LIST BY CUSTOMER DATATABLE - BEGIN
     public function getBookingListByCustomer(Request $request){
        $ticket_list = PosBooking::where('pos_booking.booking_place_id',$this->getCurrentPlaceId())
                                    ->where('pos_booking.booking_customer_id',$request->id)->get();

        //FORMAT COLUMN DATATABLE
        return Datatables::of($ticket_list)
            ->editColumn('booking_id',function($row){
                return  "<a href='/salefinance/ticket/".$row->booking_id."'>#".$row->booking_id." </a>";
            })
            ->editColumn('booking_date',function($row){
                return  format_date($row->booking_time_selected); 
            })
            ->editColumn('booking_time',function($row){
                return  gettime_by_datetime($row->booking_time_selected); 
            })
            ->addColumn('duration', function($row){
                $lst_service = explode(",",$row->booking_lstservice);
                return PosService::whereIn('service_id', $lst_service)
                                ->where('service_place_id', $this->getCurrentPlaceId())
                                ->sum('service_duration');
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
                    $service_list.=$worker->worker_nickname." - ".$value->service_name."</br>";
                }
                return $service_list;
            })
            ->addColumn('status', function($row){
                return \GeneralHelper::convertBookingStatusHtml($row->booking_status);
            })
            ->rawColumns(['booking_id','rentstation_service' ,'status'])
            ->make(true);
     }
    //GET TICKET LIST BY CUSTOMER DATATABLE - END


     //GET ORDER LIST BY CUSTOMER DATATABLE - BEGIN
     public function getOrderListByCustomer(Request $request){
        $order_list = PosOrder::join("pos_orderdetail",function($join){
                                                $join->on("pos_order.order_id","=","pos_orderdetail.orderdetail_order_id")
                                                    ->on("pos_order.order_place_id","=","pos_orderdetail.orderdetail_place_id");
                                        })
                                ->leftjoin("pos_worker",function($join){
                                                $join->on("pos_worker.worker_id","=","pos_orderdetail.orderdetail_worker_id")
                                                    ->on("pos_order.order_place_id","=","pos_worker.worker_place_id");
                                        })
                                    ->where('pos_order.order_place_id',$this->getCurrentPlaceId())
                                    ->where('pos_order.order_customer_id',$request->id)
                                    ->get();
         // dd($order_list);
        //FORMAT COLUMN DATATABLE
        return Datatables::of($order_list)
            ->editColumn('order_bill',function($row){
                return  "<a href='salefinance/order/".$row->order_id."'>".$row->order_bill." </a>";
            })
            ->editColumn('order_datetime_payment',function($row){
                return  format_datetime($row->order_datetime_payment); 
            })

            ->addColumn('status', function($row){
                return \GeneralHelper::convertPaymentType($row->order_payment_method);
            })
            ->rawColumns(['order_bill' ,'status'])
            ->make(true);
     }
    //GET ORDER LIST BY CUSTOMER DATATABLE - END

    
    public function edit($id=0,$code = "(+1)"){
        
        $data=[
            'headNumber'=>GeneralHelper::all()
        ];
        // dd($data);
        $list_customertag = PosCustomertag::where('customertag_place_id', $this->getCurrentPlaceId())->get();
        if($id >0)
        {
            $customer_item = PosCustomer::where('customer_place_id', $this->getCurrentPlaceId())
                                ->where('customer_id', $id)
                                ->first();

            $customer_dateofbirth = format_date_d_m_y($customer_item->customer_dateofbirth);
            return view('customer.customer_edit',compact('list_customertag','customer_item','id','customer_dateofbirth','data'));
        }else{
            return view('customer.customer_edit',compact('list_customertag','id','data'));
        }
        
    }


    //DELETE CUSTOMER - BEGIN
    public function deleteCustomer(Request $request)
    {
        $customer = PosCustomer::where('customer_place_id', $this->getCurrentPlaceId())
                            ->where('customer_id',$request->id)
                            ->update([ 'customer_status'=> 0 ]);
        if($customer)
        {
            return "Delete customer success!";
        }
        else
        {
            return "Delete customer error!";
        }
    }
    //DELETE CUSTOMER - END

    

    //SAVE OR EDIT CUSTOMER - BEGIN
    public function saveCustomer(Request $request)
    {
        $customer_id = $request->customer_id;
        
        if($customer_id >0){ // CHECK EXIST WHEN EDIT
            $check_exist = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                        ->where('customer_phone', $request->customer_phone)
                                        ->where('customer_id','!=',$customer_id)
                                        ->first();
        }else //CHECK EXIST WHEN ADD NEW
        {
            $check_exist = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                    ->where('customer_phone', $request->customer_phone)->first();
        }
        
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
                $validator->errors()->add('customer_phone.exists', 'Phone number is exist, Please check again!');
            });
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        else
        {
            //CHECK IS EDIT
            $list_customertag = PosCustomertag::where('customertag_place_id', $this->getCurrentPlaceId())->get();
            if($customer_id >0){
                $PosCustomer = PosCustomer::where('customer_place_id','=',$this->getCurrentPlaceId())
                            ->where('customer_id',$customer_id)
                            ->update(['customer_fullname'=>$request->customer_fullname ,
                                    'customer_phone'=>$request->customer_phone,
                                    'customer_country_code'=>$request->country_code,
                                    'customer_email'=>$request->customer_email,
                                    'customer_gender'=>$request->gender,
                                    'customer_birthdate'=>format_date_db($request->customer_dateofbirth),
                                    'customer_customertag_id'=>$request->customertag_id,
                                    'customer_address'=>$request->customer_address
                                ]);
                if($PosCustomer)
                        $request->session()->flash('message', 'Edit Customer Success!');
                else    $request->session()->flash('error', 'Edit Customer Error!');
                // return view('customer.customers',compact('list_customertag'));
                return redirect()->route('clients');

            }else //IS ADD NEW
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
                        $request->session()->flash('message', 'Insert Customer Success!');
                else    $request->session()->flash('error', 'Edit Customer Error!');
                
                // return view('customer.customers',compact('list_customertag'));
                return redirect()->route('clients');
            }
        }   
    }
    //SAVE OR EDIT CUSTOMER - END
    
     public function groups()
    {
        return view('customer.groups');
    }


    //GET DATATABLE CUSTOMERS(CLIENTS) IN PAYMENT - BEGIN
     public function getCustomerDatatablePayment(Request $request)
    {
        $customerlist = PosCustomer::join("pos_customertag",function($join){
                                        $join->on("pos_customer.customer_customertag_id","=","pos_customertag.customertag_id")
                                            ->on("pos_customer.customer_place_id","=","pos_customertag.customertag_place_id");
                                    })
                        ->where('pos_customer.customer_place_id', $this->getCurrentPlaceId())
                        ->where('pos_customer.customer_status', 1)
                        ->select('pos_customer.*' ,'pos_customertag.customertag_name');

        //FORMAT COLUMN DATATABLE
        return Datatables::of($customerlist)
            ->editColumn('customer_birthdate', function ($row) 
            {
                return format_date($row->customer_birthdate);
            })
            ->editColumn('customer_gender', function ($row) 
            {
                return \GeneralHelper::convertGender($row->customer_gender);
            })
            ->make(true);
    }
    //GET DATATABLE CUSTOMERS(CLIENTS) IN PAYMENT - END

    //GET DATATABLE CUSTOMERS(CLIENTS) - BEGIN
     public function getCustomerDatatable()
    {
        
            $customerlist = PosCustomer::leftjoin("pos_customertag",function($join){
                                                $join->on("pos_customer.customer_customertag_id","=","pos_customertag.customertag_id")
                                                    ->on("pos_customer.customer_place_id","=","pos_customertag.customertag_place_id");
                                        })
                                        ->leftjoin("pos_order",function($joinOrder){
                                            $joinOrder->on('pos_order.order_place_id','pos_customer.customer_place_id')
                                                ->on('pos_order.order_customer_id','pos_customer.customer_id');
                                        })
                                        ->leftjoin("pos_membership","membership_id","customer_membership_id")
                                        ->where('pos_customer.customer_place_id', $this->getCurrentPlaceId())
                                        ->where('pos_customer.customer_status', 1)
                                        ->select('pos_customer.*' ,'pos_customertag.customertag_name','pos_order.order_datetime_payment','membership_name')
                                        ->get();
                                        // echo $customerlist; die();


        
        //FORMAT COLUMN DATATABLE
        return Datatables::of($customerlist)
            ->editColumn('customer_fullname', function ($row) 
            {
                return '<a href="'.route('client',$row->customer_id).'" >'.$row->customer_fullname.'</a>';
            })
            ->editColumn('customer_gender', function($row)
            {
                return \GeneralHelper::convertGender($row->customer_gender);
            })
            ->editColumn('customer_phone', function ($row) 
            {
                return '<a href="'.route('client',$row->customer_id).'" >'.GeneralHelper::formatPhoneNumber($row->customer_phone,$row->customer_country_code).'</a>';
            })
            ->editColumn('customer_birthdate', function ($row) 
            {
                return format_date($row->customer_birthdate);
            })
            ->editColumn('customertag_name', function ($row) 
            {   
                if($row->customer_customertag_id === 0){
                    return "New";
                }
                return $row->customertag_name;
            })
            ->editColumn('created_at', function ($row) 
            {
                return format_date($row->created_at);
            })
            ->addColumn('action', function($row){
                return "<a href='".route('client-info',$row->customer_id)."' class='btn btn-sm btn-secondary ' title='view client infomation'><i class='glyphicon glyphicon-eye-open'></i></a> 
                    <a href='".route('client',$row->customer_id)."' class='edit-customer btn btn-sm btn-secondary' ><i class='fa fa-pencil '></i></a> 
                    <a href='#'' class='delete-customer btn btn-sm btn-secondary' id='".$row->customer_id."' data-type='user'><i class='fa fa-trash-o '></i></a>" ;
            })
            ->addColumn('search_birthday',function($row){
                return format_month($row->customer_birthdate);
            })
            ->addColumn('visited_time_group',function($row){                
                $dataNow = Carbon::now();
                $lastDateVisited = $row->order_datetime_payment;
                // $lastDateVisited = "2017-08-22 17:31:20";
                $visited_time = strtotime($dataNow) - strtotime($lastDateVisited);
                $visited_time = floor($visited_time / (60*60*24));

                if($visited_time >= 7 && $visited_time < 14){
                    $visited_time = 7;
                } else if($visited_time >= 14 && $visited_time < 21){
                    $visited_time = 14;
                } else if($visited_time >= 21 && $visited_time < 30){
                    $visited_time = 21;
                } else if($visited_time >= 30 && $visited_time < 60){
                    $visited_time = 30;
                } else if($visited_time >= 60 && $visited_time < 90){
                    $visited_time = 60;
                } else if($visited_time >= 90 && $visited_time < 180){
                    $visited_time = 90;
                } else if($visited_time >= 180 && $visited_time < 365){
                    $visited_time = 180;
                } else if($visited_time >= 365){
                    $visited_time = 365;
                }                
                return $visited_time."_DAYS";
            })            
            ->rawColumns(['customer_fullname' , 'customer_phone', 'action'])
            ->make(true);
    }
    //GET DATATABLE CUSTOMERS(CLIENTS) - END


    //GET DATATABLE CUSTOMERTAG - BEGIN
    public function getGroupsDatatable()
    {
        $groups = PosCustomertag::where('customertag_place_id', $this->getCurrentPlaceId() )->get();

        return Datatables::of($groups)
            ->addColumn('customertag_status_value', function ($row) 
            {
                //CONVERT STATUS
                if($row->customertag_status==1){
                    return "Active";
                }else{
                    return "Inactive";
                }
                ;
            })
            ->addColumn('action', function($row){
                return '<a href="#" class="btn btn-sm btn-secondary" name="edit_group" id="'.$row->customertag_id.'"><i class="fa fa-edit"></i></a><a href="#" class="delete-group btn btn-sm btn-secondary" name="delete_group" id="'.$row->customertag_id.'"><i class="fa fa-trash-o"></i></a>';
            })
            ->make(true);
    }
    //GET DATATABLE CUSTOMERTAG - END

    //SAVE GROUP - BEGIN
    public function saveGroup(Request $request)
    {
        $customertag_id = $request->group_id;
        if(!isset($customertag_id)){
            //CHECK EXIST 
            $exist = PosCustomertag::where('customertag_name',$request->group_name)
                                    ->where('customertag_place_id',$this->getCurrentPlaceId())
                                    ->first();
            if($exist === NULL)
            {
                $group = new PosCustomertag ;
                $group->customertag_place_id = $this->getCurrentPlaceId();
                $group->customertag_name = $request->group_name;
                $group->customertag_description = $request->group_description;
                $group->customertag_rule_chargedup = $request->group_rule_chargedup;
                $group->customertag_rule_months = $request->group_rule_months;
                $group->customertag_status = $request->active;
                if($group->save())
                {
                    return "Insert Group Success!";
                }else return "Insert Group Error!";
            }else return "Customer Group is exist please check again.";

            
        }else{

            //UDATE CUSTOMER GROUP
            $group = PosCustomertag::where('customertag_id',$customertag_id)
                                    ->where('customertag_place_id', $this->getCurrentPlaceId())
                                    ->update(['customertag_name'=>$request->group_name , 'customertag_description'=>$request->group_description ,'customertag_rule_chargedup'=>$request->group_rule_chargedup , 'customertag_rule_months'=>$request->group_rule_months , 'customertag_status'=>$request->active]);
            if($group)
            {
                return "Update Group Success!";
            }else return "Update Group Error!";           
        }
    }
    //SAVE GROUP - END

    //DELETE GROUP - BEGIN
    public function deleteGroup(Request $request)
    {
        $group = PosCustomertag::find($request->id);
        if($group->delete())
        {
            return "Delete group success!";
        }
    }
    //DELETE GROUP - END
    



    //GET LIST CUSTOMER TAG DROPDOWN - BEGIN
    public function getListCustomerTag()
    {
        $list_customertag = PosCustomertag::where('customertag_place_id', $this->getCurrentPlaceId());
            $customertag_arr = array();
        foreach($list_customertag as $customertag)
        {
            $customertag_id = $row['customertag_id'];
            $customertag_name = $row['customertag_name'];

            $customertag_arr[] = array("customertag_id" => $userid, "customertag_name" => $name);
        }   
        
        return json_encode($customertag_arr);  
    }
    //GET LIST CUSTOMER TAG DROPDOWN - END




     public function import()
    {
        return view('customer.import');
    }

    public function exportClients()
    {
        //return Excel::download(new PosCustomerExport, 'Customers.csv');
        $data = PosCustomer::join("pos_customertag",function($join){
                                                $join->on("pos_customer.customer_customertag_id","=","pos_customertag.customertag_id")
                                                    ->on("pos_customer.customer_place_id","=","pos_customertag.customertag_place_id");
                                        })
                            ->where('customer_place_id', $this->getCurrentPlaceId())
                            ->select('customertag_name','customer_fullname','customer_gender','customer_phone','customer_country_code','customer_email','customer_birthdate','customer_address')
                            ->get()->toArray();

        return Excel::create('customer', function($excel) use ($data) {

        

        $excel->sheet('mySheet', function($sheet) use ($data)
        {
            $sheet->fromArray($data);
        });
        })->download("xlsx");
    }

    public function importClients(Request $request)
    {
        if($request->hasFile('file')){
            $path = $request->file('file')->getRealPath();
            $begin_row = $request->begin_row;
            $end_row = $request->end_row;
            $update_exist = $request->check_update_exist;
            $update_count = 0;
            $insert_count = 0;
            DB::beginTransaction();
            try {
                $data = \Excel::load($path)->get();
                // dd($data);
                if($data->count()){
                    $count = $data->count();
                    foreach ($data as $key => $value) {
                        if($key>= $begin_row && $key <= $end_row){
                            //CHECK EXIST CUSTOMER
                            $check_exist = PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                            ->where('customer_phone', $value->customer_phone)->first();
                             
                            if(isset($check_exist)){
                                // echo 0;
                                if($update_exist =="on"){
                                    // return "update";
                                    $customertag = PosCustomertag::where('customertag_place_id',$this->getCurrentPlaceId())
                                                        ->where('customertag_name',$value->customertag_name)->first();

                                    $check_id= PosCustomer::where('customer_phone', $value->customer_phone)->first()->customer_id;
                                    $pos_cus=PosCustomer::where('customer_place_id',$this->getCurrentPlaceId())
                                            ->where('customer_phone', $value->customer_phone)
                                            ->where('customer_id',$check_id)
                                            ->update(['customer_customertag_id'=>$customertag->customertag_id , 
                                                'customer_fullname'=>$value->customer_fullname,
                                                'customer_gender'=>$value->customer_gender,
                                                'customer_phone'=>$value->customer_phone,
                                                'customer_country_code'=>$value->customer_country_code,
                                                'customer_email'=>$value->customer_email,
                                                'customer_birthdate'=>format_date_db($value->customer_birthdate),
                                                'customer_address'=>$value->customer_address
                                    ]);
                                    $update_count++;

                                    // dd($pos_cus);
                                            
                                }
                            }
                            else
                            {
                                $customertag = PosCustomertag::where('customertag_place_id',$this->getCurrentPlaceId())
                                                        ->where('customertag_name',$value->customertag_name)->first();
                                                        // dd($customertag);
                                $idCustomer = PosCustomer::where('customer_place_id','=',$this->getCurrentPlaceId())->max('customer_id') +1;
                                $PosCustomer = new PosCustomer();
                                $PosCustomer->customer_id = $idCustomer;
                                $PosCustomer->customer_place_id = $this->getCurrentPlaceId();
                                $PosCustomer->customer_customertag_id = $customertag->customertag_id;
                                $PosCustomer->customer_fullname = $value->customer_fullname;
                                $PosCustomer->customer_gender = $value->customer_gender;
                                $PosCustomer->customer_phone = $value->customer_phone;
                                $PosCustomer->customer_country_code = $value->customer_country_code;
                                $PosCustomer->customer_email = $value->customer_email;
                                $PosCustomer->customer_birthdate = format_date_db($value->customer_birthdate);
                                $PosCustomer->customer_address = $value->customer_address;
                                $PosCustomer->save();
                                $insert_count++;
                            }
                        }    
                    }
                    DB::commit();
                    $request->session()->flash('message', 'Import File Success , updated:'.$update_count.' row, inserted:'.$insert_count.' row');
                    return back();

                }
                else{
                    $request->session()->flash('error', 'Import File Not Data');
                    return back();
                }
            } catch (\Exception $e) {
               // dd(1);
                DB::rollback();
                $request->session()->flash('error', 'Import File is Error! Please check import file again!');
                return back();
            }

        }
        else{
            $request->session()->flash('error', 'Please choose file import.');
            return back();
        }
        
    }
        
}
