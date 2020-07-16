<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\PosCustomerRating;
use Illuminate\Http\Request;
use App\Models\MainService;
use App\Models\MainServiceDetail;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use net\authorize\util\Mapper;
use App\Models\PosPlace;
use App\Models\MainCustomerService;
use DB;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // private $merchant_id = null;

    private $package=[
            "SMS mời khách viết review",
            "SMS thông báo booking thành công",
            "SMS thông báo về Giftcard khi khách hàng mua Giftcard",
            "SMS Coupon Happy Birthday, ngày lễ, sự kiện",
            "SMS nhắc nhở khách hàng đến tiệm làm dịch vụ sau 1 thời gian tùy theo chủ tiệm đặt hàng",
        ];

    public function __construct()
    {
        
    }


    public function listReviews()
    {      
        $sum_website = PosCustomerRating::where('cr_place_id',$this->getCurrentPlaceId())->where('cr_status',1)->count();
        if($sum_website) $data_sum['sum_website'] = $sum_website;
        else  $data_sum['sum_website'] = 0;

        try {

            $sum = 'sum/?merchant_id='.$this->getCurrentPlaceId().'';

            $call = $this->callMPIT($sum);
            
            $data = json_decode($call);

            if($data->status == 1){
                $data_sum['sum_yelp'] = $data->data->yelp;
                $data_sum['sum_google'] = $data->data->google;
                $data_sum['sum_facebook'] = $data->data->facebook;
                $data_sum['sum_totalBad'] = $data->data->totalBad;
                $data_sum['sum_total'] = $data->data->total;                
            }       

        } 
        catch (\Exception $e) {
                $data_sum['sum_yelp'] = '';
                $data_sum['sum_google'] = '';
                $data_sum['sum_facebook'] = '';
                $data_sum['sum_totalBad'] = '';
                $data_sum['sum_total'] = '';       
        }
        
        return view('marketing.reviews',$data_sum);
    }

    public function return_table_ajax(Request $request)
    {
        $arrpackage=$this->package;
        $data=MainService::where("service_type",1)->first();
        $explode=explode(";", MainService::where("service_type",1)->first()->service_listservicedetail_id);
        // return $explode;
        $count=count($explode);
        $colspan=$count+1;

        $arr=[];
        foreach ($explode as $value) {
            $sms=MainServiceDetail::where("servicedetail_id",$value)->first();
            $arr[$sms->servicedetail_id]=$sms;
            // return $sms;
        }
        // dd($arr);
        // $smspackage=MainServiceDetail::where("servicedetail_type",1)->get();
        // $countsmspackage=count($smspackage);
        return view('marketing.table_sms',compact("arrpackage","data","count","colspan","arr"));
    }
    
    
    public function badReviews()
    {
        
        return view('marketing.badreviews');
    }
    
    private function callMPIT($url = ""){
        try {
            $url = env("REVIEW_SMS_API_URL").'review/'.$url;

            $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
            //$url="http://user.tag.com/api/v1/receiveTo";
            $client = new Client([
                // 'timeout'  => 5.0,            
            ]);
            //$params ["yelp_id"] =
            
                    
            $response = $client->get($url, array('headers' => $header));
            // Call external API
            // $response = $client->post("http://d29u17ylf1ylz9.cloudfront.net/phuler-v4/index.html", ['form_params' => $smsData]);
            //$response = $client->get("http://d29u17ylf1ylz9.cloudfront.net/phuler-v4/index.html");
            // Check whether API call was successfull or not...
            //$zonerStatusCode = $response->getStatusCode();
            $resp=  (string)$response->getBody();
            //echo $resp;
            return $resp;
        } catch (\Exception $e) {
            
        }
        
        

    }

    public function ajax_yelp(){
        $yelp = 'filter?merchant_id='.$this->getCurrentPlaceId().'&type=1&start=0&length=20';
        $data = $this->callMPIT($yelp);        
        return $data;
    }

    public function ajax_facebook(){
        $fb = 'filter?merchant_id='.$this->getCurrentPlaceId().'&type=3&start=0&length=20';
        $data = $this->callMPIT($fb);        
        return $data;
    }

    public function ajax_google(){
        $gg = 'filter?merchant_id='.$this->getCurrentPlaceId().'&type=2&start=0&length=20';
        $data = $this->callMPIT($gg);
        return $data;
    }

    public function ajax_allreviews(){
        $allreviews = 'filter?merchant_id='.$this->getCurrentPlaceId()."&start=0&length=20";
        $data = $this->callMPIT($allreviews);        
        return $data;
    }

    public function ajax_website(){
        // 
        $website = PosCustomerRating::select('cr_id','pos_customer_rating.cr_fullname as customer','pos_customer_rating.cr_rating as rating','pos_customer_rating.cr_description as message','pos_customer_rating.created_at as created_date')
            /*-> join('pos_customer',function($join){
                $join->on('pos_customer.customer_phone','pos_customer_rating.cr_phone')
                ->on('pos_customer.customer_place_id','pos_customer_rating.cr_place_id');
            })*/
                -> where('cr_place_id',$this->getCurrentPlaceId())
                                        ->where('cr_status',1)->take(20)->get();
            // echo $website; die();
        //add row type = 5 to array $website 
        foreach ($website as $key => $value) {
           $website[$key]['type'] = 5;
        }

        //check website
        if($website){
            $data = [
                'total' => $website->count(),
                'data'=> $website,
            ];
        } 
        else{
            $data = [
                'total'=> 0,
                'data' => [],
            ];
        }

        return json_encode($data);
    }
    public function ajax_bad_review(){

        $filter = 'filter?merchant_id='.$this->getCurrentPlaceId().'&badReview=1';
        $data = $this->callMPIT($filter);
        return $data;
    }

    public function ajax_filter_form(Request $request){
        $filter = 'filter?merchant_id='.$this->getCurrentPlaceId();
        //check type
        if($request->type == 6){
            $filter = 'filter?merchant_id='.$this->getCurrentPlaceId().'&badReview=1';
        }
        if($request->type && $request->type != 6){
            $filter .='&type='.$request->type;
        }        
        if($request->type == 5){
            return $this->ajax_filter_website($request);  //break;
        }
        if($request->start_date && $request->end_date && $request->review_date){
            $filter .= '&from='.$request->start_date.'&to='.$request->end_date;
        }
        if($request->rating){
            $filter .='&rating='.$request->rating;
        }
        if($request->customer_name){
            $filter .= '&search='.$request->customer_name;
        }
        if($request->badReview){
            $filter .='&badReview=1';
        }
        if($request->show_items_length){
            $filter .= '&length='.$request->show_items_length;
        }
        if($request->show_more == 1 && $request->data_length_start && !$request->show_items_length){
            $filter .= '&start='.$request->data_length_start.'&length=20';
            $data = $this->callMPIT($filter);
            return $data;
        }

        
        // return $filter;
        $data = $this->callMPIT($filter);
    
        return $data;
    }

    private function ajax_filter_website($request){            
        $arr = [];
        
        if($request->rating){
            array_push($arr,['cr_rating',$request->rating]);
        }

        if($request->customer_name){
            array_push($arr,['cr_fullname',$request->customer_name]);
        }

        if($request->start_date && $request->end_date && $request->review_date){
            $end_date = format_date_db($request->end_date);
            $start_date = format_date_db($request->start_date);
            array_push($arr,['created_at','>=',''.$start_date.''],['created_at','<=',''.$end_date.'']);
        }

        $website = PosCustomerRating::select('cr_fullname as customer','cr_rating as rating','cr_description as message','created_at as created_date')->where('cr_place_id',$this->getCurrentPlaceId())
                                        ->where('cr_status',1)->where($arr);

        if($request->show_items_length){
            $website = $website->take($request->show_items_length);
        }


        //check badReview
        if($request->badReview){            
            
            $bad = ['abc','service'];
            $arr_bad = [];
            foreach ($bad as $value) {
                array_push($arr_bad,['cr_description','LIKE','%'.$value.'%']);
            }

            
               // $website = PosCustomerRating::select('cr_fullname as customer','cr_rating as rating','cr_description as message','created_at as created_date')->where('cr_place_id',$this->getCurrentPlaceId())
               //                          ->where('cr_status',1)->take($request->show_items_length)->where($arr)
               //                          ->orWhere($arr_bad)->get();
            $website = $website->where('cr_rating','<',3)->orWhere($arr_bad);
        }

        if($request->show_more == 1 && $request->data_length_start && !$request->show_items_length){            
            $website = $website->take(20)->skip($request->data_length_start);
        }

        $website = $website->get();
        

        //add row type = 5 to array $website 
        foreach ($website as $key => $value) {
           $website[$key]['type'] = 5;
        }

        //check website
        if($website){
            $data = [
                'total' => $website->count(),
                'data'=> $website,
            ];
        } 
        else{
            $data = [
                'total'=> 0,
                'data' => [],
            ];
        }
        return json_encode($data);
    }   


   
    public function buysms(Request $request)
    {
        $arrpackage=$this->package;
        $dataMainSV=MainService::where("service_type",1)->first();
        $explodeMainSV=explode(";", MainService::where("service_type",1)->first()->service_listservicedetail_id);

        $arr=[];
        foreach ($explodeMainSV as $value) {
            $sms=MainServiceDetail::where("servicedetail_id",$value)->first();
            $arr[]=$sms;
            // return $sms;
        }


        $data=MainServiceDetail::where('servicedetail_id',$request->id)->first();
        $explode=explode(";", $data->servicedetail_value);

        $decode=json_decode($explode[2]);
        $explode1=explode(",",$decode->id);
        
        return view('marketing.buy_sms_package',compact("arrpackage","arr","data","explode","explode1"));
    }

    public function post_authorization_sms_pakage(Request $request)
    {                                        
        $id_api=env("AUTHORIZE_ID");
        $transaction_key=env("AUTHORIZE_KEY");
        // dd($transaction_key);
        $total_sms=$request->total_sms;
        $bonus_sms=$request->bonus_sms;
        $serviceId=$request->serviceId;

        if($request->card_number && $request->ccv && $request->exporation_date_card){
                // Common setup for API credentials
                $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                $merchantAuthentication->setName($id_api);
                $merchantAuthentication->setTransactionKey($transaction_key);
                $refId = 'ref'.time();
                // Create the payment data for a credit card
                $creditCard = new AnetAPI\CreditCardType();
                $creditCard->setCardNumber($request->card_number);
                // $creditCard->setExpirationDate( "2038-12");
                $expiry = $request->exporation_date_card;
                $creditCard->setExpirationDate($expiry);
                $cardCode=$request->ccv;
                $creditCard->setCardCode($cardCode);
                $paymentOne = new AnetAPI\PaymentType();
                $paymentOne->setCreditCard($creditCard);
                // Create a transaction
                $transactionRequestType = new AnetAPI\TransactionRequestType();
                $transactionRequestType->setTransactionType("authCaptureTransaction");
                $transactionRequestType->setAmount($request->price);
                $transactionRequestType->setPayment($paymentOne);
                $request = new AnetAPI\CreateTransactionRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setRefId( $refId);
                $request->setTransactionRequest($transactionRequestType);
                $controller = new AnetController\CreateTransactionController($request);
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
                if ($response != null)
                {
                  $tresponse = $response->getTransactionResponse();
                  // dd($tresponse->getResponseCode());

                  if ($tresponse && ($tresponse->getResponseCode()=="1"))
                  { 
                    DB::beginTransaction();
                    try {
                        $place = PosPlace::where('place_id',$this->getCurrentPlaceId())->first();                     
                        $place->place_total_sms = $place->place_total_sms + $total_sms + $bonus_sms;
                        $place->save();
                        // dd($total_sms );
                        $idCutomerService = MainCustomerService::where('cs_place_id',$this->getCurrentPlaceId())
                                            ->max('cs_id')+1;

                        $customerService = new MainCustomerService;
                        $customerService->cs_id = $idCutomerService;
                        $customerService->cs_place_id = $this->getCurrentPlaceId();
                        $customerService->cs_customer_id = $place->place_customer_id;
                        $customerService->cs_service_id = $serviceId;
                        $customerService->cs_date_expire = 0;
                        $customerService->cs_type = 0;
                        $customerService->save();
                        DB::commit();
                        return redirect()->route('list_reviews')->with('message', 'Buy SMS Success!');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return back()->with('error','Buy SMS Error!');
                    }                    
                  }
                  else
                  {
                    return back()->with('error', 'Charge Credit Card ERROR!');
                  }
                }
                else
                {
                  return back()->with('error', 'Charge Credit Card ERROR!');
                }
        }
        else
        {
            return back()->with('error', 'Charge Credit Card ERROR!');
        }
    }


    public function checkReviewWebsite(){
        $service = MainService::select('cs_id')->join('main_customer_service','cs_service_id','service_id')
                                ->where('service_type',1)
                                ->where('cs_status',1)
                                ->where('cs_type',0)
                                ->where('cs_place_id',$this->getCurrentPlaceId())
                                ->get();

        if(count($service) > 0){
            return 1;
        } else return 0;
    }

}
