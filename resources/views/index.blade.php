@extends('user.layout.app')

@section('content')
<div class="banner row no-margin" style="background-image: url('{{ asset('asset/img/image.png') }}');">
    <div class="banner-overlay"></div>
    <div class="container">
        <div class="col-md-8">
            <!-- <div class="bannerLogo">
                <img src="{{Setting::get('site_logo',asset('asset/img/logo-white.png'))}}">
            </div> -->
            <h2 class="banner-head">Your new app that connects you
                with <br/>cargo transport units.<br> With {{Setting::get('site_title')}} app you decide… <br>Freight or Moving!</h2>
            </div>
            <div class="col-md-4">
                <div class="banner-form">
                    <div class="row no-margin fields">
                        <div class="left">
                            <img src="{{ asset('asset/img/send-stuff.png') }}">
                        </div>
                        <div class="right">
                            <a href="{{url('register')}}">
                                <h3>Ask for a {{Setting::get('site_title')}}</h3>
                                <h5>I am a registered user  <i class="fa fa-chevron-right"></i></h5>
                            </a>
                        </div>
                    </div>
                    <div class="row no-margin fields">
                        <div class="left">
                            <img src="{{ asset('asset/img/ride-form-icon.png') }}">
                        </div>
                        <div class="right">
                            <a href="{{ url('/provider/register') }}">
                                <h3>Register as a driver {{Setting::get('site_title')}}</h3>
                                <h5>I am a registered driver <i class="fa fa-chevron-right"></i></h5>
                            </a>
                        </div>
                    </div>
                    <p class="note-or">Or <a href="{{ url('/provider/login') }}">Start </a> with an existing account</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row white-section no-margin">
        <div class="container">
            <div class="col-md-6 img-block text-center">
                <img src="{{ asset('asset/img/tap.png') }}">
            </div>
            <div class="col-md-6 content-block">
                <h2>Request your unit with a single Click </h2>
                <div class="title-divider"></div>
                <!-- <p>{{ Setting::get('site_title', 'Fetschstr')  }} is the smartest way to get around. One tap and a car comes directly to you. Your Deliver knows exactly where to go. And you can pay with either cash or card.</p> -->
                <!-- <p>Whether its shoes, a phone, office documents or a bag  that you left at your friends house again, {{Setting::get('site_title')}} will pick it up and deliver it for you at the click of a button.</p> -->
                <p>{{Setting::get('site_title')}} app connects you with registered drivers and charging units that are very close to you. We have dry box type loading units, racks and loading cranes available at all times.  </p>
                <!-- <a class="content-more" href="#">Sign up <i class="fa fa-chevron-right"></i></a> -->
            </div>
        </div>
    </div>

    <div class="row gray-section no-margin">
        <div class="container">
            <div class="col-md-6 content-block">
                <h2>Maximum security for your goods, products and merchandise</h2>
                <div class="title-divider"></div>
                <p>Through your {{Setting::get('site_title')}} app you can follow the progress of your journey from start to finish in real time. At all times you know where the unit that transports your cargo is and who is transporting it. </p>
                <!-- <a class="content-more" href="#">MORE REASONS TO DELIVERY <i class="fa fa-chevron-right"></i></a> -->
            </div>
            <div class="col-md-6 img-block text-center">
                <img src="{{ asset('asset/img/anywhere.png') }}">
            </div>
        </div>
    </div>

    <div class="row white-section no-margin">
        <div class="container">
            <div class="col-md-6 img-block text-center">
                <img src="{{ asset('asset/img/low-cost.png') }}">
            </div>
            <div class="col-md-6 content-block">
                <h2>The cheapest rates</h2>
                <div class="title-divider"></div>
                <p>Know the rate of your trip before requesting it and select the number of chargers you require. Charge your unit with family or friends and improve your trip rate even more.</p>
                <!-- <a class="content-more-btn" href="#">Get a fair estimate <i class="fa fa-chevron-right"></i></a> -->
            </div>
        </div>
    </div>

    <div class="row gray-section no-margin full-section">
        <div class="container">
            <div class="col-md-6 content-block">
                <h2>Increase your EARNINGS quickly </h2>
                <div class="title-divider"></div>
                <p>Do you have a truck or cargo truck?  </p>
                <p>Work the day and time you have available</p>
                <p>Travel without overload for your unit</p>
                <p>Surveillance and roadside assistance 24/7</p>
                <p>Constant transfers</p>
                <p>Fair rates per kilometer</p>
                <a class="content-more-btn" href="{{ url('/provider/register') }}">REGISTER {{Setting::get('site_title')}} CONDUCTOR <i class="fa fa-chevron-right"></i></a>
            </div>
            <div class="col-md-6 full-img text-center" style="background-image: url({{ asset('asset/img/truck.png') }});">
                <!-- <img src="img/anywhere.png"> -->
            </div>
        </div>
    </div>

<!-- <div class="row white-section no-margin">
    <div class="container">
        <div class="col-md-6 img-block text-center">
            <img src="{{ asset('asset/img/low-cost.png') }}">
        </div>
        <div class="col-md-6 content-block">
            <h2>Helping Cities For the good of all</h2>
            <div class="title-divider"></div>
            <p>A city with {{ Setting::get('site_title', 'Fetschstr') }} has more economic opportunities for residents, fewer drunk Deliver on the streets, and better access to transportation for those without it.</p>
            <a class="content-more" href="#">OUR LOCAL IMPACT <i class="fa fa-chevron-right"></i></a>
        </div>
    </div>
</div> -->

<!-- <div class="row gray-section no-margin">
    <div class="container">
        <div class="col-md-6 content-block">
            <h2>Safety Putting people first</h2>
            <div class="title-divider"></div>
            <p>Whether riding in the backseat or Delivering up front, every part of the {{ Setting::get('site_title', 'Fetschstr') }} experience has been designed around your safety and security.</p>
            <a class="content-more" href="#">HOW WE KEEP YOU SAFE <i class="fa fa-chevron-right"></i></a>
        </div>
        <div class="col-md-6 img-block text-center">
            <img src="{{ asset('asset/img/seat-belt.jpg') }}">
        </div>
    </div>
</div>

<div class="row find-city no-margin">
    <div class="container">
        <h2>{{ Setting::get('site_title','Tranxit') }} in Your City</h2>
        <form>
            <div class="input-group find-form">
                <input type="text" class="form-control" placeholder="Search" >
                <span class="input-group-addon">
                    <button type="submit">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
</div> -->

<div class="footer-city row no-margin"></div>
@endsection
