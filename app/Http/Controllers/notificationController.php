<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PosNotification;
use yajra\Datatables\Datatables;
class NotificationController extends Controller
{
    public function notification()
    {
        return view('notification.notification');
    }
    public function postNotification(Request $request)
    {
        // dd($request->all());
        $noti=new PosNotification;
        $noti->notification_place_id=$this->getCurrentPlaceId();
        $noti->notification_type=$request->type_noti;
        $noti->notification_message=$request->message?$request->message:"No Message!";
        $noti->notification_link=$request->link;
        $noti->notification_readed=0;
        $noti->notification_receiver_place_id='1,2,3,4';
        $noti->notification_user_phone=$request->user_phone;
        $noti->created_at=date('Y-m-d H:i:s');
        $noti->updated_at=date('Y-m-d H:i:s');
        $noti->save();
        return $noti;
    }
    
    public function editReadNotification(Request $re)
    {
            $id=$re->id;
	    	$noti=PosNotification::find($id);
	    	$noti->notification_readed=1;
	    	$noti->save();
            $link=$noti->notification_link;
	    	return redirect($link);
    }

    public function allNotification()
    {
    	
    	return view("notification.show_notice");
    }
    public function getNotification(Request $request)
    {
        $search_worker_status = 1;

        $worker_list = PosNotification::where('notification_place_id', $this->getCurrentPlaceId())
        ->orderBy('notification_readed','desc')->get();

        
        if( $request->search_worker_status != "")
        {
           $worker_list=PosNotification::where('notification_place_id', $this->getCurrentPlaceId())
           ->where('notification_readed',$request->search_worker_status)->get();
        }

        return Datatables::of($worker_list)
            ->editColumn('created_at', function ($row) 
            {
                return format_date($row->created_at);
            })
            ->editColumn('notification_readed', function ($row) 
            {
                if($row->notification_readed==0)
                {
                	return "<span class='badge badge-primary' style='font-size: x-small'>Unread</span>";
                }
                else{
                	return "<span class='badge bg-green' style='font-size: x-small'>Readed</span>";
                }
            })
            ->addColumn('action',function($row){
            	return "<a href='".url('/notification/edit-read-notification?id=').$row->id."' link='".url("/").$row->notification_link."' id='".$row->id."' class='click-notifice-class btn btn-sm btn-default' ><i class='fa fa-eye'></i></a>" ;
            })
            ->rawColumns(['notification_readed','action'])//vẽ lại nhưng column đã được edit với dạng HTML
            ->make(true);
    }

    public function get5Notification(Request $request){
        $skip = $request->skip;
        $skip = $skip*10;
        $notification = PosNotification::select('id','notification_message','notification_link','notification_readed')
                        ->where('notification_place_id',$this->getCurrentPlaceId())
                        ->skip($skip)
                        ->take(10)
                        ->orderBy('id','desc')
                        ->get();
        $count = PosNotification::select('id')
                        ->where('notification_place_id',$this->getCurrentPlaceId())
                        ->where('notification_readed',0)
                        ->count();

        return response()->json(['status'=>1,'data'=>$notification,'count'=>$count]);
    }

    public function read(Request $request){
        if($request->id){
            PosNotification::where('notification_place_id',$this->getCurrentPlaceId())
                        ->where('id',$request->id)
                        ->update(['notification_readed'=>1]);

            return response()->json(['status'=>1],200);
        }
    }

}
