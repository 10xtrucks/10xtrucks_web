@extends('user.layout.auth')

@section('content')

<?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
<div class="full-page-bg" style="background-image: url({{$login_user}});">
<div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class="login-logo"><a href=""><img src="{{Setting::get('site_logo', asset('logo-black.png'))}}"></a></span>
                <!-- <h2>Create your account now to start sending in minutes</h2> -->
                <!-- <p>Welcome to {{ Setting::get('site_title', 'Fetschstr')  }}, the easiest way to get around at the tap of a button.</p> -->
            </div>
            <div class="col-sm-6"></div>

        </div>
        <div class="row no-margin">
          <div class="col-md-12 log-right password-reset-page">
              <div class="login-box-outer">
              <div class="login-box row no-margin">
                  <div class="col-md-12">
                      <a class="log-blk-btn" href="{{url('login')}}">@lang('unauthorized.user.already_have_account')</a>
                      <h3>@lang('unauthorized.user.reset_password')</h3>
                  </div>
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif
                  <form role="form" method="POST" action="{{ url('/password/email') }}">
                      {{ csrf_field() }}

                      <div class="col-md-12">
                          <input type="email" class="form-control" name="email" placeholder="@lang('unauthorized.user.email_address')" value="{{ old('email') }}">

                          @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                      </div>


                      <div class="col-md-12">
                          <button class="log-teal-btn" type="submit">@lang('unauthorized.user.send_link')</button>
                      </div>
                  </form>

                  <div class="col-md-12">
                      <p class="helper">@lang('unauthorized.user.or') <a href="{{route('login')}}">@lang('unauthorized.user.signin1')</a> @lang('unauthorized.user.with_account')</p>
                  </div>

              </div>


              <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' 10XTrucks') }}</p></div>
              </div>
          </div>
        </div>
    </div>
@endsection
