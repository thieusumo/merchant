<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosCustomer;
use App\Models\PosOrder;
use App\Models\PosCustomertag;
use App\Models\PosOrderdetail;
use App\Models\PosPackagedetail;
use Carbon\Carbon;
use DataTables;
use Session;

class ClientController extends Controller
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
        $group_list = PosCustomertag::where('customertag_place_id',$this->getCurrentPlaceId())
                                    ->where('customertag_status',1)
                                    ->distinct()
                                    ->get();
        Session::put('group_list',$group_list);

        $combo_list = PosPackagedetail::where('packagedetail_place_id',$this->getCurrentPlaceId())
                                            ->select('packagedetail_name','packagedetail_id','packagedetail_package_id')
                                            ->get();
        $combo_arr = [];
        foreach ($combo_list as $key => $value) {

            $combo_arr[$value->packagedetail_package_id][$value->packagedetail_id] = $value->packagedetail_name;
        }
        Session::put('combo_arr',$combo_arr);

        return view('report.client',compact('group_list'));
    }
    
    public function loadReport(Request $request){
    }
    
    public function getTicketHistory(Request $request){
        $id = $request->get('id');
        return view('report.partials.client_ticket_history');
    }
    public function getClient(Request $request){

        $client_group = $request->client_group;

        $date_order = $request->date_order;
        
        $month_order = Carbon::parse($date_order)->format('m');
        $year_order = Carbon::parse($date_order)->format('Y');

        $time_format = $request->time_format;

        $place_id = $this->getCurrentPlaceId();

        $customer_list = PosOrder::join('pos_customer',function($join){
                        $join->on('pos_order.order_place_id','pos_customer.customer_place_id')
                        ->on('pos_order.order_customer_id','pos_customer.customer_id');
                        })
                        ->where('pos_order.order_place_id',$place_id)
                        ->where('pos_order.order_status',1)
                        ->where('pos_customer.customer_customertag_id',$client_group)
                        ->select('pos_order.*','pos_customer.customer_fullname','pos_customer.customer_phone','pos_customer.customer_birthdate','pos_customer.customer_point_total','pos_customer.customer_customertag_id');

        if($time_format == "" || $time_format == 'day')

            $customer_list->where('pos_order.updated_at',">=",format_date_db($date_order). " 00:00:00")
                          ->where('pos_order.updated_at',"<=",format_date_db($date_order). " 23:59:59");
        
        if($time_format == 'week'){

            $start_of_week = Carbon::parse($date_order)->startOfWeek();
            $end_of_week = Carbon::parse($date_order)->endOfWeek();
            $customer_list->whereBetween('pos_order.updated_at',[$start_of_week,$end_of_week]);
        }
        if($time_format == 'month')

            $customer_list->whereYear('pos_order.updated_at',$year_order)
                          ->whereMonth('pos_order.updated_at',$month_order);

        if($time_format == 'year')

            $customer_list->whereYear('pos_order.updated_at',$year_order);
        

        return DataTables::of($customer_list)
                        ->editColumn('booking_type',function($row){
                            if($row->order_booking_id == 1)
                            {
                                $booking_type = "Wellcome Guest";
                            }elseif($row->order_booking_id == 2){

                                $booking_type = "Client Call";
                            }elseif($row->order_booking_id == 3){
                                $booking_type = "Website";
                            }else
                                $booking_type = "UNKNOWN";
                            return  $booking_type;
                        })
                        ->editColumn('client_group',function($row){
                            foreach (Session::get('group_list') as $value) {
                                if($row->customer_customertag_id == $value->customertag_id)
                                    return $value->customertag_name;
                            }
                        })
                        ->addColumn('action',function($row){
                            return '<a class="view-ticket-his" id="'.$row->order_id.'" href="javascript:void(0)">
                                        <i class="fa fa-eye"></i>
                                    </a>';
                        })
                        ->rawColumns(['action'])
                        ->make(true);
    }
    public function getDetailOrder(Request $request){

        $order_id = $request->order_id;

        if($order_id == ""){

            $detail_order_list = [];
        }else{

            $detail_order_list = PosOrderdetail::leftJoin('pos_order',function($join){
                            $join->on('pos_orderdetail.orderdetail_place_id','pos_order.order_place_id')
                            ->on('pos_orderdetail.orderdetail_order_id','pos_order.order_id');
                            })
                            ->join('pos_service',function($join){
                            $join->on('pos_orderdetail.orderdetail_place_id','pos_service.service_place_id')
                            ->on('pos_orderdetail.orderdetail_service_id','pos_service.service_id');
                            })
                            ->join('pos_worker',function($join1){
                            $join1->on('pos_orderdetail.orderdetail_place_id','pos_worker.worker_place_id')
                            ->on('pos_orderdetail.orderdetail_worker_id','pos_worker.worker_id');
                            })
                            ->where('pos_orderdetail.orderdetail_place_id',$this->getCurrentPlaceId())
                            ->where('pos_orderdetail.orderdetail_order_id',$order_id)
                            ->select('pos_orderdetail.*','pos_order.order_bill','pos_service.service_name','pos_worker.worker_nickname');
        }
        
        return DataTables::of($detail_order_list)
                        ->editColumn('combo_detail',function($row){
                            $combo_arr = Session::get('combo_arr');
                            $result = '';
                            foreach ($combo_arr[$row->orderdetail_package_id] as $key => $value) {
                                $result .= "- ".ucfirst($value)."<br>"; 
                            }
                            return  $result;
                        })
                        ->editColumn('orderdetail_order_id',function($row){
                            return "#".$row->order_bill;
                        })
                        ->editColumn('orderdetail_price',function($row){
                            return "$".$row->orderdetail_price;
                        })
                        ->editColumn('orderdetail_extra',function($row){
                            return "$".$row->orderdetail_extra;
                        })
                        ->editColumn('orderdetail_tip',function($row){
                            return "$".$row->orderdetail_tip;
                        })
                        ->editColumn('orderdetail_tax',function($row){
                            return "$".$row->orderdetail_tax;
                        })
                        ->rawColumns(['combo_detail'])
                        ->make(true);
    }
    public function getGiftcard(Request $request){

        $order_id = $request->order_id;

        if($order_id == ""){

            $giftcard_list = [];

        }else
            $giftcard_list = PosOrder::where('order_place_id',$this->getCurrentPlaceId())
                                    ->where('order_id',$order_id)
                                    ->select('order_bill','order_giftcard_code','order_giftcard_amount');
        

        return DataTables::of($giftcard_list)
                        ->editColumn('order_bill',function($row){
                            return "#".$row->order_bill;
                        })
                        ->editColumn('order_giftcard_amount',function($row){
                            return "$".$row->order_giftcard_amount;
                        })
                        ->make(true);
    }
   
}
