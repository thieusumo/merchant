<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use App\Models\PosContactCustomer;
use App\Models\PosMenu;
use Session;
use Validator;

class ContactController extends Controller
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
     * Show the list of contact of websites.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('webbuilder.contacts');
    }
    public function getContact()
    {
        $list_contact = PosContactCustomer::where('cc_place_id',$this->getCurrentPlaceId())
                                            ->where('cc_status',1);
        return Datatables::of($list_contact)
            ->editColumn('cc_datetime',function($row){
                return format_datetime($row->cc_datetime);
            })
           ->addColumn('action', function($row){
                return " <a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-contact' id='".$row->cc_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
            })
            ->rawColumns(['action'])
            ->make(true);
        
    }
    public function deleteContact(Request $request)
    {
        $cc_id = $request->id;

        PosContactCustomer::where('cc_place_id',$this->getCurrentPlaceId())
                            ->where('cc_id',$cc_id)
                            ->update(['cc_status'=>0]);
    }
    
}
