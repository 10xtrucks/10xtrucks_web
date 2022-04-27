<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title'){{ Setting::get('site_title', 'Fetschstr') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>


    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div class="full-page-bg" style="background-image: url({{ asset('asset/img/login-bg.jpg') }});">
        <div class="log-overlay"></div>
            <div class="full-page-bg-inner">
                <div class="row no-margin">
                    <div class="col-md-6 log-left">
                        <a href="{{ url('/')}}"><span class="login-logo"><img src="{{ Setting::get('site_logo', asset('logo-black.png')) }}"></span></a>
                        <h2>@lang('unauthorized.provider.earn_money') {{Setting::get('site_title')}}.</h2>
                        <a class="log-teal-btn" href="{{url('/provider/register')}}">@lang('unauthorized.user.earn_money_signup')</a>
                        <!-- <p>Deliver with {{Setting::get('site_title','Fetschstr')}} and earn great money as an independent contractor. Get paid weekly just for helping our community of riders get rides around town. Be your own boss and get paid in fares for driving on your own schedule.</p> -->
                    </div>
                    <div class="col-md-6 log-right">
                        <div class="login-box-outer">
                            <div class="login-box row no-margin">
                                @yield('content')
                            </div>
                            <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' 10XTrucks') }}</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/js/scripts.js') }}"></script>

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

    @yield('scripts')
    
</body>
</html>
