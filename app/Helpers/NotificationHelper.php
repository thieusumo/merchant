<?php
namespace App\Helpers;
use App\Helpers\DeviceHelper;
use App\Models\PosNotification;
use App\Models\PosUser;
use App\Http\Controllers\Controller;

class NotificationHelper extends Controller
{	
	/**
     * send notification One signal
     * @param  string $title     
     * @param  string $message    
     * @param  string $url       
     * @param  int $placeId       
     * @return json
     */
    public static function send($title,$message,$url,$placeId) { 
            try {
                
            $notificationHelper = new self;
            /**
             * Array
             */
            $player_ids = $notificationHelper->getArrayListDeviceToken($placeId);

            $heading = array(
                "en" => $title,
            );  

            $content = array(
                "en" => $message,
            );

            $fields = array(
                'app_id' => env('ONE_SIGNAL_APP_ID'),
                'include_player_ids' => $player_ids,
                'headings' => $heading,
                'contents' => $content,
                'url' => $url,
                "chrome_web_icon" =>  asset('images/logo_60x17.png')  ,
            );
            
            $fields = json_encode($fields);

            $headers = [
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic '.env('ONE_SIGNAL_TOKEN'),
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('ONE_SIGNAL_URL'));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
            $response = curl_exec($ch);
            curl_close($ch);

            $notificationHelper->saveNotification($title,$message,$url,$placeId);
            \Log::info($response);
            return $response;
            } catch (\Exception $e) {
                \Log::info($e);
                return 'Failed to push notification!';
            }
    }

    private function saveNotification($title, $message, $url, $placeId){
        $notify = new PosNotification;
        $notify->notification_place_id = $placeId;
        $notify->notification_message = $message;
        $notify->notification_link = $url;
        $notify->save();
    }

    private function getArrayListDeviceToken($placeId){
        $user = PosUser::select('web_device_token')
                        ->where('user_place_id',$placeId)
                        ->where('user_status',1)
                        ->where('enable_status',1)
                        ->get();

        $result = [];
        foreach ($user as $value) {
            try {
                $arr = explode(";", $value->web_device_token);
                foreach ($arr as $arrValue) {
                    if($arrValue){
                        $result[] = $arrValue;
                    }
                }
            } catch (\Exception $e) {
                //continue
            }
            
        }
        return $result;
    }

}