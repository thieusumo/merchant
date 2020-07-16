<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\PosOrder;
use DB;

class GiftCardController extends Controller
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
        return view('report.giftcard');
    }
    
    public function loadReport(Request $request){
        $join_date = $request->search_join_date; 
        
        if($join_date){
          $join_date = explode(' - ', $join_date);     

          $order = PosOrder::select('order_giftcard_code as gift_code','customer_fullname as client_name','customer_phone as client_phone','customer_email as client_email','order_price as amount','giftcode_balance as balance','giftcode_redemption as redemption',DB::raw('date(pos_order.created_at) as purchase_date' ))
                          ->leftjoin('pos_customer',function($joinCustomer){
                              $joinCustomer->on('order_place_id','customer_place_id')
                              ->on('order_customer_id','customer_id');
                          })
                          ->leftjoin('pos_giftcode',function($joinGiftCode){
                              $joinGiftCode->on('order_giftcard_code','giftcode_code')
                              ->on('order_place_id','giftcode_place_id');
                          })
                          ->where('order_place_id',$this->getCurrentPlaceId())
                          ->where('order_status',1)
                          ->whereDate('pos_order.created_at', '<=', format_date_db($join_date[1]))
                          ->whereDate('pos_order.created_at', '>=', format_date_db($join_date[0]))                         
                          ->get();
        } else {
          $order = PosOrder::select('order_giftcard_code as gift_code','customer_fullname as client_name','customer_phone as client_phone','customer_email as client_email','order_price as amount','giftcode_balance as balance','giftcode_redemption as redemption',DB::raw('date(pos_order.created_at) as purchase_date' ))
                          ->leftjoin('pos_customer',function($joinCustomer){
                              $joinCustomer->on('order_place_id','customer_place_id')
                              ->on('order_customer_id','customer_id');
                          })
                          ->leftjoin('pos_giftcode',function($joinGiftCode){
                              $joinGiftCode->on('order_giftcard_code','giftcode_code')
                              ->on('order_place_id','giftcode_place_id');
                          })
                          ->where('order_place_id',$this->getCurrentPlaceId())    
                          ->where('order_status',1)                                           
                          ->get();
        }       
                        
        return DataTables::of($order)
        ->editColumn('purchase_date',function($order){
          return format_date($order->purchase_date);
        })
        ->make(true);

    
    }
}