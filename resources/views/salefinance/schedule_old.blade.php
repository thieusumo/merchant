@extends('layouts.master')
@section('title', 'Sales & Finances | Schedule')
@section('styles')
<link href="{{ asset('plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/fullcalendar/fullcalendar.print.css') }}" rel="stylesheet" media="print">    
@stop
@section('content')

<div class="row">
    <div class="x_panel">
        <div id='calendar'></div>
    </div>
</div>
@stop
@section('scripts')
<script src="{{ asset('plugins/fullcalendar/fullcalendar.min.js') }}"></script>
<script type="text/javascript">
var popoverElement;
var current_date="";
function closePopovers() {
    $('.popover').not(this).popover('hide');
}     
function initCalendar(){

    var date = new Date(),
        d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();

    

    $('#calendar').fullCalendar({
        themeSystem: 'standard',
        header: {
                left: 'prev,next today',
                center: 'title',
                right: 'agendaDay,month,agendaWeek',
        },
        defaultView: 'agendaDay',        
        firstDay: moment().day(),
        minTime: "{{$start_time}}:00",
        maxTime: "{{$end_time}}:00",
        weekNumbers: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        
        allDaySlot: false,
        timeFormat: 'HH:mm',
        slotLabelFormat: [
            'MMMM YYYY', // top level of text
            'ddd',        // lower level of text,
            'HH A'
        ],
        businessHours: {
          // days of week. an array of zero-based day of week integers (0=Sunday)
          dow: [ 0, 1, 2, 3, 4, 5, 6], // Monday - Sunday

          start: '{{$start_time}}', // a start time (10am in this example)
          end: '{{$end_time}}', // an end time (6pm in this example)
        },
        editable: false,    
        dayClick: function(date, jsEvent, view) {                   
            //redirect to payment page
            /*$(location).attr('href',"{{asset('/salefinance/payment')}}");*/
        },
        eventSources: [ getCalData() ],
        viewDisplay: function (view) {
            $('#calendar').fullCalendar('removeEvents');
            $('#calendar').fullCalendar('addEventSource', getCalData());
            $('#calendar').fullCalendar('rerenderEvents');
        },
        /*events: [{
                    id: 6,
                    price:70,
                    title: 'Jane Doe',
                    start: new Date(y, m, d, 13, 30),
                    end: new Date(y, m, d, 14, 30),
                    description: 'Blow Dry',
                    allDay: false,
                    textEscape: false,
              }],*/
         eventClick: function (calEvent, jsEvent, view) {   
             popoverElement = $(jsEvent.currentTarget);
        },  
        viewRender: function (view, element) {

            var date = $('#calendar').fullCalendar('getDate');
            current_date = date.format('YYYY-MM');

            $('#calendar').fullCalendar('removeEvents');
            $('#calendar').fullCalendar('addEventSource', getCalData());
            $('#calendar').fullCalendar('rerenderEvents');
            
         },
        eventRender: function(event, element){
            if(typeof(event.title) != 'undefined'){
                element.find('.fc-time').append(' <b>' + event.title + '</b>');
            }
            element.find('.fc-title, .fc-list-item-title').html(event.description); 
                $.ajax({
                async: false,
                url: '{{route("get-services-by-bookingid")}}',
                data: { id: event.id },
                success: function (data) {
                    popup_item = data;
                },
                error: function () {
                    alert('could not get the data');
                },
                });
            element.popover({       
                trigger: 'click',
                header: 'Detail',
                title:  '<div class="float-left" ><div class="name">'+event.title+'</div><div class="phone">(+'+event.country_code+')'+event.phone+'</div></div><div class="float-right" >'+popup_item['status']+'</div></br>',
                content:  `Time: `+popup_item['booking_datetime']+`   
                    
                    <div>`+event.description+`</div>
                    <div>`+popup_item['service_html']+`</div> 
                    </br>  
                    <div class="float-left align-middle "><b>Total: $`+popup_item['total_price']+`</b></div>         
                    <div class="float-right links">     
                        <a class="btn btn-success" href="/salefinance/payment/`+event.id+`">Payment</a>
                    </div>`,
                html: true,
                animation: true,
                container: 'body',
                placement: 'auto',
            });

          },  
      });   


    function getCalData() {

        if(current_date =="")
        {
            current_date = moment().format('YYYY-MM');
        }
        var source = [{}];
        $.ajax({
            async: false,
            url: '{{route("get-schedule-by-month")}}',
            data: { date: current_date },
            success: function (data) {
                $(data).each(function () {
                    source.push({
                        id: $(this).attr('booking_id'),
                        title: $(this).attr('customer_fullname'),
                        phone: $(this).attr('customer_phone'),
                        country_code: $(this).attr('customer_country_code'),
                        start: $(this).attr('booking_time_selected'),
                        description: "</br>Rent Station: "+$(this).attr('worker_nickname')
                    });
                });
            },
            error: function () {
                toastr.error('could not get the data');
            },
        });
        return source;
    }

      $('body').on('click', function (e) {
        // close the popover if: click outside of the popover || click on the close button of the popover
        if (popoverElement && ((!popoverElement.is(e.target) && popoverElement.has(e.target).length === 0 && $('.popover').has(e.target).length === 0) || (popoverElement.has(e.target) && e.target.id === 'closepopover'))) {

            ///$('.popover').popover('hide'); --> works
            closePopovers();
        }
    });       
}
$(document).ready(function(){
   initCalendar();
});
</script>
    
@stop

