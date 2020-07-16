<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use App\Models\PosWebsiteProperty;
use Validator;
use App\Helpers\ImagesHelper;

class WebSitePropertyController extends Controller
{
    public function index(){
        return view('webbuilder.website_properties');
    }

    public function datatable(){
        $wp = PosWebSiteProperty::where('wp_place_id',$this->getCurrentPlaceId())->get();

        return Datatables::of($wp)
        ->editColumn('wp_value',function($wp){
            if($wp->wp_type == 1){
                return $wp->wp_value;
            } else if ($wp->wp_type == 2) {
                return "<img height='150px' src='".config('app.url_file_view').$wp->wp_value."' >";
            }
        })
        ->editColumn('action',function($wp){
            return '<a href="#" data="'.$wp->wp_variable.'" class="btn btn-sm btn-secondary edit" ><i class="fa fa-edit"></i></a>
                        <a href="#" class="delete btn btn-sm btn-secondary" data="'.$wp->wp_variable.'"><i class="fa fa-trash-o"></i></a>';
        })
        ->rawColumns(['action','wp_value'])
        ->make(true);
    }
    /**
     * add or update 
     * @return json
     */
    public function save(Request $request){
        $value = null;
        $type = null;

        if($request->value) {
            $value = $request->value;
            $type = 1;

        }
        if ($request->hasFile('image')) {
                $value = ImagesHelper::uploadImage($request->file('image'),"website_properties",'');
                $type = 2;
        }

        if($request->action == "Create"){ 
        // create 
            // $validate = Validator::make($request->all(),[
            //     'variable' => 'required|unique:pos_website_properties,wp_variable',
            //     // 'image' => 'mimes:image|max:2048',
            // ]);

            // $error_array = [];
            // if($validate->fails()){
            //     foreach ($validate->messages()->getMessages() as $messages) {
            //         $error_array[] = $messages;
            //    }
            //    return response()->json(["status"=>0,"msg"=>$error_array],200);
            // }
            $arr = [
                'wp_variable' => $request->variable,
                'wp_place_id' => $this->getCurrentPlaceId(),
                'wp_name' => $request->name,
                'wp_value' => $value,
                'wp_type' => $type,
                
            ];
            PosWebSiteProperty::create($arr);
        } else {
        // update
             $arr = [                       
                'wp_place_id' => $this->getCurrentPlaceId(),
                'wp_name' => $request->name,
                'wp_value' => $value,
                'wp_type' => $type,
                
            ];
            if(empty($arr['wp_value'])) {
                unset($arr['wp_value']);
                unset($arr['wp_type']);
            }

            PosWebSiteProperty::where('wp_variable',$request->variable)->update($arr);
        }
        return response()->json(["status"=>1,"msg"=>$request->action."d successfully!"],200); 
    }

    public function getWebsitePropertyByVariable(Request $request){
        if($request->data){
            $wp = PosWebSiteProperty::where('wp_variable',$request->data)->first();

            return response()->json(['status'=>1,'data'=>$wp]);
        }
    }

    public function deleteWebsiteProperty(Request $request){
        if($request->data){
            PosWebSiteProperty::where('wp_variable',$request->data)->delete();

            return "Deleted successfully!";
        }
    }

    public function export(){
        $data = PosWebsiteProperty::where('wp_place_id',$this->getCurrentPlaceId())->get();

        $date = format_date(now());

        return \Excel::create('WEBSITE_PROPERTIES_'.$date,function($excel) use ($data){

            $excel ->sheet('WEBSITE PROPERTIES TABLE', function ($sheet) use ($data)
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('Variable');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('Name');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Value');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('Type');   });

                if (!empty($data)) {
                    foreach ($data as $key => $value) {
                        $i=$key+2;
                        $sheet->cell('A'.$i, $value->wp_variable); 
                        $sheet->cell('B'.$i, $value->wp_name); 
                        $sheet->cell('C'.$i, $value->wp_value);
                        $sheet->cell('D'.$i, $value->wp_type);
                    }
                }
            });
        })->download("xlsx");
    }

    public function template(){
        // $data = PosWebsiteProperty::where('wp_place_id',$this->getCurrentPlaceId())->get();

        // $date = format_date(now());

        return \Excel::create('WEBSITE_PROPERTIES_TEMPLATE',function($excel) {

            $excel ->sheet('WEBSITE PROPERTIES TABLE', function ($sheet)
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('Variable');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('Name');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Value');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('Type');   });

                // if (!empty($data)) {
                //     foreach ($data as $key => $value) {
                //         $i=$key+2;
                //         $sheet->cell('A'.$i, $value->wp_variable); 
                //         $sheet->cell('B'.$i, $value->wp_name); 
                //         $sheet->cell('C'.$i, $value->wp_value);
                //     }
                // }
            });
        })->download("xlsx");
    }

    public function getImport(){
        return view('webbuilder.import_website_properties');
    }
    public function postImport(Request $request){
        try {
             if($request->hasFile('fileImport')){
                $path = $request->file('fileImport')->getRealPath();
                $data = \Excel::load($path)->toArray();
                $insert=0;
                $update=0;
                // dd($data);                
                foreach ($data as $value) {

                        $check_exist = PosWebSiteProperty::where('wp_place_id',$this->getCurrentPlaceId())
                                                ->where('wp_variable',$value['variable'])->count();

                                
                        if($check_exist == 0)                 
                        {   
                            //create
                            $arr = [];
                            $arr['wp_variable'] = $value['variable'];
                            $arr['wp_place_id'] = $this->getCurrentPlaceId();
                            $arr['wp_name'] = $value['name'];
                            $arr['wp_value'] = $value['value'];
                            $arr['wp_type'] = $value['type'] ?? "";

                            PosWebSiteProperty::create($arr);
                            $insert++;
                        }
                        else
                        {
                            // update
                            $arr = [];
                            $arr['wp_place_id'] = $this->getCurrentPlaceId();
                            $arr['wp_name'] = $value['name'];
                            $arr['wp_value'] = $value['value'];
                            $arr['wp_type'] = $value['type'] ?? "";

                            $p=PosWebSiteProperty::where('wp_place_id',$this->getCurrentPlaceId())
                                                ->where('wp_variable',$value['variable'])
                                                ->update($arr);
                            $update++;
                        }
                }
            }
            return back()->with('message',"Import Success!, update: ".$update." row, inserted: ".$insert."row");     

        } catch (\Exception $e) {
            \Log::info($e);
            return back()->with('error','Import Error!');           
        }
    }

    // public static function getCurrentPlaceId(){
    //     return 14;
    // }
}
