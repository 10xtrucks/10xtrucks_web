@extends('admin.layout.base')

@section('title', 'Providers ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">
                @lang('admin.static_content_provider.providers')
                @if(Setting::get('demo_mode', 0) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            <a href="{{ route('admin.provider.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.static_content_provider.add_new')</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.static_content_user.id')</th>
                        <th>@lang('admin.static_content_provider.full_name')</th>
                        <th>@lang('admin.static_content_user.email')</th>
                        <th>@lang('admin.static_content_user.mobile')</th>
                        <th>@lang('admin.static_content_provider.total_requests')</th>
                        <th>@lang('admin.static_content_provider.accepted_requests')</th>
                        <th>@lang('admin.static_content_provider.cancelled_requests')</th>
                        <th>@lang('admin.static_content_provider.service_type')</th>
                        <th>@lang('admin.static_content_provider.online')</th>
                        <th>@lang('admin.static_content_user.action')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($providers as $index => $provider)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $provider->first_name }} {{ $provider->last_name }}</td>
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>{{ substr($provider->email, 0, 3).'****'.substr($provider->email, strpos($provider->email, "@")) }}</td>
                        @else
                        <td>{{ $provider->email }}</td>
                        @endif
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>+919876543210</td>
                        @else
                        <td>{{ $provider->mobile }}</td>
                        @endif
                        <td>{{ $provider->total_requests }}</td>
                        <td>{{ $provider->accepted_requests }}</td>
                        <td>{{ $provider->cancelled_requests }}</td>
                        <td>
                            @if($provider->active_documents() == $total_documents && $provider->service != null)
                                 <a class="btn btn-success btn-block" href="{{route('admin.provider.document.index', $provider->id )}}">@lang('admin.static_content_provider.all_set')</a>
                            @else                               
                                <a class="btn btn-danger btn-block label-right" href="{{route('admin.provider.document.index', $provider->id )}}">@lang('admin.static_content_provider.attention') <span class="btn-label">{{ $provider->pending_documents() }}</span></a>
                            @endif
                        </td>
                        <td>
                            @if($provider->service)
                                @if($provider->service->status == 'active')
                                    <label class="btn btn-block btn-primary">@lang('admin.static_content_provider.yes')</label>
                                @else
                                    <label class="btn btn-block btn-warning">@lang('admin.static_content_provider.no')</label>
                                @endif
                            @else
                                <label class="btn btn-block btn-danger">@lang('admin.static_content_provider.na')</label>
                            @endif
                        </td>
                        <td>
                            <div class="input-group-btn">
                                @if($provider->status == 'approved')
                                <a class="btn btn-danger btn-block" href="{{ route('admin.provider.disapprove', $provider->id ) }}">@lang('admin.static_content_provider.disable')</a>
                                @else
                                <a class="btn btn-success btn-block" href="{{ route('admin.provider.approve', $provider->id ) }}">@lang('admin.static_content_provider.enable')</a>
                                @endif
                                <button type="button" 
                                    class="btn btn-info btn-block dropdown-toggle"
                                    data-toggle="dropdown">@lang('admin.static_content_user.action')
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.provider.request', $provider->id) }}" class="btn btn-default"><i class="fa fa-search"></i> @lang('admin.static_content_user.history')</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.provider.statement', $provider->id) }}" class="btn btn-default"><i class="fa fa-account"></i> @lang('admin.static_content_provider.statements')</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.provider.edit', $provider->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> @lang('admin.static_content_user.edit')</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.provider.destroy', $provider->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-default look-a-like" onclick="var del_cont='{{trans("admin.static_content_user.are_sure")}}';return confirm(del_cont)"><i class="fa fa-trash"></i> @lang('admin.static_content_user.delete')</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.static_content_user.id')</th>
                        <th>@lang('admin.static_content_provider.full_name')</th>
                        <th>@lang('admin.static_content_user.email')</th>
                        <th>@lang('admin.static_content_user.mobile')</th>
                        <th>@lang('admin.static_content_provider.total_requests')</th>
                        <th>@lang('admin.static_content_provider.accepted_requests')</th>
                        <th>@lang('admin.static_content_provider.cancelled_requests')</th>
                        <th>@lang('admin.static_content_provider.service_type')</th>
                        <th>@lang('admin.static_content_provider.online')</th>
                        <th>@lang('admin.static_content_user.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection