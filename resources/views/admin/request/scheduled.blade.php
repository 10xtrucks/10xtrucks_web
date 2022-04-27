@extends('admin.layout.base')

@section('title', 'Scheduled Rides ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.include.scheduled_rides')</h5>
                @if(count($requests) != 0)
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('admin.id')</th>
                            <th>@lang('admin.request.Request_Id')</th>
                            <th>@lang('admin.request.User_Name')</th>
                            <th>@lang('admin.request.Provider_Name')</th>
                            <th>@lang('admin.request.Scheduled_Date_Time')</th>
                            <th>@lang('admin.static_content_provider.status')</th>
                            <th>@lang('admin.payment.payment_mode')</th>
                            <th>@lang('admin.payment.payment_status')</th>
                            <th>@lang('admin.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $index => $request)
                        <tr>
                            <td>{{$index + 1}}</td>

                            <td>{{$request->id}}</td>
                            <td>{{$request->user->first_name}} {{$request->user->last_name}}</td>
                            <td>
                                @if($request->provider_id)
                                    {{$request->provider->first_name}} {{$request->provider->last_name}}
                                @else
                                    @lang('admin.static_content_provider.na')
                                @endif
                            </td>
                            <td>{{$request->schedule_at->toDayDateTimeString()}}</td>
                            <td>
                                {{$request->status}}
                            </td>

                            <td>{{$request->payment_mode}}</td>
                            <td>
                                @if($request->paid)
                                    @lang('admin.static_content_dashboard.paid')
                                @else
                                    @lang('admin.static_content_dashboard.not_paid')
                                @endif
                            </td>
                            <td>
                                <div class="input-group-btn">
                                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">@lang('admin.action')
                                    <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-default"><i class="fa fa-search"></i> @lang('admin.static_content_dashboard.more_details')</a>
                                    </li>
                                  </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('admin.id')</th>
                            <th>@lang('admin.request.Request_Id')</th>
                            <th>@lang('admin.request.User_Name')</th>
                            <th>@lang('admin.request.Provider_Name')</th>
                            <th>@lang('admin.request.Scheduled_Date_Time')</th>
                            <th>@lang('admin.static_content_provider.status')</th>
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