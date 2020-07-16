<?php
namespace App\Traits;
use Session;

trait License
{
	public static function getLicense(){
		$ipPlaceLicense = Session::get('place_ip_license');
		return $ipPlaceLicense;
	}
}