<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosBooking;
use App\Models\PosBookingDetail;
use App\Models\PosService;
use App\Models\PosPlace;
use App\Models\PosWorker;
use App\Models\PosOrder;
use App\Models\PosCustomerRating;
use Auth;
use Carbon\Carbon;

class BookingController extends Controller
{      
    private function getBookingPaginateByStatus($page, $status){
        $page = $page*10;

        $totalPage = PosBooking::select('booking_id')
                                ->where('booking_place_id',$this->getPlaceId())
                                ->join('pos_customer',function($joinCustomer){
                                    $joinCustomer->on('booking_place_id','customer_place_id')
                                    ->on('booking_customer_id','customer_id');
                                })                                
                                ->where('booking_status',$status)
                                ->count()/10;
        $totalPage = CEIL($totalPage);
        if($totalPage > 0){
            $totalPage = $totalPage - 1;
        }

        $booking = PosBooking::select('booking_id','booking_code','booking_time_selected','customer_phone','customer_fullname','booking_status','booking_lstservice')
                                ->where('booking_place_id',$this->getPlaceId())
                                ->join('pos_customer',function($joinCustomer){
                                    $joinCustomer->on('booking_place_id','customer_place_id')
                                    ->on('booking_customer_id','customer_id');
                                })                                
                                ->where('booking_status',$status)
                                ->skip($page)
                                ->take(10)
                                ->orderBy('booking_id','desc')
                                ->get(); 

        $result = [];
        foreach ($booking as $value) {
            $date = format_date($value->booking_time_selected);
            $time = format_time24h($value->booking_time_selected);

            if($value->booking_lstservice){
                $idServices = explode(",", $value->booking_lstservice);                  
            } else {
                $idServices = PosBookingDetail::select('service_id')
                                            ->where('bookingdetail_place_id',$this->getPlaceId())
                                            ->where('booking_time',$value->booking_time_selected)
                                            ->get(); 
            } 
            $totalPriceServices = PosService::select('service_price')
                                    ->where('service_place_id',$this->getPlaceId())
                                    ->whereIn('service_id',$idServices)
                                    ->sum('service_price'); 

            $result[] = [
                'booking_id' => $value->booking_id,
                'booking_code' => $value->booking_code,
                'booking_date' => $date,
                'booking_time' => $time,
                'booking_total_price'=>$totalPriceServices,
                'booking_status' => $value->booking_status,
                'customer_fullname' => $value->customer_fullname,  
                'customer_phone' => $value->customer_phone,
            ];
        } 
         
        
        return ['listBookings'=>$result,'totalPage'=>$totalPage ?? ''];
    }


    private function getAllBookingByStatus($status , $orderBy){

        
        $booking = PosBooking::select('booking_id','booking_code','booking_time_selected','customer_phone','customer_fullname','booking_status','booking_lstservice')
                                ->where('booking_place_id',$this->getPlaceId())
                                ->join('pos_customer',function($joinCustomer){
                                    $joinCustomer->on('booking_place_id','customer_place_id')
                                    ->on('booking_customer_id','customer_id');
                                })                                
                                ->where('booking_status',$status)
                                ->orderBy('booking_id',$orderBy)
                                ->get(); 

        $result = [];
        foreach ($booking as $value) {
            $date = format_date($value->booking_time_selected);
            $time = format_time24h($value->booking_time_selected);

            if($value->booking_lstservice){
                $idServices = explode(",", $value->booking_lstservice);                  
            } else {
                $idServices = PosBookingDetail::select('service_id')
                                            ->where('bookingdetail_place_id',$this->getPlaceId())
                                            ->where('booking_time',$value->booking_time_selected)
                                            ->get(); 
            } 
            $totalPriceServices = PosService::select('service_price')
                                    ->where('service_place_id',$this->getPlaceId())
                                    ->whereIn('service_id',$idServices)
                                    ->sum('service_price'); 

            $result[] = [
                'booking_id' => $value->booking_id,
                'booking_code' => $value->booking_code,
                'booking_date' => $date,
                'booking_time' => $time,
                'booking_total_price'=>$totalPriceServices,
                'booking_status' => $value->booking_status,
                'customer_fullname' => $value->customer_fullname,  
                'customer_phone' => $value->customer_phone,
            ];
        } 
         
        
        return ['listBookings'=>$result,'totalPage'=>0];
    }

    private function updateStatusBooking($id, $status){
        $booking = PosBooking::where('booking_place_id',$this->getPlaceId())
                            ->where('booking_id',$id)                            
                            ->update(['booking_status' => $status]);                           
    }
    /**
     * Call API get list new booking
     * @param  int $request->page
     * @return json
     */
    public function getNew(Request $request){
        $booking = $this->getAllBookingByStatus(1, "asc");

        $result = [
            'status' => 1,
            'msg' => "success",
            'data' => $booking,
        ];

        return response()->json($result, 200);        
    }
    /**
     * Call API get list confirm booking
     * @param  int $request->page
     * @return json
     */
    public function getConfirm(Request $request){
        $booking = $this->getAllBookingByStatus(2,"desc");

        $result = [
            'status' => 1,
            'msg' => "success",
            'data' => $booking,
        ];

        return response()->json($result, 200);   
    }

    public function confirm(Request $request, $id){
        if($id){
            $booking = $this->updateStatusBooking($id,2);

            $result = [
            'status' => 1,
            'msg' => "success",
        ];

        return response()->json($result, 200);  
        }
    }

    public function cancel(Request $request, $id){
        if($id){
            $booking = $this->updateStatusBooking($id,0);

            $result = [
            'status' => 1,
            'msg' => "success",
        ];

        return response()->json($result, 200);  
        }
    }
    /**
     * create new booking
     * @param  customer[phone]
     * @param  customer[name]
     * @param  customer[email]
     * @param  customer[gender]
     * @param  date(yyyy-mm-dd H:i)     dateTimeSel
     * @param  lstServiceSel[]
     * @param  booking_note
     * @return 
     */
    public function create(Request $request){
        $request->merge(['license' =>$this->getlicenseByPlaceId()]);
        $arrFormat = [
            'customer' => [
                'phone' => $request->customer_phone,
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'gender' => $request->customer_gender,
            ],
        ];
        $request->merge($arrFormat);
        // dd($request->all());
        $webbooking = new WebBookingController;
        return $webbooking->bookingOrderWeb($request);
    }

    private function getlicenseByPlaceId(){
        return PosPlace::select('place_ip_license')
            ->where('place_id',$this->getPlaceId())
            ->where('place_status',1)
            ->first()->place_ip_license;
    }

    /**
     * Call API get booking detail by booking id
     * @param  int  $id
     * @return json
     */
    public function getBookingDetail($id, Request $request){
        $booking = PosBooking::select('booking_customer_id','booking_id','booking_code','booking_time_selected','customer_fullname','booking_status','worker_nickname','booking_worker_id','booking_lstservice','customer_email','customer_phone','customer_country_code','booking_note','customer_note')
                            ->where('booking_place_id',$this->getPlaceId())
                            ->join('pos_customer',function($joinCustomer){
                                    $joinCustomer->on('booking_place_id','customer_place_id')
                                    ->on('booking_customer_id','customer_id');
                            })
                            ->leftjoin('pos_worker',function($joinWorker){
                                    $joinWorker->on('worker_place_id','booking_place_id')
                                    ->on('booking_worker_id','worker_id');
                            })
                            ->where('booking_id',$id)
                            ->first();
        //check 
        if(empty($booking)){
            return response()->json(['status'=>0,'msg'=>'Booking does not exits'],400);
        }

        $booking_customer_id = $booking->booking_customer_id;

        $booking_first = PosBooking::where('booking_customer_id',$booking_customer_id)
                                    ->where('booking_place_id', $this->getPlaceId())
                                    ->where('booking_status',"!=",0)
                                    ->first();

        $booking_last = PosBooking::where('booking_customer_id',$booking_customer_id)
                                    ->where('booking_place_id', $this->getPlaceId())
                                    ->where('booking_status',"!=",0)
                                    ->latest()->first();

        $worker_id_last = $booking_last->booking_worker_id;

        $worker_nickname = PosWorker::where('worker_place_id', $this->getPlaceId())
                                    ->where('worker_id',$worker_id_last)
                                    ->where('enable_status',1)
                                    ->where('worker_status',1)
                                    ->first();

        $countVisit = PosBooking::where('booking_place_id',$this->getPlaceId())
                                  ->where('booking_customer_id',$booking_customer_id)
                                  ->where('booking_status',"!=",0)->count();

        $totalSpend = PosOrder::where('order_place_id',$this->getPlaceId())
                                ->where('order_status',1)
                                ->where('order_customer_id',$booking_customer_id)
                                ->sum('order_price');

        $customerLastReview = PosCustomerRating::select('cr_rating','cr_description')
                                    ->where('cr_status',1)
                                    ->where('cr_phone',$booking->customer_phone)
                                    ->where('cr_place_id',$this->getPlaceId())
                                    ->orderBy('cr_id','desc')
                                    ->first();
        $arrLastReview = [
            'rating' => isset($customerLastReview->cr_rating) ? $customerLastReview->cr_rating : '',
            'description' => isset($customerLastReview->cr_description) ? $customerLastReview->cr_description : '',
        ];

        //check 
        $service = [];
        $total_price = 0;
        if(empty($booking->booking_worker_id) && empty($booking->booking_lstservice)){
          // multy worker
          $bookingDetail = PosBookingDetail::select('worker_nickname','service_name','service_duration','service_price')
                                                      // ->where('pos_booking_details.worker_id',$request->worker_id)
                                                      ->where('booking_time',$booking->booking_time_selected)
                                                      ->where('bookingdetail_place_id',$this->getPlaceId())
                                                      ->join('pos_worker',function($joinWorker){
                                                        $joinWorker->on('worker_place_id','bookingdetail_place_id')
                                                        ->on('pos_worker.worker_id','pos_booking_details.worker_id');
                                                      })
                                                      ->join('pos_service',function($joinService){
                                                        $joinService->on('service_place_id','bookingdetail_place_id')
                                                        ->on('pos_service.service_id','pos_booking_details.service_id');
                                                      })
                                                      ->get();

          foreach ($bookingDetail as $key => $value) {
            $service[] = [
                'service_name' => $value->service_name,
                'service_duration' => $value->service_duration,
            ];
            $total_price += $value->service_price;
          }
        } else {
          // only one worker
          $lst_service = explode(",",$booking->booking_lstservice);
         
          $services = PosService::select('service_name','service_duration','service_price')
                          ->whereIn('service_id', $lst_service)
                          ->where('service_place_id', $this->getPlaceId())
                          ->get();

          foreach ($services as $key => $value) {
              $service[] = [
                    'service_name' => $value->service_name,
                    'service_duration' => $value->service_duration,
              ];
              $total_price += $value->service_price;
          }
        }

        $date = format_date($booking->booking_time_selected);
        $time = format_time24h($booking->booking_time_selected);

        $status = '';
        if($booking->booking_status === 0){
            $status = "CANCEL";
        } else if($booking->booking_status === 2){
            $status = "CONFIRM";
        } else if($booking->booking_status === 3){
            $status = "WORKING";
        } else if($booking->booking_status === 4){
            $status = "PAID";
        }

        $result = [
            'status' => 1,
            'msg' => 'success',
            'data' => [
                'customer_info' => [
                    'customer_fullname' => $booking->customer_fullname,
                    'customer_email' => $booking->customer_email,
                    'customer_phone' => \App\Helpers\GeneralHelper::formatPhoneNumber($booking->customer_phone,$booking->customer_country_code),
                    'customer_description' => $booking->customer_note,
                    'first_visit' => format_date($booking_first->booking_time_selected),
                    'last_visit' => format_date($booking_last->booking_time_selected),
                    'visit_count' => $countVisit,
                    'worker_name_last' => $worker_nickname->worker_nickname ?? '',
                    'total_spend' => $totalSpend,
                    'last_review' => $arrLastReview,
                    ],
                'booking_info' =>[
                    'total_price' => $total_price,
                    'services' => $service,
                    'booking_date' => $date,
                    'booking_time' => $time,
                    'booking_status' => $status,
                    'booking_note' => $booking->booking_note,
                    // 'worker' => '',
                ],
            ],            
        ];

        return response()->json($result,200);
    }

    public function getTotals(Request $request){
        $date = Carbon::now();
        $date = format_date_db($date);

        $totalNewBooking = $this->totalByStatus(1, $date);
        $totalConfirmBooking = $this->totalByStatus(2, $date);
        $totalPrice = $this->totalPrice($date);
        

        $results = [
            'totalNewBooking' => $totalNewBooking,
            'totalConfirmBooking' => $totalConfirmBooking,
            'totalPrice' => $totalPrice,
        ];

        return response()->json(['status'=>1,'data'=>$results],200);
    }

    private function totalByStatus($status, $date){
        $booking = PosBooking::select('booking_id')
                            ->where('booking_place_id',$this->getPlaceId())
                            ->where('booking_status',$status)
                            ->whereDate('booking_time_selected',$date)
                            ->count();

        return $booking;
    }

    private function totalPrice($date){
        $price = PosOrder::select('order_receipt')
                        ->where('order_place_id',$this->getPlaceId())
                        ->whereDate('order_datetime_payment',$date)
                        ->where('order_status',1)
                        ->sum('order_receipt');

        return $price;
    }


}