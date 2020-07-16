
<a href="#" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                      <i class="fa  fa-bell-o"></i>
                      <span class="bg-blue" id="number_notification">{{$users}}</span>
                    </a>
                    <ul style="width: 350px; margin-right: 150px;" id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                      <li id="info_notification">
                        @foreach($data as $k=>$v)
                        <a id="{{$v->id}}" class="click-notifice-class" link="{!!$v->link_notification!!}">
                          <span>
                            <span>{{$v->type_notification}}</span>
                            <span class="time">{!!
                              date('d-m-Y', strtotime($v->created_at));
                              !!}
                            </span>
                          </span>
                          <span class="message">
                            {{$v->name_notification}}
                          </span>
                          <span style="padding-left: 50px;">-----------------------</span>
                        </a>
                        @endforeach

                      </li>

                    </ul>
