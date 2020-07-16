<?php

namespace App\Http\Controllers\WebBuilder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PosPlace;

class SocialNetworkController extends Controller
{
    private $socialNetworkArr = [
        'Facebook',
        'Yelp',
        'Youtube',
        'Google Plus',
        'Linkedin',
        'Printerest',
        'Instagram',
        'VK',
        'Stack Over Flow',
        'Twitter',
        'Stumbleupon',
        'Tumblr',
        'Sound Cloud',
        'Behance',
        'Rss',
        'Flickr',
        'Vine',
        'Reddit',
        'Github'
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the list of contact of websites.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $contact_list = PosPlace::where('place_id',$this->getCurrentPlaceId())->first()->place_social_network;

        if($contact_list != ""){

            $social_array = explode(";", $contact_list);

            $social_array = str_replace(";",",", $social_array);
        }
        $socialNetworkArr = [];
        
        foreach ($this->socialNetworkArr as $key => $social) {

            $socialNetworkArr[$social] = ($contact_list)?$social_array[$key]:"";
        }
        return view('webbuilder.social_network',compact('socialNetworkArr'));
    }
    public function saveSocial(Request $request)
    {
        $arr = "";

        foreach($this->socialNetworkArr as $item){

            $social = str_replace(' ', '_', $item);

            if($request->$social == "")
            {
                $result = "";
            }
            else{
                $result = $request->$social;
            }
                $arr .= $result.";";
        }

        PosPlace::where('place_id',$this->getCurrentPlaceId())
                 ->update(['place_social_network'=>$arr]);
        return back()->with('message','Change Social Success');
    }
    
}
