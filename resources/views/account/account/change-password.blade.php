@extends('account.layout.base')

@section('title', 'Change Password ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;">@lang('fleet_dispatcher.change_password')</h5>

            <form class="form-horizontal" action="{{route('account.password.update')}}" method="POST" role="form">
            	{{csrf_field()}}

            	<div class="form-group row">
					<label for="old_password" class="col-xs-12 col-form-label">@lang('fleet_dispatcher.old_password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="old_password" id="old_password" placeholder="@lang('fleet_dispatcher.old_password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-12 col-form-label">@lang('fleet_dispatcher.password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('fleet_dispatcher.new_password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-12 col-form-label">@lang('fleet_dispatcher.pass_conform')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('fleet_dispatcher.retype_pass')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('fleet_dispatcher.change_password')</button>
					</div>
				</div>

			</form>
		</div>
    </div>
</div>

@endsection
