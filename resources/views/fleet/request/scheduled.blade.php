@extends('fleet.layout.base')

@section('title', 'Scheduled Rides ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('fleet_dispatcher.schedule_rides')</h5>
                @if(count($requests) != 0)
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('fleet_dispatcher.id')</th>
                            <th>@lang('fleet_dispatcher.booking_id')</th>
                            <th>@lang('fleet_dispatcher.user_name')</th>
                            <th>@lang('fleet_dispatcher.provider_name')</th>
                            <th>@lang('fleet_dispatcher.schedule_date')</th>
                            <th>@lang('fleet_dispatcher.status')</th>
                            <th>@lang('fleet_dispatcher.payment_mode')</th>
                            <th>@lang('fleet_dispatcher.payment_status')</th>
                            <th>@lang('fleet_dispatcher.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $index => $request)
                        <tr>
                            <td>{{$index + 1}}</td>

                            <td>{{$request->booking_id}}</td>
                            <td>{{$request->user->first_name}} {{$request->user->last_name}}</td>
                            <td>
                                @if($request->provider_id)
                                    {{$request->provider->first_name}} {{$request->provider->last_name}}
                                @else
                                    @lang('fleet_dispatcher.na')
                                @endif
                            </td>
                            <td>{{$request->schedule_at}}</td>
                            <td>
                                {{$request->status}}
                            </td>

                            <td>{{$request->payment_mode}}</td>
                            <td>
                                @if($request->paid)
                                    @lang('fleet_dispatcher.paid')
                                @else
                                    @lang('fleet_dispatcher.not_paid')
                                @endif
                            </td>
                            <td>
                                <div class="input-group-btn">
                                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">@lang('fleet_dispatcher.action')
                                    <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('fleet.requests.show', $request->id) }}" class="btn btn-default"><i class="fa fa-search"></i> @lang('fleet_dispatcher.more_details')</a>
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
                            <th>@lang('fleet_dispatcher.booking_id')</th>
                            <th>@lang('fleet_dispatcher.user_name')</th>
                            <th>@lang('fleet_dispatcher.provider_name')</th>
                            <th>@lang('fleet_dispatcher.schedule_date')</th>
                            <th>@lang('fleet_dispatcher.status')</th>
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