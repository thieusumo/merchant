<table class="table table-bordered">
                <thead>
                  <tr class="text-center" style="background-color: #009FD6">
                    <th colspan="{{$colspan}}">
                      <h5 style="color: white;">SMS MARKETING FULL PACKAGE</h>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="background-color: #BED6EE">
                    <td class="with_column1">Package Price</td>
                    @foreach($arr as $value)
                    <td class="text-center">${{$value->servicedetail_price}}</td>
                    @endforeach
                  </tr>
                  <tr style="background-color: #E0EAF6">
                    <td class="with_column1">Total SMS</td>
                    @foreach($arr as $value)
                      @if($value->servicedetail_value)

                        @php
                          $explode=explode(";",$value->servicedetail_value);
                        @endphp
                        <td class="text-center">${{$explode[0]}}</td>
                      @else
                        <td class="text-center"></td>
                      @endif
                    @endforeach
                  </tr>
                  <tr style="background-color: #EEEFEA">
                    <td class="with_column1">Bonus SMS</td>
                   @foreach($arr as $value)
                      @if($value->servicedetail_value)
                        @php
                          $explode=explode(";",$value->servicedetail_value);
                        @endphp
                        <td class="text-center">${{$explode[1]}}</td>
                      @else
                        <td class="text-center"></td>
                      @endif
                    @endforeach
                  </tr>

                  
                  @foreach($arrpackage as $key=>$value)
                  <tr>
                    <td class="with_column1">{{$value}}</td>
                          @foreach($arr as $vl)
                            @php
                              $explode=explode(";",$vl->servicedetail_value);
                              $decode=json_decode($explode[2]);
                              $explode1=explode(",",$decode->id);
                            @endphp
                            @if(in_array($key, explode(",",$decode->id)))
                              <td class="text-center"><i class='fa fa-check' style='font-size:24px'></i></td>
                            @else
                              <td class="text-center"><i class='fa fa-close' style='font-size:24px; color: #B30909'></i></td>
                            @endif
                          @endforeach
                  </tr>
                  @endforeach
                      
                  <tr class="text-center">
                    <td style="border-color: white; font-size: 16px; color: red;">tollfree : 888 840 8070</td>
                    @foreach($arr as $value)
                    <td id="{{$value->servicedetail_id}}" class="" style="background-color: #009FD6;color: white;">
                      <form action="{{route('view_buy_sms')}}" method="get">
                        @csrf
                        <input type="hidden" name="id" value="{{$value->servicedetail_id}}">
                        <button type="submit" class="hv_pointer" style="background-color: #009FD6;color: white;border: none; width: 100%; height: 100%;">Buy</button>
                      </form>
                    </td>
                    @endforeach
                  </tr>
                </tbody>
              </table>