<!-- sidebar menu -->
<div id="sidebar-menu" class="clear main_menu_side hidden-print main_menu">    
  <div class="menu_section">          
    <ul class="nav side-menu">

@php   
    //dd(Session::get('user_menus'));
    $request_url = request()->segment(1);
    $place_menu = Session::get('place_menu');

    foreach(Session::get('user_menus') as $item){
        $className = ($request_url == $item["mer_menu_url"])?' class="active"':'';
        // return $item['mer_menu_id'];
        if(in_array($item['mer_menu_id'],$place_menu)){
        // dd($place_menu);
            if($item['mer_menu_url'] == $item['child_mer_menu_url']){
                $url = $item['mer_menu_url'];
            }  
            else{
                $url = $item['mer_menu_url'].'/'.$item['child_mer_menu_url'];
            }
            printf('<li%s ><a href="%s"><i class="fa %s"></i> %s</a></li>',$className, asset($url),$item["mer_menu_class"], $item["mer_menu_text"]);
        }
    }
@endphp
    </ul>
  </div>
</div>
<!-- /sidebar menu -->