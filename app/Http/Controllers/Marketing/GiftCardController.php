<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosGiftcode;
use App\Models\PosPlace;
use Carbon\Carbon;
use Validator;
use yajra\Datatables\Datatables;
use App\Models\PosCustomer;
use App\Models\PosCustomertag;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use net\authorize\util\Mapper;

class GiftCardController extends Controller
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
        return view('marketing.giftcards');
    }
    
    public function add()
    {
        $giftCardCode = $this->__randomGiftcardCode($this->getCurrentPlaceId());
        return view('marketing.giftcard_add',compact('giftCardCode'));
    }
    
    public function view($code)
    {
        // dd($code);
        // $data['giftcart_detail'] = PosGiftcode::select()->join('pos_customer','pos_customer.customer_id','pos_giftcode.giftcode_customer_id')->where('giftcode_code',$code)->first();
        $data['giftcart_detail'] = PosGiftcode::where('giftcode_code',$code)->where('giftcode_place_id',$this->getCurrentPlaceId())->first();

        if(!$data['giftcart_detail']) return abort(404);

        $giftcode_B_P_I = $data['giftcart_detail']->giftcode_Billing_Payment_Information;

        if($giftcode_B_P_I){
                $explode = explode(';',$giftcode_B_P_I);
                $data['customer_fullname'] = $explode[0];
                $data['customer_phone'] = $explode[1];
                $data['customer_email'] = $explode[5];
                $data['customer_address'] = $explode[3];           
        }else{
                $data['customer_fullname'] = '';
                $data['customer_phone'] = '';
                $data['customer_email'] = '';
                $data['customer_address'] = '';                
        }

        $giftcode_A_N = $data['giftcart_detail']->giftcode_Authorize_Net;
        if($giftcode_A_N){
                $explode = explode(';',$giftcode_A_N);
                $data['cart_type'] = $explode[0];
                $data['cart_number'] = $explode[1];        
        }else{
                $data['cart_type'] = '';
                $data['cart_number'] = '';           
        }
        //
        // $giftcode_customer_id = $data['giftcart_detail']->giftcode_customer_id;
        //view Statement History
        $data['statement_history'] = PosGiftcode::select('pos_giftcode.giftcode_code','pos_order.order_id','pos_order.created_at','pos_order.order_price','pos_worker.worker_nickname','pos_customer.customer_fullname','pos_customer.customer_phone')
        ->join('pos_order',function($join){
            $join->on('pos_order.order_giftcard_code','pos_giftcode.giftcode_code')
                ->on('pos_order.order_place_id','pos_giftcode.giftcode_place_id');
        })
        ->join('pos_orderdetail',function($join1){
            $join1->on('pos_orderdetail.orderdetail_place_id','pos_order.order_place_id')
            ->on('pos_order.order_id','pos_orderdetail.orderdetail_order_id');
        })
        ->join('pos_worker',function($join2){
            $join2->on('pos_worker.worker_place_id','pos_orderdetail.orderdetail_place_id')
                ->on('pos_orderdetail.orderdetail_worker_id','pos_worker.worker_id');
        })
        ->join('pos_customer',function($join3){
            $join3->on('pos_customer.customer_id','pos_order.order_customer_id')
                ->on('pos_customer.customer_place_id','pos_order.order_place_id');
        })
        ->where('giftcode_place_id',$this->getCurrentPlaceId())
        ->where('pos_giftcode.giftcode_code',$code)        
        ->get();

        
        // foreach ($data['statement_history'] as $key ) {
        //     echo $key."<br>";
        // }
        // die();

        return view('marketing.giftcard_detail',$data);
    }

    /**
    *   @param idGiftCode
    *   @param myGiftCode
    *   @return true/false
    **/


    public function save(Request $request){
        //code by tri
        //get id API authorize.net in table pos_plade
        $pos_place=PosPlace::orderBy('updated_at','desc')->first();
        $place_authorize_payment=explode(';', $pos_place->place_authorize_payment);
        $id_api=$place_authorize_payment[0];
        $transaction_key=$place_authorize_payment[1];
        $rules = [
            // 'gift_code' => 'required',
            // 'price' => 'required',
            // 'loyalty_referral' => 'required',
            // 'expire_date' => 'required',
            // 'giftCardType' => 'required',
            // 'customer_id' => 'required',
        ];
        $messages = [
            // 'customer_id.required' => 'Customer is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $check_exist = PosGiftcode::where('giftcode_place_id',$this->getCurrentPlaceId())
                                    ->where('giftcode_code', $request->gift_code)->first();

        if($check_exist){
            $validator->after(function ($validator) {
                $validator->errors()->add('gift_code.exists', 'Gift code is exist, Please check again!');
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        
        $idGiftCode = PosGiftcode::where('giftcode_place_id','=',$this->getCurrentPlaceId())->max('giftcode_id') +1;

        $myGiftCode = new PosGiftcode;
        $card_number=substr($request->card_number, -4);
        $card_type=$request->card_type;
        $name_on_card=$request->name_on_card;
        $myGiftCode->giftcode_id        = $idGiftCode;
        $myGiftCode->giftcode_place_id  = $this->getCurrentPlaceId();
        $myGiftCode->giftcode_code      = $request->gift_code;
        $myGiftCode->giftcode_price     = $request->price;
        $myGiftCode->giftcode_surplus   = $request->price;
        $myGiftCode->giftcode_sale_date = Carbon::now();
        $myGiftCode->giftcode_date_expire   = $request->expire_date;        
        $myGiftCode->giftcode_type          = $request->giftCardType;
        $myGiftCode->giftcode_status        = 1;
        $myGiftCode->giftcode_loyalty_referral = $request->loyalty_referral;
        //Payment Type
        $myGiftCode->giftcode_payment_type = $request->giftCardPaymentType;
         //check phone
        $check_pos_customer = PosCustomer::where('customer_phone',$request->customer_phone)
                                        ->where('customer_place_id','=',$this->getCurrentPlaceId())->first();
        if($check_pos_customer){
        $myGiftCode->giftcode_customer_id = $check_pos_customer->customer_id;
        }else{
            $idCustomer = PosCustomer::where('customer_place_id','=',$this->getCurrentPlaceId())->max('customer_id') +1;
                       
                        $PosCustomer = new PosCustomer ;
                        $PosCustomer->customer_id = $idCustomer;
                        $PosCustomer->customer_place_id = $this->getCurrentPlaceId();
                        $PosCustomer->customer_history = "";
                        $PosCustomer->customer_fullname = $request->customer_fullname;
                        $PosCustomer->customer_phone = $request->customer_phone;
                        // $PosCustomer->customer_country_code = $request->country_code; customer_country_code = null
                        $PosCustomer->customer_email = $request->customer_email;
                        $PosCustomer->customer_gender = 'null';
                        $PosCustomer->customer_birthdate = 'null';

                        $pos_customertag = PosCustomertag::select('customertag_id')->where('customertag_place_id',$this->getCurrentPlaceId())->first();
                        // $customer_customertag_id =  customertag_id.min;
                        
                        if($pos_customertag){
                        $PosCustomer->customer_customertag_id = $pos_customertag->customertag_id; // 
                        } 

                        $PosCustomer->customer_address = $request->customer_address;
                        $PosCustomer->customer_status = 1;
                        // echo $PosCustomer; die();
                        $PosCustomer->save();
                        if($PosCustomer)
                        $request->session()->flash('status', 'Insert Customer Success!');
                        else    $request->session()->flash('status', 'Edit Customer Error!');

                        $myGiftCode->giftcode_customer_id   = $idCustomer;
        }

        //giftcode_Billing_Payment_Information 'Client Name;Client Phone;Street Address;State;Country;Client Email;City;Zip'
        $giftcode_Billing_Payment_Information = $request->customer_fullname.';'.$request->customer_phone.';'.$request->customer_address.';'.$request->customer_state.';'.$request->customer_country.';'.$request->customer_email.';'.$request->customer_city.';'.$request->customer_zip;
        
        $myGiftCode->giftcode_Billing_Payment_Information = $giftcode_Billing_Payment_Information;

        
        if($request->giftCardPaymentType == 2){
            if($request->card_type && $request->card_number && $request->name_on_card){
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
                  if (($tresponse != null) && ($tresponse->getResponseCode()=="1"))
                  {
                    // dd($tresponse);
                    $myGiftCode->giftcode_Authorize_Net = $card_type.';'.'*********'.$card_number.';'.$name_on_card;
                    $myGiftCode->save();
                    // echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    // echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                    return redirect('/marketing/giftcards')->with('message', 'Success!');
                  }
                  else
                  {
                    return redirect()->back()->with('message', 'Charge Credit Card ERROR!');
                  }
                }
                else
                {
                  return redirect()->back()->with('message', 'Charge Credit Card ERROR!');
                }
            }
            return redirect()->back()->with('message', 'Charge Credit Card ERROR!');
        }
        else
        {
            $myGiftCode->save();
            return redirect('/marketing/giftcards')->with('message', 'Success!');
        }

    //end code tri
    }

    /**
     * random giftcard code
     * @param int $placeId
     * @return string giftcard code( 8 characters long include alphanumeric)
     */
    private function __randomGiftcardCode($placeId){
        return 'g'.$placeId.substr(uniqid(),6);
    }

    //load datatable
    public function loadData(){
        $giftcart = PosGiftcode::select('pos_giftcode.giftcode_code','pos_giftcode.giftcode_price','pos_giftcode.giftcode_surplus','pos_giftcode.giftcode_loyalty_referral','pos_giftcode.giftcode_payment_type','pos_customer.customer_fullname','pos_customer.customer_phone','pos_giftcode.giftcode_date_expire','pos_giftcode.created_at')
        ->leftjoin('pos_customer',function($join){ 
            $join->on('pos_customer.customer_id','pos_giftcode.giftcode_customer_id')
              ->on('pos_customer.customer_place_id','pos_giftcode.giftcode_place_id');
                                        })
        ->where('pos_giftcode.giftcode_status',1)
        ->where('giftcode_place_id',$this->getCurrentPlaceId())
        ->get();
        
        return Datatables::of($giftcart)
        ->addColumn('action',function($giftcart){
            return '<a href="'.route('viewGiftCard',$giftcart->giftcode_code).'" class="btn btn-sm btn-default"><i class="fa fa-eye"></i></a>
            <a href="#" class="btn btn-sm btn-default deleteColumn_giftcart" data="'.$giftcart->giftcode_code.'"><i class="fa fa-trash-o"></i></a>';
        })
        ->editColumn('giftcode_date_expire',function($giftcart){ 
            return format_date($giftcart->giftcode_date_expire);
        })
        ->editColumn('created_at',function($giftcart){ 
            return format_datetime($giftcart->created_at);
        })
        ->editColumn('giftcode_type',function($giftcart){
            return $giftcart->giftcode_type == 1 ? 'VIP' : 'Nomal';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    // delete culumn == ajax update giftcode_status = 0
    public function deleteColumn(Request $request){
        $gc = $request->code;
        if($gc){
            $giftcart = PosGiftcode::where('giftcode_code',$gc)->update(['giftcode_status'=>0]);
            //$giftcart->giftcode_status = 0;
            // $giftcart->save();
            return 1;
           
        }else{
            return 0;
        }

    }

    // public function test(){
    //  echo $this->getCurrentPlaceId();   
    // }
}
