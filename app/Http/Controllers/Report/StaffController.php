<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosOrder;
use App\Models\PosWorker;
use App\Models\PosOrderdetail;
use Carbon\Carbon;
use DataTables;

class StaffController extends Controller
{
    
    const DAILY = 1;
    const WEEKLY = 2;
    const MONTHLY = 3;
    const QUARTERLY = 4;    
    const YEARLY = 5;       
    
    private $viewType = 1;
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
        $staff_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                ->select('worker_id','worker_nickname')
                                ->get();
        return view('report.staff',compact('staff_list'));
    }
   
   public function loadReport(Request $request){
        $view_type = $this->viewType =  $request->get('view_type');
        $view_date = Carbon::parse($request->get('view_date'))->format('Y-m-d');
        $view_staff = $request->get('view_staff');
        $placeId = $this->getCurrentPlaceId();
        switch ($this->viewType) {
            case self::WEEKLY: {
                $select_time_func = "DAYOFWEEK"; 
                $where_time_func = "YEARWEEK";                 
                break;                
            }
            case self::MONTHLY: {
                $select_time_func = "DATE";
                $where_time_func = "MONTH";
                break;   
            }                            
            case self::QUARTERLY: {
                $select_time_func = "QUARTER";
                $where_time_func = "YEAR";
                break;
            }
            case self::YEARLY: {
                $select_time_func = "MONTH";
                $where_time_func = "YEAR";
                break;
            }
            default:{                
                $select_time_func = "HOUR";
                $where_time_func = "DATE";
                break;
            }
        }   
        if(!empty($view_staff)){
            $view_staff = rtrim($view_staff,',');
            $staff_condition = " AND orderdetail_worker_id IN ({$view_staff})";
        }
        $dbResult = \DB::select("SELECT {$select_time_func}(orderdetail_datetime) AS view_time,
            COUNT(orderdetail_id) AS total_service,
            SUM(orderdetail_price) AS total_service_price,
            SUM(orderdetail_tip) AS total_tip,                
            SUM((orderdetail_worker_percent/100)*orderdetail_price) AS total_price_agreement,
            SUM(orderdetail_price_hold) AS total_price_hold            
            FROM pos_orderdetail                
            WHERE orderdetail_place_id = {$placeId} AND orderdetail_status =1 AND {$where_time_func}(orderdetail_datetime)  = {$where_time_func}('{$view_date}')                   
                AND YEAR(orderdetail_datetime) = YEAR('{$view_date}') {$staff_condition} 
            GROUP BY view_time ORDER BY view_time ASC"); 
        // Map array Std Obj to array
        $reportData = array_map(function($item) {
            return (array)$item; 
        }, $dbResult);
        
        return DataTables::of($reportData)
                ->editColumn('view_time', function($row) {                     
                switch ($this->viewType) {
                    case self::DAILY:  {                            
                        return sprintf("<div style='white-space: nowrap'>%s - %s</div>",date("g A", strtotime('2019-10-10 '.$row['view_time'].':00:00')),
                                date("g A", strtotime('2019-10-10 '.($row['view_time']+1).':00:00')));                            
                    }
                    case self::WEEKLY: {
                        $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                        return $dowMap[$row['view_time']-1];
                    }
                    case self::MONTHLY: { 
                        return $row['view_time'];                            
                    }                            
                    case self::QUARTERLY: {
                        $dowMap = array('1-3', '3-6', '6-9', '9-12');
                        return $dowMap[$row['view_time']-1];                       
                    }
                    case self::YEARLY: { 
                        return date("F", mktime(0, 0, 0, $row['view_time'], 10));
                    }   
                }

                return $row['view_time'];
            })->editColumn('total_service_price', function($row) {                      
                return $row['total_service_price'] > 0?("$".number_format($row['total_service_price'], 2, '.', ',')):'';
            })->editColumn('total_tip', function($row) {                      
                return $row['total_tip'] > 0?("$".number_format($row['total_tip'], 2, '.', ',')):'';
            })->editColumn('total_price_agreement', function($row) {                      
                return $row['total_price_agreement'] > 0?("$".number_format($row['total_price_agreement'], 2, '.', ',')):'';
            })->editColumn('total_price_hold', function($row) {                      
                return $row['total_price_hold'] > 0?("$".number_format($row['total_price_hold'], 2, '.', ',')):'';            
            })->editColumn('total_service', function($row) {                      
                return $row['total_service'] > 0?$row['total_service']:'';
            })->editColumn('total', function($row) {                      
                $total = $row['total_price_hold'] + $row['total_tip'] + $row['total_price_agreement'];
                return $total > 0?("$".number_format($total, 2, '.', ',')):'';            
            })->rawColumns(['view_time'])
            ->make(true); 
    }
}

