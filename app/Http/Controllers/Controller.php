<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\Models\PosPlace;
use App\Models\PosActionLog;
use App\Models\PosLogException;

class Controller extends BaseController
{   
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public static function getCurrentPlaceId(){        
        return \Session::get('current_place_id');
    }
    
    protected function setCurrentPlaceId($place_id){
         \Session::put('current_place_id', $place_id );
         $place = PosPlace::where('place_id',$place_id)->where('place_status',1)->first();
         \Session::put('current_place_ip_license', $place->place_ip_license );
    }

    protected function getCurrentPlaceIpLicense(){
        return \Session::get('current_place_ip_license');
    }

    protected function actionLogTable($action){       

        $posActionLog = new PosActionLog();
        $logId = $posActionLog
                ->where('log_place_id','=', $this->getCurrentPlaceId())
                ->max('log_id');
        $logId = intval($logId)+1;
        $posActionLog->log_id = $logId;
        $posActionLog->log_place_id = $this->getCurrentPlaceId();
        $posActionLog->log_user_id = Auth::user()->user_id;
        $posActionLog->log_action = $action;
        $date = date('Y-m-d H:i', time());
        $gmtDateTime = gmdate('Y-m-d H:i',strtotime($date));
        $posActionLog->log_datetime = $gmtDateTime;
        $saveLog = $posActionLog->save();
        if(!$saveLog){
                return false;
        }else{
                return true;
        }
        // $logId = 
}
    protected function actionLogException($exception, $valueNew, $valueOld = null, $error = null, $user = null){

        DB::beginTransaction();
        try{
                $placeModel = new PosPlace();
                if($user == null){
                    $user = Auth::user();
                }

                $place = $placeModel->selectRaw('place_name as name, place_phone as phone')
                                        ->where('place_id','=',$user->user_place_id)
                                        ->first();
                $logExceptionModel = new PosLogException();
                $logId = $logExceptionModel->where('log_place_id','=',$this->getCurrentPlaceId())->max('log_id');
                $logExceptionModel->log_id = intval($logId) + 1;
                $logExceptionModel->log_place_id = $user->user_place_id;
                $logExceptionModel->log_place_name = $place->name;
                $logExceptionModel->log_place_phone = $place->phone;
                $logExceptionModel->log_user_id = $user->user_id;
                $logExceptionModel->log_user_fullname = $user->user_fullname;
                $logExceptionModel->log_user_phone = $user->user_phone;
                $logExceptionModel->log_value_new = json_encode($valueNew);
                $logExceptionModel->log_exception = $exception;
                $logExceptionModel->log_domain = $_SERVER['REQUEST_URI'];
                if($valueOld != null){
                        $logExceptionModel->log_value_old = json_encode($valueOld);
                }
                if($error != null){
                        $logExceptionModel->log_error = $error;
                }
                $logExceptionModel->created_at = date("Y-m-d H:i:s");
                $logExceptionModel->log_status = 1;
                $saveLog = $logExceptionModel->save();
                if($saveLog){
                        DB::commit();
                        return true;
                }else return false;
        }catch(\Exception $e){
                DB::rollBack();
                return false;
        }
    }
    /**
     * get Place Id use for API
     * @return int PlaceId
     */
    protected function getPlaceId(){
        return Auth::user()->user_place_id;
    }
}
