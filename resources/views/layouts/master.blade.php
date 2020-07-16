<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>DEG | @yield('title')</title>
<link rel="icon" href="{{ asset('favicon.ico') }}?v=2019218" type="image/ico" />
<link href="{{ asset('css/glyphicons.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/nprogress/nprogress.css') }}" rel="stylesheet">    
<link href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">    
<link href="{{ asset('plugins/iCheck/skins/flat/green.css') }}" rel="stylesheet">      
<link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">             
@if(!empty($displayDataTables) and $displayDataTables)
<link href="{{ asset('plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">    
<link href="{{ asset('plugins/datatables.net-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">    
<link href="{{ asset('plugins/datatables.net-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"> 
<link href="{{ asset('plugins/datatables.net-bs4/css/fixedHeader.bootstrap4.min.css') }}" rel="stylesheet">    
<link href="{{ asset('plugins/datatables.net-bs4/css/keyTable.bootstrap4.min.css') }}" rel="stylesheet">    
<link href="{{ asset('plugins/datatables.net-bs4/css/rowGroup.bootstrap4.min.css') }}" rel="stylesheet">    
  
@endif
<link href="{{ asset('css/theme.css') }}" rel="stylesheet">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style type="text/css">
  a:hover{
    color: blue;
  }
  li#notification ul.msg_list li a{
    padding: 5px 5px!important;
  }
  li#notification ul.msg_list li:last-child{
    padding: 3px 3px!important;
  }
</style>
@yield('styles')   
</head>

<body class="nav-sm">
   <div id="mode-view" style="width: 100%;height: 100%;background-color: #274360;display:none ;z-index: 10000;position: absolute;top: 0px;left: 0px">
     <h2 class="text-center" style="background-color: #000;color: #fff">PLEASE USE LANDSCAPE MODE</h2>
   </div>
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
              <div class="left_col scroll-view">
                <div class="nav_logo">
                    <a class="logo" href="{{asset('/')}}" class="site_title">
                        <img height="19px" src="{{ asset('images/logo_60x17.png') }}"/>
                    </a>
                </div>
                 @include('layouts.partials.slidebar_menu')
              </div>
            </div>
            
            @include('layouts.partials.header')            
            <div id="page-content" class=" right_col" role="main">
                @yield('content')
            </div>
           
            @include('layouts.partials.footer')
        </div>
    </div>    
</body>

<!--Common Libraries -->
<script type="text/javascript">
  var socket="";
</script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/nprogress/nprogress.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/min/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datejs/date.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script>
   $(document).ready(function() {
    
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
      $("#mode-view").css('display','');
}
    window.addEventListener("orientationchange", function() {

      var mode = window.orientation;
          if(mode === 0){
            $("#mode-view").css('display', '');
          }else
            $("#mode-view").css('display', 'none');
    }, false);
    
  });
 </script>
<script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
@include('layouts.message.message')
@if(!empty($displayDataTables) and $displayDataTables)
<!-- Datatables -->
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/jquery.dataTables.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/dataTables.buttons.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/buttons.bootstrap4.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/buttons.flash.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/buttons.html5.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/dataTables.keyTable.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('plugins/datatables.net-bs4/js/responsive.bootstrap4.min.js') }}"></script>
 
 <script type="text/javascript">
     $(document).ready(function(){
         $.fn.dataTable.ext.errMode = function (settings, tn, msg) {
            if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
               // Handling for 401 specifically
               toastr.warning("Session expired");               
            }else{
               toastr.warning(msg);
            }
         };
     });
 </script>
@endif
<script src="{{ asset('js/app.js') }}"></script>

@yield('scripts')
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "{{env('ONE_SIGNAL_APP_ID')}}",
      notifyButton: {
        enable: true,
      },

      allowLocalhostAsSecureOrigin: true,
    });
  });
  OneSignal.push(function() {
      OneSignal.getUserId(function(userId) {
        console.log(userId);
        $.ajax({
          url:"{{ route('addDeviceToken') }}",
          method:"post",
          data:{
            _token:"{{csrf_token()}}",
            userId,
          },
        });
      });
    });


</script>
<script>
  function appendNotification(skip = null){
    $.ajax({
      url:"{{ route('get5Notification') }}",
      method:"get",
      dataType:"json",
      data:{skip},
      success:function(data){
        if(data.status){
          html = '';
          for(var i = 0; i < data.data.length; i++ ){
            var readed = '';
            if(data.data[i].notification_readed == 1){
              readed = 'style="color: #495663;"';
            }
            html += '<li class=" ">'
                    +'<a id="'+data.data[i].id+'" '+readed+' class="click-notifice-class" link=" " href="'+data.data[i].notification_link+'"><span class="time"></span>'
                    +'<span class="message">'+data.data[i].notification_message+'</span>'
                    +'</a>'
                    +'</li>';
          }
          $("#showNotification").append(html);
          $("#count_notification").text(data.count);
          if(data.data.length == 0){
            $("#seeMoreNotification").parent().hide();
          }
        }
      },
      error:function(){
        toastr.error("Failed to show notification!");
      }
    })
  } 
  $(document).ready(function(){
    appendNotification();
    $(document).on('click','#seeMoreNotification',function(e){
      e.preventDefault();
      var skip = $(this).attr('skip');
      skip++;
      $(this).attr('skip',skip);
      appendNotification(skip);
      return false;
    });

    $(document).on('click','a.click-notifice-class',function(e){
      e.preventDefault();
      var id = $(this).attr('id');
      var url = $(this).attr('href');
      $.ajax({
        url:"{{ route('readNotification') }}",
        method:"post",
        data:{
          _token:"{{csrf_token()}}",
          id,
        },
        success:function(data){
          $(location).attr('href', url);
        },
        error:function(){
          toastr.error("Failed to read notification!");
        }
      });
    });
  });
</script>
</html>