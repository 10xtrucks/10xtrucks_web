@extends('admin.layout.base')

@section('title', 'Site Settings ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
			<h5>@lang('admin.setting.Site_Settings')</h5>

            <form class="form-horizontal" action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">@lang('admin.setting.Site_Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('site_title', 'Fetschstr')  }}" name="site_title" required id="site_title" placeholder="@lang('admin.setting.Site_Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_logo" class="col-xs-2 col-form-label">@lang('admin.setting.Site_Logo')</label>
					<div class="col-xs-10">
						@if(Setting::get('site_logo')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ Setting::get('site_logo', asset('logo-black.png')) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_logo" class="dropify form-control-file" id="site_logo" aria-describedby="fileHelp">
					</div>
				</div>


				<div class="form-group row">
					<label for="site_icon" class="col-xs-2 col-form-label">@lang('admin.setting.Site_Icon')</label>
					<div class="col-xs-10">
						@if(Setting::get('site_icon')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ Setting::get('site_icon') }}">
	                    @endif
						<input type="file" accept="image/*" name="site_icon" class="dropify form-control-file" id="site_icon" aria-describedby="fileHelp">
					</div>
				</div>

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.setting.Copyright_Content')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('site_copyright', '&copy; '.date('Y').' 10XTrucks') }}" name="site_copyright" id="site_copyright" placeholder="@lang('admin.setting.Copyright_Content')">
                    </div>
                </div>

				<div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">@lang('admin.setting.Playstore_link')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android', '')  }}" name="store_link_android"  id="store_link_android" placeholder="@lang('admin.setting.Playstore_link')">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">@lang('admin.setting.Appstore_Link')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_ios', '')  }}" name="store_link_ios"  id="store_link_ios" placeholder="@lang('admin.setting.Appstore_Link')">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_select_timeout" class="col-xs-2 col-form-label">@lang('admin.setting.Provider_Accept_Timeout')</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ Setting::get('provider_select_timeout', '60')  }}" name="provider_select_timeout" required id="provider_select_timeout" placeholder="@lang('admin.setting.Provider_Accept_Timeout')">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_search_radius" class="col-xs-2 col-form-label">@lang('admin.setting.Provider_Search_Radius')</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ Setting::get('provider_search_radius', '100')  }}" name="provider_search_radius" required id="provider_search_radius" placeholder="@lang('admin.setting.Provider_Search_Radius')">
					</div>
				</div>

				<div class="form-group row">
					<label for="sos_number" class="col-xs-2 col-form-label">@lang('admin.setting.SOS_Number')</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ Setting::get('sos_number', '911')  }}" name="sos_number" required id="sos_number" placeholder="@lang('admin.setting.SOS_Number')">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_number" class="col-xs-2 col-form-label">@lang('admin.setting.Contact_Number')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('contact_number', '911')  }}" name="contact_number" required id="contact_number" placeholder="@lang('admin.setting.Contact_Number')">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_email" class="col-xs-2 col-form-label">@lang('admin.setting.Contact_Email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" value="{{ Setting::get('contact_email', '')  }}" name="contact_email" required id="contact_email" placeholder="@lang('admin.setting.Contact_Email')">
					</div>
				</div>

				<div class="form-group row">
                    <label for="help_content" class="col-xs-2 col-form-label">@lang('admin.setting.help_content')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('help_content') }}" name="help" id="help" placeholder="@lang('admin.setting.help_content')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="google_map_key" class="col-xs-2 col-form-label">@lang('admin.setting.map_key')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('google_map_key') }}" name="google_map_key" id="google_map_key" placeholder="@lang('admin.setting.map_key')">
                    </div>
                </div>

                <div class="form-group row">
					<label for="social_login" class="col-xs-2 col-form-label">@lang('admin.setting.default_lang')</label>
					<div class="col-xs-10">
						<select class="form-control" disabled="disabled" id="default_lang" name="default_lang">
							<option value="en" selected="selected">English</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="social_login" class="col-xs-2 col-form-label">@lang('admin.setting.Social_Login')</label>
					<div class="col-xs-10">
						<select class="form-control" id="social_login" name="social_login">
							<option value="1" @if(Setting::get('social_login', 0) == 1) selected @endif>@lang('admin.static_content_provider.enable')</option>
							<option value="0" @if(Setting::get('social_login', 0) == 0) selected @endif>@lang('admin.static_content_provider.disable')</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="track_distance" class="col-xs-2 col-form-label">@lang('admin.setting.track_dist')</label>
					<div class="col-xs-10">
						<select class="form-control" id="track_distance" name="track_distance">
							<option value="1" @if(Setting::get('track_distance', 0) == 1) selected @endif>@lang('admin.static_content_provider.enable')</option>
							<option value="0" @if(Setting::get('track_distance', 0) == 0) selected @endif>@lang('admin.static_content_provider.disable')</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="twilio_accountsid" class="col-xs-2 col-form-label">@lang('admin.setting.twilio_sid')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('twilio_accountsid')  }}" name="twilio_accountsid" required id="twilio_accountsid" placeholder="@lang('admin.setting.twilio_sid')">
					</div>
				</div>

				<div class="form-group row">
					<label for="twilio_token" class="col-xs-2 col-form-label">@lang('admin.setting.twilio_sid1')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('twilio_token')  }}" name="twilio_token" required id="twilio_token" placeholder="@lang('admin.setting.twilio_sid1')">
					</div>
				</div>

				<div class="form-group row">
					<label for="twilio_mobile" class="col-xs-2 col-form-label">@lang('admin.setting.twilio_sid2')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('twilio_mobile')  }}" name="twilio_mobile" required id="twilio_mobile" placeholder="@lang('admin.setting.twilio_sid2')">
					</div>
				</div>

				<div class="form-group row">
					<label for="twilio_app_sid" class="col-xs-2 col-form-label">@lang('admin.setting.twilio_sid3')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('twilio_app_sid')  }}" name="twilio_app_sid" required id="twilio_app_sid" placeholder="@lang('admin.setting.twilio_sid3')">
					</div>
				</div>

				<div class="form-group row">
					<label for="twilio_secret" class="col-xs-2 col-form-label">@lang('admin.setting.twilio_sid4')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('twilio_secret')  }}" name="twilio_secret" required id="twilio_secret" placeholder="@lang('admin.setting.twilio_sid4')">
					</div>
				</div>

				<div class="form-group row">
					<label for="twilio_key" class="col-xs-2 col-form-label">@lang('admin.setting.twilio_sid5')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('twilio_key')  }}" name="twilio_key" required id="twilio_key" placeholder="@lang('admin.setting.twilio_sid5')">
					</div>
				</div>

				<div class="form-group row">
					<label for="twilio_key" class="col-xs-2 col-form-label">@lang('admin.setting.default_country_code')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('default_country_code')  }}" name="default_country_code" required id="default_country_code" placeholder="@lang('admin.setting.default_country_code')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.setting.Update_Site_Settings')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection
