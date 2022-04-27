@extends('admin.layout.base')

@section('title', 'Service Types ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.include.service_types')</h5>
            <a href="{{ route('admin.service.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.include.add_new_service_type')</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.provides.service_name')</th>
                        <th>@lang('admin.provides.provider_name')</th>
                        <th>@lang('admin.service.capacity')</th>
                        <th>@lang('admin.service.Base_Price')</th>
                        <th>@lang('admin.service.Base_Distance')</th>
                        <th>@lang('admin.service.distance_price')</th>
                        <th>@lang('admin.service.time_price')</th>
                        <th>@lang('admin.service.price_calc')</th>
                        <th>@lang('admin.static_content_user.user_type')</th>
                        <th>@lang('admin.service.Service_Image')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($services as $index => $service)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->provider_name }}</td>
                        <td>{{ $service->capacity }}</td>
                        <td>{{ currency($service->fixed) }}</td>
                        <td>{{ distance($service->distance) }}</td>
                        <td>{{ currency($service->price) }}</td>
                        <td>{{ currency($service->minute) }}</td>
                        <td>@lang('servicetypes.'.$service->calculator)</td>
                        <td>{{ $service->user_type }}</td>
                        <td>
                            @if($service->image) 
                                <img src="{{$service->image}}" style="height: 50px" >
                            @else
                                @lang('admin.static_content_provider.na')
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.service.destroy', $service->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="{{ route('admin.service.edit', $service->id) }}" class="btn btn-info btn-block">
                                    <i class="fa fa-pencil"></i> @lang('admin.edit')
                                </a>
                                <button class="btn btn-danger btn-block" onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash"></i> @lang('admin.delete')
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.provides.service_name')</th>
                        <th>@lang('admin.provides.provider_name')</th>
                        <th>@lang('admin.service.capacity')</th>
                        <th>@lang('admin.service.Base_Price')</th>
                        <th>@lang('admin.service.Base_Distance')</th>
                        <th>@lang('admin.service.distance_price')</th>
                        <th>@lang('admin.service.time_price')</th>
                        <th>@lang('admin.service.price_calc')</th>
                        <th>@lang('admin.static_content_user.user_type')</th>
                        <th>@lang('admin.service.Service_Image')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection