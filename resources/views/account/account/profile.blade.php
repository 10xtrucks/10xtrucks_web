@extends('account.layout.base')

@section('title', 'Update Profile ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;">@lang('fleet_dispatcher.update_profile')</h5>

            <form class="form-horizontal" action="{{route('account.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('fleet_dispatcher.name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Auth::guard('account')->user()->name }}" name="name" required id="name" placeholder=" @lang('fleet_dispatcher.name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('fleet_dispatcher.email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" required name="email" value="{{ isset(Auth::guard('account')->user()->email) ? Auth::guard('account')->user()->email : '' }}" id="email" placeholder="@lang('fleet_dispatcher.email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">@lang('fleet_dispatcher.mobile')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="mobile" value="{{ isset(Auth::guard('account')->user()->mobile) ? Auth::guard('account')->user()->mobile : '' }}" id="mobile" placeholder="@lang('fleet_dispatcher.mobile')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('fleet_dispatcher.update_profile')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
