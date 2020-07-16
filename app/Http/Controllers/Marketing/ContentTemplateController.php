<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosSubject;
use yajra\Datatables\Datatables;
use Validator;
use DB;


class ContentTemplateController extends Controller
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
        return view('marketing.contenttemplates');
    }
    
    public function edit($id=0){
        $subject = PosSubject::where('sub_place_id', $this->getCurrentPlaceId())
                            ->where('sub_id',$id)->first();

        if($subject)
        {            
            return view('marketing.contenttemplate_edit',compact('subject','id'));
        }

        return view('marketing.contenttemplate_edit',compact('id'));
    }

    /**
    * @param name
    * @param image
    * @param contentID;
    * @param description
    * @return true/false
    */
    public function save(Request $request){
        // dd($request->all());
        $contentID = $request->content_id;
        $content = PosSubject::where('sub_id',$contentID)
                            ->where('sub_place_id', $this->getCurrentPlaceId())
                            ->first(); 


        if($content){
            $rules = [
            'name' => 'required',
            'description' => 'required',
            ];
        } else {
            $rules = [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required',
            ];
        }
        
        $messages = [
           
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       

        if($content){
            //edit
            if($request->file("image")){
                try {
                    $imgLink = \App\Helpers\ImagesHelper::uploadImage($request->file("image"), "marketing", $this->getCurrentPlaceIpLicense());
                } catch (Exception $e) {
                    return redirect()->back()->withErrors("Error in upload file!");
                }
            } else {
                $imgLink = $content->sub_image;
            }

            $updateTable = PosSubject::where('sub_id',$contentID)
                                        ->where('sub_place_id', $this->getCurrentPlaceId())->update([
                            'sub_name'=>$request->name,
                            'sub_image'=>$imgLink,
                            'sub_description'=>$request->description,
                            'sub_type' => $request->userType,]);
            
            if($updateTable)
                    $request->session()->flash('status', 'update content Success!');
            else    $request->session()->flash('status', 'update content Error!');

            return redirect()->route('contenttemplates');
        } else {
            //add 
            if($request->file("image")){
                try {
                    $imgLink = \App\Helpers\ImagesHelper::uploadImage($request->file("image"), "marketing", $this->getCurrentPlaceIpLicense());
                } catch (Exception $e) {
                    return redirect()->back()->withErrors("Error in upload file!");
                }
            }

            $id = PosSubject::where('sub_place_id','=',$this->getCurrentPlaceId())->max('sub_id') +1;

            $subject = new PosSubject;
            $subject->sub_id = $id;
            $subject->sub_place_id = $this->getCurrentPlaceId();
            $subject->sub_name = $request->name;
            $subject->sub_image = $imgLink;
            $subject->sub_description = $request->description;
            $subject->sub_type = $request->userType;
            $subject->sub_status = 1;

            $subject->save();

            if($subject)
                    $request->session()->flash('status', 'add content Success!');
            else    $request->session()->flash('status', 'add content Error!');

            return redirect()->route('contenttemplates');
        }  
    }


    /**
    * @param content
    * @return true/false
    */
    public function deleteContent(Request $request){
        $content = PosSubject::where('sub_place_id', $this->getCurrentPlaceId())
                            ->where('sub_id',$request->id)
                            ->update(['sub_status'=> 0 ]);
        if($content)
        {
            return "Delete  success!";
        }
        else
        {
            return "Delete  error!";
        }
    }
    

    /**
    *   @param subject
    *   @return table
    */

    public function getContent(Request $request)
    {   
        $subject = PosSubject::leftjoin('pos_user',function($join){
                                $join->on('pos_subject.created_by','=','pos_user.user_id')->on('pos_subject.sub_place_id','=','pos_user.user_place_id');
                            })
                            ->where('sub_place_id',$this->getCurrentPlaceId())
                            ->where('sub_status',1);
		
        return Datatables::of($subject)

            ->editColumn('sub_name', function ($row) 
            {
                return $row->sub_name;
            })
            ->editColumn('image', function ($row) 
            {
				return '<img onerror="this.style.display='."'none'".'" src="'.config('app.url_file_view').'/'.$row->sub_image.'" height="80" >' ;
            })
            ->addColumn('description', function($row){
                return  $row->sub_description;
            })
            ->editColumn('type', function ($row) 
            {
                if($row->sub_type == 0){
                    return 'Gift card and Coupon';
                }elseif($row->sub_type == 0){
                    return 'Gift card';
                }else{
                    return 'Coupon';
                }
            })
            ->addColumn('updated_at', function($row){
                //return  $row->updated_at;
                return format_datetime($row->updated_at)." by ".$row->user_nickname;
            })
            ->addColumn('action', function($row){
                return " <a href='".route('contentedit',$row->sub_id)."' class='btn btn-sm btn-secondary edit-worker' ><i class='fa fa-pencil fa-lg'></i></a> <a href='javascript:void(0)' class='btn btn-sm btn-secondary delete-content' id='".$row->sub_id."' data-type='user'><i class='fa fa-trash-o fa-lg'></i></a>" ;
            })
            ->rawColumns(['image','description','type','updated_at','action'])
            ->make(true);
    }
}
