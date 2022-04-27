@extends('user.layout.auth')

@section('content')

<div class="full-page-bg" style="background-image: url({{ asset('asset/img/login-user-bg.jpg') }});">
    <div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <a href="{{ url('/')}}"><span class="login-logo"><img src="{{ Setting::get('site_logo', asset('logo-black.png'))}}"></span></a>
                <h2>@lang('unauthorized.user.login1')</h2> <h2>@lang('unauthorized.user.login_desc')</h2>
                <p>{{Setting::get('site_title')}} @lang('unauthorized.user.login_desc1')</p>
            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                <div class="login-box row no-margin">
                    <div class="col-md-12">
                        <a class="log-blk-btn" href="{{url('register')}}">@lang('unauthorized.provider.create_account')</a>
                        <h3>@lang('unauthorized.user.signin')</h3>
                    </div>
                    <form  role="form" method="POST" action="{{ url('/login') }}"> 
                    {{ csrf_field() }}                      
                        <div class="col-md-12">
                             <input id="email" type="email" class="form-control" placeholder="@lang('unauthorized.user.email_address')" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control" placeholder="@lang('unauthorized.provider.password')" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}><span> @lang('unauthorized.provider.remember')</span>
                        </div>
                       
                        <div class="col-md-12">
                            <button type="submit" class="log-teal-btn">@lang('unauthorized.provider.login')</button>
                        </div>
                    </form>

                    @if(Setting::get('social_login', 0) == 1)
                    <!-- <div class="col-md-12">
                        <a href="{{ url('/auth/facebook') }}"><button type="submit" class="log-teal-btn fb"><i class="fa fa-facebook"></i>@lang('unauthorized.provider.login_facebook')</button></a>
                    </div>   -->
                    <div class="col-md-12">
                        <a href="{{ url('/auth/google') }}"><button type="submit" class="log-teal-btn gp"><i class="fa fa-google-plus"></i>@lang('unauthorized.provider.login_google')</button></a>
                    </div>
                    @endif

                    <div class="col-md-12">
                        <p class="helper"> <a href="{{ url('/password/reset') }}">@lang('unauthorized.user.forgot')</a></p>   
                    </div>
                </div>


                <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' 10XTrucks') }}</p></div></div>
            </div>
        </div>
    </div>
</div>
@endsection