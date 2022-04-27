@extends('user.layout.base')

@section('title', 'Dashboard ')

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
                <form action="{{url('confirm/ride')}}" method="GET" onkeypress="return disableEnterKey(event);">

                <div class="add-card-section">
                     <input type="hidden" name="looping_card[]" id="looping_card" value="1">
                    <div class="add-card">

                    <a id="AddButton" class="AddButton"><span class="fa fa-plus-circle"></span></a>
                    <div class="input-group dash-form">
                        <input type="text" class="form-control" id="origin-input1" name="s_address[1]"  placeholder="@lang('user.enter_sender_location')" required="">
                    </div>

                    <div class="input-group dash-form">
                        <input type="text" class="form-control" id="destination-input1" name="d_address[1]"  placeholder="@lang('user.enter_reciver_location')" required="">
                    </div>



                    <div class="input-group dash-form">
                        <input type="text" class="form-control" id="reseiver_name" name="reseiver_name[1]"  placeholder="@lang('user.enter_reciver_name')" required="">
                    </div>


                    @php
                        $country_code = \Setting::get('default_country_code', '1');
                    @endphp
                    <div class="input-group dash-form">
                        <div class="col-md-2">
                          <input value="+{{ $country_code }}" type="text" placeholder="+{{ $country_code }}" id="reseiver_country_code" name="reseiver_country_code[1]" required="" />
                        </div>
                        <div class="col-md-10">
                          <input type="text" class="form-control" id="reseiver_mobile" name="reseiver_mobile[1]"  placeholder="@lang('user.enter_reciver_number')" required="">
                        </div>
                    </div>



                    <div class="input-group dash-form">
                        <label class="sr-only" for="exampleInputAmount">Transfer Items</label>
                        <input type="text" class="form-control" id="service_items1" name="service_items[1]"  placeholder="@lang('user.enter_reciver_transport')" required="">
                    </div>

                    <div class="input-group dash-form">
                        <label class="sr-only" for="exampleInputAmount">Weight</label>
                        <input type="text" class="form-control weight" id="weight1" name="weight[1]"  placeholder="@lang('user.enter_reciver_weight')" required="">
                    </div>
                   

                    <div class="custom-control">
                      <input type="checkbox" name="fragile[1]" class="fragile" id="fragile" >
                      <label class="custom-control-label">@lang('user.fragile')</label>
                    </div>
                   <div class="input-group dash-form comment" style="display: none;">
                        <label class="sr-only" for="exampleInputAmount">Comment</label>
                        <input type="text" class="form-control" id="comment1" name="comment[1]" placeholder="Enter the Comment">
                    </div>

                 
                    <input type="hidden" name="s_latitude[1]" id="origin_latitude1">
                    <input type="hidden" name="s_longitude[1]" id="origin_longitude1">
                    <input type="hidden" name="d_latitude[1]" id="destination_latitude1">
                    <input type="hidden" name="d_longitude[1]" id="destination_longitude1">
                    <input type="hidden" name="current_longitude[1]" id="long1">
                    <input type="hidden" name="current_latitude[1]" id="lat1">
                   
                   </div>
                    
                </div>

                 <div class="car-detail" id="cars">


                </div>
                    

                    <button type="submit"  class="full-primary-btn fare-btn">@lang('user.ride.ride_now')</button>

                </form>
            </div>

            <div class="col-md-6">
                <div class="map-responsive">
                    <div id="map" style="width: 100%; height: 450px;"></div>
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')    
<script type="text/javascript">
    var current_latitude = 9.0820;
    var current_longitude = 8.6753;
</script>

<script type="text/javascript">
    if( navigator.geolocation ) {
       navigator.geolocation.getCurrentPosition( success, fail );
    } else {
        console.log('Sorry, your browser does not support geolocation services');
        initMap();
    }

    function success(position)
    {
        var id = jQuery('#looping_card').val();

        document.getElementById('long'+id).value = position.coords.longitude;
        document.getElementById('lat'+id).value = position.coords.latitude

        if(position.coords.longitude != "" && position.coords.latitude != ""){
            current_longitude = position.coords.longitude;
            current_latitude = position.coords.latitude;
        }
        initMap();
    }

    function fail()
    {
        // Could not obtain location
        console.log('unable to get your location');
        initMap();
    }
       
    function change_val(id)
    {
        var set = jQuery('#looping_card').val(id);
        var get =jQuery('#looping_card').val();
         initMap();
    }
    

    $('body').on('change', '.fragile', function(){

        if($(this).is(':checked')){
        $(this).closest('.add-card').find('.fragile').val(1);
        $(this).closest('.add-card').find('.comment').show();
        }else{
        $(this).closest('.add-card').find('.fragile').val(0);
        $(this).closest('.add-card').find('.comment').hide();
        }
  
    });




    jQuery(document).ready(function() {
        var country_code = '{{ $country_code }}';

        var $j=jQuery.noConflict();

        var MaxInputs       = 3;
        var InputsWrapper   = $j(".add-card-section");
        var AddButton       = $j("#AddButton"); 
        var x               = InputsWrapper.length; 
        var Removecard = $j(".add-card");
        var FieldCount; 

        $j(AddButton).click(function (e) 
    {
       var length =$j(".add-card").length;

//alert(FieldCount)
        if(length < MaxInputs)
        {

           var dest_data = jQuery('#origin-input'+length).val();
           var origin = jQuery('#origin_latitude'+length).val();
           var destination = jQuery('#origin_longitude'+length).val();
           FieldCount=length+1
      

           $j(InputsWrapper).append('<div class="add-card" id="add-card'+FieldCount+'"><div class="col-md-12"><a class="removebtn" onclick="removechk('+FieldCount+')" id="rmv'+FieldCount+'"><span class="fa fa-minus-circle" style="color:red;"></span></a></div><div class="input-group dash-form"><input id="origin-input'+FieldCount+'" readonly  required type="text" class="form-control" name="s_address['+FieldCount+']" value="'+dest_data+'" placeholder="@lang("user.enter_sender_location")" required></div><div class="input-group dash-form"><input onclick="change_val('+FieldCount+')" required id="destination-input'+FieldCount+'" type="text" class="form-control" name="d_address['+FieldCount+']" value="" placeholder="@lang("user.enter_reciver_location")" required><div class="input-group dash-form"><input type="text" class="form-control" id="reseiver_name" name="reseiver_name['+FieldCount+']"  placeholder="@lang("user.enter_reciver_name")" required=""></div><div class="input-group dash-form"><div class="col-md-2"><input value="+'+country_code+'" type="text" placeholder="+'+country_code+'" id="reseiver_country_code" name="reseiver_country_code['+FieldCount+']" required="" /></div><div class="col-md-10"><input type="text" class="form-control" id="reseiver_mobile" name="reseiver_mobile['+FieldCount+']"  placeholder="@lang("user.enter_reciver_number")" required=""></div></div><input required id="service_items_'+FieldCount+'" type="text"class="input-group dash-form" name="service_items['+FieldCount+']" value="" placeholder="@lang("user.enter_reciver_transport")"><input required id="weight_'+FieldCount+'" type="text"class="input-group dash-form weight" name="weight['+FieldCount+']" value="" placeholder="@lang("user.enter_reciver_weight")"></div><div class="custom-control"><input type="checkbox" name="fragile['+FieldCount+']" class="fragile" id="fragile_['+FieldCount+']" ><label class="custom-control-label">@lang("user.fragile")</label></div><div class="input-group dash-form comment" style="display: none;"><label class="sr-only" for="exampleInputAmount">Comment</label><input type="text" class="form-control" id="comment_['+FieldCount+']" name="comment['+FieldCount+']"  placeholder="Enter the Comment"></div><input type="hidden" name="s_latitude['+FieldCount+']" id="origin_latitude'+FieldCount+'" value="'+origin+'"><input type="hidden" name="s_longitude['+FieldCount+']" id="origin_longitude'+FieldCount+'" value="'+destination+'"><input type="hidden" name="d_latitude['+FieldCount+']" id="destination_latitude'+FieldCount+'"><input type="hidden" name="d_longitude['+FieldCount+']" id="destination_longitude'+FieldCount+'"><input type="hidden" name="current_longitude['+FieldCount+']" id="long'+FieldCount+'"><input type="hidden" name="current_latitude['+FieldCount+']" id="lat'+FieldCount+'"> </div>');
           x++; 


          
           return false;
        }

  });


    });


    jQuery(document).ready(function(){

    jQuery('body').on('change', '.weight', function () {


        var tableVal = $('.weight').map(function(i,v) {
                return  $(this).val();

        }).toArray()

        /*var total = 0;
        for (var i = 0; i < tableVal.length; i++) {
            total += tableVal[i] << 0;
        }  */          
        
            $.ajax({
                 url: '{{ url("/services") }}',
                 type: 'get', 
                 data: {
                    _token : '{{ csrf_token() }}',
                    weight: tableVal
                    },

                 dataType: "json",
                    success:function(data, textStatus, jqXHR) {
                        console.log(data);
                        $('#cars').html(data.html);
                        //console.log($('#cars').html(data));
                        
                },
             error:function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                alert("New Request Failed " +textStatus);
            }
        });

    });
});




</script> 

<script src="{{asset('asset/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('asset/js/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{Setting::get('google_map_key')}}&libraries=places&callback=initMap" async defer></script>

<script type="text/javascript">

function removechk(a)
{
  var  $j=jQuery.noConflict();
  var result = confirm("Want to delete?");
  if (result==true) {
    $j("#add-card"+a).remove();

  }
}

function disableEnterKey(e)
    {
     var key;
        if(window.e)
            key = window.e.keyCode; // IE
        else
            key = e.which; // Firefox

        if(key == 13)
            return e.preventDefault();
    }
</script>

@endsection