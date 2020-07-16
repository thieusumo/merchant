<div class="col-md-3"  onclick="changeService()" >
    <h2 class="StepTitle text-center">Service & Rent</h2>
    <select class="selectpicker form-control form-control-sm" id="cateservice_list" data-show-subtext="true" data-live-search="true">
       @foreach($cateservice_list as $cateservice)
          <optgroup label="{{$cateservice->cateservice_name}}">
            @php
            $service_collect = collect($service_list);
            $service_array = $service_collect->where('service_cate_id',$cateservice->cateservice_id);
            @endphp
            @foreach($service_array as $service)
                <option class="service" value="{{$service->service_id}}">{{$service->service_name}}</option>
            @endforeach
          </optgroup>
        @endforeach
    </select>
</div>