@extends('fleet.layout.base')

@section('title', 'Request History ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('fleet_dispatcher.request_history')</h5>
            @if(count($requests) != 0)
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('fleet_dispatcher.booking_id')</th>
                        <th>@lang('fleet_dispatcher.user_name')</th>
                        <th>@lang('fleet_dispatcher.provider_name')</th>
                        <th>@lang('fleet_dispatcher.date') &amp; @lang('fleet_dispatcher.time')</th>
                        <th>@lang('fleet_dispatcher.status')</th>
                        <th>@lang('fleet_dispatcher.amount')</th>
                        <th>@lang('fleet_dispatcher.payment_mode')</th>
                        <th>@lang('fleet_dispatcher.payment_status')</th>
                        <th>@lang('fleet_dispatcher.action')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($requests as $index => $request)
                    <tr>
                        <td>{{ $request->booking_id }}</td>
                        <td>{{ $request->user->first_name }} {{ $request->user->last_name }}</td>
                        <td>
                            @if($request->provider)
                                {{ $request->provider->first_name }} {{ $request->provider->last_name }}
                            @else
                                @lang('fleet_dispatcher.na')
                            @endif
                        </td>
                        <td>{{ $request->created_at }}</td>
                        <td>{{ $request->status }}</td>
                        <td>
                            @if($request->payment != "")
                                {{ currency($request->payment->total) }}
                            @else
                                @lang('fleet_dispatcher.na')
                            @endif
                        </td>
                        <td>{{ $request->payment_mode }}</td>
                        <td>
                            @if($request->paid)
                                @lang('fleet_dispatcher.paid')
                            @else
                                @lang('fleet_dispatcher.not_paid')
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    @lang('fleet_dispatcher.action')
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('fleet.requests.show', $request->id) }}" class="dropdown-item">
                                        <i class="fa fa-search"></i> @lang('fleet_dispatcher.more_details')
                                    </a>
                                    <form action="{{ route('fleet.requests.destroy', $request->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="dropdown-item">
                                            <i class="fa fa-trash"></i> @lang('fleet_dispatcher.delete')
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('fleet_dispatcher.booking_id')</th>
                        <th>@lang('fleet_dispatcher.user_name')</th>
                        <th>@lang('fleet_dispatcher.provider_name')</th>
                        <th>@lang('fleet_dispatcher.date') &amp; @lang('fleet_dispatcher.time')</th>
                        <th>@lang('fleet_dispatcher.status')</th>
                        <th>@lang('fleet_dispatcher.amount')</th>
                        <th>@lang('fleet_dispatcher.payment_mode')</th>
                        <th>@lang('fleet_dispatcher.payment_status')</th>
                        <th>@lang('fleet_dispatcher.action')</th>
                    </tr>
                </tfoot>
            </table>
            @else
            <h6 class="no-result">@lang('fleet_dispatcher.no_result')</h6>
            @endif 
        </div>
    </div>
</div>
@endsection