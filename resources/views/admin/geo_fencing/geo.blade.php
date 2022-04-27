@extends('admin.layout.base')

@section('title', 'Geo Fencing ')

@section('content')


<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
			<h5>Geo Fencing</h5>

            <form class="form-horizontal" action="{{ route('admin.geo_fencing.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<div class="col-xs-12">

						<div id="map"></div>
						<div id="bar">
					      	<p><a id="clear" href="#">Click here</a> to clear map.</p>
					    </div>

					    <div id="info"></div>
						<input type="hidden" name="service_range" class="service_range" value="{{Setting::get('service_range', '')}}">
					</div>
				</div>

				<div class="form-group row">
			
					<div class="col-xs-12">
						<button type="submit" class="btn btn-primary">@lang('admin.update')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@if(count($details)!=0)
	@foreach($details as $list_details)
		<input type="hidden" name="ranges" value="{{$list_details->ranges}}" class="OldPath">
	@endforeach
@endif


@endsection

@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?key={{Setting::get('google_map_key')}}&sensor=false&libraries=drawing" type="text/javascript"></script> 

<script type="text/javascript">


if(!!navigator.geolocation) {
	    	
		var map;
		var geocoder;
		var polygonArray = [];
		//var userLocation = new google.maps.LatLng(13.0574400, 80.2482605);
		var mapOptions = {
			zoom: 15,
			
		};
		map = new google.maps.Map(document.getElementById('map'), mapOptions);
		navigator.geolocation.getCurrentPosition(function(position) {

			var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

			map.setCenter(geolocate);

		});

	   

		$(".OldPath").each(function(index, value) {
			var old_polygon = [];
			var GeoPaths = JSON.parse($(this).val());
			$(GeoPaths).each(function(index, value){
				old_polygon.push(new google.maps.LatLng(value.lat, value.lng));
			});


			polygon = new google.maps.Polygon({
			path: old_polygon,
			strokeColor: "#ff0000",
			strokeOpacity: 0.8,
			// strokeWeight: 1,
			fillColor: "#ff0000",
			fillOpacity: 0.3,
			editable: true,
			draggable: true,
			});

			polygon.setMap(map);
		});

	    		
} 
else{
  document.getElementById('map').innerHTML = 'No Geolocation Support.';
}

function initializeDrawer() {
    var drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
           
            google.maps.drawing.OverlayType.POLYGON]
            
        },
        markerOptions: {
            icon: 'images/car-icon.png'
        },
        circleOptions: {
            fillColor: '#ffff00',
            fillOpacity: 1,
            strokeWeight: 5,
            clickable: false,
            editable: true,
            zIndex: 1
        },
        polygonOptions: {
           		editable: true, 
	 			draggable: true,
				fillColor: '#ff0000', 
	 			strokeColor: '#ff0000', 
	 			strokeWeight: 1
        }
    });
    console.log(drawingManager)
    drawingManager.setMap(map);

 /*   $(document).on('click', '#clear', function(ev) {
            drawingManager.setMap(map);
            polygon.setMap(null);
            deleteSelectedShape();
            $('input.service_range').val('');
            ev.preventDefault();
            return false;
        });
*/
    google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
        
        var selected_range = $('.service_range').val();
        var arr_loc = [];
        for (var i = 0; i < polygon.getPath().getLength(); i++) {
           
        	var co_ordinates = polygon.getPath().getAt(i).toUrlValue(6);
        	var array = co_ordinates.split(",");

			var locLat = array[0];
			var locLng =  array[1];

			ltlg = {
				'lat': locLat,
				'lng': locLng
			};

			arr_loc.push(ltlg); 
			   
           // document.getElementById('info').innerHTML += "" + polygon.getPath().getAt(i).toUrlValue(6) + ";";
        }
        polygonArray.push(polygon);

        if(selected_range!='')
        {
        	   $('input.service_range').val(JSON.stringify(arr_loc)+"/"+selected_range);
        }
        else
        {
        	  $('input.service_range').val(JSON.stringify(arr_loc));
        }
        
       
    });

}

// Initialize the drawer tool
google.maps.event.addDomListener(window, "load", initializeDrawer);





function deleteSelectedShape () {
        if (selectedShape) {
            $('input.service_range').val('');
            selectedShape.setMap(null);
        }
}

$(document).ready(function(){
      $("#clear").click(function(){
                
                 $.ajax({url: "{{ url('admin/clear/map') }}/",success: function(data){
                   location.reload();
                 }});
 
        });
    });

</script>

@endsection

@section('styles')
<style type="text/css">
    #map {
        height: 100%;
        min-height: 400px; 
    }
    
    .controls {
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        margin-bottom: 10px;
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 100%;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    #bar {
        width: 240px;
        background-color: rgba(255, 255, 255, 0.75);
        margin: 8px;
        padding: 4px;
        border-radius: 4px;
  	}
</style>
@endsection

