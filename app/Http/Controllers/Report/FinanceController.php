<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelper;
use App\Models\PosOrder;
use App\Models\PosPlaceExpense;
use Carbon\Carbon;
use DataTables;

class FinanceController extends Controller {

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
    public function __construct() {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {       
        return view('report.finance');
    }

    public function loadReport(Request $request) {
        $this->viewType = $request->get('view_type');
        if($this->viewType == self::YEARLY){
            return $this->reportFinanceYearly($request);
        }else{
            return $this->reportFinanceDailyWeeklyMonthlyQuaterly($request);
        }       
    }
    // get data for report daily,weekly, monthly, quaterly
    private function reportFinanceDailyWeeklyMonthlyQuaterly(Request $request) {
        //$this->setCurrentPlaceId(11);
        $placeId = $this->getCurrentPlaceId();
        $dateYmd = Carbon::parse($request->get('view_date'))->format('Y-m-d');
        //$dateYmd = '2019-05-15';        
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
            default:{                
                $select_time_func = "HOUR";
                $where_time_func = "DATE";
                break;
            }
        } 
        $reportData = [];
        $dbResult = \DB::select("
            SELECT {$select_time_func}(order_datetime_payment) AS view_time,
            SUM(IF(order_customer_type=1,1,0)) AS total_newcustomer,
            SUM(IF(order_customer_type IN('set',0,2), 1, 0)) AS total_walkin,
            SUM(order_promotion_discount) AS total_promo,
            COUNT(order_id) AS total_ticket,
            SUM(order_price) AS total_gross,
            SUM(order_giftcard_amount) as total_paygiftcard,
            SUM(IF(order_use_point=1,order_discount_point,0)) as total_paypoint,
            GROUP_CONCAT('',order_id) AS lstorder
            FROM pos_order
            WHERE order_place_id = {$placeId} AND order_status = 1
            AND {$where_time_func}(order_datetime_payment)  = {$where_time_func}('{$dateYmd}')
            AND YEAR(order_datetime_payment) = YEAR('{$dateYmd}')    
            GROUP BY view_time ORDER BY view_time ASC");         
         $this->_updateReportDataDailyWeekMonthQuaterly($reportData, $dbResult);
         if(!empty($dbResult)){
            $lstorder = isset($dbResult[0]->lstorder)?rtrim($dbResult[0]->lstorder,','):[];
            // GET ORDER SUPPLY NAIL
            $dbResult = \DB::select("SELECT  {$select_time_func}(os_datetime) AS view_time,
               IF(os_type_discount = 1, (os_price*((os_discount*100)/os_quantity)), ((os_price - os_discount) * os_quantity) ) AS total_product,
               SUM(IF(os_type_discount = 1, (os_price*((os_discount*100)/os_quantity)), ((os_price - os_discount) * os_quantity))*(os_sale_tax/100)) AS total_tax
               FROM pos_order_supply_nail
               WHERE os_place_id = {$placeId} AND os_status = 1 AND {$where_time_func}(os_datetime)  = {$where_time_func}('{$dateYmd}')
                   AND os_order_id IN({$lstorder})
                   AND YEAR(os_datetime) = YEAR('{$dateYmd}')        
               GROUP BY view_time ORDER BY view_time ASC");     
            $this->_updateReportDataDailyWeekMonthQuaterly($reportData, $dbResult);    

            // GET ORDER DETAIL DATA
            $dbResult = \DB::select("SELECT {$select_time_func}(orderdetail_datetime) AS view_time,
               SUM(orderdetail_tip) AS total_tips, 
               SUM( (orderdetail_worker_percent/100)*orderdetail_price) AS total_rs,
               COUNT(orderdetail_id) AS total_service,
               SUM(orderdetail_price*(orderdetail_tax/100)) AS total_tax,
               SUM(orderdetail_extra) AS total_extra
               FROM pos_orderdetail 
               WHERE orderdetail_place_id = {$placeId} AND orderdetail_status =1 AND {$where_time_func}(orderdetail_datetime)  = {$where_time_func}('{$dateYmd}')
                   AND orderdetail_order_id IN({$lstorder})
                   AND YEAR(orderdetail_datetime) = YEAR('{$dateYmd}')        
               GROUP BY view_time ORDER BY view_time ASC");        
           $this->_updateReportDataDailyWeekMonthQuaterly($reportData, $dbResult);    
           // GET ORDER BUY GIFT CARD
           $dbResult = \DB::select("SELECT  {$select_time_func}(og_datetime) AS view_time,
               SUM(og_price) AS total_buygiftcard
               FROM pos_order_giftcard
               WHERE og_place_id = {$placeId} AND {$where_time_func}(og_datetime)  = {$where_time_func}('{$dateYmd}')
                   AND og_order_id IN({$lstorder})
                   AND YEAR(og_datetime) = YEAR('{$dateYmd}')        
               GROUP BY view_time"); 
           $this->_updateReportDataDailyWeekMonthQuaterly($reportData, $dbResult);    
        }
        return DataTables::of($reportData)
                ->editColumn('view_time', function($row) {  
                   
                    switch ($this->viewType) {
                        case self::DAILY:  {                            
                            return sprintf("<div style='white-space: nowrap'>%s - %s</div>",date("g A", strtotime('2019-10-10 '.$row['view_time'].':00:00')),
                                    date("g A", strtotime('2019-10-10 '.($row['view_time']+1).':00:00')));                            
                        }
                        case self::WEEKLY: {
                            return $row['view_time'];
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
                    }
                    
                    return $row['view_time'];
                })->editColumn('total_net', function($row) {   
                    $total_net = floatval($row['total_gross']) - floatval($row['total_rs']) - floatval($row['total_tips']);
                    return $total_net > 0?("$".number_format($total_net, 2, '.', ',')):'';
                })->editColumn('total_promo', function($row) {                      
                    return $row['total_promo'] > 0?("$".number_format($row['total_promo'], 2, '.', ',')):'';
                })->editColumn('total_tips', function($row) {                      
                    return $row['total_tips'] > 0?("$".number_format($row['total_tips'], 2, '.', ',')):'';
                })->editColumn('total_product', function($row) {                      
                    return $row['total_product'] > 0?("$".number_format($row['total_product'], 2, '.', ',')):'';
                })->editColumn('total_buygiftcard', function($row) {                      
                    return $row['total_buygiftcard'] > 0?("$".number_format($row['total_buygiftcard'], 2, '.', ',')):'';
                })->editColumn('total_tax', function($row) {                      
                    return $row['total_tax'] > 0?("$".number_format($row['total_tax'], 2, '.', ',')):'';
                })->editColumn('total_tax', function($row) {                      
                    return $row['total_tax'] > 0?("$".number_format($row['total_tax'], 2, '.', ',')):'';
                })->editColumn('total_gross', function($row) {                      
                    return $row['total_gross'] > 0?("$".number_format($row['total_gross'], 2, '.', ',')):'';
                })->editColumn('total_paygiftcard', function($row) {                      
                    return $row['total_paygiftcard'] > 0?("$".number_format($row['total_paygiftcard'], 2, '.', ',')):'';
                })->editColumn('total_paypoint', function($row) {                      
                    return $row['total_paypoint'] > 0?("$".number_format($row['total_paypoint'], 2, '.', ',')):'';
                })->editColumn('total_rs', function($row) {                      
                    return $row['total_rs'] > 0?("$".number_format($row['total_rs'], 2, '.', ',')):'';
                })->editColumn('total_extra', function($row) {                      
                    return $row['total_extra'] > 0?("$".number_format($row['total_extra'], 2, '.', ',')):'';
                })->editColumn('total_walkin', function($row) {                      
                    return $row['total_walkin'] > 0?$row['total_walkin']:'';
                })->editColumn('total_newcustomer', function($row) {                      
                    return $row['total_newcustomer'] > 0?$row['total_newcustomer']:'';
                })->editColumn('total_services', function($row) {                      
                    return $row['total_services'] > 0?$row['total_services']:'';
                })->rawColumns(['view_time'])
                ->make(true);
    }
    function _updateReportDataDailyWeekMonthQuaterly(&$reportData, $dbResult){
        
        $arr_fields = ['total_ticket','total_walkin','total_newcustomer','total_services',
        'total_promo','total_tips','total_product','total_buygiftcard','total_tax','total_gross',
        'total_paygiftcard','total_paypoint','total_rs','total_extra','total_net'];        
        //$primary_key is time
        foreach($dbResult as $row){
            $time = is_array($row)?$row['view_time']:$row->view_time;                        
            $rowData = isset($reportData[$time])?$reportData[$time]:['view_time'=>$time];            
            foreach($arr_fields as $fieldname){
               if(!isset($rowData[$fieldname])){ 
                   $rowData[$fieldname] = 0;
               }
               if(is_array($row) && isset($row[$fieldname])){                   
                   $rowData[$fieldname] = $row[$fieldname] + $rowData[$fieldname];
               }else if(isset($row->$fieldname)){ // is object
                  $rowData[$fieldname] =  $row->$fieldname + $rowData[$fieldname];
               }               
            }   
                       
            $reportData[$time] = $rowData;
        }
        
    }
    private function reportFinanceYearly(Request $request) {
                
        $reportResult = [];
        $rowDefault = ['name'=>''];
        $month_arr = [];
        for($month = 1; $month <= 12; $month++){
           $monthName = date("M", mktime(0, 0, 0, $month, 10));
           $rowDefault[$monthName] = "";
           $month_arr[] = $monthName;
        }        
        $rowDefault['percent'] = 0; 
        $rowDefault['total'] = 0;
        $globalTotal = 0;
            
        $payment_methods = [0 => 'CASH', 1=> 'CREDIT CARD', 2 => 'CHECK', 3=> 'GIFT CARD'];
        $year_order = Carbon::parse($request->get('view_date'))->format('Y');
        $placeId = $this->getCurrentPlaceId();
        if($request->get("command") == "get-yearly-gross-income"){ 
            // GET DATA TABLE GROSS INCOME
            $dbResult = \DB::select('select order_payment_method, 
			CONCAT(
				"[",
					GROUP_CONCAT(
						CONCAT(
							"{\"month\":\"", month, "\",",
							"\"amount\":\"", amount, "\",", 
							"\"rent_station\":\"", ROUND(rent_station, 0),"\"}"
						)
					),	
				"]"
			)
			AS month_amount
			FROM (
				select order_id, order_customer_id, SUM(od_tip) as totalTip, sum(od_salary) as rent_station, DATE_FORMAT(datetime, \'%m\') AS month, 
				(SUM(od_price) + SUM(od_extra)) AS amount, order_payment_method from `pos_order` inner join 
				(select orderdetail_order_id, SUM(orderdetail_price) AS od_price, SUM(orderdetail_tip) AS od_tip, SUM(orderdetail_extra) AS od_extra, 
					SUM((worker_percent*orderdetail_price)/100) AS od_salary, pos_orderdetail.orderdetail_datetime as datetime from pos_orderdetail 
				INNER JOIN pos_worker ON pos_worker.worker_id = pos_orderdetail.orderdetail_worker_id AND pos_worker.worker_place_id = ' . $placeId . ' 
				LEFT JOIN pos_service ON pos_service.service_id = pos_orderdetail.orderdetail_service_id AND pos_service.service_place_id = ' . $placeId . ' 
				LEFT JOIN pos_package ON pos_package.package_id = pos_orderdetail.orderdetail_package_id AND pos_package.package_place_id = ' . $placeId . ' 
				WHERE pos_orderdetail.orderdetail_place_id = ' . $placeId . ' 
				AND pos_orderdetail.orderdetail_status = 1
				AND YEAR(pos_orderdetail.orderdetail_datetime) = \'' . $year_order . '\'
				GROUP BY orderdetail_order_id ) orderDetailJoined on `orderDetailJoined`.`orderdetail_order_id` = `pos_order`.`order_id` where `order_place_id` = ' . $placeId . ' and `order_status` = 1 group by `month`, `order_payment_method`
			) orderView 
			GROUP BY order_payment_method');
            
            foreach($dbResult as $obj){
                $rowData = $rowDefault;
                $rowData['name'] = isset($obj->order_payment_method)? $payment_methods[$obj->order_payment_method]:"";
                // dd($obj->month_amount);
                $monthData = json_decode($obj->month_amount,true);
                $subTotal = 0;
                foreach($monthData as $mData){
                    $monthName = date("M", mktime(0, 0, 0, $mData->month, 10));
                    $rowData[$monthName] = $mData->amount;
                    $subTotal += $mData->amount;
                }
                $rowData['total'] = $subTotal;
                $reportResult[] = $rowData;
                $globalTotal += $subTotal;                
            }
            
        }else{ // GET DATA TABLE EXPENSE
                $orderModel = new PosOrder();
                $placeExpenseModel = new PosPlaceExpense();
                $discount = $orderModel->selectRaw('SUM(order_promotion_discount) AS promotion_discount, SUM(order_coupon_discount) AS coupon_discount, DATE_FORMAT(orderdetail_datetime, \'%m\') AS month')
			->join(\DB::Raw('(SELECT orderdetail_order_id, orderdetail_datetime FROM pos_orderdetail WHERE 
				orderdetail_place_id = '.$placeId.'
				AND YEAR(pos_orderdetail.orderdetail_datetime) = \''.$year_order.'\'
				AND pos_orderdetail.orderdetail_status = 1
				GROUP BY orderdetail_order_id)
			AS orderDetailView'), function($join){
				$join->on('orderDetailView.orderdetail_order_id','=','pos_order.order_id');
			})
			->where('order_place_id', '=', $placeId)
			->where('order_status', '=', 1)
			->groupBy('month')
			->get();
		$arrDiscount = array();
		if(!empty($discount)){
			$couponDiscount = array();
			$promotionDiscount = array();
			$couponDiscount['name'] = "COUPON";
			$couponDiscount['month_cost'] = "[";
			$promotionDiscount['name'] = "PROMOTION";
			$promotionDiscount['month_cost'] = "[";
			foreach ($discount as $key => $value) {
				$couponDiscount['month_cost'] .= "{ \"month\": ". "\"".$value['month']."\", \"cost\": \"".$value['coupon_discount']."\"},";
				$promotionDiscount['month_cost'] .= "{ \"month\": ". "\"".$value['month']."\", \"cost\": \"".$value['promotion_discount']."\"},";
			}

			$promotionDiscount['month_cost'] = substr($promotionDiscount['month_cost'], 0, -1);
			$couponDiscount['month_cost'] = substr($couponDiscount['month_cost'], 0, -1);
			$promotionDiscount['month_cost'] .= "]";
			$couponDiscount['month_cost'] .= "]";
			array_push($arrDiscount, $promotionDiscount);
			array_push($arrDiscount, $couponDiscount);
		}
		
		$expense = $placeExpenseModel->selectRaw('pe_name AS name, 
			CONCAT( "[",
				GROUP_CONCAT(
					CONCAT(
						"{\"month\":\"", DATE_FORMAT(pe_date, \'%m\'), "\",",
						"\"cost\":\"", pe_cost, "\"}"
					)
				), 
			"]") AS month_cost')
			->whereRaw('YEAR(pe_date) >= \''.$year_order.'\' ')
			->where('pe_place_id', '=', $placeId)
			->where('pe_status', '=', 1)
			->groupBy('pe_name')
			->get()->toArray();
            $expense = array_merge($expense, $arrDiscount);
            foreach($expense as $arr){
                $rowData = $rowDefault;
                $rowData['name'] = $arr['name'];                
                $monthData = json_decode($arr['month_cost'],true);
                // dd($monthData[0]['month']);
                $subTotal = 0;
                if(isset($monthData)){
                foreach($monthData as $mData){                    
                    $monthName = date("M", mktime(0, 0, 0, $mData['month'], 10));
                    $rowData[$monthName] = round(abs($mData['cost']));
                    $subTotal += round(abs($mData['cost']));
                }
                }
                $rowData['total'] = $subTotal;
                $reportResult[] = $rowData;
                $globalTotal += $subTotal;                
            }
        }       
        
        for($i=0; $i<count($reportResult); $i++){
            $reportResult[$i]['percent'] = round(abs($reportResult[$i]['total'] *100/$globalTotal)).'%';
            foreach($month_arr as $monthName){
                if(!empty($reportResult[$i][$monthName])){
                    $reportResult[$i][$monthName] = "$". number_format($reportResult[$i][$monthName],0,'.',',');
                }
            }
            $reportResult[$i]['total'] = "$".number_format($reportResult[$i]['total'],0,'.',',');
        }
        return DataTables::of($reportResult)
                ->make(true);

    }
}
