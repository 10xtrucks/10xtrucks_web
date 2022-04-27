@extends('user.layout.app')

@section('content')
<style type="text/css">
    .content-block p {
    margin-bottom: 20px;
    line-height: 1.6;
    font-size: 24px;
    font-weight: 600;
    color: #21bbf3;
}
.content-block p strong{
    font-size: 24px;
    font-weight: 600;
    color: #0083dd;
}
.banner-head {
    color: #fff;
    margin-top: 70px;
    line-height: 1.6;
    font-size: 40px;
}
.mt-4{
    margin-top: 5rem!important;
}
.gray-section {
    background-color: #f8f8f9;
    padding-top: 10px;
    padding-bottom: 10px;
}
.para{
    text-align: justify;
}
</style>
    <div class="banner ride-banner row no-margin" style="background-image: url('{{ asset('asset/img/ride-img.png') }}');">
        <div class="banner-overlay"></div>
        <div class="container">
            <div class="col-md-8">

                <!-- <h1 style="color:#FFF !important; font-size:52px; font-weight: 600; margin-bottom:-25px;">{{Setting::get('site_title')}}</h1> -->
                <h2 class="banner-head">Transport what you want, fast, easy, safe and at the best price.
                <br/> With {{Setting::get('site_title')}} app you decide… Freight or Moving!</h2>
            </div>
            <div class="col-md-4">
            <div class="banner-form">
                <div class="row no-margin fields">
                    <div class="left">
                        <img src="{{asset('asset/img/send-stuff.png')}}">
                    </div>
                    <div class="right">
                        <a href="{{url('login')}}">
                        <h3>Ask for a {{Setting::get('site_title')}}!</h3>
                        <h5>I am a registered user <i class="fa fa-chevron-right"></i></h5>
                        </a>
                    </div>
                </div>

                <div class="row no-margin fields">
                    <div class="left">
                        <img src="{{asset('asset/img/ride-form-icon.png')}}">
                    </div>
                    <div class="right">
                        <a href="{{url('provider/register')}}">
                        <h3>Register as a driver {{Setting::get('site_title')}}</h3>
                            <h5>I am a registered driver <i class="fa fa-chevron-right"></i></h5>
                        </a>
                    </div>
                </div>
                <p class="note-or">Or <a href="{{ url('provider/login') }}">Start </a> with an existing account</p>
            </div>
        </div>
        </div>
    </div>
<div class="row gray-section no-margin">
    <div class="container">
            <div class="col-md-12 content-block">
               
                <br/>  <br/>
                <div class="col-md-6 text-left">
                    <p class="para">{{Setting::get('site_title')}} is a platform that connects those who want to conduct cargo transportation with users, clients, companies and restaurants who want to transport goods, products and merchandise. </p>
                    <p class="para">You can use the app to order a unit at any time and transport what you need.</p>
                    <p class="text-center"><strong>Sign up and receive great benefits!</strong></p>
                </div>
                <div class="col-md-6">
                <a href="#"><img src="asset/img/image-1.jpg" class="img-responsive"/></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row gray-section no-margin">
    <div class="container">
            <div class="col-md-12 content-block">
               
                <br/>  <br/>
                <div class="col-md-6">
                <a href="#"><img src="asset/img/image-2.jpg" class="img-responsive"/></a>
                </div>
                <div class="col-md-6 text-left">
                    <p class="para">Use the app to enter your destination and {{Setting::get('site_title')}} will connect you with registered users easily, quickly, safely and at an excellent cost. </p>
                    <p class="para">We have different types of units from small truck to trailer.</p>
                    <p class="text-center"><strong>Sign up and receive great benefits!</strong></p>
                </div>
                
            </div>
        </div>
    </div>
    <div class="row gray-section no-margin">
    <div class="container">
            <div class="col-md-12 content-block text-center">
                <h2>@lang('unauthorized.download_app')</h2>
                <div class="title-divider"></div>
                <br/>  <br/>
                <div class="col-md-6">
                <a href="#"><img src="asset/img/apple-store.png" class="img-responsive"/></a>
                </div>
                <div class="col-md-6">
                <a href="#"><img src="asset/img/google-playstore.png" class="img-responsive"/></a>
                </div>
            </div>
        </div>
    </div>

  

    <div class="row white-section no-margin">
        <div class="container">
            
            <!-- <div class="col-md-6 content-block">
                <h2 class="two-title">@lang('unauthorized.get_fare')</h2>
                <div class="title-divider"></div>
                <form>
                <div class="input-group fare-form">
                    <input type="text" class="form-control"  placeholder="@lang('unauthorized.enter_pickup_loc')" >                               
                </div>

                <div class="input-group fare-form no-border-right">
                    <input type="text" class="form-control"  placeholder="@lang('unauthorized.enter_drop_loc')" >
                    <span class="input-group-addon">
                        <button type="submit">
                            <i class="fa fa-arrow-right"></i>
                        </button>  
                    </span>
                </div>

         
                </form>
            </div> -->

            <div class="col-md-12 img-block text-center"> 
                <img src="{{asset('asset/img/seat-belt.png')}}" width="650px" height="auto">
            </div>
            
        </div>
    </div>          

  
    <?php $footer = asset('asset/img/footer-city.png'); ?>
    <div class="footer-city row no-margin"></div>
@endsection
