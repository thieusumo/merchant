<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Models\PosPlace;
use App\Models\PosUser;
use App\Models\PosRole;
use App\Models\PosCateservice;
use App\Models\PosUserGroup;
use App\Models\PosMerchantPermission;
use App\Models\PosMerchantPerUserGroup;
use App\Models\PosMerchantMenus;
use App\Models\PosPromotion;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\DB;
use yajra\Datatables\Datatables;
use Validator;
use Session;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Helpers\GeneralHelper;
use Hash;
use App\Helpers\NotificationHelper;



class UserController extends Controller {

    public function login() {
        $useragent=$_SERVER['HTTP_USER_AGENT'];

        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            return "Merchant be supported on Laptop or Computer!";
        return view('auth.login');
    }

    public function changePassword() {
        return view('auth.change_password');
    }
    
    public function changeProfile() {
        $user_name = explode(";",Auth::user()->user_fullname);
        $nickname = Auth::user()->user_nickname;
        $avatar = Auth::user()->user_avatar;
        if(!isset($user_name[1])) $user_name[1]="";
        return view('auth.change_profile', compact('user_name' ,'nickname','avatar'  ) );
    }

    public function postLogin(Request $request) {
        $rules = [
            'phone' => 'required',
            'password' => 'required'
        ];
        $messages = [
            'phone.required' => 'Please enter phone number',
            'phone.number' => 'Phone is a number',
            'password.required' => 'Please enter phone number',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $phone = $request->input('phone');
            $password = $request->input('password');
            $phone = preg_replace("/[^0-9]/", "", $phone );
            $start_phone = substr($phone,0,1);
            if( $start_phone == '0' )
            {
                $phone = $request->input('country_code').substr($phone,1);
            }
            else
            {
                $phone = $request->input('country_code').$phone;
            }


            if (Auth::attempt(['user_phone' => $phone  , 'user_password' => $password, 'user_status' => '1'])) {

                Session::put('current_user_id',Auth::user()->user_id); 
                Session::put('current_user_nickname',Auth::user()->user_nickname);
                Session::put('current_user_phone',Auth::user()->user_phone);
                Session::put('current_user_fullname',Auth::user()->user_fullname);
                Session::put('current_user_avatar',Auth::user()->user_avatar);
                Session::put('current_user_email',Auth::user()->user_email);
                Session::put('current_user_default_place_id',Auth::user()->user_default_place_id);
                Session::put('current_user_place_id',Auth::user()->user_place_id);
                Session::put('current_user_places_id',Auth::user()->user_places_id);
                Session::put('current_user_usergroup_id',Auth::user()->user_usergroup_id);
                
                $user_default_place_id ="";
                // Set Auth Details
                if(Auth::user()->user_default_place_id > 0)
                    $user_default_place_id = Auth::user()->user_default_place_id;
                else
                    $user_default_place_id = Auth::user()->user_place_id;
                $this->setCurrentPlaceId($user_default_place_id);
                //CHECK EXIST DATA IN user_places_id
                if(isset(Auth::user()->user_places_id))
                {
                    $places_array = explode(",",Auth::user()->user_places_id);
                    $places = PosPlace::whereIn('place_id',$places_array )->select('place_id', 'place_ip_license' , 'place_name')->get();
                }else{
                    $places = PosPlace::where('place_id',Auth::user()->user_place_id)->select('place_id', 'place_ip_license' , 'place_name')->get();
                }
                $ipPlaceLicense = PosPlace::select('place_ip_license')->where('place_id',$this->getCurrentPlaceId())->first();

                Session::put('place_ip_license',$ipPlaceLicense->place_ip_license);
                //Create List Permissions , New User Login - BEGIN
                $check_permission_exist=PosMerchantPerUserGroup::where('mpug_place_id',$this->getCurrentPlaceId())
                                                        ->count();
                //if($check_permission_exist ==0)
                //PermissionHelper::createListPermissions($this->getCurrentPlaceId());
                //Create List Permissions , New User Login - END

                //get Menu and Permission for user - BEGIN
                 $place_menu =  PermissionHelper::getMenuAndPermission($this->getCurrentPlaceId());
                 // return $place_menu;
                //get Menu and Permission for user - END
                 if(count($place_menu) == 0){
                    $errors = new MessageBag(['errorlogin' => 'You Do Not Have Permission']);
                    return redirect()->back()->withInput()->withErrors($errors);
                 }
                 else{
                    Session::put('place_arr', $places );
                    Session::put('selected_country_code',$request->country_code);
                    // dd(Auth::user()->user_id);

                    return redirect()->intended('/');


                 }
            } else {                
                $errors = new MessageBag(['errorlogin' => 'Phone or Password is Wrong']);
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }

    public function postChangeProfile(Request $request) {
        // dd($request->all());
        // dd(Session::get('current_user_id'));
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'nickname' => 'required'
                ], [
            'firstname.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'nickname.required' => 'Nickname is required'
        ]);
        $user_id = Session::get('current_user_id');
        $posuser = PosUser::find(Session::get('current_user_phone'));
        $posuser->user_fullname = $request->firstname.";".$request->lastname ;
        $posuser->user_nickname = $request->nickname;
        if($request->hasFile('profile_image')){
                //insert image 
            $posuser->user_avatar = \App\Helpers\ImagesHelper::uploadImage($request->profile_image,'user','');
            // $image_path= \App\Helpers\ImagesHelper::uploadImage($request->file('profile_image'),"user",Session::get('place_ip_license'));
            // $posuser->user_avatar = strtotime('now') . strtolower($request->profile_image->getClientOriginalName());
        }
        $posuser->save();

        if ( $posuser->save())
        {
            return redirect()->back()->with('message', 'Change Profile Success!');
        }else{
            return redirect()->back()->with('error', 'Change Profile is Wrong!');
        }
       
    }

    public function postChangePassword(Request $request) {
        //return dd($request);
        $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'password_confirmation' => 'required'
                ], [
            'oldpassword.required' => 'Old Password is required',
            'newpassword.required' => 'New Password is required',
            'password_confirmation.required' => 'Password Confirmation is required'
        ]);

        if (Auth::attempt(['user_phone' => Session::get('current_user_phone') , 'user_password' => $request->oldpassword])) {
            $posuser = PosUser::find(Session::get('current_user_phone'));
            $posuser->user_password = bcrypt($request->newpassword);
            $posuser->save();

            return redirect()->back()->with('notification', 'Change Password Success!');
        } else {
            return redirect()->back()->with('error', 'Old Password is Rong!');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
    
    public function listUser(){
        Session::put('selected_place_id',15);
         $list_service_cates = PosCateservice::where('cateservice_place_id', $this->getCurrentPlaceId())->get();
        return view('user.users');
    }
    
    public function listRole(){
        return view('user.roles');
    }    
    
    public function getUserDatatable(Request $request)
    {
        $user_list = PosUser::leftjoin('pos_user_group',function($join){
                                $join->on("pos_user.user_usergroup_id","=","pos_user_group.ug_id")
                                   ->on("pos_user.user_place_id","=","pos_user_group.ug_place_id");
                                })
                                ->where('user_place_id',$this->getCurrentPlaceId())
                                ->where('user_status',1)
                                ->select('pos_user.*','pos_user_group.ug_name','pos_user_group.ug_merchant_role')->get();
                                        
                                // echo $user_list; die();
        return Datatables::of($user_list)
        ->editColumn('user_name',function($row){

            return str_replace(";"," ",$row->user_fullname);
        })
        ->editColumn('user_phone',function($row){

            return GeneralHelper::formatPhoneNumber($row->user_phone);
        })

        ->editColumn('user_login_time',function($row){
            $created_by = PosUser::where('user_place_id', $this->getCurrentPlaceId())
                                   ->where('user_id',$row->created_by)
                                   ->first();
                                   $by="";
                                   if(!empty($created_by))
                                   {
                                    $by=" by ".$created_by->user_nickname;
                                   }

            return format_datetime($row->user_login_time). $by;
        })

        ->editColumn('enable_status',function($row){

            $checked = "";
            if($row->enable_status == 1){
                $checked = "checked";
            }
            return "<input type='checkbox' name='enable_status' id='".$row->user_id."' class='js-switch switchery switchery-small'" .$checked. "/>";
        })

        ->addColumn('action',function($row){

            //GET PERMISSON TO COMPARE 

            // $ug_merchant_role = $row->ug_merchant_role;

            // if($ug_merchant_role != ''){
            //     $data = json_decode($ug_merchant_role,true);

            //     $update = $data['users']['users']['update'];

            //     $user_current = \Auth()->user()->user_usergroup_id;
            //     $place = $this->getCurrentPlaceId();

            //     $user_role_current =  PosUserGroup::where('ug_place_id',$this->getCurrentPlaceId())
            //                                         ->where('ug_id',$user_current)
            //                                         ->first()->ug_merchant_role;
            //     $data_current = json_decode($user_role_current,true);

            //     $update_current = $data_current['users']['users']['update'];

            //     if((\Auth()->user()->user_id == $row->user_id) || $update_current > $update )
            //     {
            //         $route = route('edit-user',$row->user_id);
            //     }else $route = "#";
            // } else $route = "#";
            $route = route('edit-user',$row->user_id);

            //END GET PERMISSION TOCOMPARE
            return '<a href="'.$route.'" title="Edit" class="edit-user btn btn-sm btn-secondary" ><i class="fa fa-pencil"></i></a> <a href="javascript:void(0)" class="btn btn-sm btn-secondary delete_user" title="Delete" name="delete_user"id="'.$row->user_id.'" active = "'.$row->user_status.'"><i class="fa fa-trash-o"></i></a>';
        })

        ->rawColumns(['action','enable_status'])
        ->make(true);
    }
    public function deleteUser(Request $request)
    {
        $user_id = $request->user_id;

        if($user_id == Auth::user()->user_id) return abort(404);

        PosUser::where('user_place_id',$this->getCurrentPlaceId())
                 ->where('user_id',$user_id)
                 ->update(['user_status'=>0]);
    }
    public function changeStatus(Request $request)
    {
        $user_id = $request->user_id;

        $checked = $request->checked;

        if($checked == "checked"){

            $enable_status = 0;
        }else $enable_status = 1;

        PosUser::where('user_place_id',$this->getCurrentPlaceId())
                 ->where('user_id',$user_id)
                 ->update(['enable_status'=>$enable_status]);
    }
    
    //GET VIEW EDIT/ADD USER
    public function editUser($id = 0){
        $ug_list = PosUserGroup::where('ug_place_id',$this->getCurrentPlaceId())->get();

        $data=[
            'headNumber'=>GeneralHelper::all()
        ];

        if($id>0){
            $user_list = PosUser::where('user_place_id',$this->getCurrentPlaceId())
                                  ->where('user_id',$id)->first();

            $user_name = explode(";",$user_list->user_fullname);

            // +84 viet nam
            if(substr($user_list->user_phone, 0,2) == '84'){
            $user_phone = substr($user_list->user_phone, 2);
            $user_phone = '0'.$user_phone;        
            }
            
            return view('user.user_edit',compact('ug_list','user_list','id','user_name','user_phone','data'));
        }
        
        // dd($data);
        return view('user.user_edit',compact('ug_list','id','data'));
    }

   //END GET VIEW EDIT/ADD USER

    //SAVE ADD/EDIT USER
    public function saveUser(Request $request)
    {

        $user_group = PosUserGroup::where('ug_place_id',$this->getCurrentPlaceId())->first();

        $user_id = $request->user_id;
        $user_email = $request->user_email;


        if($user_id >0){ // CHECK EXIST WHEN EDIT
            $check_exist = PosUser::where('user_place_id',$this->getCurrentPlaceId())
                                        ->where('user_email', $request->user_email)
                                        ->where('user_id','!=',$user_id)
                                        ->first();
        }else //CHECK EXIST WHEN ADD NEW
        {
            $check_exist = PosUser::where('user_place_id',$this->getCurrentPlaceId())
                                    ->where('user_email', $request->user_email)->first();
        }
        $rule = [
            'first_name'=>'required|min:3|max:50',
            'last_name'=>'required|min:3|max:50',
            'user_phone'=>'required|min:9|max:15',
            'user_email'=>'email',
            'user_password'=>'same:user_password_confirm',
            'user_password_confirm'=>'',
            'user_nickname'=>'required'

        ];
        $message = [
            'first_name.required'=>'Please enter First Name',
            'first_name.min'=>'First Name at least 3 characters',
            'first_name.max'=>'First Name max 50 characters',
            'last_name.required'=>'Please enter Last Name',
            'last_name.min'=>'Last Name at least 3 characters',
            'last_name.max'=>'Last Name max 50 characters',
            'user_phone.required'=>'Please enter Phone Number',
            'user_phone.min'=>'Phone Number not Correct',
            'user_phone.max'=>'Phone Number not Correct',
            'user_email.required'=>'Please enter an Email',
            'user_email.email'=>'Please enter an Email',
            'user_password.required'=>'Please enter Password',
            'user_password_confirm.required'=>'Please enter Password Confirm',
            'user_password.min'=>'Password at least 6 characters',
            'user_password_confirm.min'=>'Password Confirm at least 6 characters',
            'user_password.same'=>'Password do not match',
            'user_nickname.required'=>'Please enter Nickname'
        ];
        $validator = Validator::make($request->all(),$rule,$message);

        if(isset($check_exist)){ // PUSH ERROR WHEN EXIST EMAIL
            $validator->after(function ($validator) {
                $validator->errors()->add('user_email.exists', 'Email is exist, Please check again!');
            });
        }


        if($validator->fails()){

            return back()->withErrors($validator)->withInput();
        }
        else{

            $check_user =  PosUser::where('user_place_id', $this->getCurrentPlaceId())
                                    ->where('user_id',$user_id)
                                    ->count();
            if($check_user == 0)
            {
                $idUser = PosUser::where('user_place_id',$this->getCurrentPlaceId())->max('user_id')+1;

            }else{

                $idUser = $user_id;
            }
            $place_ip_license = PosPlace::where('place_id',$this->getCurrentPlaceId())->first()->place_ip_license;

             if(isset($request->user_avatar))
            {
                $user_avatar = \App\Helpers\ImagesHelper::uploadImage($request->user_avatar,'user',$place_ip_license);
            }else
            {
                $user_avatar = $request->user_avatar_hidden;
            }
            $user_fullname = $request->first_name.";". $request->last_name;

            $phone = preg_replace("/[^0-9]/", "", $request->user_phone );
            $start_phone = substr($phone,0,1);
            if( $start_phone == '0' )
            {
                $phone = $request->country_code.substr($phone,1);
            }
            else
            {
                $phone = $request->country_code.$phone;
            }

            $arr_user = [
                'user_id'=>$idUser,
                'user_fullname'=>$user_fullname,
                'user_token'=>$request->_token,
                'remember_token'=>$request->_token,
                'user_place_id'=>$this->getCurrentPlaceId(),
                'user_places_id'=>NULL,
                'user_usergroup_id'=>$request->user_usergroup_id,
                'user_nickname'=>$request->user_nickname,
                'user_phone'=>$phone,
                'user_email'=>$request->user_email,
                // 'user_password'=>\Hash::make($request->user_password),
                'user_avatar'=>$user_avatar,
                'user_permission'=>$request->user_permission,
                'enable_status'=>$request->enable_status,
                'created_by'=>Session::get('current_user_id'),
                'updated_by'=>Session::get('current_user_id'),
                'user_permission'=>$request->user_permission,
                'user_status'=>1
            ];
            //dd($arr_user);

            if($check_user == 0){

                $user_list = PosUser::create($arr_user);

                if($user_list){
                    $request->session()->flash('message','Insert User Success');
                }
                else
                    $request->session()->flash('error','Insert User Error');
            }else{
                $user_list = PosUser::where('user_place_id',$this->getCurrentPlaceId())
                                      ->where('user_id',$user_id)
                                      ->update($arr_user);
                if($user_list)

                    $request->session()->flash('message','Edit User Success');
                else
                    $request->session()->flash('error','Edit User Error');
            }

            return redirect('users/');
        }
    }

    public function getRoleDatatable(Request $request){
        $ug_list = PosUserGroup::where('ug_place_id',$this->getCurrentPlaceId())->orderBy('ug_name','asc')->get();
        return Datatables::of($ug_list)
               ->addColumn('ug_status_name', function ($row) 
            {
                //CONVERT STATUS
                if($row->ug_status==1){
                    return "Active";
                }else{
                    return "Inactive";
                }
                ;
            })
               ->addColumn('action', function($row){
                return '<a href="#" name="edit_role" id="'.$row->ug_id.'"><i class="fa fa-edit"></i></a> <a href="#" class="delete-role" name="delete_role" id="'.$row->ug_id.'"><i class="fa fa-trash-o"></i></a>';
            })
            ->make(true);

    }
    public function saveRole(Request $request)
    {

        if($request->ug_name=="")
        {
            return 0;
        }
        else{

            $roles = PosUserGroup::where('ug_place_id', $this->getCurrentPlaceId())->get();
            

            $ug_id = $request->ug_id;
            if(!isset($ug_id)){
                //CHECK EXIST 
                $exist = PosUserGroup::where('ug_name',$request->ug_name)
                                        ->where('ug_place_id',$this->getCurrentPlaceId())
                                        ->first();
                if($exist === NULL)
                {
                    DB::beginTransaction();
                    $role_id = PosUserGroup::where('ug_place_id',$this->getCurrentPlaceId())->max('ug_id')+1;

                    $mpug = new PosMerchantPerUserGroup;
                    $mpug->mp_id = "";
                    $mpug->ug_id = $role_id;
                    $mpug->mpug_place_id = $this->getCurrentPlaceId();


                    $role = new PosUserGroup ;
                    $role->ug_place_id = $this->getCurrentPlaceId();
                    $role->ug_name = $request->ug_name;
                    $role->ug_id = $role_id;
                    $role->ug_description = $request->ug_description;
                    $role->ug_status = $request->active;
                    $role->ug_merchant_role = "";
                    if($role->save() && $mpug->save())
                    {
                        DB::commit();
                        return "Insert Role Success!";
                    }else
                    {
                        DB::rollback();
                        return "Insert Role Error!";
                    } 
                }else return "User Role is exist please check again.";

                
            }else{

                //UDATE USER ROLE
                $role = PosUserGroup::where('ug_id',$ug_id)
                                        ->where('ug_place_id', $this->getCurrentPlaceId())
                                        ->update(['ug_name'=>$request->ug_name , 'ug_description'=>$request->ug_description ,'ug_status'=>$request->active]);
                if($role)
                {
                    return "Update Role Success!";
                }else return "Update Role Error!";           
            }
        }
    }

    public function deleteRole(Request $request)
    {
        $role = PosUserGroup::where('ug_place_id', $this->getCurrentPlaceId())
                         ->where('ug_id',$request->id);
        if($role->delete())
        {
            return "Delete user role success!";
        }
    }

    

    public function permissions(){

        //CHECK EXIST ROLE IN TABLE POS_MERCHANT_PER_USER_GROUP

        $roles = PosUserGroup::where('ug_place_id',$this->getCurrentPlaceId())
                               ->select('ug_id')
                               ->get();
        foreach($roles as $role){
            $count_role = PosMerchantPerUserGroup::where('mpug_place_id',$this->getCurrentPlaceId())
                                                 ->where('ug_id',$role->ug_id)
                                                 ->count();
            if($count_role == 0){
                $mpug = new PosMerchantPerUserGroup;
                $mpug->mp_id = "";
                $mpug->ug_id = $role->ug_id;
                $mpug->mpug_place_id = $this->getCurrentPlaceId();
                $mpug->save();
            }
        }
        $roles = PosUserGroup::join('pos_merchant_per_user_group',function($join){
                                 $join->on('pos_user_group.ug_place_id','pos_merchant_per_user_group.mpug_place_id')
                                 ->on('pos_user_group.ug_id','pos_merchant_per_user_group.ug_id');
                               })
                               ->where('ug_place_id',$this->getCurrentPlaceId())
                               // ->where('pos_user_group.ug_id','!=',1)
                               ->select('pos_user_group.ug_id','pos_user_group.ug_name','pos_merchant_per_user_group.*')
                               ->get();

        $place_menu = Session::get('place_menu');
        
        return view('user.permissions',compact('roles','place_menu'));
    }
    public function changePermission(Request $request)
    {
        $permission_id = $request->permission_id;

        $ug_id = $request->ug_id;

        $check_id = $request->check_id;

        $id = $request->id;

        if($check_id == 1)
        {
            $checked = 0;
        }
        else 
            $checked = 1;

        $ug_list = PosMerchantPerUserGroup::where('mpug_place_id', $this->getCurrentPlaceId())
                      ->where('ug_id',$ug_id)
                      ->first();

        $permission_text = $ug_list->mp_id;

        $permission_array = explode(",", $permission_text);

        if ($checked == 0 && ($key = array_search($permission_id, $permission_array)) !== false) {

            unset($permission_array[$key]);
        }
        elseif($checked == 1)
        {
            array_push($permission_array, $permission_id);
        }
        $permission_text = implode(",",$permission_array);

        PosMerchantPerUserGroup::where('mpug_place_id', $this->getCurrentPlaceId())
                      ->where('ug_id',$ug_id)
                      ->update(['mp_id'=>$permission_text]);
        $json = '{
                "id" : "'.$id.'",
                "check_id" : "'.$checked.'",
                 }';
        return $json;
    }



    //TEST CREATE MULTI PLACE
    public function testCreateMultiPlace(){

        return view('user.test_create_multi_place');
    }
    
    public function postTestCreateMultiPlace(Request $request){

        //GET PERMISSON
        $permission_arr = [];
        
        $permissions = PosMerchantPermission::select('mp_id')->get();

        foreach($permissions as $permission){
            $permission_arr[] = $permission->mp_id;
        }
        $permission_list = implode(',',$permission_arr );

        //SET PLACE_ID
        $quantity = $request->quantity;

        $place_max_old = PosPlace::max('place_id');

        $place_max_new = $place_max_old + $quantity;

        for ($i = $place_max_old+1; $i <= $place_max_new; $i++) {

            //CREATE PLACE
            $place_arr = [
                'place_id' => $i,
                'place_code'=> 'place_'.$i,
                'place_logo' => 'logo',
                'place_name' => 'Nail_'.$i,
                'place_address' => 'address_'.$i,
                'place_website' => 'website_'.$i,
                'place_taxcode' => '1234'.$i,
                'place_customer_type' => 1,
                'place_url_plugin' => "place_url_plugin",
                'place_ip_license' => '1234567'.$i,
                'place_status' => 1
            ];
            PosPlace::create($place_arr);

            //CREATE USER
            $user_arr = [
                'user_id' => 1,
                'user_place_id' => $i,
                'user_default_place_id' => $i,
                'user_usergroup_id' => 1,
                'user_password' => Hash::make('abc123'),
                'user_fullname' => 'user_'.$i,
                'user_token' => $request->_token,
                'remember_token' => $request->_token,
                'user_status' => 1,
                'user_phone' => '84111111'.$i,
            ];
            PosUser::create($user_arr);

            //CREATE ROLE
            $role_arr = [
                'ug_id' => 1,
                'ug_place_id' => $i,
                'ug_name' => 'admin',
                'ug_role' => '',
                'ug_merchant_role' => '',
                'ug_status' => 1
            ];
            PosUserGroup::create($role_arr);

            //CREATE PERMISSION 
            $permission_admin = [
                'mp_id' => $permission_list,
                'ug_id' => 1,
                'mpug_place_id' => $i
            ];
            PosMerchantPerUserGroup::create($permission_admin);
        }
        $arr = [
            'from' => $place_max_old+1,
            'to' => $place_max_new
        ];
        return $arr;
    }
}

?>