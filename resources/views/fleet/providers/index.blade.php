@extends('fleet.layout.base')

@section('title', 'Providers ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">
                Providers
                @if(Setting::get('demo_mode', 0) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            <a href="{{ route('fleet.provider.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('fleet_dispatcher.add_new_provider')</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('fleet_dispatcher.id')</th>
                        <th>@lang('fleet_dispatcher.full_name')</th>
                        <th>@lang('fleet_dispatcher.email')</th>
                        <th>@lang('fleet_dispatcher.mobile')</th>
                        <th>@lang('fleet_dispatcher.total_reqst')</th>
                        <th>@lang('fleet_dispatcher.accepeted_reqst')</th>
                        <th>@lang('fleet_dispatcher.cancelled_reqst')</th>
                        <th>@lang('fleet_dispatcher.document_service')</th>
                        <th>@lang('fleet_dispatcher.online')</th>
                        <th>@lang('fleet_dispatcher.action')</th>
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
                        <td>{{ $provider->total_requests - $provider->accepted_requests }}</td>
                        <td>
                            @if($provider->pending_documents() > 0 || $provider->service == null)
                                <a class="btn btn-danger btn-block label-right" href="{{route('fleet.provider.document.index', $provider->id )}}">@lang('fleet_dispatcher.attention')! <span class="btn-label">{{ $provider->pending_documents() }}</span></a>
                            @else
                                <a class="btn btn-success btn-block" href="{{route('fleet.provider.document.index', $provider->id )}}">@lang('fleet_dispatcher.all_set')!</a>
                            @endif
                        </td>
                        <td>
                            @if($provider->service)
                                @if($provider->service->status == 'active')
                                    <label class="btn btn-block btn-primary">@lang('fleet_dispatcher.yes')</label>
                                @else
                                    <label class="btn btn-block btn-warning">@lang('fleet_dispatcher.no')</label>
                                @endif
                            @else
                                <label class="btn btn-block btn-danger">@lang('fleet_dispatcher.na')</label>
                            @endif
                        </td>
                        <td>
                            <div class="input-group-btn">
                                @if($provider->status == 'approved')
                                <a class="btn btn-danger btn-block" href="{{ route('fleet.provider.disapprove', $provider->id ) }}">@lang('fleet_dispatcher.Disable')</a>
                                @else
                                <a class="btn btn-success btn-block" href="{{ route('fleet.provider.approve', $provider->id ) }}">@lang('fleet_dispatcher.Enable')</a>
                                @endif
                                <button type="button" 
                                    class="btn btn-info btn-block dropdown-toggle"
                                    data-toggle="dropdown">@lang('fleet_dispatcher.action')
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('fleet.provider.request', $provider->id) }}" class="btn btn-default"><i class="fa fa-search"></i> @lang('fleet_dispatcher.History')</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('fleet.provider.edit', $provider->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> @lang('fleet_dispatcher.edit')</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('fleet.provider.destroy', $provider->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> @lang('fleet_dispatcher.delete')</button>
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
                        <th>@lang('fleet_dispatcher.id')</th>
                        <th>@lang('fleet_dispatcher.full_name')</th>
                        <th>@lang('fleet_dispatcher.email')</th>
                        <th>@lang('fleet_dispatcher.mobile')</th>
                        <th>@lang('fleet_dispatcher.total_reqst')</th>
                        <th>@lang('fleet_dispatcher.accepeted_reqst')</th>
                        <th>@lang('fleet_dispatcher.cancelled_reqst')</th>
                        <th>@lang('fleet_dispatcher.document_service')</th>
                        <th>@lang('fleet_dispatcher.online')</th>
                        <th>@lang('fleet_dispatcher.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection