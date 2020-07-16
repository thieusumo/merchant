@extends('layouts.master')
@section('title', 'Dashboard')
@section('styles')
    
@stop
@section('content')
<div class="clearfix">&nbsp;</div>
<div class="col-md-12">
    <div class="col-md-5 col-sm-11 col-xs-11">
        <div class="x_panel" style="min-height:420px;">
            <div class="x_title">
                <h6>Today's Appointments</h6>
            </div>
            <div id="divTodayAppointments" class="x_content row">
                
            </div>
        </div>
    </div>
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="x_panel" style="min-height:420px;">
            <div class="x_title">
                <h6>Upcoming Appointments</h6>
            </div>
            <div class="x_content">
                <canvas id="chartCanvas"> </canvas>
            </div>
        </div>
    </div>
    
    
</div>    
@stop 
@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/chart.js/chart.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    
   $.ajax({
        type    : 'GET',
        url     : '{{ route("getTodayAppointments")}}',
        dataType: 'html',
        success :function(response){
            $("#divTodayAppointments").html(response);
        }
    }); 
   $.ajax({
        type    : 'GET',
        url     : '{{ route("getUpcomingAppointmentChart")}}',
        dataType: 'json',
        success :function(response){
            var ctx = document.getElementById("chartCanvas");
            var chartCanvas = new Chart(ctx, {
                  type: 'bar',
                  data: response,
                  options: {
                    scales: {
                          yAxes: [{
                            ticks: {
                                  beginAtZero: true
                            }
                          }]
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                              fontSize: 12,
                              boxWidth: 12,
                        }
                    },
                    animation: {
                        duration: 1,
                        onComplete: function () {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;
                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];                            
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    }
                  },

            });
        }
    });
    
});    
</script>    
@stop

