<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use App\Models\PosWebSeo;

class WebSeoController extends Controller
{
    public function index(){
        $data['webSeo'] = PosWebSeo::select('web_seo_descript','web_seo_meta')
                            ->where('web_seo_place_id',$this->getCurrentPlaceId())
                            ->first();

        return view('webbuilder.web_seo',$data);
    }

    public function save(Request $request){
        $webSeo = PosWebSeo::where('web_seo_place_id',$this->getCurrentPlaceId())
                        ->first();
        if(!$webSeo){
            $webSeo = new PosWebSeo;
            $webSeo->web_seo_place_id = $this->getCurrentPlaceId();   
        }
        $webSeo->web_seo_descript = $request->description;
        $webSeo->web_seo_meta = $request->keywords;
        $webSeo->save();

        return back()->with('message',"Web Seo have been saved successfully!");
    }
}
