<?php
namespace App\Http\Controllers;

use App\Models\PosGiftcode;
use Carbon\Carbon;
use Validator;
use yajra\Datatables\Datatables;
use App\Models\PosCustomer;
use App\Models\PosCustomertag;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use net\authorize\util\Mapper;
class AuthorizeController extends Controller
{
	public function chargeCreditCard(Request $request)
    {

    	// dd($request->all());
    	// dd($request->all());
        
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
        // $myGiftCode->giftcode_Authorize_Net = $card_type.';'.'*********'.$card_number.';'.$name_on_card;
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
//----------------------------------------------autho----------------------------------------------
                // Common setup for API credentials
                $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                $merchantAuthentication->setName('9Y8t3Xy4mn8');
                $merchantAuthentication->setTransactionKey('4dnbPW7w8849PZc2');
                $refId = 'ref'.time();
        // Create the payment data for a credit card
                  $creditCard = new AnetAPI\CreditCardType();
                $creditCard->setCardNumber($request->card_number);
                  // $creditCard->setExpirationDate( "2038-12");
                $expiry = $request->exporation_date_card;
                $creditCard->setExpirationDate($expiry);
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
                  // $myGiftCode->giftcode_Authorize_Net = $card_type.';'.'*********'.$card_number.';'.$name_on_card;
                  $tresponse = $response->getTransactionResponse();
                  if (($tresponse != null) && ($tresponse->getResponseCode()=="1"))
                  {
                    $myGiftCode->giftcode_Authorize_Net = $card_type.';'.'*********'.$card_number.';'.$name_on_card;
                    // $card_number = substr($request->card_number, -4);
                    echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                  }
                  else
                  {
                    echo "Charge Credit Card ERROR :  Invalid response\n";
                  }
                }
                else
                {
                  echo  "Charge Credit Card Null response returned";
                }
 //------------------------------------------------end autho----------------------------------------------------
            }
            else return 404;
        }

       
        $myGiftCode->save();
        // if($myGiftCode)
        //         $request->session()->flash('status', 'Insert gift Success!');
        // else    $request->session()->flash('status', 'Edit gift Error!');
        
        // return view('marketing.giftcards');
        // $myGiftCode->
        // return redirect('/marketing/giftcards');
    }
}