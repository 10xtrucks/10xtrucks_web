@extends('admin.layout.base')

@section('title', 'Request details ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h4>@lang('admin.static_content_provider.request_details')</h4>
            <a href="{{ route('admin.requests.index') }}" class="btn btn-default pull-right">
                <i class="fa fa-angle-left"></i> @lang('admin.back')
            </a>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">@lang('admin.request.User_Name') :</dt>
                        @if($request->user != null)
                            <dd class="col-sm-8">{{ $request->user->first_name }}</dd>
                        @else
                            <dd class="col-sm-8"> - </dd>
                        @endif

                        <dt class="col-sm-4">@lang('admin.request.Provider_Name') :</dt>
                        @if($request->provider)
                        <dd class="col-sm-8">{{ $request->provider->first_name }}</dd>
                        @else
                        <dd class="col-sm-8">@lang('admin.request.not_provider')!</dd>
                        @endif

                        <dt class="col-sm-4">@lang('admin.request.total_distance') :</dt>
                        <dd class="col-sm-8">{{ $request->distance ? $request->distance : '-' }}</dd>

                        @if($request->status == 'SCHEDULED')
                        <dt class="col-sm-4">@lang('admin.request.schedule_time') :</dt>
                        <dd class="col-sm-8">
                            @if($request->schedule_at != "0000-00-00 00:00:00")
                                {{ date('jS \of F Y h:i:s A', strtotime($request->schedule_at)) }} 
                            @else
                                - 
                            @endif
                        </dd>
                        @else
                        <dt class="col-sm-4">@lang('admin.request.start_time') :</dt>
                        <dd class="col-sm-8">
                            @if($request->started_at != "0000-00-00 00:00:00")
                                {{ date('jS \of F Y h:i:s A', strtotime($request->started_at)) }} 
                            @else
                                - 
                            @endif
                         </dd>

                        <dt class="col-sm-4">@lang('admin.request.end_time') :</dt>
                        <dd class="col-sm-8">
                            @if($request->finished_at != "0000-00-00 00:00:00") 
                                {{ date('jS \of F Y h:i:s A', strtotime($request->finished_at)) }}
                            @else
                                - 
                            @endif
                        </dd>

                        <dt class="col-sm-4">@lang('admin.request.after_image') :</dt>
                        <dd class="col-sm-8">
                            @foreach($request->userdrop as $userdrop)
                                <img src="{{ $userdrop->after_image}}" style="height:300px;width:500px"></img> <br>
                            @endforeach
                        </dd>
                        @endif

                        <dt class="col-sm-4">@lang('admin.static_content_provider.sender_address') :</dt>
                        <dd class="col-sm-8">{{ $request->s_address ? $request->s_address : '-' }}</dd>

                        <dt class="col-sm-4">@lang('admin.static_content_provider.receiver_address') :</dt>
                        <dd class="col-sm-8">{{ $request->d_address ? $request->d_address : '-' }}</dd>

                        @if($request->payment)
                        <dt class="col-sm-4">@lang('admin.service.Base_Price') :</dt>
                        <dd class="col-sm-8">{{ currency($request->payment->fixed) }}</dd>

                        <dt class="col-sm-4">@lang('admin.service.tax_price') :</dt>
                        <dd class="col-sm-8">{{ currency($request->payment->tax) }}</dd>

                        <dt class="col-sm-4">@lang('admin.payment.total_amount') :</dt>
                        <dd class="col-sm-8">{{ currency($request->payment->total) }}</dd>
                        @endif

                        <dt class="col-sm-4">@lang('admin.service.delivery_status') : </dt>
                        <dd class="col-sm-8">
                            {{ $request->status }}
                        </dd>

                    </dl>
                </div>
                <div class="col-md-6">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style type="text/css">
    #map {
        height: 450px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    var map;
    var zoomLevel = 11;

    function initMap() {

        map = new google.maps.Map(document.getElementById('map'));

        var marker = new google.maps.Marker({
            map: map,
            icon: '/asset/img/marker-start.png',
            anchorPoint: new google.maps.Point(0, -29)
        });

         var markerSecond = new google.maps.Marker({
            map: map,
            icon: '/asset/img/marker-end.png',
            anchorPoint: new google.maps.Point(0, -29)
        });

        var bounds = new google.maps.LatLngBounds();

        source = new google.maps.LatLng({{ $request->s_latitude }}, {{ $request->s_longitude }});
        destination = new google.maps.LatLng({{ $request->d_latitude }}, {{ $request->d_longitude }});

        marker.setPosition(source);
        markerSecond.setPosition(destination);

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true, preserveViewport: true});
        directionsDisplay.setMap(map);

        directionsService.route({
            origin: source,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                console.log(result);
                directionsDisplay.setDirections(result);

                marker.setPosition(result.routes[0].legs[0].start_location);
                markerSecond.setPosition(result.routes[0].legs[0].end_location);
            }
        });

        @if($request->provider && $request->status != 'COMPLETED')
        var markerProvider = new google.maps.Marker({
            map: map,
            icon: "/asset/img/marker-car.png",
            anchorPoint: new google.maps.Point(0, -29)
        });

        provider = new google.maps.LatLng({{ $request->provider->latitude }}, {{ $request->provider->longitude }});
        markerProvider.setVisible(true);
        markerProvider.setPosition(provider);
        console.log('Provider Bounds', markerProvider.getPosition());
        bounds.extend(markerProvider.getPosition());
        @endif

        bounds.extend(marker.getPosition());
        bounds.extend(markerSecond.getPosition());
        map.fitBounds(bounds);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{Setting::get('google_map_key')}}&libraries=places&callback=initMap" async defer></script>
@endsection