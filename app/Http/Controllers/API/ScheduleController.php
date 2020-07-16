<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosBooking;
use App\Models\PosBookingDetail;
use App\Models\PosService;
use App\Models\PosWorker;
use App\Models\PosOrder;
use Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{        
    public function getSchedule(Request $request){
        if($request->date){
            $date = format_date_db($request->date);
        } else {
            $date = format_date_db(Carbon::now());
        }
        $booking = PosBooking::select('booking_id','booking_code','booking_time_selected','customer_fullname','booking_status','booking_worker_id')
                            ->where('booking_place_id',$this->getPlaceId())
                            ->join('pos_customer',function($joinCustomer){
                                    $joinCustomer->on('booking_place_id','customer_place_id')
                                    ->on('booking_customer_id','customer_id');
                            })
                            ->whereDate('booking_time_selected',$date)
                            ->where('booking_status','!=',1)
                            ->get();
                            // echo $booking; die();
        $data = [];
        
        foreach ($booking as $value) {
            $date = format_date($value->booking_time_selected);
            $time = format_time24h($value->booking_time_selected);

            $status = '';
            if($value->booking_status === 0){
                $status = "CANCEL";
            } else if($value->booking_status === 2){
                $status = "CONFIRM";
            } else if($value->booking_status === 3){
                $status = "WORKING";
            } else if($value->booking_status === 4){
                $status = "PAID";
            }

            if(empty($value->booking_worker_id)){
                $bookingDetail = PosBookingDetail::select('worker_id')
                                              ->distinct() 
                                              ->where('booking_time',$value->booking_time_selected)
                                              ->where('bookingdetail_place_id',$this->getPlaceId()) 
                                              ->get();
                foreach ($bookingDetail as $valueDetail) {
                    $data[] = [
                        'booking_id' => $value->booking_id,
                        'booking_code' => $value->booking_code,
                        'booking_date' => $date,
                        'booking_time' => $time,
                        'customer_fullname' => $value->customer_fullname,
                        'booking_status' => $status,     
                        'booking_worker_id' => $valueDetail->worker_id,
                    ];
                } 
            } else {
                $data[] = [
                    'booking_id' => $value->booking_id,
                    'booking_code' => $value->booking_code,
                    'booking_date' => $date,
                    'booking_time' => $time,
                    'customer_fullname' => $value->customer_fullname,
                    'booking_status' => $status,     
                    'booking_worker_id' => $value->booking_worker_id,
                ];
            }
        }
        

        $result = [
            'status' => 1,
            'data' => $data,
            'msg' => 'success',
        ];
        
        return response()->json($result,200);
    }
    

}