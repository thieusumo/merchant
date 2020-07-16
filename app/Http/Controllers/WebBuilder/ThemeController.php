<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainTheme;

class ThemeController extends Controller
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
        $themes = MainTheme::paginate(15);;

        return view('webbuilder.themes',compact('themes'));
    }
    
    public function payment($id=0){
        $themes = MainTheme::all();
        return view('webbuilder.theme_payment',compact('themes','id'));
    }

    /**
    * @param id
    * @return img
    */
    public function getImgLink(Request $request){
        $theme = MainTheme::where('theme_id','=',$request->id)->first();

        return response()->json(['image' => $theme->theme_image]);
    }
        
}
