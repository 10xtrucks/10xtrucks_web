@extends('provider.layout.auth')

@section('content')
<div class="col-md-12">
    <a class="log-blk-btn" href="{{ url('/provider/register') }}">@lang('unauthorized.provider.create_account')</a>
    <h3>@lang('unauthorized.user.signin')</h3>
</div>

<div class="col-md-12">
    <form role="form" method="POST" action="{{ url('/provider/login') }}">
        {{ csrf_field() }}

        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="@lang('unauthorized.provider.email')" autofocus>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input id="password" type="password" class="form-control" name="password" placeholder="@lang('unauthorized.provider.password')">

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember">@lang('unauthorized.provider.remember')
            </label>
        </div>

        <br>

        <button type="submit" class="log-teal-btn">
            @lang('unauthorized.provider.login')
        </button>

        <p class="helper"><a href="{{ url('/provider/password/reset') }}">@lang('unauthorized.provider.forgot_password')</a></p>   
    </form>
    @if(Setting::get('social_login', 0) == 1)
    <!-- <div class="col-md-12">
        <a href="{{ url('provider/auth/facebook') }}"><button type="submit" class="log-teal-btn fb"><i class="fa fa-facebook"></i>@lang('unauthorized.provider.login_facebook')</button></a>
    </div>   -->
    <div class="col-md-12">
        <a href="{{ url('provider/auth/google') }}"><button type="submit" class="log-teal-btn gp"><i class="fa fa-google-plus"></i>@lang('unauthorized.provider.login_google')</button></a>
    </div>
    @endif
</div>
@endsection
