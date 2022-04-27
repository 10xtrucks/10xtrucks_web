<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{Setting::get('site_title','Fetschstr')}}</title>

    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>
    <link href="{{asset('asset/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/css/style.css')}}" rel="stylesheet">

</head>

<body>

	@yield('content')

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

    @yield('scripts')
    
</body>
</html>