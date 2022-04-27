@extends('admin.layout.base')

@section('title', 'Add Provider ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.static_content_user.back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.static_content_provider.add_provider')</h5>

            <form class="form-horizontal" action="{{route('admin.provider.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">@lang('admin.static_content_user.first_name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('first_name') }}" name="first_name" required id="first_name" placeholder="@lang('admin.static_content_user.first_name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="last_name" class="col-xs-12 col-form-label">@lang('admin.static_content_user.last_name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('last_name') }}" name="last_name" required id="last_name" placeholder="@lang('admin.static_content_user.last_name')">
					</div>
				</div>



				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">@lang('admin.static_content_user.email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" required name="email" value="{{old('email')}}" id="email" placeholder="@lang('admin.static_content_user.email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-12 col-form-label">@lang('admin.static_content_account.password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('admin.static_content_account.password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-12 col-form-label">@lang('admin.static_content_account.password_confirmation')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.static_content_account.retype_password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="picture" class="col-xs-12 col-form-label">@lang('admin.static_content_user.picture')</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="avatar" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-12 col-form-label">@lang('admin.static_content_user.mobile')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('mobile') }}" name="mobile" required id="mobile" placeholder="@lang('admin.static_content_user.mobile')">
					</div>
				</div>


				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.static_content_provider.add_provider')</button>
						<a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.static_content_user.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
