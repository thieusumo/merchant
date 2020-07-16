<?php

namespace App\Http\Controllers\SaleFinance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;
use Carbon\Carbon;
use App\Models\PosPlaceExpense;
use yajra\Datatables\Datatables;
use App\Models\PosExpenseTemplate;

class ExpenseController extends Controller
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

    public function getDatatable(Request $request)
    {
        $result = [];
        $arr_month = [];
        $exp_month=$request->expense_month;
        if($exp_month==""){
            //GET ALL IF DATE IS NULL
            $expenseslist = PosPlaceExpense::all();
            $total_month = Carbon::now()->format('m');
        }
        else{

            $total_month = $exp_month;
            $expenseslist = PosPlaceExpense::where('pe_place_id', $this->getCurrentPlaceId())
                            ->whereMonth('pe_date', '=', $exp_month)
                            ->get();
        }
        $current_year = Carbon::now()->format('Y');

        for ($i=1; $i <= $total_month; $i++) { 
            $arr_month[] = $i;
        }

        
        foreach ($expenseslist as $key => $value) {
            

            //$pe_name_arr = json_decode($value,true);
            $pe_name = $value->pe_name;

            $category_cost = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                            ->whereYear('pe_date',$current_year)
                                            ->where(function($q) use($arr_month) {
                                                foreach ($arr_month as $month) {
                                                    $q->whereMonth('pe_date',"=", $month,"or");
                                                }
                                            })
                                            ->where('pe_name',$pe_name)
                                            ->sum('pe_cost');

            $category_cost_old = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                            ->whereYear('pe_date',($current_year-1))
                                            ->where(function($q) use($arr_month) {
                                                foreach ($arr_month as $month) {
                                                    $q->whereMonth('pe_date',"=", $month,"or");
                                                }
                                            })
                                            ->where('pe_name',$pe_name)
                                            ->sum('pe_cost');
            $result[]= [
                'category_cost' => $category_cost,
                'aver_month' => round($category_cost/$total_month,2),
                'last_year' => round($category_cost_old/$total_month,2),
                'pe_id' => $value->pe_id,
                'pe_date' => $value->pe_date,
                'pe_name' => $value->pe_name,
                'pe_cost' => $value->pe_cost,
                'pe_pay' => $value->pe_pay,
                'pe_cycle' => $value->pe_cycle,
                'pe_bill' => $value->pe_bill
            ];
        }
            //return $result;
        //FORMAT COLUMN DATATABLE           
        return Datatables::of($result)
            ->editColumn('pe_date', function ($row) 
            {
                return '<a href="'.route('expense',$row['pe_id']).'" >'.format_date_m_d($row['pe_date']).'</a>';
            })
            ->editColumn('pe_cost', function ($row) 
            {
                return '<span date="'.$row['pe_date'].'" name="'.$row['pe_name'].'" id="'.$row['pe_id'].'" value="'.$row['pe_cost'].'" >'.$row['pe_cost'].'</span>';
            })
            ->editColumn('pe_pay',function($row){
                $check1 = "";$check2 = "";$check3 = "";
                if($row['pe_pay'] == 1) $check1 = "selected";
                if($row['pe_pay'] == 2) $check2 = "selected";
                if($row['pe_pay'] == 3) $check3 = "selected";
                return '<select class="form-control-sm form-control status" date="'.$row['pe_date'].'" column="pe_pay" id="'.$row['pe_id'].'"><option value="1" '.$check1.'>Check</option><option value="2"'.$check2.'>Cash</option><option value="3"'.$check3.'>Credit</option></select>';
            })
            ->editColumn('pe_cycle',function($row){
                $check1 = "";$check2 = "";
                if($row['pe_cycle'] == 1) $check1 = "selected";
                if($row['pe_cycle'] == 2) $check2 = "selected";
                return '<select class="form-control-sm form-control status" name="'.$row['pe_name'].'" date="'.$row['pe_date'].'"  column="pe_cycle" id="'.$row['pe_id'].'"><option value="1" '.$check1.'>Same</option><option value="2" '.$check2.'>Regular</option></select>';
            })
            ->editColumn('pe_bill',function($row){
                return '<span date="'.$row['pe_date'].'"  id="'.$row['pe_id'].'" value="'.$row['pe_bill'].'">'.$row['pe_bill'].'</span>';
            })
            ->addColumn('action', function($row){
                return "<a href='#' date='".$row['pe_date']."' class='delete-expense btn btn-sm btn-secondary' id='".$row['pe_id']."' data-type='user'><i class='fa fa-trash-o '></i></a>" ;
            })
            ->rawColumns(['pe_date' ,'pe_cost', 'action','pe_pay','pe_cycle','pe_bill'])
            ->make(true); 
             
    }

    public function index(Request $request)
    {
        return view('salefinance.expenses');
    }
        
    public function edit(Request $request)
    {
        $id = $request->id ;
        $expense_list="";
        $expense_date = format_date(get_nowDate());
        if(isset($request->id))
        {  
            $expense = PosPlaceExpense::find($id);
            $expense_date = format_date($expense->pe_date);
            $expense_list = PosPlaceExpense::where('pe_date',$expense->pe_date)->get();

            return view('salefinance.expense_detail', compact ('id', 'expense_list','expense_date'));
        }
        else{
            return view('salefinance.expense_detail', compact('id', 'expense_list','expense_date'));
        }
        
    }
    /**
     * Update Expense
     * @return void
     */
    public function updateExpense($request)
    {
        $check = PosPlaceExpense::where('pe_name',$expense_arr[$i]['pe_name'])
                                ->where('pe_date',$expense_arr[$i]['pe_date'])
                                ->update(['pe_name'=>$expense_arr[$i]['pe_name'] , 'pe_cost'=>$expense_arr[$i]['pe_cost']]);
    }
    public function delete(Request $request)
    {
        $expense = PosPlaceExpense::find($request->id);
        if($expense->delete())
        {
            $request->session()->flash('Expense item Deleted');
        }
    }
    public function saveAdd(Request $request)
    {
        //SAVE EXPENSE LIST
        $expense_arr = $request->expense_arr ;
        $count = count($expense_arr);         
        $check = 1 ;
        DB::beginTransaction();
        try {
            for($i = 0; $i < $count; $i++){
            //IF EXIST -> UPDATE ELSE INSERT
            $check_exist = PosPlaceExpense::where('pe_name',$expense_arr[$i]['pe_name'])
                                            ->where('pe_date' , $expense_arr[$i]['pe_date'])
                                            ->first();
            if ($check_exist === null) {
                $PosPlaceExpense = new PosPlaceExpense ;
                $PosPlaceExpense->pe_place_id = $expense_arr[$i]['pe_place_id'];
                $PosPlaceExpense->pe_name = $expense_arr[$i]['pe_name'];
                $PosPlaceExpense->pe_cost = $expense_arr[$i]['pe_cost'];
                $PosPlaceExpense->pe_date = $expense_arr[$i]['pe_date'];
                $PosPlaceExpense->save();
                $check = $PosPlaceExpense ;
            }
            else
            {
                $check = PosPlaceExpense::where('pe_name',$expense_arr[$i]['pe_name'])
                                ->where('pe_date',$expense_arr[$i]['pe_date'])
                                ->update(['pe_name'=>$expense_arr[$i]['pe_name'] , 'pe_cost'=>$expense_arr[$i]['pe_cost']]);
            }

            if(!$check)
            {
                DB::rollBack();
                $request->session()->flash('status', 'Insert Expense is Error!');
                break;
            }
            
            }
            if( $check )
            {
                DB::commit();
                $request->session()->flash('status', 'Insert Expense Success!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('status', 'Insert Expense is Error!');
        }
    }

    public function updateAmount(Request $request){
        if($request->ajax()){
            $check = PosPlaceExpense::where('pe_id',$request->id_exp)
                                ->where('pe_place_id',$this->getCurrentPlaceId())
                                ->update(['pe_cost'=>$request->pe_cost]);
                                // dd($check);
                                //return 1;
            $pe_cost_current = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                               ->where('pe_id',$request->id_exp)
                                               ->first()
                                               ->pe_cost;

            $pe_cost_old = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                            ->whereMonth('pe_date',$request->month_current-1)
                                            ->where('pe_name',$request->pe_name)
                                            ->first()
                                            ->pe_cost;

            if(isset($pe_cost_old) && $pe_cost_old == $pe_cost_current)
                $pe_cycle = 1;
            else $pe_cycle = 2;

            PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                ->where('pe_id',$request->id_exp)
                                ->update(['pe_cycle'=>$pe_cycle]);
        }
    }

    //show ExpenseTemplate
    public function expenseTemplate()
    {
        $expTemplate=PosExpenseTemplate::where('ex_template_place_id',$this->getCurrentPlaceId())->get();
        return view('salefinance.expense_template',compact('expTemplate'));
    }
    //delete ExpenseTemplate
    public function deleteExpenseTemplate(Request $request)
    {
        if($request->ajax())
        {

            $expense = PosExpenseTemplate::where('ex_template_id',$request->id_ex_template)
            ->where('ex_template_place_id',$this->getCurrentPlaceId());
            $expense->delete();
            return 1;
        }
        
    }
    //add ExpenseTemplate
    public function addExpenseTemplate(Request $request)
    {
        if($request->ajax())
        {
            $name_ex_template=$request->name_ex_template;
            $cost_ex_template=$request->cost_ex_template;

            $add= new PosExpenseTemplate();
            $add->ex_template_place_id=$this->getCurrentPlaceId();
            $add->ex_template_name=$name_ex_template;
            $add->ex_template_cost=$cost_ex_template;
            $add->save();
            $expense = PosExpenseTemplate::where('ex_template_place_id',$this->getCurrentPlaceId())->orderBy('ex_template_id','desc')->first();

            $exp_json=json_encode($expense);
            return $exp_json;

        }
        
    }

    public function checkData(Request $request)
    {
        if($request->ajax())
        {
            $monthData=$request->checkMonth;
            $pos_place=PosPlaceExpense::where('pe_place_id', $this->getCurrentPlaceId())
                            ->whereMonth('pe_date', '=', $monthData)->count();
            // $count= count($pos_place);
            return $pos_place;
        }
    }

    public function insertExpense(Request $request)
    {
        if($request->ajax())
        {
            $checkMonth= $request->checkMonth;
            $exp_date=date("Y").'-'.$checkMonth.'-'.date("d");
            $exp_template=PosExpenseTemplate::where('ex_template_place_id', $this->getCurrentPlaceId())->get();
            foreach($exp_template as $value){
                $exp_expense= new PosPlaceExpense;
                $exp_expense->pe_place_id = $value['ex_template_place_id'];

                $exp_expense->pe_name = $value['ex_template_name'];
                if($value['ex_template_cost']!=""){
                    $exp_expense->pe_cost = $value['ex_template_cost'];

                }
                else{
                    $exp_expense->pe_cost = "";
                }
                $exp_expense->pe_date = $exp_date;
                $exp_expense->save();
            }
            return $exp_template;
        }
    }
    public function changeStyle(Request $request){

        PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                        ->where('pe_id',$request->id)
                        ->update([$request->column => $request->style_id]);

        if($request->column == 'pe_cycle' && $request->style_id == 1){

            $cost_last_month = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                                ->where('pe_name',$request->pe_name)
                                                ->whereMonth('pe_date',$request->month_current-1)
                                                ->first()
                                                ->pe_cost;

            if(isset($cost_last_month)){

                PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                        ->whereMonth('pe_date',$request->month_current)
                        ->where('pe_name',$request->pe_name)
                        ->update(['pe_cost' => $cost_last_month]);
             }    
        }
    }
    public function changeBill(Request $request){

        PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                        ->where('pe_id',$request->id)
                        ->update(['pe_bill' => $request->pe_bill]);
    }
    // public function getExpensesAver(Request $request){
    //     $result = [];
    //     $arr_month = [];
    //     $expense_month = $request->expense_month;

    //     if($expense_month != "")
    //         $total_month = $expense_month;
    //     else
    //         $total_month = Carbon::now()->format('m');

    //     $current_year = Carbon::now()->format('Y');

    //     for ($i=1; $i <= $total_month; $i++) { 
    //         $arr_month[] = $i;
    //     }

    //     $catetory_list = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
    //                                     ->groupBy('pe_name')
    //                                     ->select('pe_name')
    //                                     ->get();
        
    //     foreach ($catetory_list as $key => $value) {

    //         $pe_name_arr = json_decode($value,true);
    //         $pe_name = $pe_name_arr['pe_name'];

    //         $category_cost = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
    //                                         ->whereYear('pe_date',$current_year)
    //                                         ->where(function($q) use($arr_month) {
    //                                             foreach ($arr_month as $month) {
    //                                                 $q->whereMonth('pe_date',"=", $month,"or");
    //                                             }
    //                                         })
    //                                         ->where('pe_name',$pe_name)
    //                                         ->sum('pe_cost');

    //         $category_cost_old = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
    //                                         ->whereYear('pe_date',($current_year-1))
    //                                         ->where(function($q) use($arr_month) {
    //                                             foreach ($arr_month as $month) {
    //                                                 $q->whereMonth('pe_date',"=", $month,"or");
    //                                             }
    //                                         })
    //                                         ->where('pe_name',$pe_name)
    //                                         ->sum('pe_cost');
    //         $result[]= [
    //             'pe_name' => $pe_name,
    //             'category_cost' => $category_cost,
    //             'aver_month' => round($category_cost/$total_month,2),
    //             'last_year' => round($category_cost_old/$total_month,2)
    //         ];
    //     }
    //     return Datatables::of($result)
    //                     ->make(true);

    // }
    public function expensesCopy(Request $request){

        $pe_list = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                ->whereMonth('pe_date',$request->month_selected-1)
                                ->get();

        

        $date = Carbon::now()->format('d');
        $year = Carbon::now()->format('Y');

            foreach($pe_list as $pe){

                $max_pe_id = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                      ->max('pe_id');
                $count = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                        ->whereMonth('pe_date',$request->month_selected)
                                        ->where('pe_name',$pe->pe_name)
                                        ->count();
                if($count == 0){
                    $arr = [
                        'pe_id' => $max_pe_id+1,
                        'pe_place_id' => $this->getCurrentPlaceId(),
                        'pe_name' => $pe->pe_name,
                        'pe_cost' => $pe->pe_cost,
                        'pe_date' => $year."-".$request->month_selected."-".$date,
                        'pe_pay' => $pe->pe_pay,
                        'pe_cycle' => 1,
                        'pe_bill' => $pe->pe_bill,
                        'pe_status' =>1
                    ];
                    PosPlaceExpense::create($arr);
                }
                
            }
            return $message = "Copy done!";
    }
    public function addNewPe(Request $request){

        $year = Carbon::now()->format('Y');
        $date = Carbon::now()->format('d');

        $check_name = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                      ->where('pe_name',$request->category)
                                      ->whereMonth('pe_date',$request->month_selected)
                                      ->whereYear('pe_date',$year)
                                      ->count();

        if($check_name > 0 )
            return 0;
        else{
            $max_pe_id = PosPlaceExpense::where('pe_place_id',$this->getCurrentPlaceId())
                                      ->max('pe_id');
            $arr = [
                'pe_id' => $max_pe_id+1,
                'pe_place_id' => $this->getCurrentPlaceId(),
                'pe_name' => $request->category,
                'pe_cost' => $request->amount,
                'pe_pay' =>$request->pay,
                'pe_cycle' => $request->cycle,
                'pe_bill' => $request->bill,
                'pe_date' => $year."-".$request->month_selected."-".$date,
                'pe_status' =>1
            ];
            PosPlaceExpense::create($arr);
            return 1;
        }
    }
}
