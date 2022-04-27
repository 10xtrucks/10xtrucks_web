@extends('provider.layout.auth')

@section('content')
<div class="col-md-12">
    <a class="log-blk-btn" href="{{ url('/provider/login') }}">@lang('unauthorized.provider.already_register')</a>
    <h3>@lang('unauthorized.provider.sign_up')</h3>
</div>

<div class="col-md-12">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/register') }}" enctype="multipart/form-data">
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

             <div class="print-error-msg">
                        <ul></ul>
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

            <input id="name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="@lang('user.profile.first_name')" autofocus>

            @if ($errors->has('first_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
            @endif

            <input id="name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="@lang('user.profile.last_name')">

            @if ($errors->has('last_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
            @endif

            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="@lang('unauthorized.provider.email_address')">

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

            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="@lang('unauthorized.provider.confirm_password')">

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif

            <select class="form-control" name="service_type" id="service_type">
                <option value="">@lang('unauthorized.provider.select_service')</option>
                @foreach(get_all_service_types() as $type)
                    <option value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select>

            @if ($errors->has('service_type'))
                <span class="help-block">
                    <strong>{{ $errors->first('service_type') }}</strong>
                </span>
            @endif

            <input id="service-number" type="text" class="form-control" name="service_number" value="{{ old('service_number') }}" placeholder="@lang('unauthorized.provider.car_number')">

            @if ($errors->has('service_number'))
                <span class="help-block">
                    <strong>{{ $errors->first('service_number') }}</strong>
                </span>
            @endif

            <input id="service-model" type="text" class="form-control" name="service_model" value="{{ old('service_model') }}" placeholder="@lang('unauthorized.provider.car_model')">

            @if ($errors->has('service_model'))
                <span class="help-block">
                    <strong>{{ $errors->first('service_model') }}</strong>
                </span>
            @endif

            @foreach($DriverDocuments as $Document)


            <span class="input-group-addon btn btn-default btn-file">
            {{$Document->name}}
            <input type="file" name="document[]" accept="application/pdf, image/*" value="" required>
            <input type="hidden" name='id[]' value="{{$Document->id}}">
            </span>
            <input type="date" data-provide="datepicker" class="datepicker_input"  placeholder="{{$Document->name}} {{trans('unauthorized.provider.expiry_date')}}" name="expires_at[]" id="expires_at" required >

            @endforeach


            <button type="submit" class="log-teal-btn">
                @lang('unauthorized.provider.register')
            </button>

        </div>
    </form>
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
                url: "{{url('/provider/otp')}}",
                type:'POST',
                data:{ mobile : countryCode+''+phoneNumber,'_token':csrf ,phoneonly:phoneNumber},
                success: function(data) {

                    if($.isEmptyObject(data.error)){

                        console.log(data);
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
