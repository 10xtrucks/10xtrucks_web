@extends('user.layout.app')

@section('content')
<style type="text/css">
    .content-block h2 {
    margin-top: 30px;
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 31px;
    color: #FF5722;
}
.content-block p {
    margin-bottom: 15px;
    line-height: 1.6;
    font-size: 16px;
    color: #FF5722;
}
.content-block p strong {

    color: #FF5722;
}
.content-block p strong a{

    color: #00adef;
    font-weight: 700;
}
.content-block h3 {
    margin-bottom: 0px;
    margin-top: 0;
    color: #FF5722;
}
.content-block ul li {
    color: #FF5722;
    line-height: 1.6;
}
.content-more-btn {
    margin: 0;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    background-color: #ff5722;
    color: #fff;
    padding: 15px 20px;
    display: inline-block;
    padding-top: 18px;
}
.mt-5{
    margin-top: 5rem!important;
}
.mt-4{
    margin-top: 5rem!important;
}
.banner-head {
    color: #fff;
    margin-top: 70px;
    line-height: 1.6;
    font-size: 40px;
}
</style>
<div class="banner drive-banner row no-margin" style="background-image: url('{{ asset('asset/img/drive-img.png') }}');">
    <div class="banner-overlay"></div>
    <div class="container">
        <div class="col-md-8">

          <!-- <h1 style="color:#FFF !important; font-size:52px; font-weight: 600; margin-bottom:-25px;">Earn extra money</h1> -->
            <h2 class="banner-head">Increase your EARNINGS with your truck or truck.<br>
Carry out freight and removals
</h2>

        </div>
        <div class="col-md-4">
            <div class="banner-form">
                <div class="row no-margin fields">
                    <div class="left">
                    	<img src="{{asset('asset/img/send-stuff.png')}}">
                    </div>
                    <div class="right">
                        <!-- <a href="{{url('provider/register')}}"> -->
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
                        <!-- <a href="{{url('provider/login')}}"> -->
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
        <div class="col-md-5 img-block text-center">
            <img src="{{ asset('asset/img/low-cost.png') }}" class="mt-5">
        </div>
        <div class="col-md-7 content-block">
            <h2>Increase your EARNINGS with your truck or truck</h2>

<p>You will have access to receive travel requests from different clients and companies that need to use your cargo unit to transport goods, merchandise and products.</p>
<p>You can increase your earnings in an easy and fast way since if any of our users requests a load unit, you are notified immediately through the application and you decide if you accept the trip or not.</p>
<p>Work on the day and time you have available. With {{Setting::get('site_title')}} CONDUCTORES you continue to be your own boss. </p>

            <a class="content-more-btn" href="{{ url('/provider/login')}}">REGISTER {{Setting::get('site_title')}} CONDUCTOR<i class="fa fa-chevron-right"></i></a>
    </div>
</div>

<div class="row gray-section no-margin full-section">
    <div class="container">
        <div class="col-md-6 content-block">

            <h2>What documents do I need?</h2>
            <h3>Documents for driver partner</h3>
            <ul>
<li>Official identification (INE, IFE or passport on both sides)</li>
<li>Proof of official address (telephone, electricity, property, water no more than three months)</li>
<li>CURP</li>
<li>3 photos (right, left and front profile with white background)</li>
<li>Driver's license (both sides)</li>
<li>RFC (Proof of federal registration of taxpayers with QR code)</li>
<li>Electronic invoicing tax credentials</li>
<li>Bank account number (where the deposits of your earnings will be made)</li>

       
             </ul>
<h3>Documents from your unit</h3>
<ul class="">
<li>Invoice for your unit</li>
<li>Circulation card (both sides)</li>
<li>Safe as a cargo truck</li>
<li>Unit photo (front, rear, left, right, with plate view)</li>
</ul>
<br>
<p><strong>For more information send us an email to <a href="{{Setting::get('contact_email')}}">{{Setting::get('contact_email')}}</a></strong></p>

             @if(Setting::get('contact_email'))
                <b>for more info <a class="content-more" href="mailto:{{ Setting::get('contact_email') }}">@lang('unauthorized.provider.email') {{ Setting::get('contact_email') }}</a></b>
            @endif
        </div>
        <div class="col-md-6 full-img text-center" style="background-image: url({{ asset('asset/img/driver-car.jpg') }});">
            <!-- <img src="img/anywhere.png"> -->
        </div>
    </div>
</div>



<div class="footer-city row no-margin"></div>
@endsection
