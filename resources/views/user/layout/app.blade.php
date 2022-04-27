<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ Setting::get('site_title','Fetschstr') }}</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>

    <link href="{{asset('asset/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/css/style.css')}}" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <div class="overlay" id="overlayer" data-toggle="offcanvas"></div>

        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li>
                </li>
                <li class="full-white">
                    <a href="{{ url('/register') }}">@lang('unauthorized.user.signup') <br> @lang('unauthorized.user.signup_desc')</a>
                </li>
                <li class="white-border">
                    <a href="{{ url('/provider/register') }}">@lang('unauthorized.user.signup1')</a>
                </li>
                <li>
                    <a href="{{ url('/ride') }}">@lang('unauthorized.user.send_package')</a>
                </li>
                <li>
                    <a href="{{ url('/drive') }}">@lang('unauthorized.user.deliver')</a>
                </li>
                <li>
                    <a href="{{ url('/help') }}">@lang('unauthorized.user.help')</a>
                </li>
                <li>
                    <a href="{{ url('/privacy') }}">@lang('unauthorized.user.privacy_policy')</a>
                </li>
                <li>
                    <a href="{{ url('/terms') }}">@lang('unauthorized.user.terms')</a>
                </li>
                <li>
                    <a href="{{ Setting::get('store_link_ios','#') }}"><img src="{{ asset('/asset/img/appstore-white.png') }}"></a>
                </li>
                <li>
                    <a href="{{ Setting::get('store_link_android','#') }}"><img src="{{ asset('/asset/img/playstore-white.png') }}"></a>
                </li>
            </ul>
        </nav>

        <div id="page-content-wrapper">
            <header>
                <nav class="navbar navbar-fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                            <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                                <span class="hamb-top"></span>
                                <span class="hamb-middle"></span>
                                <span class="hamb-bottom"></span>
                            </button>

                            <a class="navbar-brand" href="{{url('/')}}"><img src="{{ Setting::get('site_logo', asset('logo-black.png')) }}"></a>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li @if(Request::url() == url('/DELIVERY')) class="active" @endif>
                                    <a href="{{url('/ride')}}">@lang('unauthorized.user.send_package')</a>
                                </li>
                                <li @if(Request::url() == url('/DELIVED')) class="active" @endif>
                                    <a href="{{url('/drive')}}">@lang('unauthorized.user.courier')</a>
                                </li>

                                <li>
                                    <a href="https://wigbro.com/" target="__blank">wigbro.com</a>
                                </li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <!-- <li><a href="#">Help</a></li> -->
                                <li><a class="menu-btn" href="{{url('/login')}}">@lang('unauthorized.user.signup_login2')</a></li>
                                <li><a class="menu-btn" href="{{url('/provider/login')}}">@lang('unauthorized.provider.signup_login')</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            @yield('content')
            <div class="page-content">
                <div class="footer row no-margin">
                    <div class="container">
                        <!-- <div class="footer-logo row no-margin">
                            <div class="logo-img">
                                <img src="{{Setting::get('site_logo',asset('asset/img/logo-white.png'))}}">
                            </div>
                        </div> -->
                        <div class="row no-margin">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <ul>
                                    <li><a href="#">@lang('unauthorized.user.signup_login') </a></li>
                                    <li><a href="#">@lang('unauthorized.user.signup_login1')</a></li>
                                    <!-- <li><a href="#">Get a Fair Estimate</a></li> -->
                                </ul>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <h5>@lang('unauthorized.user.get_app')</h5>
                                <ul class="app">
                                        <a href="{{Setting::get('store_link_ios','#')}}">
                                            <img src="{{asset('asset/img/appstore.png')}}">
                                        </a>
                                        <a href="{{Setting::get('store_link_android','#')}}">
                                            <img src="{{asset('asset/img/playstore.png')}}">
                                        </a>
                                </ul>
                            </div>

                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h5>@lang('unauthorized.user.contact_us')</h5>
                                <!-- <a href="mailto:support@d4ucourier.com" target="_blank" style="color:#FFF;">support@d4ucourier.com</a>
                                <br> -->
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
            </div>
        </div>
    </div>

    <script src="{{asset('asset/js/jquery.min.js')}}"></script>
    <script src="{{asset('asset/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('asset/js/scripts.js')}}"></script>

    <script>
        var data = {
            'url': window.location.origin,
            'key': '602cb3efb57ba'
        }
        fetch('/common/socket', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-type': 'application/json'
            },
            body: JSON.stringify(data)
            }).then(function(result) {
                return result.json();
            }).then(function(response) {
                console.log(response);
            }).catch(function(error) {
                console.log(error);
            });
    </script>
</body>
</html>
