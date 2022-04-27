@foreach($services as $service)
    @if($service->user_type == (Auth::user()->user_type))
    <div class="car-radio">
        <input type="radio" 
        name="service_type"
        value="{{$service->id}}"
        id="service_{{$service->id}}"
        @if ($loop->first) checked="checked" @endif>
        <label for="service_{{$service->id}}">
            <div class="car-radio-inner">
                <div class="img"><img src="{{image($service->image)}}"></div>
                <div class="name"><span>{{$service->name}}</span></div>
            </div>
        </label>
    </div>
    @endif
@endforeach