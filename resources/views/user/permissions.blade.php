@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Users | Permission Setting')
@section('styles')    
<link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">  
<style>
    #table-per tbody tr td{padding: .35rem;}
    .switchery-small{width:43px;}
    .switchery-small > small{left: 33px;}
    .child{display: none}
</style>
@stop
@section('content')
<div class="x_panel">
    <table id="table-per" class="table table-striped table-bordered" style="width:100%;">
        <thead>
          <tr>
            <th>Action/Permission</th>
            @foreach($roles as $role)
            <th class="text-center" width="100">{{$role->ug_name}}</th>
            @endforeach              
          </tr>
        </thead>
        <tbody>
            @foreach(Session::get('user_menus') as $parent)
            @if( in_array($parent->mer_menu_id,$place_menu))
                <tr class="group" onclick="foggleElement(this,'{{preg_replace("/[^A-Za-z0-9\-]/",'_',$parent->mer_menu_text)}}')"><td colspan="10"><span   style="color: #0874e8" class="glyphicon glyphicon-plus-sign"></span> {{$parent->mer_menu_text}}</td></tr>
                @php
                $collection = collect(Session::get('user_sub_menu'));
                $children = $collection->where('mer_menu_parent_id', $parent->mer_menu_id);
                @endphp
                @foreach($children as $child)
                @if( in_array($child['mer_menu_id'], $place_menu))
                    <tr  class="child group {{preg_replace("/[^A-Za-z0-9\-]/",'_',$parent->mer_menu_text)}}" style="text-indent: 30px"><td colspan="10">{{$child['mer_menu_text']}}</td></tr>
                    @php
                    $collection_permissions = collect(Session::get('user_permission'));
                    $permission = $collection_permissions->where('mer_menu_id', $child['mer_menu_id']);
                    @endphp
                    @foreach($permission as $per)
                        <tr class="child {{preg_replace("/[^A-Za-z0-9\-]/",'_',$parent->mer_menu_text)}}">
                            <td style="text-indent: 60px">{{$per->mp_display_name}}</td>

                            @foreach($roles as $role)
                            @php 
                            $permission_text = $role->mp_id;
                            $permission_array = explode(",", $permission_text);
                            if(in_array($per->mp_id,$permission_array)){
                                $check = "checked";
                                $check_id = 1;
                            }else 
                            {
                                $check = "";
                                $check_id = 0;
                            }
                            @endphp
                            <td class="text-center" id="per_{{$role->ug_id}}_{{$per->mp_id}}">
                                <input type="checkbox" permission_id="{{$per->mp_id}}" id="permission_{{$role->ug_id}}_{{$per->mp_id}}" ug_id="{{$role->ug_id}}" check_id="{{$check_id}}" class="js-switch" {{$check}} />
                            </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endif
                @endforeach
            @endif
            @endforeach
        </tbody>    
    </table>  
</div>
@stop
@section('scripts')
 <script type="text/javascript" src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
   if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A',
                className : 'switchery switchery-small'
                
            });
        });
   }

}); 
function onChange(el) {
        if (typeof Event === 'function' || !document.fireEvent) {
            var event = document.createEvent('HTMLEvents');
            event.initEvent('change', true, true);
            el.dispatchEvent(event);
        } else {
            el.fireEvent('onchange');
        }
    }
$(document).on('click','.switchery', function(){

        var id = $(this).siblings('input').attr('id');

        var permission_id = $(this).siblings('input').attr('permission_id');

        var check_id = $(this).siblings('input').attr('check_id');

        var ug_id = $(this).siblings('input').attr('ug_id');

        var _token = $('meta[name=csrf-token]').attr('content');

        if(1 == 1)
        {
          $.ajax({
            url:"{{route('change-permission')}}",
            method:"post",
            data:{id:id,_token:_token,permission_id:permission_id,check_id:check_id,ug_id:ug_id},
            success:function(response)
            {
                var data = eval('('+response +')'); // Ajax response
                var id = data.id;
                var check_id = data.check_id;
                $("#"+id).attr('check_id',check_id);
                // console.log(response);
                toastr.success('Update Permission Succsess!',"Success!");
            }
          })
        }
        else{
            var parent_id = $(this).parent().attr('id');

            var clickCheckbox = document.querySelector( "#"+parent_id+" .js-switch");

            if (clickCheckbox.checked) {
                clickCheckbox.checked = false;
                onChange(clickCheckbox);
            }
            else {
                clickCheckbox.checked = true;
                onChange(clickCheckbox);
            }

        }
    });
function foggleElement(that,class_element){
    $("."+class_element).slideToggle(500);
    $(that).children().children('span').toggleClass('glyphicon-plus-sign glyphicon-minus-sign');
}
</script>     
@stop
