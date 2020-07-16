<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\MessageBag;

class SalefinanceController extends Controller {

	public function index(Request $request){
		$order_date = $request->order_date;
		
    	$expenseslist = DB::table('pos_place_expense')->where('pe_place_id',Auth::user()->user_default_place_id )->paginate(10);
    	//return dd($expenseslist);
    	return view('salefinance.expenses', compact('expenseslist') );
 }
}

?>