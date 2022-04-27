<div class="footer row no-margin">
    <div class="container">
        <div class="row no-margin">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <ul>
                    <li><a href="#">Delivery</a></li>
                    <li><a href="#">Deliver</a></li>
                    <li><a href="#">Cities</a></li>
                    <li><a href="#">Fare Estimate</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <ul>
                    <li><a href="#">Signup to Delivery</a></li>
                    <li><a href="#">Become a Deliver</a></li>
                    <li><a href="#">Delivery Now</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <h5>Get App on</h5>
                <ul class="app">
                    <li><a href="{{Setting::get('store_link_ios','#')}}"><img src="{{ asset('asset/img/appstore.png') }}"></a></li>
                    <li><a href="{{Setting::get('store_link_android','#')}}"><img src="{{ asset('asset/img/playstore.png') }}"></a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <h5>Connect us</h5>
                <a href="mailto:{{ Setting::get('contact_email') }}" target="_blank" style="color:#FFF;">{{ Setting::get('contact_email') }}</a>
                <ul class="social">
                    <li><a href="https://www.facebook.com/10XTrucksoficial/"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://twitter.com/10XTrucks_app?s=20"><i class="fa fa-twitter"></i></a></li>
                </ul>
            </div>
        </div>

        <div class="row no-margin">
            <div class="col-md-12 copy">
                <p>{{ Setting::get('site_copyright', '&copy; '.date('Y').' 10XTrucks') }}</p>
            </div>
        </div>
    </div>
</div>