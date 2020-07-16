@foreach ($today_appointments as $value)

<div class="col-md-12">
    <div class="col-md-2 text-center"><b>{{format_dayMonth($value->booking_time_selected)}}<br />{{format_month($value->booking_time_selected)}}</b></div>
    <div class="col-md-9">
         <div class="appt_date">{{format_dayWeek($value->booking_time_selected)}} {{format_time24h($value->booking_time_selected)}} {!!\GeneralHelper::convertBookingStatusHtml($value->booking_status)!!} </div>
        <div>
            <span clas="staff">{{$value->customer_fullname}}</span> - <span class="service">{{$value->worker_nickname}}</span>
        </div>
    </div>
    {{-- <div class="col-md-1 text-right">$25</div> --}}
</div>           
<div class="col-md-12"><hr class="border-top" /></div>

@endforeach