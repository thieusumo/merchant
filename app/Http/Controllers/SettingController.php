<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PosPlace;
use App\Helpers\ImagesHelper;
use Validator;
use App\Http\Requests;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use GuzzleHttp\Client;
use Session;
use app\Helpers\GeneralHelper;
class SettingController extends Controller {
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

    public function configBusinessStore()
    {
        $data=[
            'headNumber'=>GeneralHelper::all()
        ];
        $place_list = PosPlace::where('place_id',$this->getCurrentPlaceId())->first();
        $place_actiondate = json_decode($place_list->place_actiondate,true);
        return view('setting.business-store',compact('place_list','place_actiondate','data'));
    }
    
    public function configMarketing()
    {
        return view('setting.marketing');
    }
    
    public function saveBusinessStore(Request $request)
    {
        // dd($request->all());
         $rules = [
            'place_logo' => 'mimes:jpeg,jpg,png,gif|max:2000',
            'place_favicon' => 'mimes:jpeg,jpg,png,gif|max:2000',
            // 'place_name' => 'required',
            'place_address' => 'required',
            // 'place_taxcode'  => 'required|numeric',
            // 'place_email' => 'email',
            'place_phone' => 'required',
            // 'place_worker_mark_bonus' => 'required|numeric',
            // 'place_interest' => 'required|numeric',
            // 'place_latlng' => 'required',
         ];
         $messages = [
            'place_logo.mimes' => 'Format file not allow',
            'place_logo.max' => 'File limited 2M',
            'place_favicon.mimes' => 'Format file not allow',
            'place_favicon.max' => 'File limited 2M',
            'place_name' => 'Please enter name',
            'place_address' => 'Please enter address',
            'place_taxcode.required' => 'Please enter tax',
            'place_taxcode.numeric' => 'Please enter number',
            // 'place_email.required' => 'Please enter an email',
            // 'place_email.email' => 'Please enter an email',
            'place_phone.required' => 'Please enter phone',
            'place_phone.numeric' => 'Please enter number',
            'place_worker_mark_bonus.required' => 'Please enter Price floor',
            'place_worker_mark_bonus.numeric' => 'Please enter number',
            // 'place_interest.numeric' => 'Please enter number',
            // 'place_interest.required' => 'Please enter Interest',
            // 'place_latlng' => 'Please enter coordinates'
         ];
         $validator = Validator::make($request->all(), $rules, $messages);

         // if($request->place_logo_hidden == "" && $request->place_logo == "" )
         // {
         //    $validator->after(function ($validator) {
         //        $validator->errors()->add('place_logo.required', 'Please choose logo');
         //    });
         // }

         if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        else{
            $place_actiondate = json_decode($request->place_actiondate,true);
            $weekday = ['mon','tue','wed','thur','fri','sat','sun'];
            foreach( $weekday as $day)
            {
                $time_start_day = "time_start_".$day;

                $time_end_day = "time_end_".$day;

                $work_day = "work_".$day;

                if($request->$work_day == 0)
                {
                    $closed = true;
                }
                if($request->$work_day == 1)
                {
                    $closed = false;
                }

                $place_actiondate[$day]['start'] =  format_time24h($request->$time_start_day);

                $place_actiondate[$day]['end'] = format_time24h($request->$time_end_day);

                $place_actiondate[$day]['closed'] = $closed;
            }
            $place_actiondate = json_encode($place_actiondate);


            if(isset($request->place_logo))
            {
               $place_logo = ImagesHelper::uploadImage($request->place_logo,'setting',$this->getCurrentPlaceIpLicense() );
            }
            else
            {
                 $place_logo = $request->place_logo_hidden;
            }
            if(isset($request->place_favicon))
            {
               $place_favicon = ImagesHelper::uploadImage($request->place_favicon,'setting',$this->getCurrentPlaceIpLicense() );
            }
            else
            {
                 $place_favicon = $request->place_favicon_hidden;
            }
            //dd($place_logo);
            $arr = [
                'place_logo' => $place_logo,
                'place_favicon'=> $place_favicon,
                'place_name'=>$request->place_name,
                'place_address' => $request->place_address,
                'place_email' => $request->place_email,
                'place_phone' => $request->place_phone,
                'place_website' => $request->place_website,
                'place_worker_mark_bonus' => $request->place_worker_mark_bonus,
                'place_interest' => $request->place_interest,
                'place_latlng' => $request->place_latlng,
                'place_actiondate' => $place_actiondate,
                'place_taxcode' => $request->place_taxcode,
                'place_country_id'=> $request->place_country_id,
                'hide_service_price'=>$request->hide_service_price,
                'place_description'=>$request->description
            ];
            //dd($arr);
            PosPlace::where('place_id',$this->getCurrentPlaceId())
                    ->update($arr);

            return back()->with('message','Change Setting Success!');
        }
    }
    ///-- view setting.system
    public function configSystem()
    {
        $data['post_place'] = PosPlace::select('place_email_host','place_email_port','place_email_encryption','place_email','place_email_password','place_authorize_payment','place_social_network_account')->where('place_id',$this->getCurrentPlaceId())->first();
        // exlode ; place_authorize_payment
        $str_p_a_p = $data['post_place']->place_authorize_payment;
        if($str_p_a_p!=""){
            $explode_p_a_p = explode(";", $str_p_a_p);
            $data['api_login_id'] = $explode_p_a_p[0];
            $data['transaction_key'] = $explode_p_a_p[1];
            $data['test_mode'] = $explode_p_a_p[2];
        }
        
        // exlode ; place_social_network_account
        $str_s_n_a = $data['post_place']->place_social_network_account;        
        if($str_s_n_a){
            $explode_s_n_a = explode(";", $str_s_n_a);
            $data['yelp_id'] = $explode_s_n_a[0];
            $data['yelp_url'] = $explode_s_n_a[1];
            $data['facebook_id'] = $explode_s_n_a[2];
            $data['facebook_url'] = $explode_s_n_a[3];
            $data['google_id'] = $explode_s_n_a[4];
            $data['google_url'] = $explode_s_n_a[5];
        }
        

        return view('setting.system',$data);
    }

    // post SMTP ServerSetting - view setting.system
    public function postServerSetting(Request $request){
        $this->validate($request,[
            'host'=>'required',
            'port'=>'required',
            // 'auth_username'=>'required',
            // 'auth_password'=>'required',
            // 'send_test_email'=>'required|email',            
        ],[

        ]);
        $pos = PosPlace::where('place_id',$this->getCurrentPlaceId())->first();
        $pos->place_email_host = $request->host;
        $pos->place_email_port = $request->port;
        $pos->place_email_encryption = $request->encryption;
        $pos->place_email = $request->auth_username;
        if(isset($request->auth_password))
        $pos->place_email_password = $request->auth_password;
        // $pos->place_email_send_test = $request->send_test_email;
        $pos->save();
        
        return back()->with('message',"Update SMTP SERVER SETTING Success!");
    }
    // post postAuthorize - view setting.system
    public function postAuthorize(Request $request){
        $this->validate($request,[
            'api_login_id' => 'required',
            'transaction_key' => 'required',
        ],[

        ]);
        $pos = PosPlace::where('place_id',$this->getCurrentPlaceId())->first();
        $authorize_payment = $request->api_login_id.';'.$request->transaction_key.';'.$request->test_mode;
        $pos->place_authorize_payment = $authorize_payment;
        $pos->save();
        return back()->with('message',"Update AUTHORIZE.NET PAYMENT Success!");
    }
    // post postSocialNetworkAccount - view setting.system
    public function postSocialNetworkAccount(Request $request){
        // dd($request->all());
        $this->validate($request,[
            // 'yelp_id' =>'required',
            // 'facebook_id' =>'required',
            // 'google_id' =>'required',
            // 'yelp_url' =>'required',
            // 'facebook_url' =>'required',
            // 'google_url' =>'required',
        ],[

        ]);
        $result=$this->PushApiSocialNetworkAccount($request->all());
        $pos = PosPlace::where('place_id',$this->getCurrentPlaceId())->first();
        $social_network_account = $request->yelp_id.';'.$request->yelp_url.';'.$request->facebook_id.';'.$request->facebook_url.';'.$request->google_id.';'.$request->google_url;
        $pos->place_social_network_account = $social_network_account;
        $pos->save();
        // dd($result);
        return back()->with('message',"Update SOCIAL NETWORK ACCOUNT Success!");

    }

    private function PushApiSocialNetworkAccount ($input,$url = ""){
        // dd($input);
        $url = env("REVIEW_SMS_API_URL")."config?first_name=".Session::get('current_user_id')."&g_id=".$input['google_id']."&g_url=".$input['google_url']."&y_id=".$input['yelp_id']."&y_url=".$input['yelp_url']."&f_url=".$input['facebook_url']."&f_id=".$input['facebook_id']."&merchant_id=".$this->getCurrentPlaceId();

        $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));

        $client = new Client([
            // 'timeout'  => 5.0,            
        ]);
        //$url="http://user.tag.com/api/v1/receiveTo";
        $check_response = $client->request('GET', $url ,[
                    'headers' => [
                        'Authorization' => 'Bearer ' . env("REVIEW_SMS_API_KEY"),
                                ],
                ]);
        $method="";
        $result_check = (string)$check_response->getBody();
        $result_check = json_decode($result_check);
        // dd($result_check);
        if($result_check->status==0)
        {
            $method='POST';
        }
        else{
            $method="PUT";
        }

        $response = $client->request($method, $url ,[
                    'headers' => [
                                    'Authorization' => 'Bearer ' . env("REVIEW_SMS_API_KEY"),
                                ],
                ]);
        $resp =  (string)$response->getBody();
        //echo $resp;
        return $resp;

    }
    //send test email
    public function sendEmailTest(Request $request){

        //return $request->all();

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $request->host;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $request->auth_username;                 // SMTP username
            $mail->Password = $request->auth_password;                           // SMTP password
            $mail->SMTPSecure = $request->encryption;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $request->port;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($request->auth_username, 'Mailer Test');
             $mail->addAddress($request->email_test, 'Tester Mail');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Testing email of sever';
            $mail->Body    = 'Just is test send mail function of sever<br>If you received this email, mean everything <span style="color:red">OK</span><br>Thanks!';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return 'Message has been sent';
        } catch (Exception $e) {
            \Log::info('Mailer Error: ' . $mail->ErrorInfo);
            return 'Message could not be sent.';
        }

    }
    
}
