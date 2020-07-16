<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use Hash;
use App\User;
use App\Models\MainUser;
use Session;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    	$user_phone = $request->user_phone;
    	$user_password = $request->user_password;
        $credentials = $request->only('user_phone', 'user_password');
        $checkUserPhone = User::where('user_phone',$user_phone)->count();

        if($checkUserPhone == 0){
        	return response()->json(['status' => '0','msg'=>'Phone is wrong!'], Response::HTTP_BAD_REQUEST);
        }else{
	        $checkUserPhone = User::where('user_phone',$user_phone)->first();

	        //Check lock user
	        if($checkUserPhone->user_lock_status){
		        return response()->json(['status' => '0','msg'=>'You have entered the wrong password 5 times. Account will temporality lock. Please contact 888 840 8070 to support.'], Response::HTTP_BAD_REQUEST);
	        }
	        if(Hash::check($user_password, $checkUserPhone->user_password)) {
		        
		    } else {
		    	$user_wrong_password_number = $checkUserPhone->user_wrong_password_number +1;
		    	User::where('user_phone',$user_phone)
		    		->update(
		    			['user_wrong_password_number' => $user_wrong_password_number]);
		    	if($user_wrong_password_number==5){
		    		User::where('user_phone',$user_phone)
		    		->update(
		    			['user_lock_status' => 1]);
		    	}
		    	//Check old password
		    	if(Hash::check($user_password, $checkUserPhone->user_old_password)) 
		    	{
		        	return response()->json(['status' => '0','msg'=>'You have imported old password!'], Response::HTTP_BAD_REQUEST);
		    	}

		    	//Check number wrong password
		    	if($checkUserPhone->user_wrong_password_number == 0){
		    		return response()->json(['status' => '0','msg'=>'Password is wrong!'], Response::HTTP_BAD_REQUEST);
		    	}else{

		    		return response()->json(['status' => '0','msg'=>'You enter the wrong password '.$user_wrong_password_number.' times'], Response::HTTP_BAD_REQUEST);
		    	}	
		        
		    }
        }

        if (($token = JWTAuth::attempt($credentials))) {
         // Login Success
        	User::where('user_phone',$user_phone)
		    		->update(['user_wrong_password_number' => 0 , 'user_sms_forgot_number'=>0]);

		    //update device token
		    $saveDeviceToken = new \App\Http\Controllers\API\UserController;
		    $saveDeviceToken->saveDeviceToken($request->device_key);

		    $user = Auth::user();
		    $data = [
		    	'user_nickname' => $user->user_nickname,
		    	'user_phone' => $user->user_phone,
		    	'user_email' => $user->user_email,
		    	'user_avatar' => $user->user_avatar,
		    ];
        	return response()->json(['token' => $token, 'user' => $data], Response::HTTP_OK);
        }
        
    }

    /**
     * Forgot Password
     * 
     * @param Request $request
     */
    public function fogotPassword(Request $request){
    	$user_phone = $request->user_phone;
    	$checkPhone = User::where('user_phone',$user_phone)->first();

    	if(!$checkPhone){
    		return response()->json(['status' => '0','msg'=>'Phone number does not exist!'], Response::HTTP_OK);
    	}
    	//If user locked return error
        if($checkPhone->user_sms_forgot_number >= 3){
        	return response()->json(['status' => '0','msg'=>'You have received 3 SMS. Account locked. Please contact 888 840 8070 to support.'], Response::HTTP_OK);
        }

        //If check phone success
        if($checkPhone->user_id > 0){
        	$basic  = new \Nexmo\Client\Credentials\Basic(env("NEXMO_API_KEY") ,env("NEXMO_API_SECRET"));
			$client = new \Nexmo\Client($basic);

			$six_digit_random_number = mt_rand(1000, 9999);
	        $sms_content_template = "DEG REPORT Forgot Password Code: ".$six_digit_random_number;
	        //IF max 3 sms -> update Lock user
		    if($checkPhone->user_sms_forgot_number>=3){
		    	// User::where('user_phone',$user_phone)
		    	// 	->update(
		    	// 		['user_lock_status' => 1]);
		    	return response()->json(['status' => '0','msg'=>"You have received 3 sms but haven't changed the password. Please contact 888 840 8070 to support."], Response::HTTP_OK);
		    }

	        //Update number send sms (max 3)
	        User::where('user_phone',$user_phone)
		    		->update([
		    				'user_sms_forgot_number' => ($checkPhone->user_sms_forgot_number + 1),
		    				'user_code_forgot' => $six_digit_random_number,
		    				]);

		   	
		    // SEND SMS
			$message = $client->message()->send([
			    'to' => $user_phone,
			    'from' => 'DEG',
			    'text' => $sms_content_template
			]);

			return response()->json(['status' => '1','msg'=>'Send Code Success!','code'=>$six_digit_random_number], Response::HTTP_OK);
	        /*$url_event = '/sendsms';

	        $url = env("SMS_API_URL").$url_event;

	        $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
	        $client = new Client([]);
	        $six_digit_random_number = mt_rand(1000, 9999);
	        $sms_content_template = "DEG REPORT Forgot Password Code: ".$six_digit_random_number;


	        $response = $client->request('POST', $url ,[
	                    'multipart' => [
	                            [
	                                'name' => 'content',
	                                'contents' => $sms_content_template,
	                            ],
	                            [
	                                'name' => 'phone',
	                                'contents' => $user_phone,
	                            ],
	                            [
	                                'name' => 'from',
	                                'contents' => "nexmo",
	                            ],
	                            [
	                                'name' => 'merchant_id',
	                                'contents' => 0,
	                            ],     
	                    ],
	                    'headers' => [
	                        'Authorization' => 'Bearer ' .env("REVIEW_SMS_API_KEY"),
	                                ],
	                ]);
	                
	         $resp =  json_decode($response->getBody());
	        $resp = '{"status": 1,"messages": "Send successful"}';
			$resp = json_decode($resp, true); 
	        if($resp['status']){
	        	return response()->json(['status' => '1','msg'=>'Send Code Success!','code'=>$six_digit_random_number], Response::HTTP_OK);
	        }*/
        }
        else{
        	return response()->json(['status' => '0','msg'=>'Phone is wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
    
    public function user(Request $request)
    {	
        $user = Auth::user();

        if ($user) {
            return response($user, Response::HTTP_OK);
        }

        return response(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json('You have successfully logged out.', Response::HTTP_OK);
        } catch (JWTException $e) {
            return response()->json('Failed to logout, please try again.', Response::HTTP_BAD_REQUEST);
        }
    }

    public function refresh()
    {
        return response(JWTAuth::getToken(), Response::HTTP_OK);
    }


    public function check_user(Request $request)
    {
    	// return($request->all());
    	$phone=$request->phone;
    	$password=$request->password;
    	$place_id=$request->place_id;
    	$main=MainUser::where("user_phone",$phone)->first();
    	// return $main;
    	$passdb=$main->user_password;
    	// return $passdb;
    	if(Hash::check($password,$passdb)) {
		    $user=User::where("user_place_id",$place_id)->first();
		    $user_phone=$user->user_phone;
		    $user_password=$user->user_password;
    		 if (Auth::attempt(['user_phone' => $user_phone,"user_password"=>$user_password])) {
					return 1;
    		 }
		    // return 1;
        }    	
    }

    /**
     * change Password 
     * @param  Request $request->user_password
     * @param  Request $request->new_user_password
     * @return json
     */
    public function changePassword(Request $request){
    	try {
    		JWTAuth::invalidate($request->input('token'));
    		$user = User::where('user_place_id',$this->getPlaceId())
	    					->where('user_id',Auth::user()->user_id)
	    					->first();

	    	if(Hash::check($request->user_password, $user->user_password)){

		    	$user->user_password = bcrypt($request->new_user_password);
		    	$user->save();

	    		return response()->json(['status'=>1,'msg'=>"Changed successfully!"],200);
	    	} else {
	    		return response()->json(['status'=>0,'msg'=>"Password is wrong!"]); 
	    	}
    	} catch (\Exception $e) {
    		return response()->json('Permission denied!', 401);
    	}
    }
    /**
     * new password
     * @param  Request $request->user_phone 
     * @param  Request $request->user_code_forgot 
     * @param  Request $request->new_user_password
     * @return json
     */
    public function newPassword(Request $request){
    	$validate = Validator::make($request->all(),[
    		'user_phone' => 'required',
    		'user_code_forgot' => 'required',
    		'new_user_password' => 'required',
    	]);

    	$error_array = [];

    	if($validate->fails()){
    		foreach ($validate->messages()->getMessages() as $messages) {
    			$error_array[] = $messages;
    		}
    	}

    	if(count($error_array) > 0){
    		return response()->json(['status'=>0,"msg"=>$error_array],400);
    	}

    	$user = User::where('user_phone',$request->user_phone)->first();

		if(!$user){
			return response()->json(['status'=>0,"msg"=>'Phone does not exist!'],400);
		}

		if($user->user_code_forgot == $request->user_code_forgot){
			
			$user->user_password = bcrypt($request->new_user_password);
    		$user->user_code_forgot = null;
    		$user->save();
    		// update
    		User::where('user_phone',$request->user_phone)
		    		->update(['user_wrong_password_number' => 0 , 'user_sms_forgot_number'=>0 , 'user_lock_status'=>0]);

		    return response()->json(['status'=>1,"msg"=>'Changed Successfully!'],200);
		} else {
			$user->user_code_forgot = null;
			$user->save();

			return response()->json(['status'=>0,"msg"=>'Code does not exist!'],400);
		}

    }

}
