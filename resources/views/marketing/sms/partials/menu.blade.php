@php $list_menu = [
        ['url' => 'marketing/sms','text' => 'Account Summary', 'class' => ''],
        ['url' => 'marketing/sms/bps','text' => 'Booking & Payment', 'class' => ''],
        ['url' => 'marketing/sms/sendsms','text' => 'Send SMS', 'class' => ''],
        ['url' => 'marketing/sms/mgmt','text' => 'SMS Management', 'class' => ''],        
        ['url' => 'marketing/sms/tpl','text' => 'List Content Template', 'class' => ''],
        ['url' => 'marketing/sms/tpladd','text' => 'Add  Content Template', 'class' => ''],
        ['url' => 'marketing/sms/greceiver','text' => 'List Group Receivers', 'class' => ''],
        ['url' => 'marketing/sms/greceiveradd','text' => 'Add Group Receivers', 'class' => ''],
    ];
    
    for($i=0; $i< count($list_menu); $i++){        
        $list_menu[$i]['class'] = request()->is($list_menu[$i]['url'])?'active':'';        
        $list_menu[$i]['url'] = url($list_menu[$i]['url']);
    }
@endphp
<ul class="list-menu-bar">     
    @foreach($list_menu as $menu)
        <li class="{{ $menu['class'] }}"> <a href="{{ $menu['url'] }}">{{ $menu['text'] }}</a></li>              
   @endforeach    
</ul>        