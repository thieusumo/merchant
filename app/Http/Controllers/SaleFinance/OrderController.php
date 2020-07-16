<?php

namespace App\Http\Controllers\SaleFinance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosOrder;
use App\Models\PosOrderdetail;
use App\Models\PosService;
use App\Models\PosWorker;
use Session;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
        return view('salefinance.orders');
    }
     
    
    public function view($id = 0)
    {
        $order = PosOrder::leftjoin('pos_orderdetail', function($join){
                                $join->on('pos_order.order_id','=','pos_orderdetail.orderdetail_order_id')
                                ->on('pos_orderdetail.orderdetail_place_id', '=','pos_order.order_place_id');

                            })->leftjoin('pos_customer',function($join){
                                $join->on('pos_order.order_customer_id' ,'=','pos_customer.customer_id')
                                ->on('pos_order.order_place_id','=','pos_customer.customer_place_id');
                            })
                            ->where('pos_order.order_place_id',$this->getCurrentPlaceId())
                            ->where('pos_order.order_id', $id )
                            ->groupBy('pos_orderdetail.orderdetail_order_id')
                            ->first();

        $tip = PosOrderdetail::where('orderdetail_place_id', $this->getCurrentPlaceId())
                                    ->where('orderdetail_order_id',$order->order_id)
                                    ->sum('orderdetail_tip');

        $order_services = PosOrderdetail::leftjoin('pos_service', function($join){
                                        $join->on('pos_orderdetail.orderdetail_place_id', '=','pos_service.service_place_id')
                                        ->on('pos_orderdetail.orderdetail_service_id', '=' ,'pos_service.service_id');
                                    })
                                    ->leftjoin('pos_worker' , function($join){
                                        $join->on('pos_orderdetail.orderdetail_place_id', '=','pos_worker.worker_place_id')
                                        ->on('pos_orderdetail.orderdetail_worker_id', '=' , 'pos_worker.worker_id');
                                    })
                                    ->where('pos_orderdetail.orderdetail_order_id',$order->order_id)->get();
        return view('salefinance.order_detail',compact('order','tip','order_services'));
    }


    //GET ORDER HISTORY DATATABLE - BEGIN
     public function getOrderHistory(Request $request){

        $order_date = $request->order_date;
        $select_type = $request->select_type;
        
        $order_list = PosOrder::leftjoin('pos_orderdetail', function($join){
                                $join->on('pos_order.order_id','=','pos_orderdetail.orderdetail_order_id')
                                ->on('pos_orderdetail.orderdetail_place_id', '=','pos_order.order_place_id');

                            })->leftjoin('pos_customer',function($join){
                                $join->on('pos_order.order_customer_id' ,'=','pos_customer.customer_id')
                                ->on('pos_order.order_place_id','=','pos_customer.customer_place_id');
                            });  
        

        if($order_date!="")
        {
            $order_date_arr = explode(' - ', $order_date);
            $order_list =$order_list->whereBetween('pos_order.order_datetime_payment', 
                                            array(format_date_db($order_date_arr[0]),format_date_db($order_date_arr[1])));
        }       
        if($select_type >="0")
        {
            $order_list =$order_list->where('pos_order.order_payment_method',$select_type);
        }     
           
            
        $order_list = $order_list
                    ->where('pos_order.order_place_id',$this->getCurrentPlaceId())
                    ->select('order_id','order_bill','order_datetime_payment' ,'customer_fullname','order_price')
                    ->groupBy('pos_orderdetail.orderdetail_order_id');
        //FORMAT COLUMN DATATABLE
        return Datatables::of($order_list)
            ->editColumn('order_id',function($row){
                return  "<a href='".route('order-history-detail',$row->order_id)."'>#".$row->order_bill." </a>";
            })
            ->editColumn('order_datetime_payment',function($row){
                return  format_datetime($row->order_datetime_payment); 
            })
/*            ->addColumn('worker_name',function($row){
                $worker = PosOrderdetail::rightJoin('pos_worker', function($join){
                                        $join->on('pos_orderdetail.orderdetail_worker_id','=','pos_worker.worker_id')
                                        ->on('pos_orderdetail.orderdetail_place_id','=','pos_worker.worker_place_id');
                })
                ->where('pos_orderdetail.orderdetail_order_id',$row->order_id)
                ->select('worker_nickname')->get();

                $worker_list ="";
                foreach ($worker as $key => $value) {
                    $worker_list.="- ".$value->worker_nickname."</br>";
                }
                return $worker_list;
                
            })*/
            ->addColumn('tip', function($row){
                return PosOrderdetail::where('orderdetail_place_id', $this->getCurrentPlaceId())
                                    ->where('orderdetail_order_id',$row->order_id)
                                    ->sum('orderdetail_tip');

            })
            ->addColumn('amount', function($row){
                $tip = PosOrderdetail::where('orderdetail_place_id', $this->getCurrentPlaceId())
                                    ->where('orderdetail_order_id',$row->order_id)
                                    ->sum('orderdetail_tip');
                return $row->order_price - $tip;
            })
            ->addColumn('duration', function($row){
                $lst_service = explode(",",$row->booking_lstservice);
                return PosService::whereIn('service_id', $lst_service)
                                ->where('service_place_id', $this->getCurrentPlaceId())
                                ->sum('service_duration');
            })
           /* ->addColumn('rentstation_service', function($row){
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
            })*/
            ->addColumn('status', function($row){
                return \GeneralHelper::convertPaymentType($row->order_payment_method);
            })
            ->rawColumns(['order_id','worker_name','status'])
            ->make(true);
     }
    //GET ORDER HISTORY DATATABLE - END
}
