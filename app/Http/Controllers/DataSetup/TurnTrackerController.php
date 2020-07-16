<?php

namespace App\Http\Controllers\DataSetup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PosWorker;
use App\Models\PosCateservice;
use App\Models\PosWorkerCateservice;
use App\Models\PosPlace;

class TurnTrackerController extends Controller
{
    public function index(){
        $data['worker'] = PosWorker::select('worker_id','worker_nickname','worker_avatar')
                                            ->where('worker_place_id',$this->getCurrentPlaceId())
                                            ->where('worker_status',1)     
                                            ->where('enable_status',1)                                       
                                            ->get();                                      
        $data['cateservice'] = PosCateservice::select('cateservice_id','cateservice_name')
                                            ->where('cateservice_place_id',$this->getCurrentPlaceId()) 
                                            ->where('cateservice_status',1)
                                            ->get();                                            

    	return view('datasetup.turn_tracker',$data);
    }

    public function getListTurnTracker(){
        $workerCate = PosWorkerCateservice::select('ws_id','ws_turn','ws_worker_id','ws_cateservice_id','cateservice_name','worker_nickname')
                                            ->where('ws_place_id',$this->getCurrentPlaceId())
                                            ->where('ws_status',1)
                                            ->join('pos_worker',function($joinWorker){
                                                $joinWorker->on('worker_place_id','ws_place_id')
                                                ->on('ws_worker_id','worker_id');
                                            })
                                            ->join('pos_cateservice',function($joinCate){
                                                $joinCate->on('cateservice_place_id','ws_place_id')
                                                ->on('ws_cateservice_id','cateservice_id');
                                            })
                                            ->get();
        $result = [
            'success' => true,
            'data' => $workerCate
        ];
        return json_encode($result);
    }
    /**
     * ajax save in PosWorkerCateservice
     * @param  array $request->arrCreate && $request->arrUpdate
     * @return json
     */
    public function saveListTurnTracker(Request $request){
        $checkSuccess = '';
        if($request->arrCreate){
            $workerCateId = PosWorkerCateservice::where('ws_place_id',$this->getCurrentPlaceId())->max('ws_id')+1;
            foreach ($request->arrCreate as $value) {
                $arr = [
                    'ws_id' => $workerCateId,
                    'ws_place_id' => $this->getCurrentPlaceId(),
                    'ws_worker_id' => $value['workerId'],
                    'ws_cateservice_id' => $value['cateserviceId'],
                    'ws_turn' => $value['turn'],
                    'ws_status' => 1,
                ];
                // dd($arr);
                PosWorkerCateservice::create($arr);
                $workerCateId++;
            }
            $checkSuccess = true;
        }

        if($request->arrUpdate){
            foreach ($request->arrUpdate as $value) {
                $arr = [
                    'ws_worker_id' => $value['workerId'],
                    'ws_cateservice_id' => $value['cateserviceId'],
                    'ws_turn' => $value['turn'],
                ];
                // dd($arr);
                PosWorkerCateservice::where('ws_id',$value['id'])
                                    ->where('ws_place_id',$this->getCurrentPlaceId())
                                    ->update($arr);
            }
            $checkSuccess = true;
        }

        if($checkSuccess == true){
            $result = [
                'success' => true          
            ];
        } else {
            $result = [
                'error' => true          
            ];
        }
        
        return json_encode($result);
    }
    /**
     * ajax update status in PosWorkerCateservice
     * @param  only $request->id
     * @return json
     */
    public function delete(Request $request){
        if($request->id){
            PosWorkerCateservice::where('ws_id',$request->id)
                                ->where('ws_place_id',$this->getCurrentPlaceId())
                                ->update(['ws_status'=>0]);
            $result = [
                'success' => true          
            ];
            return json_encode($result);
        }
    }

    public function getOptionTurnTracker(){
        $place = PosPlace::select('place_id','place_turn_option')
                        ->where('place_id',$this->getCurrentPlaceId())
                        ->first();
        $result = [
            'success' => true,
            'data'=>$place
        ];
        return json_encode($result);      
    }

    public function postOptionTurnTracker(Request $request){
        if($request->valueOptionTurn){
            PosPlace::where('place_id',$this->getCurrentPlaceId())
                    ->update(['place_turn_option'=>$request->valueOptionTurn]);
            $result = [
                'success' => true
            ];
            return json_encode($result); 
        }       
    }
}
