@extends('admin.layout.base')

@section('title', 'Request History ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.include.request_history')</h5>
            @if(count($requests) != 0)
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.request.Booking_ID')</th>
                        <th>@lang('admin.request.User_Name')</th>
                        <th>@lang('admin.request.Provider_Name')</th>
                        <th>@lang('admin.review.date_time')</th>
                        <th>@lang('admin.static_content_provider.status')</th>
                        <th>@lang('admin.amount')</th>
                        <th>@lang('admin.payment.payment_mode')</th>
                        <th>@lang('admin.payment.payment_status')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($requests as $index => $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->booking_id }}</td>
                        <td>
                            @if($request->user)
                                {{ $request->user->first_name }} {{ $request->user->last_name }}
                            @else
                                @lang('admin.static_content_provider.na')
                            @endif
                        </td>
                        <td>
                            @if($request->provider)
                                {{ $request->provider->first_name }} {{ $request->provider->last_name }}
                            @else
                                @lang('admin.static_content_provider.na')
                            @endif
                        </td>
                        <td>
                            @if($request->created_at)
                                <span class="text-muted">{{$request->created_at->diffForHumans()}}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $request->status }}</td>
                        <td>
                            @if($request->payment != "")
                                {{ currency($request->payment->total) }}
                            @else
                                @lang('admin.static_content_provider.na')
                            @endif
                        </td>
                        <td>{{ $request->payment_mode }}</td>
                        <td>
                            @if($request->paid)
                                @lang('admin.static_content_dashboard.paid')
                            @else
                                @lang('admin.static_content_dashboard.not_paid')
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    @lang('admin.action')
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('admin.requests.show', $request->id) }}" class="dropdown-item">
                                        <i class="fa fa-search"></i> @lang('admin.static_content_dashboard.more_details')
                                    </a>
                                    <form action="{{ route('admin.requests.destroy', $request->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="dropdown-item">
                                            <i class="fa fa-trash"></i> @lang('admin.delete')
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
                         <th>#</th>
                        <th>@lang('admin.request.Booking_ID')</th>
                        <th>@lang('admin.request.User_Name')</th>
                        <th>@lang('admin.request.Provider_Name')</th>
                        <th>@lang('admin.review.date_time')</th>
                        <th>@lang('admin.static_content_provider.status')</th>
                        <th>@lang('admin.amount')</th>
                        <th>@lang('admin.payment.payment_mode')</th>
                        <th>@lang('admin.payment.payment_status')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </tfoot>
            </table>
            @else
            <h6 class="no-result">@lang('admin.static_content_provider.no_result')</h6>
            @endif 
        </div>
    </div>
</div>
@endsection