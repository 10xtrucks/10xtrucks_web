@extends('user.layout.base')

@section('title', 'Ride Confirmation ')

@section('styles')
<style type="text/css">
    .surge-block{
        background-color: black;
        width: 50px;
        height: 50px;
        border-radius: 25px;
        margin: 0 auto;
        padding: 10px;
        padding-top: 15px;
    }
    .surge-text{
        top: 11px;
        font-weight: bold;
        color: white;
    }
</style>
@endsection

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.ride.ride_now')</h4>
            </div>
        </div>
        @include('common.notify')
        <div class="row no-margin">
            <div class="col-md-6">
                <form action="{{url('create/ride')}}" method="POST" id="create_ride">
                
                {{ csrf_field() }}
                    <dl class="dl-horizontal left-right">
                        <dt>@lang('user.type')</dt>
                        <dd>{{$service->name}}</dd>
                        <dt>@lang('user.total_distance')</dt>
                        <dd>{{$fare->distance}} Kms</dd>
                        <dt>@lang('user.eta')</dt>
                        <dd>{{$fare->time}}</dd>
                        <dt>@lang('user.estimated_fare')</dt>
                        <dd>{{currency($fare->estimated_fare)}}</dd>
                        <dt>@lang('user.service_item')</dt>
                        @foreach($request->service_items as $key)
                        <dd>{{($key)}}</dd>
                        @endforeach
                        <hr>
                        @if(Auth::user()->wallet_balance > 0)

                        <input type="checkbox" name="use_wallet" value="1"><span style="padding-left: 15px;">@lang('user.use_wallet_balance')</span>
                        <br>
                        <br>
                            <dt>@lang('user.available_wallet_balance')</dt>
                            <dd>{{currency(Auth::user()->wallet_balance)}}</dd>
                        @endif
                    </dl>


                   
                    @foreach($request->s_latitude as $key => $value) 

                    <input type="hidden" name="s_address[{{$key}}]" value="{{$request->s_address[$key]}}">
                    <input type="hidden" name="d_address[{{$key}}]" value="{{$request->d_address[$key]}}">
                    <input type="hidden" name="reseiver_name[{{$key}}]" value="{{$request->reseiver_name[$key]}}">
                    <input type="hidden" name="reseiver_mobile[{{$key}}]" value="{{$request->reseiver_mobile[$key]}}">
                    <input type="hidden" name="reseiver_country_code[{{$key}}]" value="{{$request->reseiver_country_code[$key]}}">
                    <input type="hidden" name="s_latitude[{{$key}}]" value="{{$value}}">
                    <input type="hidden" name="s_longitude[{{$key}}]" value="{{$request->s_longitude[$key]}}">
                    <input type="hidden" name="d_latitude[{{$key}}]" value="{{$request->d_latitude[$key]}}">
                    <input type="hidden" name="d_longitude[{{$key}}]" value="{{$request->d_longitude[$key]}}">
                    <input type="hidden" name="service_type" value="{{$request->service_type}}">
                    <input type="hidden" name="service_items[{{$key}}]" value="{{$request->service_items[$key]}}">
                    <input type="hidden" name="weight[{{$key}}]" value="{{$request->weight[$key]}}">
                    @if($request->comment[$key]!='')
                    <input type="hidden" name="comment[{{$key}}]" value="{{$request->comment[$key]}}">
                    @endif
                    @if(isset($request->fragile[$key]))
                    <input type="hidden" name="fragile[{{$key}}]" value="{{$request->fragile[$key]}}">
                    @endif
                    <input type="hidden" name="distance" value="{{$fare->distance}}">
                    @endforeach
                    <p>@lang('user.payment_method')</p>
                    <select class="form-control" name="payment_mode" id="payment_mode" onchange="card(this.value);">
                        @if(Setting::get('CASH') == 1)
                            <option value="CASH">CASH</option>
                        @endif

                        @if(Setting::get('BOL') == 1)
                            <option value="BOL">BOL</option>
                        @endif
                        <!-- <option value="PAYPAL">PAYPAL</option> -->
                        @if(Setting::get('CARD') == 1)
                            @if($cards->count() > 0)
                                <option value="CARD">CARD</option>
                            @endif
                        @endif
                    </select>
                    <br>

                    @if(Setting::get('CARD') == 1)
                        @if($cards->count() > 0)
                        <select class="form-control" name="card_id" style="display: none;" id="card_id">
                          <option value="">Select Card</option>
                          @foreach($cards as $card)
                            <option value="{{$card->card_id}}">{{$card->brand}} **** **** **** {{$card->last_four}}</option>
                          @endforeach
                        </select>
                        @endif
                    @endif

                    @if($fare->surge == 1)

                        <span><em>Note : Due to High Demand the fare may vary!</em></span>
                        <div class="surge-block"><span class="surge-text">{{$fare->surge_value}}</span>
                        </div>
                    
                    @endif

                    <button type="submit" class="half-primary-btn fare-btn">@lang('user.ride.ride_now')</button>
                    <button type="button" class="half-secondary-btn fare-btn" data-toggle="modal" data-target="#schedule_modal">Schedule Pickup</button>

                </form>
            </div>

            <div class="col-md-6">
                <div class="user-request-map">
                 

                    <?php 
                    $map_icon = asset('asset/img/marker-start.png');
                    $static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=600x450&maptype=roadmap&format=png&visual_refresh=true&markers=icon:".$map_icon."%7C".array_first($request->s_latitude).",".array_first($request->s_longitude)."&markers=icon:".$map_icon."%7C".array_first($request->d_latitude).",".array_first($request->d_longitude)."&path=color:0x191919|weight:8|".array_first($request->s_latitude).",".array_first($request->s_longitude)."|".array_first($request->d_latitude).",".array_first($request->d_longitude)."&key=".Setting::get('google_map_key'); ?>
                    <div class="map-static" style="background-image: url({{$static_map}});">
                    </div>
                    
                    <div class="from-to row no-margin">
                        <div class="from">
                            <h5>FROM</h5>
                            <p>{{array_first($request->s_address)}}</p>
                        </div>
                        <div class="to">
                            <h5>TO</h5>
                            <p>{{array_first($request->d_address)}}</p>
                        </div>
                    </div>
                   
                </div> 
            </div>
        </div>

    </div>
</div>



<!-- Schedule Modal -->
<div id="schedule_modal" class="modal fade schedule-modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Schedule a Ride</h4>
      </div>
      <form>
      <div class="modal-body">
        
        <label>Date</label>
        <input value="{{date('m/d/Y')}}" type="text" id="datepicker" placeholder="Date" name="schedule_date">
        <label>Time</label>
        <input value="{{date('H:i')}}" type="text" id="timepicker" placeholder="Time" name="schedule_time">

      </div>
      <div class="modal-footer">
        <button type="button" id="schedule_button" class="btn btn-default" data-dismiss="modal">Schedule Ride</button>
      </div>

      </form>
    </div>

  </div>
</div>


@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#schedule_button').click(function(){
                $("#datepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
                $("#timepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
                document.getElementById('create_ride').submit();
            });
        });
    </script>
    <script type="text/javascript">
        var date = new Date();
        date.setDate(date.getDate());
        $('#datepicker').datepicker({  
            startDate: date,
            endDate : "+7d"
        });
        $('#timepicker').timepicker({showMeridian : false});
    </script>
    <script type="text/javascript">
        function card(value){
            if(value == 'CARD'){
                $('#card_id').fadeIn(300);
            }else{
                $('#card_id').fadeOut(300);
            }
        }
    </script>
@endsection