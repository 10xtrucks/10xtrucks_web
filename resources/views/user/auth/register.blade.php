@extends('user.layout.auth')

@section('content')

<?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
<div class="full-page-bg" style="background-image: url({{$login_user}});">
<div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class="login-logo"><a href="{{url('/')}}"><img src="{{ Setting::get('site_logo', asset('logo-black.png'))}}"></a></span>
                <!-- <h2>Create your account now to start sending in minutes</h2> -->
                <!-- <p>Welcome to {{Setting::get('site_title','Fetschstr')}}, the easiest way to get around at the tap of a button.</p> -->
            </div>
            <div class="col-sm-6"></div>
        </div>
        <div class="row no-margin">
          <div class="col-md-12 log-right register-page">
              <div class="login-box-outer">
              <div class="login-box row no-margin">
                  <div class="col-md-12">
                      <a class="log-blk-btn" href="{{url('login')}}">@lang('unauthorized.user.already_have_account')</a>
                      <h3>@lang('unauthorized.user.create_new_account')</h3>
                  </div>
                   <div class="print-error-msg">
                      <ul></ul>
                  </div>

                  <div class="col-md-12 exist-msg" style="display: none;">
                      <span class="help-block">
                              <strong>@lang('unauthorized.user.mobile_already')</strong>
                     </span>
                  </div>
                  <form role="form" method="POST" action="{{ url('/register') }}">

                    @php
                        $country_code = \Setting::get('default_country_code', '1');
                    @endphp
                      <div id="first_step">
                          <div class="col-md-4">
                              <input value="+{{ $country_code }}" type="text" placeholder="+{{ $country_code }}" id="country_code" name="country_code" />
                          </div>

                          <div class="col-md-8">
                              <input type="text" autofocus id="phone_number" class="form-control" placeholder="@lang('unauthorized.user.enter_number')" name="phone_number" value="{{ old('phone_number') }}" />
                          </div>

                          <div class="col-md-12 exist-msg" style="display: none;">
                              <span class="help-block">
                                      <strong>@lang('unauthorized.user.mobile_already')</strong>
                              </span>
                          </div>

                          <div class="col-md-8">
                              @if ($errors->has('phone_number'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('phone_number') }}</strong>
                                  </span>
                              @endif
                          </div>

                          <div class="col-md-12 mobile_otp_verfication" style="display: none;">
                              <input type="text" class="form-control" placeholder="@lang('unauthorized.user.otp')" name="otp" id="otp" value="">

                              @if ($errors->has('otp'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('otp') }}</strong>
                                  </span>
                              @endif
                          </div>
                          <input type="hidden" id="otp_ref"  name="otp_ref" value="" />
                          <input type="hidden" id="otp_phone"  name="phone" value="" />

                          <div class="col-md-12" style="padding-bottom: 10px;" id="mobile_verfication">
                              <input type="button" class="log-teal-btn small" onclick="smsLogin();" value="@lang('unauthorized.user.verify_number')"/>
                          </div>

                          <div class="col-md-12 mobile_otp_verfication" style="padding-bottom: 10px;display:none" id="mobile_otp_verfication">
                              <input type="button" class="log-teal-btn small" onclick="checkotp();" value="@lang('unauthorized.user.verify_otp')"/>
                          </div>

                      </div>

                      {{ csrf_field() }}

                      <div id="second_step" style="display: none;">

                          <div class="col-md-6">
                              <input type="text" class="form-control" placeholder="@lang('user.profile.first_name')" name="first_name" value="{{ old('first_name') }}">

                              @if ($errors->has('first_name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('first_name') }}</strong>
                                  </span>
                              @endif
                          </div>
                          <div class="col-md-6">
                              <input type="text" class="form-control" placeholder="@lang('user.profile.last_name')" name="last_name" value="{{ old('last_name') }}">

                              @if ($errors->has('last_name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('last_name') }}</strong>
                                  </span>
                              @endif
                          </div>
                          <div class="col-md-12">
                              <input type="email" class="form-control" name="email" placeholder="@lang('unauthorized.user.email_address')" value="{{ old('email') }}">

                              @if ($errors->has('email'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif
                          </div>



                          <div class="col-md-12">
                              <input type="password" class="form-control" name="password" placeholder="@lang('unauthorized.provider.password')">

                              @if ($errors->has('password'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('password') }}</strong>
                                  </span>
                              @endif
                          </div>
                          <div class="col-md-12">
                              <input type="password" placeholder="@lang('unauthorized.user.retype')" class="form-control" name="password_confirmation">

                              @if ($errors->has('password_confirmation'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('password_confirmation') }}</strong>
                                  </span>
                              @endif
                          </div>

                         {{--  <div class="col-md-12 text-center hide">
                              <label>@lang('unauthorized.user.choose_type')</label>
                          </div>
                          <div class="col-md-6 text-center hide">
                              <input type="radio" placeholder=""  name="user_type" class="user_type"
                              value="BUSINESSUSER">@lang('unauthorized.user.business_user')
                          </div>
                          <div class="col-md-6 text-center hide">
                              <input type="radio" placeholder="" class="user_type" name="user_type" value="NORMAL" checked="checked">@lang('unauthorized.user.normal_user')
                          </div> --}}


                          <div class="col-md-12">
                              <button class="log-teal-btn" type="submit">@lang('unauthorized.user.signup')</button>
                          </div>

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


@section('scripts')
<script src="{{asset('asset/js/jquery.min.js')}}"></script>
 <script type="text/javascript">

    $('.checkbox-inline').on('change', function() {
        $('.checkbox-inline').not(this).prop('checked', false);
    });
    function isNumberKey(evt)
    {
        var edValue = document.getElementById("phone_number");
        var s = edValue.value;
        if (event.keyCode == 13) {
            event.preventDefault();
            if(s.length>=10){
                smsLogin();
            }
        }
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function checkotp(){

        var otp = document.getElementById("otp").value;
        var my_otp = $('#otp_ref').val();
        if(otp){
            if(my_otp == otp){
                $(".print-error-msg").find("ul").html('');
                $('#mobile_otp_verfication').html("<p class='helper'> Please Wait... </p>");
                $('#phone_number').attr('readonly',true);
                $('#country_code').attr('readonly',true);
                $('.mobile_otp_verfication').hide();
                $('#second_step').fadeIn(400);
                $('#mobile_verfication').show().html("<p class='helper'> * Phone Number Verified </p>");
                my_otp='';
            }else{
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").find("ul").append('<li>Otp not Matched!</li>');
            }
        }
    }



    function smsLogin(){

        $('.exist-msg').hide();
        var countryCode = document.getElementById("country_code").value;
        var phoneNumber = document.getElementById("phone_number").value;
        $('#otp_phone').val(countryCode+''+phoneNumber);
        var csrf = $("input[name='_token']").val();;

            $.ajax({
                url: "{{url('/otp')}}",
                type:'POST',
                data:{ mobile : countryCode+''+phoneNumber,'_token':csrf ,phoneonly:phoneNumber},
                success: function(data) {

                    if($.isEmptyObject(data.error)){

                        if(data.otp != undefined){
                            $('#otp_ref').val(data.otp);
                            $('.mobile_otp_verfication').show();
                            $('#mobile_verfication').hide();
                            $('#mobile_verfication').html("<p class='helper'> Por favor espera... </p>");
                            $('#phone_number').attr('readonly',true);
                            $('#country_code').attr('readonly',true);
                            $(".print-error-msg").find("ul").html('');
                            $(".print-error-msg").find("ul").append('<li>'+data.message+'</li>');
                        }else{
                            $('.mobile_otp_verfication').hide();
                            $(".print-error-msg").find("ul").html('');
                            $(".print-error-msg").find("ul").append('<li>'+data+'</li>');
                        }

                        $('#otp_ref').val(data.otp);
                        $('.mobile_otp_verfication').show();
                        $('#mobile_verfication').hide();
                        $('#mobile_verfication').html("<p class='helper'> Please Wait... </p>");
                        $('#phone_number').attr('readonly',true);
                        $('#country_code').attr('readonly',true);
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").find("ul").append('<li>'+data.message+'</li>');

                    }else{

                        printErrorMsg(data.error);
                    }
                },
                error:function(jqXhr,status) {
                    if(jqXhr.status === 422) {
                        $(".print-error-msg").show();
                        var errors = jqXhr.responseJSON;

                        $.each( errors , function( key, value ) {
                            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                    }
                }

                });
    }

    function printErrorMsg (msg) {

        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');

        $(".print-error-msg").show();

        $(".print-error-msg").find("ul").append('<li><p>'+msg+'</p></li>');

    }

</script>



@endsection

<style type="text/css">
  
  .hide{

    display: none;
  }
</style>
