@extends('admin.layout.base')

@section('title', 'Add Service Type ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.service.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

            <h5 style="margin-bottom: 2em;">@lang('admin.service.Add_Service_Type')</h5>

            <form class="form-horizontal" action="{{route('admin.service.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">@lang('admin.provides.service_name')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="@lang('admin.provides.service_name')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="provider_name" class="col-xs-12 col-form-label">@lang('admin.provides.provider_name')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('provider_name') }}" name="provider_name" required id="provider_name" placeholder="@lang('admin.provides.provider_name')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="picture" class="col-xs-12 col-form-label">@lang('admin.service.Service_Image')</label>
                    <div class="col-xs-10">
                        <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fixed" class="col-xs-12 col-form-label">@lang('admin.service.Base_Price') ({{ currency() }})</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('fixed') }}" name="fixed" required id="fixed" placeholder="@lang('admin.service.Base_Price')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="distance" class="col-xs-12 col-form-label">@lang('admin.service.Base_Distance') ({{ distance() }})</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('distance') }}" name="distance" required id="distance" placeholder="@lang('admin.service.Base_Distance')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="minute" class="col-xs-12 col-form-label">@lang('admin.service.unit_time') ({{ currency() }})</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('minute') }}" name="minute" required id="minute" placeholder="@lang('admin.service.unit_time1')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-xs-12 col-form-label">@lang('admin.service.unit') ({{ distance() }})</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('price') }}" name="price" required id="price" placeholder="@lang('admin.service.unit')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="maximum_weight" class="col-xs-12 col-form-label">@lang('admin.service.max_weight')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('maximum_weight') }}" name="maximum_weight" required id="maximum_weight" placeholder="@lang('admin.service.max_weight')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="capacity" class="col-xs-12 col-form-label">@lang('admin.service.Seat_Capacity')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" value="{{ old('capacity') }}" name="capacity" required id="capacity" placeholder="@lang('admin.service.Seat_Capacity')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calculator" class="col-xs-12 col-form-label">@lang('admin.service.Pricing_Logic')</label>
                    <div class="col-xs-10">
                        <select class="form-control" id="calculator" name="calculator">
                            <option value="MIN">@lang('servicetypes.MIN')</option>
                            <option value="HOUR">@lang('servicetypes.HOUR')</option>
                            <option value="DISTANCE">@lang('servicetypes.DISTANCE')</option>
                            <option value="DISTANCEMIN">@lang('servicetypes.DISTANCEMIN')</option>
                            <option value="DISTANCEHOUR">@lang('servicetypes.DISTANCEHOUR')</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="choosetype" class="col-xs-12 col-form-label">@lang('admin.static_content_user.user_type')</label>
                                 <div class="col-md-6">
                                <input type="radio" placeholder=""  name="user_type" class="user_type"
                                value="BUSINESSUSER">@lang('admin.service.business_user')
                                </div>
                                 <div class="col-md-6">
                                <input type="radio" placeholder="" class="user_type" name="user_type" value="NORMAL">@lang('admin.service.normal_user')
                                </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-xs-12 col-form-label">@lang('admin.about.about_description')</label>
                    <div class="col-xs-10">
                        <textarea class="form-control" type="number" value="{{ old('description') }}" name="description" required id="description" placeholder="@lang('admin.about.about_description')" rows="4"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.service.index') }}" class="btn btn-danger btn-block">@lang('admin.cancel')</a>
                            </div>
                            <div class="col-xs-12 col-sm-6 offset-md-6 col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">@lang('admin.service.Add_Service_Type')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
