<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\PosBooking;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
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
        return view('home');
    }
    
    public function test()
    {
        return view('test');
    }
    
    public function dashboard()
    {   
        return view('dashboard');
    }
    public  function getTodayAppointments(){
        $date = Carbon::now()->toDateString();                
        
        $data['today_appointments'] = PosBooking::select('booking_time_selected','booking_status','worker_nickname','customer_fullname')
                                    ->where('booking_place_id',$this->getCurrentPlaceId())
                                    ->where('pos_booking.booking_time_selected','LIKE',''.$date.'%')
                                    ->join('pos_customer',function($join){
                                        $join->on('pos_customer.customer_id','pos_booking.booking_customer_id')
                                        ->on('pos_customer.customer_place_id','pos_booking.booking_place_id');
                                    })
                                    ->join('pos_worker',function($join){
                                        $join->on('pos_worker.worker_id','pos_booking.booking_worker_id')
                                        ->on('pos_worker.worker_place_id','pos_booking.booking_place_id');
                                    })
                                    ->get();
        
        if(count($data['today_appointments']) > 0)
        return view('dashboard_today_appt',$data);
        else return 'No booking';
    }
    public function getUpcomingAppointmentChart(){
        $date = Carbon::now()->addDay(1)->toDateString();
        $date_7 = Carbon::now()->addDay(8)->toDateString();       
        
        $confirmed = PosBooking::select(DB::raw('count(booking_time_selected) as count') , DB::raw('DATE_FORMAT(booking_time_selected, "%Y-%m-%d") as format' ))
                                ->where('booking_place_id',$this->getCurrentPlaceId())
                                ->where('booking_time_selected','>=',$date)
                                ->where('booking_time_selected','<=',$date_7)
                                ->groupBy('format')
                                ->where('booking_status','<>',0)
                                ->orderBY('booking_time_selected','ASC')
                                ->get();

        $cancelled = PosBooking::select(DB::raw('count(booking_time_selected) as count') , DB::raw('DATE_FORMAT(booking_time_selected, "%Y-%m-%d") as format' ))
                                ->where('booking_place_id',$this->getCurrentPlaceId())
                                ->where('booking_time_selected','>=',$date)
                                ->where('booking_time_selected','<=',$date_7)
                                ->groupBy('format')
                                ->where('booking_status',0)
                                ->orderBY('booking_time_selected','ASC')
                                ->get();
                                
        $labels = [];
        $data_confirmed = [];
        $data_cancelled = [];
        
        // data for example
        for($i = 1; $i <=7; $i++){
            $labels[] = strtoupper(date('D d',strtotime("+{$i} day")));
            // $data_confirmed[] = rand(10, 100);
            // $data_cancelled[] = rand(0, 10);
        }
       
        for($i = 1; $i <=7; $i++){
            $day[] = strtoupper(date('d',strtotime("+{$i} day")));            
        }
        //add data
        $data_confirmed = $this->addData_AppointmentChart($day,$confirmed,$data_confirmed);
        $data_cancelled = $this->addData_AppointmentChart($day,$cancelled,$data_cancelled);
        
        
        //---------------------------
        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Confirmed',
                    'backgroundColor' => "#26B99A",
                    'data' => $data_confirmed
                ],
                [
                    'label' => 'Cancelled',
                    'backgroundColor' => "#F74969",
                    'data' => $data_cancelled
                ]
            ]
        ];
        
       return json_encode($chartData);
                                  
    }

    public function changePlace(Request $request)
    {
        $this->setCurrentPlaceId($request->place_id);
        
    }

    private function addData_AppointmentChart($day,$arr,$arr_data){
        foreach ($day as $value) {
            $check = 0;
            foreach ($arr as $arr_value) {  
                
               if($value == format_dayMonth($arr_value->format)){                  
                array_push($arr_data,$arr_value->count);                
                $check = 1;
               }              

            }
            if($check == 0){
                array_push($arr_data,'');
               }
        }
        return $arr_data;
    }

    
}
