<?php 
namespace App\Helpers;
use App\Models\PosCustomer;
use App\Models\PosOrder;
use Carbon\Carbon;

class SmsHelper{
	public static function groupClient(){

		return $arr = ['New','Royal','VIP','NORMAL MEMBERSHIP','SILVER MEMBERSHIP','GOLDEN MEMBERSHIP','DIAMOND MEMBERSHIP','NO MEMBERSHIP','REMINDER 7 DAYS','REMINDER 14 DAYS','REMINDER 21 DAYS','REMINDER 30 DAYS','REMINDER 60 DAYS','REMINDER 90 DAYS','REMINDER 180 DAYS','REMINDER 365 DAYS','BIRTHDAY JANUARY','BIRTHDAY FEBRUARY','BIRTHDAY MARCH','BIRTHDAY APRIL','BIRTHDAY MAY','BIRTHDAY JUNE','BIRTHDAY JULY','BIRTHDAY AUGUST','BIRTHDAY SEPTEMBER','BIRTHDAY OCTOBER','BIRTHDAY NOVEMBER','BIRTHDAY DECEMBER'];
	}
	public static function getCientWithBirthday($month,$place_id){
		$client_list = PosCustomer::where('customer_place_id',$place_id)
		                            ->whereMonth('customer_birthdate',$month)
		                            ->where('customer_status','!=',0)
		                            ->get();
		return $client_list;
	}
	public static function membership($place_id,$membership_name){
		//ex input: $membership_name = 'Normal Membership';
		$client_list = PosCustomer::join('pos_membership','customer_membership_id','membership_id')
									->where('customer_place_id',$place_id)
		                            ->where('customer_status',1)
		                            ->where('membership_name',$membership_name)
		                            ->get();		
		return $client_list;
	}
	public static function remider($place_id,$day = null){

		$date_before = Carbon::now()->subDays($day);
		$year_before = $date_before->format('Y');
		$month_before = $date_before->format('m');
		$day_defore = $date_before->format('d');
		
		$client_list = PosOrder::where('order_place_id',$place_id)
		                        ->whereMonth('order_datetime_payment',$month_before)
		                        ->whereYear('order_datetime_payment',$year_before)
		                        ->whereDay('order_datetime_payment',$day_defore)
		                        ->groupBy('order_customer_id')
		                        ->where('order_status',1)
		                        ->latest()
		                        ->get();
	    return $client_list;
	}
	public static function typeCustomer($place_id,$type){
		//ex input: $type = 'New' || 'Royal' || 'Vip';
		if($type == 'New'){			
			$client_list = PosCustomer::where('customer_customertag_id',0)->where('customer_place_id',$place_id)->get();
			return $client_list;
		}	
		$client_list = PosCustomer::join('pos_customertag',function($join){
										$join->on('pos_customertag.customertag_place_id','pos_customer.customer_place_id')
										->on('pos_customertag.customertag_id','pos_customer.customer_customertag_id');
									})
									->where('customertag_name',$type)
									->where('customer_place_id',$place_id)
									->where('customer_status',1)
									->get();
		return $client_list;
	}
}
