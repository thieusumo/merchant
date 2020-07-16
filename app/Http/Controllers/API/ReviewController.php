<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\PosCustomerRating;
use Illuminate\Http\Request;
use App\Models\MainService;
use App\Models\MainServiceDetail;
use App\Models\PosPlace;
use App\Models\MainCustomerService;
use DB;

class ReviewController extends Controller
{
	/**
	 * get review bad or good
	 * @param  $request->type 
	 * @param  $request->page 
	 * @return json
	 */
	public function getByType(Request $request){
		// dd($request->type);
		$length = 10;
		$start = $request->page * 10;

		if(!$request->type){
			$reviews = $this->allReviews($start,$length);
		}

		if($request->type == 1){
			$reviews = $this->badReviews($start,$length);
		}

		if($request->type == 2){
			$reviews = $this->goodReviews($start,$length);
		}

		try {
			$reviews = json_decode($reviews,true);
		} catch (\Exception $e) {
			return response()->json(['status'=>0,'msg'=>'Failed to get reviews'],400);
		}

		return response()->json(['status'=>1,'reviews'=>$reviews],200) ?? response()->json(['status'=>0,'msg'=>'Failed to get reviews'],400);
	}

	public function allReviews($start, $length){
        $allreviews = 'filter?merchant_id='.$this->getPlaceId().'&start='.$start.'&length='.$length;
        $data = $this->callMPIT($allreviews);        
        return $data;
    }

	private function badReviews($start, $length){
        $filter = 'filter?merchant_id='.$this->getPlaceId().'&badReview=1'.'&start='.$start.'&length='.$length;
        $data = $this->callMPIT($filter);
        return $data;
    }

    private function goodReviews($start, $length){
    	$filter = 'filter?merchant_id='.$this->getPlaceId().'&rating=5'.'&start='.$start.'&length='.$length;
        $data = $this->callMPIT($filter);
        return $data;
    }

    private function callMPIT($url = ""){
        try {
            $url = env("REVIEW_SMS_API_URL").'review/'.$url;

            $header = array('Authorization'=>'Bearer ' .env("REVIEW_SMS_API_KEY"));
            //$url="http://user.tag.com/api/v1/receiveTo";
            $client = new Client([
        
            ]);

            
                    
            $response = $client->get($url, array('headers' => $header));

            $resp=  (string)$response->getBody();

            return $resp;
        } catch (\Exception $e) {
            
        }
        
        

    }

    protected function getPlaceId(){
    	return 619;
    }

}