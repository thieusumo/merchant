<?php

namespace App\Http\Controllers\DataSetup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosWorker;
use yajra\Datatables\Datatables;
use App\Models\PosCheckin;
use App\Helpers\GeneralHelper;
use Validator;
use DB;
use Session;
class StaffController extends Controller
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
        return view('datasetup.staffs');
    }

    /**
    *   edit staff table
    *   @param id
    *   @param staff
    *   @return staff table
    */
    
    public function edit($id=0){   
        $worker = PosWorker::where('worker_place_id', $this->getCurrentPlaceId())
                            ->where('worker_id',$id)->first();
        if($worker)
        {
            
            $workerDateOfBirth = format_date_d_m_y($worker->worker_birthday);
            
            return view('datasetup.staff_edit',compact('worker','workerDateOfBirth','id'));
        }

        return view('datasetup.staff_edit',compact('id'));
    }
    

    public function getWorker(Request $request)
    {   
        // dd($request->all());
        // $search_join_date = explode(" - ",$request->search_join_date);


        $search_worker_status = $request->search_worker_status;


        $worker_list = PosWorker::leftjoin("pos_user",function($join){
                                                $join->on("pos_worker.worker_place_id","=","pos_user.user_place_id")
                                                ->on("pos_worker.updated_by","=","pos_user.user_id");
                                        })
                                    // ->where('pos_worker.updated_by','=','pos_user.user_id')
                                    ->where('worker_place_id',$this->getCurrentPlaceId())
                                 ->where('pos_worker.worker_status',1);
                                
        // return $worker_list;
        if($search_worker_status != "")
        {
            $worker_list->where('pos_worker.enable_status',$search_worker_status);
           
        }
        $worker_list->select('pos_worker.*','pos_user.user_nickname')->get();
        //return $worker_list;

        return Datatables::of($worker_list)

            ->editColumn('worker_fullname', function ($row) 
            {
                return $row->worker_firstname." ".$row->worker_lastname ;
            })
            ->editColumn('worker_date_join', function ($row) 
            {
                return format_date($row->worker_date_join);
            })
            ->editColumn('avatar', function ($row) 
            {
                return '<img onerror="this.style.display='."'none'".'" src="'.config('app.url_file_view').$row->worker_avatar.'" width="60" height="40" >' ;
            })
            ->editColumn('worker_phone',function($row){
                return $row->worker_phone;
            })
            ->addColumn('status', function($row){

                $checked="";

                if( $row->enable_status == 1 ){
                    $checked= "checked";
                }
                return "<input id='".$row->worker_id."' type='checkbox' class='js-switch'  check='".$row->enable_status."' " .$checked. "/>";
            })
            ->editColumn('updated_at', function ($row) 
            {
                return format_datetime($row->updated_at)." by ".$row->user_nickname;
            })
            ->addColumn('action', function($row){
                return " <a href='".route('staff',$row->worker_id)."' class='edit-worker btn btn-sm btn-secondary' ><i class='fa fa-pencil fa-lg'></i></a> <a href='javascript:void(0)' class='delete-worker btn btn-sm btn-secondary' id='".$row->worker_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
            })
            ->rawColumns(['status','action','avatar'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        $checked = $request->checked;

        $worker_id = $request->worker_id;
        
        if($checked == 1){

            $enable_status = 0;
        }
        else{
            $enable_status = 1;
        }
        
        PosWorker::where('worker_id',$worker_id)->where('worker_place_id',$this->getCurrentPlaceId())
                    ->update(['enable_status'=>$enable_status]);
                    //return $enable_status;
                    // return 1;
    }

    //DELETE WORKER - BEGIN
    public function deleteWorker(Request $request)
    {
        $worker = PosWorker::where('worker_place_id', $this->getCurrentPlaceId())
                            ->where('worker_id',$request->id)
                            ->update(['worker_status'=> 0 ]);
        if($worker)
        {
            return "Delete customer success!";
        }
        else
        {
            return "Delete customer error!";
        }
    }
    //DELETE WORKER - END

    //SAVE OR EDIT STAFF - BEGI
    public function saveWorker(Request $request)
    {
        $staff_id = $request->staff_id;
        // dd($request->all());
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'nick_name' => 'required',
            // 'phone' => 'required|numeric',
            //'email' => 'email',
            // 'address' => 'required',
            // 'ssn' => 'required',
            // 'start_date' => 'required',
            // 'date_of_birth' => 'required',

        ];
        $messages = [
           
        ];
        $worker = PosWorker::where('worker_id',$staff_id)
                            ->where('worker_place_id', $this->getCurrentPlaceId())
                            ->first(); 
                            // dd($worker);
        if($worker){
            $checkExistEmail = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                    ->where('worker_email', $request->email)
                                    ->where('worker_id','!=',$staff_id)
                                    ->where('worker_status','=','1')
                                    ->first();

            $checkExistPhone = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                    ->where('worker_phone', $request->phone)
                                    ->where('worker_id','!=',$staff_id)
                                    ->where('worker_status','=','1')
                                    ->first();
        } else {
            $checkExistEmail = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                    ->where('worker_email', $request->email)
                                    ->where('worker_status','=','1')
                                    ->first();

            $checkExistPhone = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                    ->where('worker_phone', $request->phone)
                                    ->where('worker_status','=','1')
                                    ->first();
        }

        // dd($checkExistPhone);
        $validator = Validator::make($request->all(), $rules, $messages);
        if(isset($checkExistPhone)){ // PUSH ERROR WHEN EXIST PHONE NUMBER
            $validator->after(function ($validator) {
                $validator->errors()->add('phone.exists', 'Phone number is exist, Please check again!');
            });
        }
        if($request->email != "" && isset($checkExistEmail)){ // PUSH ERROR WHEN EXIST EMAIL
            $validator->after(function ($validator) {
                $validator->errors()->add('email.exists', 'Email is exist, Please check again!');
            });
        }


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        if(!$worker){
            //Add new worker
           if($request->file("avatar")){
                try {
                    $imgLink = \App\Helpers\ImagesHelper::uploadImage($request->file("avatar"), "staff", $this->getCurrentPlaceIpLicense());
                } catch (Exception $e) {
                    return redirect()->back()->withErrors("Error in upload file!");
                }
            } else {
                $imgLink = null;
            }

            $idWorker = PosWorker::where('worker_place_id','=',$this->getCurrentPlaceId())->max('worker_id') +1;
            

            $postWorker = new PosWorker();

            $postWorker->worker_id =  $idWorker;
            $postWorker->worker_place_id = $this->getCurrentPlaceId();
            $postWorker->worker_firstname       =   $request->first_name;
            $postWorker->worker_lastname        =   $request->last_name;
            $postWorker->worker_nickname        =   $request->nick_name;
            $postWorker->worker_avatar          =   $imgLink;
            $postWorker->worker_gender          =   $request->gender=="male" ? 1 : 2;
            $postWorker->worker_birthday        =   format_date_db($request->date_of_birth);
            $postWorker->worker_phone           =   $request->phone;
            $postWorker->worker_email           =   $request->email;
            $postWorker->worker_address         =   $request->address;
            $postWorker->worker_zipcode         =   $request->zip_code?$request->zip_code:"";
            $postWorker->worker_cash_tax        =   $request->w2_tax?$request->w2_tax:"";
            $postWorker->worker_rate            =   $request->hour_rate?$request->hour_rate:"";
            $postWorker->worker_ssn             =   $request->ssn;
            $postWorker->worker_date_join       =   format_date_db($request->start_date);
            $postWorker->worker_percent         =   $request->agreement?$request->agreement:"";
            $postWorker->worker_cash_percent    =   $request->cash?$request->cash:0;
            $postWorker->worker_social_security =   $request->social_security?$request->social_security:0;
            $postWorker->worker_medicare        =   $request->medicare?$request->medicare:0;
            $postWorker->worker_sdi             =   $request->sdi?$request->sdi:0;
            $postWorker->worker_hour_rate       =   $request->hour_rate?$request->hour_rate:0;
            $postWorker->worker_rent_boot       =   $request->rent_boot?$request->rent_boot:0;
            $postWorker->worker_fix_amount      =   $request->fix_amount?$request->fix_amount:0;
            $postWorker->worker_country         =   $request->nationality?$request->nationality:"";
            $postWorker->worker_tip_include_check   =   $request->tip_include_check=="on" ? 1 : 0;
            $postWorker->worker_receipt             =   $request->no_receipt=="on" ? 0 : 1;
            $postWorker->worker_receiptionist       =   $request->receptionist=="on"? 1 : 0;
            $postWorker->worker_cash_draw           =   $request->no_open_cash_drawer=="on"? 0 : 1;
            $postWorker->worker_city                =   $request->city;
            $postWorker->worker_state               =   $request->state;
            $postWorker->worker_status              =   1;
            $postWorker->enable_status              =   $request->status =="active"? 1 : 0;
            $postWorker->created_by              =   $staff_id;
            $postWorker->updated_by            =   $staff_id;
            $postWorker->save();

            
            if($postWorker)
                    $request->session()->flash('status', 'Insert Customer Success!');
            else    $request->session()->flash('status', 'Insert Customer Error!');

            return redirect()->route('list-staff');
        } else {
            //edit worker
            if($request->file("avatar")){
                try {
                    $imgLink = \App\Helpers\ImagesHelper::uploadImage($request->file("avatar"), "staff", $this->getCurrentPlaceIpLicense());
                } catch (Exception $e) {
                    return redirect()->back()->withErrors("Error in upload file!");
                }
            } else {
                $imgLink = $worker->worker_avatar;
            }            
            $updateTable = PosWorker::where('worker_id',$staff_id)
                                        ->where('worker_place_id', $this->getCurrentPlaceId())->update(['worker_firstname'=>$request->first_name,
                            'worker_lastname'=>$request->last_name,
                            'worker_nickname'=>$request->nick_name,
                            'worker_avatar'=>$imgLink,
                            'worker_gender'=>$request->gender=="male" ? 1 : 2,
                            'worker_birthday'=>format_date_db($request->date_of_birth),
                            'worker_phone'=> $request->phone,
                            'worker_email'=>$request->email,
                            'worker_address'=>$request->address,
                            'worker_zipcode'=>$request->zip_code,
                            'worker_cash_tax'=>$request->w2_tax,
                            'worker_rate'=>$request->hour_rate,
                            'worker_ssn'=>$request->ssn,
                            'worker_date_join'=>format_date_db($request->start_date),
                            'worker_percent'=>$request->agreement,
                            'worker_cash_percent'=>$request->cash,
                            'worker_social_security'=>$request->social_security,
                            'worker_medicare'=>$request->medicare,
                            'worker_sdi'=>$request->sdi,
                            'worker_hour_rate'=>$request->hour_rate,
                            'worker_rent_boot'=>$request->rent_boot,
                            'worker_fix_amount'=>$request->fix_amount,
                            'worker_country' => $request->nationality,
                            'worker_tip_include_check'=>$request->tip_include_check=="on" ? 1 : 0,
                            'worker_receipt'=>$request->no_receipt=="on" ? 0 : 1,
                            'worker_receiptionist'=>$request->receptionist=="on"? 1 : 0,
                            'worker_cash_draw'=>$request->no_open_cash_drawer=="on"? 0 : 1,
                            'worker_city'=>$request->city,
                            'worker_state'=>$request->state,
                            'enable_status'=>$request->status =="active"? 1 : 0,
                            ]);
            
            if($updateTable)
                    $request->session()->flash('status', 'update Customer Success!');
            else    $request->session()->flash('status', 'update Customer Error!');

            return redirect()->route('list-staff');
        }
    }
    //SAVE OR EDIT STAFF - END
    
    public function getStaffAttendances(){

        $staff_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                 ->where('worker_status',"=",1)
                                 ->where('enable_status',"=",1)
                                 ->get();
        // dd($staff_list);
        $date = \Carbon\Carbon::now();
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $checkin_array = [];

        foreach($staff_list as $staff){
            $checkin_array[] = PosCheckin::where('checkin_place_id',$this->getCurrentPlaceId())
                                       ->where('checkin_worker_id',$staff->worker_id)
                                        ->where('checkin_datetime',">=",$start)
                                        ->where('checkin_datetime',"<=",$end)
                                        ->orderBy('checkin_datetime','desc')
                                        ->first();
        }
        // dd($checkin_array);
        return view('datasetup.staff_attendances',compact('staff_list','checkin_array'));
    }

    public function changeCheckinStatus(Request $request)
    {
        
        $checkin_id = PosCheckin::where('checkin_place_id',$this->getCurrentPlaceId())->max('checkin_id')+1;

        $pos_checkin = new PosCheckin();
        $pos_checkin->checkin_id = $checkin_id;
        $pos_checkin->checkin_place_id = $this->getCurrentPlaceId();
        $pos_checkin->checkin_worker_id = $request->worker_id;
        $pos_checkin->checkin_ip_address = $request->ip();
        $pos_checkin->checkin_datetime = \Carbon\Carbon::now();
        $pos_checkin->checkin_reason = $request->reason??"checkin";
        $pos_checkin->checkin_type = $request->checkin_type;
        $pos_checkin->save();

        //SET TURN WORKER FOR PAYMENT
        if($request->checkin_type == 1)
            $worker_checkin_max = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())->max('worker_checkin')+1;
        else
            $worker_checkin_max = 0;
        
        PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                 ->where('worker_id',$request->worker_id)
                 ->update(['worker_checkin' => $worker_checkin_max]);

        return 'Checkin Success!';
    }

    

}
