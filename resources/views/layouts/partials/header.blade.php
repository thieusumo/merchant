<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu nav_menu_title">
    <nav>   
        <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
            <div class="left title_left">
                <h5 class="nav-header-title" >
                    {!! str_replace('|','<i class="fa fa-angle-right"></i>', $__env->yieldContent('title')) !!}
                </h5>
            </div>
            <ul class="nav navbar-nav navbar-right">  
                  


                 <li class="dropdown">
                   <a href="#" class="nav-link dropdown-toggle  user-profile" data-target="#" data-toggle="dropdown"  role="button" aria-haspopup="true"  aria-expanded="false">
                       <img src="{{config('app.url_file_view').Auth::user()->user_avatar}}"  width="16px"> 
                       <span>{{Auth::user()->user_nickname}} </span>
                   </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                   <li><a href="{{ route('change-profile') }}"><i class="fa fa-user"></i> Account Profile</a></li>
                   <li><a href="{{ route('change-password') }}"><i class="fa fa-key"></i> Change Password</a></li>
                   <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Log Out</a></li>   
                  </ul>                    
                 
               </li>
             
               <li class="dropdown">
                   @php $list_places = Session::get('place_arr');  @endphp
                   @if(count($list_places) == 1)
                        <a href='#'>
                            <img src="{{asset("images/icons8-shop-40.png")}}" alt="" width="16"> 
                            @php $place = $list_places->first(); @endphp
                            @if(!Empty($place->place_name)  )
                               {{ $place->place_name }}
                            @else
                               {{ $place->place_phone }}
                            @endif
                        </a>
                    @elseif(count($list_places) > 1)
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{asset('images/icons8-shop-40.png')}}" alt="" width="16">

                         @foreach( $list_places as $place)
                            
                           @if($place->place_id == Session::get('current_place_id') )
                             @if(!empty($place->place_name)  )
                               {{ $place->place_name }}
                             @else
                               {{ $place->place_phone }}
                             @endif
                           @endif
                         @endforeach
                         </a>
                        <ul name="select_place" id="select_place" class="dropdown-menu dropdown-menu-right">
                            @foreach($list_places as $place)
                             @if($place->place_id != Session::get('current_place_id') )
                                <li name="select_place" id="select_place" ><a onclick="change_place({{ $place->place_id }})" href="#">
                                  @if(!Empty($place->place_name)  )
                                    {{ $place->place_name }}
                                  @else
                                    {{ $place->place_phone }}
                                  @endif
                                </a>
                              </li>
                            @endif
                            @endforeach
                        </ul> 
                    @endif
               </li>
               <li id="notification" url="{{ url('/') }}" role="presentation" class="">
                    <a href="#" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                      <i class="fa  fa-bell-o"></i>
                      <span  id="count_notification"></span>    
                    </a>
                    <ul style="width: 18rem; margin-right: 150px;max-height:35rem;overflow:overlay;"  class=" scroll-view dropdown-menu list-unstyled msg_list" role="menu">
                      <div id="showNotification">
                        
                      </div>
                      {{-- @for ($i = 0; $i < 2; $i++)
                      <li class=" ">
                        <a id=" " class="click-notifice-class" link=" " href="#"> <span class="time"></span><span class="message">Requires your OneSignal User Auth Key, available in Keys & IDs.</span></a>
                      </li>
                      @endfor --}}
                      {{-- <li style="text-align:center;">
                        <a href="#">See more</a>
                      </li> --}}
                      <li class="btn-link seeMore" style="text-align:center;"><a href="#" id="seeMoreNotification" skip="0">See more</a></li>
                    </ul>
                </li>

            </ul>          
    </nav>
  </div>
<!-- /top navigation -->
@php

    print '<div class="clear nav_menu nav_menu_sub"><ul class="menu-tab">';
    $request_url = request()->segment(2);
    if(is_null($request_url)){
        $request_url = request()->segment(1);
    }

    $sub_menu = \App\Helpers\PermissionHelper::getSubMenusByParent(request()->segment(1));
    $place_menu = Session::get('place_menu');

    foreach($sub_menu as $item){

        if(in_array($item['mer_menu_id'],$place_menu)){

          $url = "";
          if($item["mer_menu_url"] != $item["parent_mer_menu_url"])
              $url = $item["parent_mer_menu_url"].'/'.$item["mer_menu_url"];
          else
              $url = $item["mer_menu_url"];

          $className = ($request_url == $item["mer_menu_url"]  )?' class="active"':'';     
          
          if(empty($className) && request()->is($item["mer_menu_url"])){
              $className = ' class="active"';   
          }
          printf('<li%s ><a href="%s">%s</a></li>',$className, asset($url), $item["mer_menu_text"]);
        }
        
    }
    print '</ul></div>'; 


    
@endphp
</div>
