@extends('admin.layout.base')

@section('title', 'Payment Settings ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <form action="{{route('admin.settings.payment.store')}}" method="POST">
                {{csrf_field()}}
                <h5>@lang('admin.payment.payment_modes')</h5>
                <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-cc-stripe pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="stripe_secret_key" class="col-form-label">
                                    @lang('admin.payment.card_payments')
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CARD') == 1) checked  @endif  name="CARD" id="stripe_check" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                        <div id="card_field" @if(Setting::get('CARD') == 0) style="display: none;" @endif>
                            <div class="form-group row">
                                <label for="stripe_secret_key" class="col-xs-4 col-form-label">@lang('admin.payment.stripe_secret_key')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('stripe_secret_key', '') }}" name="stripe_secret_key" id="stripe_secret_key"  placeholder="@lang('admin.payment.stripe_secret_key')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stripe_publishable_key" class="col-xs-4 col-form-label">@lang('admin.payment.stripe_publishable_key')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('stripe_publishable_key', '') }}" name="stripe_publishable_key" id="stripe_publishable_key"  placeholder="@lang('admin.payment.stripe_publishable_key')">
                                </div>
                            </div>
                        </div>
                    </blockquote>
                </div>

                <!-- <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-cc-stripe pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="paystack_secret_key" class="col-form-label">
                                    @lang('admin.payment.paystack_payment')
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('PAYSTACK') == 1) checked  @endif  name="PAYSTACK" id="paystack_check" onchange="paystackselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                        <div id="paystack_field" @if(Setting::get('PAYSTACK') == 0) style="display: none;" @endif>
                            <div class="form-group row">
                                <label for="paystack_secret_key" class="col-xs-4 col-form-label">@lang('admin.payment.paystack_key')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('paystack_secret_key', '') }}" name="paystack_secret_key" id="paystack_secret_key"  placeholder="@lang('admin.payment.paystack_key')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="paystack_publishable_key" class="col-xs-4 col-form-label">@lang('admin.payment.paystack_key1')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('paystack_publishable_key', '') }}" name="paystack_publishable_key" id="paystack_publishable_key"  placeholder="@lang('admin.payment.paystack_key1')">
                                </div>
                            </div>
                        </div>
                    </blockquote>
                </div> -->

                <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-money pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="cash-payments" class="col-form-label">
                                    @lang('admin.payment.cash_payments')
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CASH') == 1) checked  @endif name="CASH" id="cash-payments" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                    </blockquote>
                </div>

                <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-money pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="bol-payments" class="col-form-label">
                                    @lang('admin.payment.bol_payments')
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('BOL') == 1) checked  @endif name="BOL" id="bol-payments" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                    </blockquote>
                </div>
                <h5>@lang('admin.payment.payment_settings')</h5>

                <div class="card card-block card-inverse card-info">
                    <blockquote class="card-blockquote">
                        <div class="form-group row">
                            <label for="daily_target" class="col-xs-4 col-form-label">@lang('admin.payment.daily_target')</label>
                            <div class="col-xs-8">
                                <input class="form-control" 
                                    type="number"
                                    value="{{ Setting::get('daily_target', '0')  }}"
                                    id="daily_target"
                                    name="daily_target"
                                    min="0"
                                    required
                                    placeholder="@lang('admin.payment.daily_target')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tax_percentage" class="col-xs-4 col-form-label">@lang('admin.payment.tax_percentage')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="number"
                                    value="{{ Setting::get('tax_percentage', '0')  }}"
                                    id="tax_percentage"
                                    name="tax_percentage"
                                    min="0"
                                    max="100"
                                    placeholder="@lang('admin.payment.tax_percentage')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surge_trigger" class="col-xs-4 col-form-label">@lang('admin.payment.surge_trigger_point')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="number"
                                    value="{{ Setting::get('surge_trigger', '')  }}"
                                    id="surge_trigger"
                                    name="surge_trigger"
                                    min="0"
                                    required
                                    placeholder="@lang('admin.payment.surge_trigger_point')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surge_percentage" class="col-xs-4 col-form-label">@lang('admin.payment.surge_percentage')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="number"
                                    value="{{ Setting::get('surge_percentage', '0')  }}"
                                    id="surge_percentage"
                                    name="surge_percentage"
                                    min="0"
                                    max="100"
                                    placeholder="@lang('admin.payment.surge_percentage')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="commission_percentage" class="col-xs-4 col-form-label">@lang('admin.payment.commission_percentage')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="number"
                                    value="{{ Setting::get('commission_percentage', '0') }}"
                                    id="commission_percentage"
                                    name="commission_percentage"
                                    min="0"
                                    max="100"
                                    placeholder="@lang('admin.payment.commission_percentage')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="booking_prefix" class="col-xs-4 col-form-label">@lang('admin.payment.booking_id_prefix')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="text"
                                    value="{{ Setting::get('booking_prefix', '0') }}"
                                    id="booking_prefix"
                                    name="booking_prefix"
                                    min="0"
                                    max="4"
                                    placeholder="@lang('admin.payment.booking_id_prefix')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="base_price" class="col-xs-4 col-form-label">
                                @lang('admin.payment.currency') ( <strong>{{ Setting::get('currency', '$')  }} </strong>)
                            </label>
                            <div class="col-xs-8">
                                <select name="currency" class="form-control" required>
                                    <option @if(Setting::get('currency') == "???") selected @endif value="???">Naira (NGR)</option>
                                    <option @if(Setting::get('currency') == "R") selected @endif value="R">South African (Rand)</option>
                                    <option @if(Setting::get('currency') == "$") selected @endif value="$">US Dollar (USD)</option>
                                    <option @if(Setting::get('currency') == "???") selected @endif value="???"> Indian Rupee (INR)</option>
                                    <option @if(Setting::get('currency') == "??.??") selected @endif value="??.??">Kuwaiti Dinar (KWD)</option>
                                    <option @if(Setting::get('currency') == "??.??") selected @endif value="??.??">Bahraini Dinar (BHD)</option>
                                    <option @if(Setting::get('currency') == "???") selected @endif value="???">Omani Rial (OMR)</option>
                                    <option @if(Setting::get('currency') == "??") selected @endif value="??">British Pound (GBP)</option>
                                    <option @if(Setting::get('currency') == "???") selected @endif value="???">Euro (EUR)</option>
                                    <option @if(Setting::get('currency') == "CHF") selected @endif value="CHF">Swiss Franc (CHF)</option>
                                    <option @if(Setting::get('currency') == "??.??") selected @endif value="??.??">Libyan Dinar (LYD)</option>
                                    <option @if(Setting::get('currency') == "B$") selected @endif value="B$">Bruneian Dollar (BND)</option>
                                    <option @if(Setting::get('currency') == "S$") selected @endif value="S$">Singapore Dollar (SGD)</option>
                                    <option @if(Setting::get('currency') == "AU$") selected @endif value="AU$"> Australian Dollar (AUD)</option>
                                    <option @if(Setting::get('currency') == "MX$") selected @endif value="MX$">Mexican peso (MXN)</option>
                                </select>
                            </div>
                        </div>
                    </blockquote>
                </div>

                <div class="form-group row">
                    <div class="col-xs-4">
                        <a href="{{ route('admin.index') }}" class="btn btn-warning btn-block">@lang('admin.back')</a>
                    </div>
                    <div class="offset-xs-4 col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block">@lang('admin.payment.update_site_settings')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
function cardselect()
{
    if($('#stripe_check').is(":checked")) {
        $("#card_field").fadeIn(700);
    } else {
        $("#card_field").fadeOut(700);
    }
}

function paystackselect()
{
    if($('#paystack_check').is(":checked")) {
        $("#paystack_field").fadeIn(700);
    } else {
        $("#paystack_field").fadeOut(700);
    }  
}
</script>
@endsection