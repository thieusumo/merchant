<?php

namespace App\Http\Controllers\DataSetup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosWorker;
use App\Models\PosCheckin;
use App\Models\PosPlace;
use App\Models\PosOrderdetail;
use yajra\Datatables\Datatables;
use App\Helpers\GeneralHelper;
use DB;
use DateTime;
use Carbon\Carbon;

class TaxFormController extends Controller
{
    private $join_date = '';
    private $year = '';
    private $dateNow = '';

    public function __construct()
    {
      $this->dateNow = format_date(now());
    }

    public function index()
    {
        return view('datasetup.taxforms');
    }
    public function getWorkerTaxform(Request $request){
        $this->join_date = $request->search_join_date;
        $this->year = Carbon::parse($request->get_date)->format('Y');

        $worker_list = PosWorker::join('pos_place',function($join){
                                  $join->on('pos_worker.worker_place_id','pos_place.place_id');
                                  })
                                  ->where('worker_place_id',$this->getCurrentPlaceId());
        $worker_list->where('worker_status',1)
          ->select('pos_place.*','pos_worker.*');
        return Datatables::of($worker_list)

           ->editColumn('worker_fullname',function($row){
                return $row->worker_lastname." ".$row->worker_firstname;
           })
           ->editColumn('worker_phone',function($row){
                return GeneralHelper::formatPhoneNumber($row->worker_phone);
           })
           ->editColumn('worker_birthday',function($row){
                return format_date($row->worker_birthday);
           })
           ->editColumn('worker_date_join',function($row){
                return format_date($row->worker_date_join);
           })
           ->addColumn('action',function($row){
            return '<a  class="btn btn-sm btn-secondary tax-form-1099" href="'.route('tax-form-1099',$row->worker_id).'?search='.$this->year.'" ><i class="fa fa-print"></i> Form 1099</a><a href="'.route('tax-form-w2',$row->worker_id).'?search='.$this->year.'" class="btn btn-sm btn-secondary tax-form-w2" ><i class="fa fa-print"></i> Form W2</a><a href="'.route('time-sheet',$row->worker_id).'?search='.$this->join_date.'" class="btn btn-sm btn-secondary timesheet"><i class="fa fa-download"></i>Time Sheet</a>';
           })
           ->rawColumns(['action'])
           ->make(true);
    }

    public function taxForm1099(Request $request,$id){
        $year = $request->search;

        $worker_list = PosWorker::join('pos_place',function($join){
                                  $join->on('pos_worker.worker_place_id','pos_place.place_id');
                                  })
                                 ->where('pos_worker.worker_place_id',$this->getCurrentPlaceId())
                                 ->where('worker_id',$id)
                                 ->first();

        $totalPrice = PosOrderdetail::select('orderdetail_price')
                        ->where('orderdetail_place_id',$this->getCurrentPlaceId())
                        ->where('orderdetail_worker_id',$id)
                        ->where('orderdetail_status',1)
                        ->whereYear('orderdetail_datetime',$year)
                        ->get()->sum('orderdetail_price');

        $workerPercent = $worker_list->worker_percent;
        $workerCashPercent = $worker_list->worker_cashpercent;
        $nomemployeeCompensation = $this->nomemployeeCompensation($totalPrice,$workerPercent,$workerCashPercent);
                           
        $worker_fullname =  $worker_list->worker_firstname." ".$worker_list->worker_lastname;

        $document  = new \PhpOffice\PhpWord\TemplateProcessor('file_tax/f1099.docx');
        $document->setValue('${place_name}',strtoupper($worker_list->place_name));
        $document->setValue('${place_street}',strtoupper($worker_list->place_address));
        $document->setValue('${place_city}',strtoupper($worker_list->place_city));
        $document->setValue('${place_taxcode}',strtoupper($worker_list->place_taxcode));
        $document->setValue('${place_zipcode}',strtoupper($worker_list->place_zipcode));
        $document->setValue('${worker_name}',strtoupper($worker_fullname));
        $document->setValue('${worker_address}', strtoupper($worker_list->worker_address));
        $document->setValue('${nomemployee}', number_format($nomemployeeCompensation));

        $file_name = preg_replace('/[^a-zA-Z0-9]/','_', $worker_fullname);
        $folder_path = public_path('file_tax/');
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
          }
        $document->saveAs('file_tax/'.$file_name."_taxform1099.docx");
        $file_path = public_path('file_tax/'.$file_name."_taxform1099.docx");
        return response()->download($file_path);

    }
    public function taxFormW2(Request $request,$id){
        $year = $request->search;

        $totalPrice = PosOrderdetail::select('orderdetail_price')
                        ->where('orderdetail_place_id',$this->getCurrentPlaceId())
                        ->where('orderdetail_worker_id',$id)
                        ->where('orderdetail_status',1)
                        ->whereYear('orderdetail_datetime',$year)
                        ->get()->sum('orderdetail_price');

        $worker_list = PosWorker::where('worker_place_id',$this->getCurrentPlaceId())
                                  ->where('worker_id',$id)
                                  ->first();

        $workerPercent = $worker_list->worker_percent;

        $salary = $totalPrice * $workerPercent/100;

        $socialSercurity = $salary * $worker_list->worker_cocial_security/100;

        $medicare = $salary * $worker_list->worker_medicare/100;

        $sdi = $salary * $worker_list->worker_sdi/100;

        $worker_fullname =  $worker_list->worker_firstname." ".$worker_list->worker_lastname;

        $document  = new \PhpOffice\PhpWord\TemplateProcessor('file_tax/fw2.docx');
        $document->setValue('${first_name}',strtoupper($worker_list->worker_firstname));
        $document->setValue('${last_name}',strtoupper($worker_list->worker_lastname));
        $document->setValue('${name}',strtoupper($worker_fullname));
        $document->setValue('${address}', strtoupper($worker_list->worker_address));
        $document->setValue('${salary}', number_format($salary));
        $document->setValue('${social_security}', number_format($socialSercurity));
        $document->setValue('${medicare}', number_format($medicare));
        $document->setValue('${sdi}', number_format($sdi));
        $file_name = preg_replace('/[^a-zA-Z0-9]/','_', $worker_fullname);
        $folder_path = public_path('file_tax/');
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
          }
        $document->saveAs('file_tax/'.$file_name."_taxformfw2.docx");
        $file_path = public_path('file_tax/'.$file_name."_taxformfw2.docx");
        return response()->download($file_path);

    }

    public function taxForm1096(Request $request){
        $year = $request->search;

        $place = PosPlace::where('place_id',$this->getCurrentPlaceId())->first();

        $totalWorker = PosOrderDetail::select('worker_id','worker_percent','worker_cash_percent','orderdetail_worker_id','orderdetail_status',DB::raw('SUM(orderdetail_price) as price'))
                                  ->where('worker_place_id',$this->getCurrentPlaceId())
                                  ->where('orderdetail_status',1)
                                  ->whereYear('orderdetail_datetime',$year)
                                  ->groupBy('orderdetail_worker_id')         
                                  ->join('pos_worker',function($joinWorker){
                                    $joinWorker->on('worker_place_id','orderdetail_place_id')
                                    ->on('worker_id','orderdetail_worker_id');
                                  })                                                           
                                  ->get();
                        // echo $totalWorker; die();
        $countWorker = $totalWorker->count();
        $totalNomemployeeCompensation = 0;
        foreach ($totalWorker as $value) {
          $totalNomemployeeCompensation += $this->nomemployeeCompensation($value->price,$value->worker_percent,$value->worker_cash_percent);
        }
        
        $document  = new \PhpOffice\PhpWord\TemplateProcessor('file_tax/f1096.docx');
        $document->setValue('${place_name}',strtoupper($place->place_name));
        $document->setValue('${place_street}',strtoupper($place->place_address));
        $document->setValue('${place_city}',strtoupper($place->place_city));
        $document->setValue('${place_taxcode}',strtoupper($place->place_taxcode));
        $document->setValue('${place_zipcode}',strtoupper($place->place_zipcode));
        $document->setValue('${count_worker}',strtoupper($countWorker));
        $document->setValue('${total_price}',number_format($totalNomemployeeCompensation));

        $folder_path = public_path('file_tax/');
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
          }
        $document->saveAs('file_tax/'."taxform1096.docx");
        $file_path = public_path('file_tax/'."taxform1096.docx");
        return response()->download($file_path);
    }
    /**
     * [timeSheet description]
     * @param  Request $request [ex input $request->search = "1/1/2019 - 12/12/2019"]
     * @param  [type]  $id      [id worker]
     * @return [type]           [description]
     */
    public function timeSheet(Request $request,$id){
      if($request->search){
        $arrSearch = explode("-", $request->search);
        $diff = $this->diffDateTime(trim($arrSearch[0]),trim($arrSearch[1]));
        // dd($diff);
        if($diff->days == 0){
          return $this->timeSheetDaily($arrSearch[0],$arrSearch[1],$id);
        } else if($diff->days == 6){
          return $this->timeSheetWeekly($arrSearch[0],$arrSearch[1],$id); 
        } else if($diff->days > 6){
          return $this->timeSheetMonthly($arrSearch[0],$arrSearch[1],$id);
        }        
        // die();
      }
    }

    
    /**
     * [add2times description]
     * @param  [type] $date_one [ex input: $hour_one = "2019-07-31 03:47:02"]
     * @param  [type] $date_two [ex input: $hour_two = "2019-07-31 04:47:02"]
     * @return [type]           [ex output: 01:00:00]
     */
    private function diffDateTime($date_one, $date_two){
      $date_one = trim($date_one);
      $date_two = trim($date_two);

      $date_one = date_create($date_one); 
      $date_two = date_create($date_two);
      $diff = date_diff($date_one,$date_two);

      return $diff;
    }

    public function allTimeSheet(Request $request){
      $timeSheet = PosCheckin::select('pos_checkin.*','worker_nickname')
                              ->where('checkin_place_id',$this->getCurrentPlaceId())
                              ->join('pos_worker',function($joinWorker){
                                $joinWorker->on('checkin_place_id','worker_place_id')
                                          ->on('checkin_worker_id','worker_id');
                              })
                              ->get();
      // dd($data);
      $date = format_date(now());
        return \Excel::create('TimeSheet_'.$date,function($excel) use ($timeSheet) {
            $excel ->sheet('TimeSheet', function ($sheet) use ($timeSheet)
            {   $i = 1;
                $sheet->cell('A'.$i, function($cell) {$cell->setValue('Id');});
                $sheet->cell('B'.$i, function($cell) {$cell->setValue('Nick Name');});
                $sheet->cell('C'.$i, function($cell) {$cell->setValue('IP Checkin');});
                $sheet->cell('D'.$i, function($cell) {$cell->setValue('Date');});
                $sheet->cell('E'.$i, function($cell) {$cell->setValue('Time');});
                $sheet->cell('F'.$i, function($cell) {$cell->setValue('Type');});
                $sheet->cell('G'.$i, function($cell) {$cell->setValue('Reason');});    
                $i++;
                foreach ($timeSheet as $value) {
                  $sheet->cell('A'.$i, $value->checkin_id);
                  $sheet->cell('B'.$i, $value->worker_nickname);
                  $sheet->cell('C'.$i, $value->checkin_ip_address);
                  $sheet->cell('D'.$i, format_date($value->checkin_datetime));
                  $sheet->cell('E'.$i, format_time24h($value->checkin_datetime));
                  $sheet->cell('F'.$i, $value->checkin_type == 1 ? "Checkin" : "Checkout");
                  $sheet->cell('G'.$i, $value->checkin_reason);
                  $i++;
                }
            });
          
        })->download("xlsx");
    }

    private function nomemployeeCompensation($totalPrice, $workerPercent, $workerCashPercent){ 
      $salary = $totalPrice * $workerPercent/100;
      $result = $salary - ($salary * $workerCashPercent);
      return $result;
    }

    /**
     * [timeSheetDaily description]
     * @param  [type] $startDateSearch [format: mm/dd/yyyy]
     * @param  [type] $endDateSearch   [format: mm/dd/yyyy]
     * @param  [type] $id              [id worker]
     * @return [type]                  [file excel .xlsx]
     */
    private function timeSheetDaily($startDateSearch,$endDateSearch,$id){
        $data = [];
        $worker = PosWorker::select('worker_nickname')
                            ->where('worker_place_id',$this->getCurrentPlaceId())
                            ->where('worker_status',1)
                            ->where('worker_id',$id)
                            ->first();
        $data = PosCheckin::select('checkin_type','checkin_datetime','checkin_reason')
                            ->where('checkin_place_id',$this->getCurrentPlaceId())
                            ->where('checkin_worker_id',$id)
                            ->whereDate('checkin_datetime',format_date_db($startDateSearch))
                            ->get(); 

        return \Excel::create('Daily_TimeSheet_'.$worker->worker_nickname."_".$startDateSearch,function($excel) use ($data,$worker,$startDateSearch,$endDateSearch) {
            $this->sheetDaily($excel,$data,$worker,$startDateSearch,$endDateSearch);                        
        })->download("xlsx");
    }
    /**
     * [timeSheetWeekly description]
     * @param  [type] $startDateSearch [format: mm/dd/yyyy]
     * @param  [type] $endDateSearch   [format: mm/dd/yyyy]
     * @param  [type] $id              [id worker]
     * @return [type]                  [file excel .xlsx]
     */
    private function timeSheetWeekly($startDateSearch,$endDateSearch,$id){
        $data = [];
        $worker = PosWorker::select('worker_nickname')
                            ->where('worker_place_id',$this->getCurrentPlaceId())
                            ->where('worker_status',1)
                            ->where('worker_id',$id)
                            ->first();
        $data = PosCheckin::select('checkin_type','checkin_datetime','checkin_reason')
                            ->where('checkin_place_id',$this->getCurrentPlaceId())
                            ->where('checkin_worker_id',$id)
                            ->whereDate('checkin_datetime','>=',format_date_db($startDateSearch))
                            ->whereDate('checkin_datetime','<=',format_date_db($endDateSearch))
                            ->get(); 
                            // echo  $data; die();
        return \Excel::create('Weekly_TimeSheet_'.$worker->worker_nickname."_".$startDateSearch."_to_".$endDateSearch,function($excel) use ($data,$worker,$startDateSearch,$endDateSearch) {
          $this->sheetWeeklyOrMonthly($excel,$data,$worker,$startDateSearch,$endDateSearch);
        })->download("xlsx");
    }
    /**
     * [timeSheetMonthly description]
     * @param  [type] $startDateSearch [format: mm/dd/yyyy]
     * @param  [type] $endDateSearch   [format: mm/dd/yyyy]
     * @param  [type] $id              [id worker]
     * @return [type]                  [file excel .xlsx]
     */
    private function timeSheetMonthly($startDateSearch,$endDateSearch,$id){
        $data = [];
        $worker = PosWorker::select('worker_nickname')
                            ->where('worker_place_id',$this->getCurrentPlaceId())
                            ->where('worker_id',$id)
                            ->where('worker_status',1)
                            ->first();
        $data = PosCheckin::select('checkin_type','checkin_datetime','checkin_reason')
                            ->where('checkin_place_id',$this->getCurrentPlaceId())
                            ->where('checkin_worker_id',$id)
                            ->whereDate('checkin_datetime','>=',format_date_db($startDateSearch))
                            ->whereDate('checkin_datetime','<=',format_date_db($endDateSearch))
                            ->get(); 
                            // echo  $data; die();
        return \Excel::create('Monthly_TimeSheet_'.$worker->worker_nickname."_".$startDateSearch."_to_".$endDateSearch,function($excel) use ($data,$worker,$startDateSearch,$endDateSearch) {
            $this->sheetWeeklyOrMonthly($excel,$data,$worker,$startDateSearch,$endDateSearch);
        })->download("xlsx");
    }
    /**
     * [sheetDaily description]
     * @return [type]                  [sheet column excel]
     */
    private function sheetDaily($excel,$data,$worker,$startDateSearch,$endDateSearch){
        $excel ->sheet('TimeSheet_'.$worker->worker_nickname, function ($sheet) use ($data,$worker,$startDateSearch,$endDateSearch)
            {  
                $sheet->cell('A1', "Employee Name: ".$worker->worker_nickname);
                $sheet->cell('A2', "Date: ".$startDateSearch);

                $sheet->cell('A4', function($cell) {$cell->setValue('Time');});
                $sheet->cell('B4', function($cell) {$cell->setValue('Status');});
                $sheet->cell('C4', function($cell) {$cell->setValue('Reason');});
                
                if (!empty($data)) {
                  $i = 5;
                  foreach ($data as $key => $value) {      
                        $sheet->cell('A'.$i, format_time24h($value->checkin_datetime)); 
                        $sheet->cell('B'.$i, $value->checkin_type == 1 ? "Checkin" : "Checkout"); 
                        $sheet->cell('C'.$i, $value->checkin_reason); 
                        $i++;  
                  }                     
                  $i++;  
                  $arrTime = [];
                  if(count($data) > 1){
                    for($key = 0; $key < count($data); $key = $key + 2){
                      $diff = $this->diffDateTime($data[$key]->checkin_datetime,$data[$key+1]->checkin_datetime);
                      $arrTime[$key]['h'] = $diff->h;
                      $arrTime[$key]['i'] = $diff->i;
                      $arrTime[$key]['s'] = $diff->s;
                    }
                  }
                  $totalHoursWorked = $this->totalArrTime($arrTime);

                  $sheet->cell('A'.$i++, 'Total Hours Worked: '.$totalHoursWorked); 
                  // $sheet->cell('A'.$i++, 'Total Hours: '); 
              }   
            });
    }
    /**
     * [sheetWeeklyOrMonthly description]
     * @return [type]   [sheet column excel]
     */
    private function sheetWeeklyOrMonthly($excel,$data,$worker,$startDateSearch,$endDateSearch){
        $excel ->sheet('TimeSheet_'.$worker->worker_nickname, function ($sheet) use ($data,$worker,$startDateSearch,$endDateSearch)
            {   
                $sheet->cell('A1', "Employee Name: ".$worker->worker_nickname);
                $sheet->cell('A2', "Date: ".$startDateSearch." - ".$endDateSearch);

                $sheet->cell('A4', function($cell) {$cell->setValue('Date');});
                $sheet->cell('B4', function($cell) {$cell->setValue('Day');});
                // $sheet->cell('C4', function($cell) {$cell->setValue('Total Hours');});
                $sheet->cell('C4', function($cell) {$cell->setValue('Hours Worked');});

                $startData = format_date_db($startDateSearch);
                $endData = format_date_db($endDateSearch);

                $arrDate = [];
                $j = 5;
                // field date column A and B
                while(strtotime($startData) <= strtotime($endData)){
                  $arrDate[] = format_date($startData);                   
                  $sheet->cell('A'.$j, format_date($startData));
                  $sheet->cell('B'.$j, format_dayWeek($startData));
                  $startData = Carbon::parse($startData)->addDay();   
                  $j++;
                }
                
                $arrDetail = $this->getDataByDate($arrDate,$data);
                // dd($arrDetail);
                // field date column C
                if (!empty($arrDetail)) {
                    // dd($arrDetail);
                    $rowOfColumnC = 5;                       
                    $arrTotalHoursWorker = [];
                    foreach ($arrDate as $keyArrDate => $valueDate) {
                      // dd($arrDetail[$valueDate]);
                      if(isset($arrDetail[$valueDate])){
                        // echo count($arrDetail[$valueDate]);
                            $arrTime = [];
                            if(count($arrDetail[$valueDate]) > 1){
                              for($key = 0; $key < count($arrDetail[$valueDate]); $key = $key + 2){
                                $diff = $this->diffDateTime($arrDetail[$valueDate][$key]->checkin_datetime, $arrDetail[$valueDate][$key+1]->checkin_datetime);
                                $arrTime[$key]['h'] = $diff->h;
                                $arrTime[$key]['i'] = $diff->i;
                                $arrTime[$key]['s'] = $diff->s;
                              }
                            }

                            $totalHoursWorked = $this->totalArrTime($arrTime);
                            $sheet->cell('C'.$rowOfColumnC, $totalHoursWorked);
                            // convert Arr when use function totalArrTime
                            $time = new Carbon($totalHoursWorked);
                            $h = Carbon::parse($time)->format('H');
                            $i = Carbon::parse($time)->format('i');
                            $s = Carbon::parse($time)->format('s');
                            $arrTotalHoursWorker[$keyArrDate]['h'] = $h;
                            $arrTotalHoursWorker[$keyArrDate]['i'] = $i;
                            $arrTotalHoursWorker[$keyArrDate]['s'] = $s;
                      }
                      $rowOfColumnC++;
                    }
                    $j++;
                // dd($arrTotalHoursWorker);
                $sumTotalHoursWorker = $this->totalArrTime($arrTotalHoursWorker);
                // $sheet->cell('A'.$j++, 'Total Hours Work: '); 
                $sheet->cell('A'.$j, 'Total Hours Worked: '.$sumTotalHoursWorker); 
                }   
                
            });
    }
    /**
     * [totalArrTime description]
     * @param  [type] $arrTime [ex $arrTime = ["h"=>"01","i"=>"00","s"=>"00"] ]
     * @return [type]          [description]
     */
    private function totalArrTime($arrTime){
      $h = 0;
      $i = 0;
      $s = 0;
      foreach ($arrTime as $value) {
        $h += $value['h'];
        $i += $value['i'];
        $s += $value['s'];
      }
      $result = $h.":".$i.":".$s;
      return $result;
    }

    private function getDataByDate($arrDate,$data){
      $arrResult = [];
      foreach ($arrDate as $valueArr){
        foreach ($data as $valueData){
          if($valueArr == format_date($valueData->checkin_datetime)){
            $arrResult[$valueArr][] = $valueData;
          }
        }
      }
      return $arrResult;
    }
      
}
