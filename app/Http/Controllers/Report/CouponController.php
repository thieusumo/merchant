<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\PosCoupon;
use App\Models\PosService;

class CouponController extends Controller
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
        return view('report.coupon');
    }
    
    public function loadReport(Request $request){
        $join_date = $request->search_join_date; 

        if($join_date){
          $join_date = explode(' - ', $join_date);    

          $coupon = PosCoupon::select('coupon_linkimage as coupon_images','coupon_code','coupon_startdate as coupon_date_start','coupon_deadline as coupon_date_end','coupon_discount','coupon_quantity_limit as coupon_quantity','coupon_list_service','pos_coupon.created_at as coupon_created','customer_fullname')
                            ->leftjoin('pos_customer',function($joinCustomer){
                                $joinCustomer->on('coupon_place_id','customer_place_id')
                                ->on('coupon_customer_id','customer_id');
                            })
                            ->where('coupon_place_id',$this->getCurrentPlaceId())
                            ->where('coupon_status',1)                    
                              ->whereDate('pos_coupon.created_at', '<=', format_date_db($join_date[1]))
                              ->whereDate('pos_coupon.created_at', '>=', format_date_db($join_date[0]))                         
                              ->get();
        } else {
          $coupon = PosCoupon::select('coupon_linkimage as coupon_images','coupon_code','coupon_startdate as coupon_date_start','coupon_deadline as coupon_date_end','coupon_discount','coupon_quantity_limit as coupon_quantity','coupon_quantity_use','coupon_list_service','pos_coupon.created_at as coupon_created','customer_fullname')
                            ->leftjoin('pos_customer',function($joinCustomer){
                                $joinCustomer->on('coupon_place_id','customer_place_id')
                                ->on('coupon_customer_id','customer_id');
                            })
                            ->where('coupon_place_id',$this->getCurrentPlaceId())
                            ->where('coupon_status',1)
                            ->get();
        }
        // echo $coupon; die();
        return DataTables::of($coupon)
        ->editColumn('coupon_images',function($coupon){            
            return "<img height='100px' width='auto' src=".config('app.url_file_view').$coupon->coupon_images." />";            
        })
        ->editColumn('coupon_created',function($coupon){
            return format_date($coupon->coupon_created);
        })
        ->editColumn('coupon_date_start',function($coupon){
            return format_date($coupon->coupon_date_start);
        })
        ->editColumn('coupon_date_end',function($coupon){
            return format_date($coupon->coupon_date_end);
        })
        ->addColumn('coupon_totalcustomers',function($coupon){
            return $coupon->customer_fullname;
        })
        ->addColumn('coupon_balance',function($coupon){
            return $coupon->coupon_quantity - $coupon->coupon_quantity_use;
        })
        ->addColumn('coupon_services',function($coupon){
            $listServices = $coupon->coupon_list_service;
            $nameServices = '';            
            try {
                $arr = explode(';', $listServices);                                               
                $services = PosService::select('service_name')->whereIn('service_id',$arr)->where('service_place_id',$this->getCurrentPlaceId())->where('service_status',1)->where('enable_status',1)->get();
                //check $services
                if(count($services) == 0){
                    return $listServices;
                }
                foreach ($services as $value) {
                    $nameServices .= $value->service_name.";";
                }            
                $nameServices = substr($nameServices, 0,-1);
            } catch (\Exception $e) {
                $nameServices = $listServices;
            } 
            return $nameServices;
        })
        ->rawColumns(['coupon_images'])
        ->make(true);    
    }
}