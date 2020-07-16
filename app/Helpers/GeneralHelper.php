<?php
namespace App\Helpers;

/**
 * ImagesHelper class
 */
class GeneralHelper{
    
   public static function unicodeVietNamese($str){
        $unicodes = array (
                'a' =>'á|à|ạ|ả|ã|ă|ắ|ằ|ặ|ẳ|ẵ|â|ấ|ầ|ậ|ẩ|ẫ',
                'A'	=>'Á|À|Ạ|Ả|Ã|Ă|Ắ|Ằ|Ặ|Ẳ|Ẵ|Â|Ấ|Ầ|Ậ|Ẩ|Ẫ',
                'o' =>'ó|ò|ọ|ỏ|õ|ô|ố|ồ|ộ|ổ|ỗ|ơ|ớ|ờ|ợ|ở|ỡ',
                'O'	=>'Ó|Ò|Ọ|Ỏ|Õ|Ô|Ố|Ồ|Ộ|Ổ|Ỗ|Ơ|Ớ|Ờ|Ợ|Ở|Ỡ',
                'e' =>'é|è|ẹ|ẻ|ẽ|ê|ế|ề|ệ|ể|ễ',
                'E'	=>'É|È|Ẹ|Ẻ|Ẽ|Ê|Ế|Ề|Ệ|Ể|Ễ',
                'u' =>'ú|ù|ụ|ủ|ũ|ư|ứ|ừ|ự|ử|ữ',
                'U'	=>'Ú|Ù|Ụ|Ủ|Ũ|Ư|Ứ|Ừ|Ự|Ử|Ữ',
                'i' =>'í|ì|ị|ỉ|ĩ',
                'I'	=>'Í|Ì|Ị|Ỉ|Ĩ',
                'y' =>'ý|ỳ|ỵ|ỷ|ỹ',
                'Y'	=>'Ý|Ỳ|Ỵ|Ỷ|Ỹ',
                'd' =>'đ',
                'D' =>'Đ',
        );
        foreach($unicodes as $ascii=>$unicode){
                $str = preg_replace("/({$unicode})/",$ascii,$str);
        }
        return $str;
    }
    //public static function convert_gender($gender)
    public static function convertGender($gender)
    {
        if($gender ==1)
                return "Male";
        elseif($gender ==2)
                return "Female";
        else return "Chirld";
    }
    //public static function get_title_by_gender($gender)
    public static function getTitleByGender($gender)
    {
        if($gender ==1)
                return "Mr. ";
        elseif($gender ==2)
                return "Ms ";
        else return "";
    }
    //public static function get_title_by_booking_type($type_id)
    public static function getTitleByBookingType($type_id)
    {
        if($type_id =="")
                return "UNKNOWN";
        elseif($type_id ==1)
                return "WEBSITE";
        elseif($type_id ==2) return "DERECT CALL";
    }
    
    //public static function convert_booking_status_html($status)
    public static function convertBookingStatusHtml($status)
    {
        if($status ==0)
                return '<span class="badge bg-info" style="font-size: x-small">CANCEL</span>';
        elseif($status ==1)
                return '<span class="badge bg-blue-sky" style="font-size: x-small">NEW APPOINTMENT</span>';
        elseif($status ==2)
                return '<span class="badge bg-blue" style="font-size: x-small">VERIFIED</span>';
        elseif($status ==3)
                return '<span class="badge bg-green" style="font-size: x-small">APPROVED</span>';
        else return "";
    }
    //public static function get_dropdown_booking_status($selected_status="")
    public static function getDropdownBookingStatus($selected_status="")    
    {
        if($selected_status==""){
                return '<option value="">-- Status --</option>
        <option value="0">CANCEL</option>
        <option value="1">NEW BOOKING</option>
        <option value="2">CONFIRM</option>
        <option value="3">WORKING</option>
        <option value="4">PAID</option>';
        }
        elseif($selected_status==0){
                return '<option value="">-- Status --</option>
        <option value="0" selected>CANCEL</option>
        <option value="1">NEW BOOKING</option>
        <option value="2">CONFIRM</option>
        <option value="3">WORKING</option>
        <option value="4">PAID</option>';
        }elseif($selected_status==1){
                return '<option value="">-- Status --</option>
        <option value="0">CANCEL</option>
        <option value="1" selected>NEW BOOKING</option>
        <option value="2">CONFIRM</option>
        <option value="3">WORKING</option>
        <option value="4">PAID</option>';
        }elseif($selected_status==2){
                return '<option value="">-- Status --</option>
        <option value="0">CANCEL</option>
        <option value="1">NEW BOOKING</option>
        <option value="2" selected>CONFIRM</option>
        <option value="3">WORKING</option>
        <option value="4">PAID</option>';
        }elseif($selected_status==3){
                return '<option value="">-- Status --</option>
        <option value="0">CANCEL</option>
        <option value="1">NEW BOOKING</option>
        <option value="2">CONFIRM</option>
        <option value="3" selected>WORKING</option>
        <option value="4">PAID</option>'; 
        }elseif($selected_status==4){
                return '<option value="">-- Status --</option>
        <option value="0">CANCEL</option>
        <option value="1">NEW BOOKING</option>
        <option value="2">CONFIRM</option>
        <option value="3" selected>WORKING</option>
        <option value="4" selected>PAID</option>'; 
        }		
    }
    public static function bookingStatusArray(){
        return $arr = [
            '#edd70a'=>'new booking',
            '#009fd5'=>'confirm',
            '#307539'=>'working',
            '#d42423'=>'cancel',
            '#bbbdc4'=>'paid'
        ];
    }
    //public static function get_dropdown_payment_type($selected_type="")
    public static function getDropdownPaymentType($selected_type="")
    {
        if($selected_type==""){
                return '<option value=""> -- Payment Type -- </option>
        <option value="0">CASH</option>
        <option value="1">CREDIT CARD</option>
        <option value="2">CHECK</option>
        <option value="3">GIFT CARD</option>';
        }
        elseif($selected_type==0){
                return '<option value=""> -- Payment Type -- </option>
        <option value="0" selected>CASH</option>
        <option value="1">CREDIT CARD</option>
        <option value="2">CHECK</option>
        <option value="3">GIFT CARD</option>';
        }elseif($selected_type==1){
                return '<option value=""> -- Payment Type -- </option>
        <option value="0">CASH</option>
        <option value="1" selected>CREDIT CARD</option>
        <option value="2">CHECK</option>
        <option value="3">GIFT CARD</option>';
        }elseif($selected_type==2){
                return '<option value=""> -- Payment Type -- </option>
        <option value="0">CASH</option>
        <option value="1">CREDIT CARD</option>
        <option value="2" selected>CHECK</option>
        <option value="3">GIFT CARD</option>';
        }elseif($selected_type==3){
                return '<option value=""> -- Payment Type -- </option>
        <option value="0">CASH</option>
        <option value="1">CREDIT CARD</option>
        <option value="2">CHECK</option>
        <option value="3" selected>GIFT CARD</option>';
        }		
    }
    //PAYMENT TYPE ARRAY
    public static function paymentTypeArray(){
        return [
            0 => 'Cash',
            1 => 'Credit Card',
            2 =>  'Check',
            3 => 'Gift Card'
        ];
    }
    //public static function convert_payment_type($status)
    public static function convertPaymentType($status)
    {
        if($status ==0)
                return 'CASH';
        elseif($status ==1)
                return 'CREDIT CARD';
        elseif($status ==2)
                return 'CHECK';
        elseif($status ==3)
                return 'GIFT CARD';
        else return "";
    }
    public static function formatPhoneNumber($number,$code = "")
    {
        if($number != ""){
            // +84 viet nam
            if($code == '84'){    
                $number = substr($number, 1);                
            }

            $number1 = substr($number, 0,3);
            $number2 = substr($number, 3,3);
            $number3 = substr($number, 6);

            if($code != ''){
                $code = $code;
            }

            $result = $code." (".$number1.") ".$number2."-".$number3;            

        } else $result = "";
        
        return $result;
    }
    public static function shortString($string){
        if( strlen($string) < 17 )
            return $string;
        else
            return substr($string,0,15)."...";
    }

    //--------
    const ONE = 1;
    const EIGHTY_FOUR = 2;
    const SIXTY_FOUR = 3;
    public static function all(){
            return [
                self::ONE => '+1',
                // self::EIGHTY_FOUR => '+84',
                // self::SIXTY_FOUR => '+64',
            ];
    }     
        
    public static function find($id = 0){
        $list = self::all();
        return isset($list[$id])?$list[$id]:0;
    }
    public static function paymentMethod(){
        return [
            0 => 'Cash',
            1 => 'Credit Card',
            2 => 'Debit card'
        ];
    }

}

?>