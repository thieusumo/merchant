<?php 
namespace App\Helpers;

use Session;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
/**
 * PermissionHelper class
 */
class PermissionHelper{
    
    const SESSION_ID = 'user_permission';

    /**
     *  Get Menu and Permission for User
     * @param place_id
    */
    public static function getMenuAndPermission($place_id){

        //GET MENU IN MAIN_SERVICE
        $place_menu = [];
        $today = Carbon::today();

        $service_list =  DB::table('main_combo_service')->join('main_customer_service',function($join){
            $join->on('main_customer_service.cs_service_id','main_combo_service.id');
        })
            ->where('main_customer_service.cs_place_id',$place_id)
            ->whereDate('cs_date_expire', '>=',$today)
            ->where('main_combo_service.cs_status',1)
            ->where('main_customer_service.cs_status',1)
            ->get();

        foreach ($service_list as $key => $service) {
            foreach(explode(";", $service->cs_menu_id) as $menu){
                $place_menu[] = $menu;
            }

        }
        $place_menu = array_unique($place_menu);

        //SET FULL PERMISSIONS FOR ADMIN

        $permission_arr = [];

        $permission_database = DB::table('pos_merchant_permission')->select('mer_menu_id','mp_id')->get();

        foreach ($permission_database as $key => $permission) {

                if( in_array($permission->mer_menu_id ,$place_menu)) {
                    $permission_arr[] = $permission->mp_id;
                }
            }

        if(Auth::user()->user_usergroup_id == 1)
        {
            $permission_list = implode(",",$permission_arr);

        }else{

            $permission_role_list_arr = [];
            //GET REOLE PERMISSION DATABASE
            $permission_role = DB::table('pos_merchant_per_user_group')
                ->where('mpug_place_id',$place_id)
                ->where('ug_id',Auth::user()->user_usergroup_id)
                ->first()
                ->mp_id;

            $permission_role = explode(",", $permission_role);

            foreach ($permission_role as $key => $value) {
                if(in_array($value, $permission_arr))
                    $permission_role_list_arr[] = $value;
            }
            $permission_list = implode(",", $permission_role_list_arr);
        }
        // UPDATE/INSERT FULL PERMISSION FOR ADMIN/ROLE
        $check_ug_exist = DB::table('pos_merchant_per_user_group')
                ->where('mpug_place_id',$place_id)
                ->where('ug_id',Auth::user()->user_usergroup_id)
                ->count();
                // return $check_ug_exist;
        if($check_ug_exist == 0)

            $update = DB::table('pos_merchant_per_user_group')
                ->insert([
                    'mp_id'=>$permission_list,
                    'mpug_place_id' => $place_id,
                    'ug_id' => Auth::user()->user_usergroup_id
                ]);
        else
            $update = DB::table('pos_merchant_per_user_group')
                ->where('mpug_place_id',$place_id)
                ->where('ug_id',Auth::user()->user_usergroup_id)
                ->update(['mp_id'=>$permission_list]);

        if(Auth::user()->user_usergroup_id == 1)
        {
            //GET MENUS -BEGIN
            $user_menus = \App\Models\PosMerchantMenus::join('pos_merchant_menus as child', function($join){
                                    $join->on('child.mer_menu_parent_id', '=','pos_merchant_menus.mer_menu_id');
                                })
                                ->whereIn('child.mer_menu_id',$place_menu)
                                ->orderBy('child.mer_menu_index', 'asc')
                                ->groupBy('pos_merchant_menus.mer_menu_id')
                                ->selectRaw('`pos_merchant_menus`.* ,child.mer_menu_url as child_mer_menu_url')
                                ->get();
            //GET MENUS -END
            //GET SUB MENU - BEGIN
            $user_sub_menu = \App\Models\PosMerchantMenus::join('pos_merchant_menus as parent','pos_merchant_menus.mer_menu_parent_id','=','parent.mer_menu_id')
                                            ->where('pos_merchant_menus.mer_menu_parent_id','>',0)
                                            ->groupBy('pos_merchant_menus.mer_menu_id')
                                            ->selectRaw('pos_merchant_menus.* , parent.mer_menu_url as parent_mer_menu_url')
                                            ->orderBy('mer_menu_index', 'asc')
                                            ->get()->toArray();
            //GET SUB MENU - END            
        }
        else{
            $permission = \App\Models\PosMerchantPerUserGroup::where('ug_id',Auth::user()->user_usergroup_id)
                                                ->where('mpug_place_id',$place_id)
                                                ->select('mp_id')
                                                ->get();
            $permission = explode(',', $permission);
            //GET MENUS -BEGIN
            $user_menus = \App\Models\PosMerchantMenus::join('pos_merchant_menus as child', function($join) use($permission){
                                    $join->on('child.mer_menu_parent_id', '=','pos_merchant_menus.mer_menu_id')
                                    ->join('pos_merchant_permission',"child.mer_menu_id",
                                                    "=","pos_merchant_permission.mer_menu_id")
                                    ->whereIn('pos_merchant_permission.mp_id',$permission);
                                })
                                ->whereIn('child.mer_menu_id',$place_menu)
                                ->groupBy('pos_merchant_menus.mer_menu_id')
                                ->selectRaw('`pos_merchant_menus`.* ,child.mer_menu_url as child_mer_menu_url')
                                ->get(); dd($user_menus);
            //GET MENUS -END

            //GET SUB MENU - BEGIN
            $user_sub_menu = \App\Models\PosMerchantMenus::join('pos_merchant_permission',"pos_merchant_menus.mer_menu_id",
                                                    "=","pos_merchant_permission.mer_menu_id")
                                            ->join('pos_merchant_menus as parent','pos_merchant_menus.mer_menu_parent_id','=','parent.mer_menu_id')
                                            ->whereIn('pos_merchant_permission.mp_id',$permission)
                                            ->where('pos_merchant_menus.mer_menu_parent_id','>',0)
                                            ->groupBy('pos_merchant_menus.mer_menu_id')
                                            ->selectRaw('pos_merchant_menus.* , parent.mer_menu_url as parent_mer_menu_url')
                                            ->orderBy('mer_menu_index', 'asc')
                                            ->get()->toArray();
            //GET SUB MENU - END
        }
        //GET PERMISSION ARRAY
        
        $permssion_arr = explode(",", $permission_list);

        $user_permission = \App\Models\PosMerchantPermission::whereIn('mp_id',$permssion_arr)
                                                ->get();
        // return $user_permission;
        Session::put('user_permission', $user_permission );
        Session::put('user_menus', $user_menus );
        Session::put('user_sub_menu', $user_sub_menu );
        Session::put('place_menu', $place_menu );

        return $place_menu;
    }

    /**
     *  Create List Permission for new User Login
     * @param place_id
    */
    public static function createListPermissions($place_id){
        //$user_group_id = Auth::user()->user_usergroup_id;
        $data = "";
        $max_id = \App\Models\PosMerchantPermission::max('mp_id');
        for($i = 1;$i <= $max_id ; $i++){
            $data.= $i.",";
        }
        return $data;
    }

    /**
     *  Get Sub Menus by Parent url
     * @param parent_url
    */
    public static function getSubMenusByParent($parent_url){
        $collection = collect(Session::get('user_sub_menu'));
        return $collection->where('parent_mer_menu_url', $parent_url);
    }
}

